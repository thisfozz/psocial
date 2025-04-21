<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('social.index');
    }
    return view('home');
})->name('home');


Route::get('/social/{id}', [App\Http\Controllers\SocialController::class, 'show'])
    ->middleware('auth')
    ->name('social.show');

require __DIR__ . '/auth.php';