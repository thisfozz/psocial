<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/social/{id}', [App\Http\Controllers\SocialController::class, 'show'])
    ->middleware('auth')
    ->name('social.show');

require __DIR__ . '/auth.php';