<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FriendRequest;

class FriendRequestController extends Controller
{
    public function sendRequest(Request $request, $toUserId){
        if($request->user()->sentFriendRequests()->where('to_user_id', $toUserId)->exists() || $request->user()->friends()->where('friend_id', $toUserId)->exists()){
            return response()->json(['error' => 'Request already sent'], 400);
        }
        $request->user()->sentFriendRequests()->create([
            'to_user_id' => $toUserId,
            'status' => 'awaiting',
            'from_user_id' => $request->user()->id,
        ]);
        return response()->json(['message' => 'Request sent successfully']);
    }
    public function acceptRequest(Request $request, $requestId){
        $friendRequest = FriendRequest::find($requestId);
        if (!$friendRequest) {
            abort(404);
        }

        if ($request->user()->id !== $friendRequest->to_user_id) {
            return response()->json(['error' => 'You are not the owner of this request'], 400);
        }
        $request->user()->friends()->attach($friendRequest->from_user_id, ['created_at' => now()]);

        $sender = \App\Models\User::find($friendRequest->from_user_id);
        if ($sender) {
            $sender->friends()->attach($friendRequest->to_user_id, ['created_at' => now()]);
        }

        $friendRequest->delete();

        return response()->json(['message' => 'Request accepted successfully']);
    }
    public function declineRequest(Request $request, $requestId){

    }
    public function cancelRequest(Request $request, $requestId){

    }
    public function removeFriend(Request $request, $friendId){

    }
    public function incomingRequests(Request $request){

    }
    public function outgoingRequests(Request $request){

    }
}