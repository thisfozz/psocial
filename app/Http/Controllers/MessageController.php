<?php

namespace App\Http\Controllers;

use App\Events\PusherBroadcast;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;

class MessageController extends Controller
{
    public function setFriend(Request $request) {
        $request->session()->put('friend_id', $request->input('friend_id'));
        return redirect()->route('messages.index');
    }

    public function index(Request $request) {
        $user = auth()->id();
        $friendId = $request->session()->get('friend_id');
        if (!$friendId) {
            return view('messages.empty');
        }
        $friend = User::findOrFail($friendId);
        $messages = Message::with('sender')
            ->where(function($query) use ($user, $friend) {
                $query->where('sender_id', $user)
                      ->where('receiver_id', $friend->id);
            })
            ->orWhere(function($query) use ($user, $friend) {
                $query->where('sender_id', $friend->id)
                      ->where('receiver_id', $user);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return view('messages.index', compact('messages', 'friend'));
    }

    public function broadcast(Request $request) {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['error' => 'Not authenticated'], 401);
            }
            $friendId = $user->id === (int)$request->get('receiver_id')
                ? (int)$request->get('sender_id')
                : (int)$request->get('receiver_id');

            $message = Message::create([
                'sender_id' => $user->id,
                'receiver_id' => $friendId,
                'content' => $request->get('message'),
                'is_read' => false,
            ]);

            broadcast(new PusherBroadcast($message));

            return response()->json([
                'status' => 'ok',
                'message_id' => $message->id,
                'message' => $message,
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function receive(Request $request) {
        return view('messages.receive', ['message' => $request->get('message')]);
    }
}