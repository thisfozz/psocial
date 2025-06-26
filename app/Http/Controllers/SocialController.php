<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class SocialController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);
        $friends = $user->friends()->take(6)->get();
        $friendsOnline = $friends->filter(function($friend){
            return $friend->lastSeen();
        })->take(6);
        $friendsCount = $user->friends()->count();

        $posts = Post::with(['author', 'images'])
            ->where('wall_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->withCount('likes')
            ->get();

        $isFriend = $user->friends()->where('friend_id', auth()->id())->exists()
                 || $user->friendOf()->where('user_id', auth()->id())->exists();
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
            'friendsOnline' => $friendsOnline,
            'friendsCount' => $friendsCount,
            'isFriend' => $isFriend,
            'isRequested' => $isRequested,
            'hasIncomingRequest' => $hasIncomingRequest,
            'incomingRequestId' => $incomingRequestId,
            'outgoingRequestId' => $outgoingRequestId,
            'posts' => $posts]);
    }
}