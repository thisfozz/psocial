<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}