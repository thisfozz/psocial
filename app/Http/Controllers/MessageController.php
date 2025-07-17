<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dialog;

use App\Models\Message;
use App\Events\PusherBroadcast;
use App\Models\MessageVideo;
use App\Events\MessageDeleted;
use App\Events\MessageEdited;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MessageController extends Controller
{
        public function setFriend(Request $request) {
            $request->session()->put('friend_id', $request->input('friend_id'));
        
        return redirect()->route('messages.index');
    }

    public function index(Request $request) {
        $user = auth()->user();
        $dialogs = $user->allDialogs();
        $contacts = [];

        $friendId = $request->session()->get('friend_id');
        $selectedDialogId = $request->input('dialog_id') ?? $request->session()->get('dialog_id');
        $dialog = null;
        $friend = null;

        if ($friendId && !$selectedDialogId) {
            $dialog = Dialog::where(function($q) use ($user, $friendId) {
                $q->where('user1_id', $user->id)->where('user2_id', $friendId);
            })->orWhere(function($q) use ($user, $friendId) {
                $q->where('user1_id', $friendId)->where('user2_id', $user->id);
            })->first();

            if (!$dialog) {
                $dialog = Dialog::create([
                    'user1_id' => min($user->id, $friendId),
                    'user2_id' => max($user->id, $friendId)
                ]);
            }
            $selectedDialogId = $dialog->id;
            $friend = User::findOrFail($friendId);
        }

        foreach ($dialogs as $dialogItem) {
            $companion = $dialogItem->companion();
            $lastMessage = $dialogItem->messages()->orderBy('created_at', 'desc')->first();
            $contacts[] = [
                'id' => $dialogItem->id,
                'user_id' => $companion->id,
                'name' => $companion->first_name . ' ' . $companion->last_name,
                'avatar' => $companion->avatar_path,
                'lastMessage' => $lastMessage
            ];
        }

        if ($selectedDialogId) {
            $dialog = $dialogs->firstWhere('id', $selectedDialogId) ?? Dialog::find($selectedDialogId);
            if ($dialog) {
                $friend = $dialog->companion();
            }
        }

        if (!$dialog || !$friend) {
            return view('messages.index', [
                'contacts' => $contacts,
                'user' => $user,
                'friend' => null,
                'dialog' => null,
                'messages' => [],
            ]);
        }

        $messages = $dialog->messages()->orderBy('created_at', 'asc')->get();

        return view('messages.index', [
            'contacts' => $contacts,
            'messages' => $messages,
            'user' => $user,
            'friend' => $friend,
            'dialog' => $dialog,
        ]);
    }

    public function broadcast(Request $request) {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['error' => 'Not authenticated'], 401);
            }

            $dialogId = $request->get('dialog_id');
            $dialog = Dialog::find($dialogId);

            if (!$dialog) {
                $friendId = (int)$request->get('receiver_id');
                if ($friendId === $user->id) {
                    return response()->json(['error' => 'Cannot create dialog with yourself'], 400);
                }
                $dialog = Dialog::create([
                    'user1_id' => min($user->id, $friendId),
                    'user2_id' => max($user->id, $friendId)
                ]);
                $dialogId = $dialog->id;
            } else {
                if (!in_array($user->id, [$dialog->user1_id, $dialog->user2_id])) {
                    return response()->json(['error' => 'Dialog not found or access denied'], 403);
                }
                $friendId = $dialog->user1_id == $user->id ? $dialog->user2_id : $dialog->user1_id;
            }

            $validated = $request->validate([
                'content' => 'nullable|string|required_without:images',
                'images' => 'nullable|array|required_without:content',
                'images.*' => 'image|max:8192',
            ]);

            $userAttachDir = 'attach/user_' . auth()->id();
            if (!Storage::disk('s3')->exists($userAttachDir)) {
                Storage::disk('s3')->makeDirectory($userAttachDir);
            }

            $content = $request->input('content') ?? '';

            $message = $request->user()->messages()->create([
                'sender_id' => $user->id,
                'receiver_id' => $friendId,
                'content' => $content,
                'user_id' => auth()->id(),
                'dialog_id' => $dialogId,
            ]);

            $videoData = MessageVideo::extractVideoData($content);
            if($videoData) {
                $message->videos()->create([
                    'platform' => $videoData['platform'],
                    'video_id' => $videoData['video_id'],
                    'embed_code' => $videoData['embed_code'],
                    'thumbnail_url' => $videoData['thumbnail_url'],
                    'dialog_id' => $dialogId,
                ]);
                $message->update(['content' => '']);
            }

            if($request->hasFile('images')){
                foreach ($request->file('images', []) as $image) {
                    $extension = $image->getClientOriginalExtension();
                    $filename = 'images_' . now()->format('YmdHis') . '_' . Str::random(8) . '.' . $extension;
                    $path = $image->storeAs($userAttachDir, $filename, 's3');
    
                    $message->images()->create([
                        'user_id' => auth()->id(),
                        'image_path' => $path,
                    ]);
                }
            }

            broadcast(new PusherBroadcast($message, $request->get('client_id')))->toOthers();

            return response()->json([
                'status' => 'ok',
                'message_id' => $message->id,
                'message' => [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'receiver_id' => $message->receiver_id,
                    'content' => $message->content,
                    'is_read' => $message->is_read,
                    'created_at' => $message->created_at->toIso8601String(),
                    'updated_at' => $message->updated_at->toIso8601String(),
                ],
                'temp_id' => $request->get('client_id')
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function receive(Request $request) {
        $message = $request->get('message');
        if (is_array($message) && isset($message['id'])) {
            $message = Message::with('sender')->find($message['id']);
        }
        return view('messages.receive', ['message' => $message, 'authId' => $request->get('authId')]);
    }

    public function edit(Request $request) {
        $message = Message::find($request->get('message_id'));
        if (!$message) {
            return response()->json(['error' => 'Message not found'], 404);
        }
        $message->content = $request->get('content');
        $message->save(); // Это автоматически обновит updated_at
        
        broadcast(new MessageEdited(
            $message->id, 
            $message->sender_id, 
            $message->receiver_id, 
            $message->content,
            $message->created_at,
            $message->updated_at
        ))->toOthers();
        
        return response()->json(['status' => 'ok']);
    }

    public function delete(Request $request) {
        $message = Message::find($request->get('message_id'));
        if (!$message) {
            return response()->json(['error' => 'Message not found'], 404);
        }
        $messageData = [
            'id' => $message->id,
            'dialog_id' => $message->dialog_id,
            'sender_id' => $message->sender_id,
            'receiver_id' => $message->receiver_id,
            'content' => $message->content,
            'is_read' => $message->is_read,
            'created_at' => $message->created_at->toIso8601String(),
            'updated_at' => $message->updated_at->toIso8601String(),
        ];

        $message->delete();

        broadcast(new MessageDeleted($message->id, $message->sender_id, $message->receiver_id))->toOthers();
        return response()->json(['status' => 'ok']);
    }
}