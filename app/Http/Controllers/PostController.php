<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
class PostController extends Controller
{
    public function show(){

    }
    public function store(Request $request){
        $request->validate([
            'content' => 'required|string|max:600',
            'wall_id' => 'required|exists:users,id',
        ]);
        $request->user()->posts()->create([
            'content' => $request->input('content'),
            'user_id' => auth()->id(),
            'wall_id' => $request->input('wall_id'),
        ]);
        
        return redirect()->back()->with('success', 'Пост успешно создан!');
    }

    public function destroy(Post $post){
        $post->delete();
        return redirect()->back()->with('success', 'Пост успешно удален!');
    }

    public function toggleLike(Post $post)
    {
        $isLiked = $post->likes()->where('user_id', auth()->id())->exists();
        
        if ($isLiked) {
            $post->likes()->where('user_id', auth()->id())->delete();
            $isLiked = false;
        } else {
            $post->likes()->create([
                'user_id' => auth()->id()
            ]);
            $isLiked = true;
        }

        return response()->json([
            'likes_count' => $post->likes()->count(),
            'is_liked' => $isLiked
        ]);
    }
}