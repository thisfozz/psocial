<?php

namespace App\Http\Controllers;

use App\Events\PusherBroadcast;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Models\Dialog;

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

        if ($selectedDialogId) {
            $dialog = $dialogs->firstWhere('id', $selectedDialogId) ?? Dialog::find($selectedDialogId);
            if ($dialog) {
                $friend = $dialog->companion();
            }
        }

        if (!$dialog || !$friend) {
            return view('messages.index', compact('contacts', 'user'));
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

        $messages = $dialog->messages()->orderBy('created_at', 'asc')->get();

        return view('messages.index', compact('contacts', 'messages', 'user', 'friend', 'dialog'));
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

            $message = Message::create([
                'sender_id' => $user->id,
                'receiver_id' => $friendId,
                'content' => $request->get('message'),
                'is_read' => false,
                'dialog_id' => $dialogId
            ]);

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
}