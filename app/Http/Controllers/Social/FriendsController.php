<?php

namespace App\Http\Controllers\Social;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FriendsController extends Controller
{
    public function index(Request $request){
        $friends = $request->user()->friends;
        return view('social.friends', compact('friends'));
    }
}