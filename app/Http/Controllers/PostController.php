<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\PostVideo;
class PostController extends Controller
{
    public function show(){}
    public function store(Request $request){
        $validated = $request->validate([
            'content' => 'nullable|string|required_without:images',
            'images' => 'nullable|array|required_without:content',
            'images.*' => 'image|max:8192',
        ]);

        if (!Storage::disk('s3')->exists('user_' . auth()->id())) {
            Storage::disk('s3')->makeDirectory('user_' . auth()->id());
        }

        $content = $request->input('content') ?? '';
        $post = $request->user()->posts()->create([
            'content' => $content,
            'user_id' => auth()->id(),
            'wall_id' => $request->input('wall_id'),
        ]);

        $videoData = PostVideo::extractVideoData($content);
        if($videoData) {
            $post->videos()->create([
                'platform' => $videoData['platform'],
                'video_id' => $videoData['video_id'],
                'embed_code' => $videoData['embed_code'],
                'thumbnail_url' => $videoData['thumbnail_url'],
            ]);
            $post->update(['content' => '']);
        }

        if($request->hasFile('images')){
            foreach ($request->file('images', []) as $image) {
                $extension = $image->getClientOriginalExtension();
                $filename = 'images_' . now()->format('YmdHis') . '_' . Str::random(8) . '.' . $extension;
                $path = $image->storeAs('user_' . auth()->id(), $filename, 's3');

                $post->images()->create([
                    'user_id' => auth()->id(),
                    'image_path' => $path,
                ]);
            }
        }

        if ($request->wantsJson()) {
            return response()->json(['ok' => true]);
        }
        return redirect()->back()->with('success', 'Пост успешно опубликован!');
    }

    public function destroy(Post $post){
        foreach ($post->images as $image) {
            Storage::disk('s3')->delete($image->image_path);
        }
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

        return response()->json(['likes_count' => $post->likes()->count(),'is_liked' => $isLiked]);
    }
}