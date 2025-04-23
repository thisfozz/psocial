<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FriendRequest;

class FriendRequestController extends Controller
{
    public function sendRequest(Request $request, $toUserId){
        if($request->user()->sentFriendRequests()->where('to_user_id', $toUserId)->exists() || $request->user()->friends()->where('friend_id', $toUserId)->exists()){
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Request already sent'], 400);
            } else {
                return redirect()->back()->with('error', 'Запрос уже отправлен');
            }
        }
        $request->user()->sentFriendRequests()->create([
            'to_user_id' => $toUserId,
            'status' => 'awaiting',
            'from_user_id' => $request->user()->id,
        ]);
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Request sent successfully']);
        } else {
            return redirect()->back()->with('success', 'Заявка отправлена!');
        }
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
        $friendRequest = FriendRequest::find($requestId);

        if(!$friendRequest){
            abort(404);
        }

        if($request->user()->id != $friendRequest->to_user_id){
            return response()->json(['error' => 'You are not the owner of this request'], 400);
        }

        $friendRequest->delete();

        return response()->json(['message' => 'Request declined successfully']);
    }
    public function cancelRequest(Request $request, $requestId){
        $friendRequest = FriendRequest::find($requestId);
        if(!$friendRequest){
            abort(404);
        }
        $request->user()->sentFriendRequests()->where('id', $requestId)->delete();
        return response()->json(['message' => 'Request canceled successfully']);
    }
    public function removeFriend(Request $request, $friendId){
        $user = $request->user();
        $friend = \App\Models\User::find($friendId);
        if(!$friend){
            abort(404);
        }
        $user->friends()->detach($friendId);
        $friend->friends()->detach($user->id);

        $requestModel = null;
        $alreadyRequested = $friend->sentFriendRequests()
            ->where('to_user_id', $user->id)
            ->where('status', 'awaiting')
            ->first();

        if(!$alreadyRequested){
            $requestModel = $friend->sentFriendRequests()->create([
                'to_user_id' => $user->id,
                'status' => 'awaiting',
                'from_user_id' => $friend->id,
            ]);
        }

        return response()->json([
            'message' => 'Friend removed successfully',
            'request_id' => $requestModel ? $requestModel->id : ($alreadyRequested ? $alreadyRequested->id : null)
        ]);
    }
    public function incomingRequests(Request $request){

    }
    public function outgoingRequests(Request $request){

    }
}