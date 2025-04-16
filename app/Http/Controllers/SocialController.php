<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friend;
class SocialController extends Controller
{
    public function index()
    {
        return redirect()->route('social.show', ['id' => auth()->id()]);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        $friends = $user->friends()->with('friend')->get();
        return view('social.index', ['user' => $user, 'friends' => $friends]);
    }
}