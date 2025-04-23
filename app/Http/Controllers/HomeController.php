<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            return redirect()->route('social.show', ['id' => auth()->id()]);
        }
        return view('home');
    }
}