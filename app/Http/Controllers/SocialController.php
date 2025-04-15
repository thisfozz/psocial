<?php

namespace App\Http\Controllers;

use App\Models\User;

class SocialController extends Controller
{
    public function index()
    {
        return redirect()->route('social.show', ['id' => auth()->id()]);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('social.index', ['user' => $user]);
    }
}