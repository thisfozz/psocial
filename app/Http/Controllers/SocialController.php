<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;

class SocialController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);
        $friends = $user->friends()->get();
        $posts = Post::with('author')->where('wall_id', $user->id)->orderBy('created_at', 'desc')->get();

        $isFriend = false;
        if($user->friends()->where('friend_id', auth()->id())->exists()){
            $isFriend = true;
        }
        $isRequested = false;
        if(auth()->user()->sentFriendRequests()->where('to_user_id', $user->id)->exists()){
            $isRequested = true;
        }
        $incomingRequest = auth()->user()->receivedFriendRequests()->where('from_user_id', $user->id)->first();
        $hasIncomingRequest = $incomingRequest ? true : false;
        $incomingRequestId = $incomingRequest ? $incomingRequest->id : null;

        $outgoingRequest = auth()->user()->sentFriendRequests()->where('to_user_id', $user->id)->first();
        $outgoingRequestId = $outgoingRequest ? $outgoingRequest->id : null;

        return view('social.index', [
            'user' => $user,
            'friends' => $friends,
            'isFriend' => $isFriend,
            'isRequested' => $isRequested,
            'hasIncomingRequest' => $hasIncomingRequest,
            'incomingRequestId' => $incomingRequestId,
            'outgoingRequestId' => $outgoingRequestId,
            'posts' => $posts]);
    }
}