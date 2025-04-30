<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

Broadcast::routes();

Route::middleware('auth')->group(function (){
    Broadcast::channel('chat.{userA}.{userB}', function (User $user, $userA, $userB){
        $userA = (int) $userA;
        $userB = (int) $userB;
    
        return $user->id === $userA || $user->id === $userB;
    });
});

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/social/{id}', [App\Http\Controllers\SocialController::class, 'show'])
    ->middleware('auth')
    ->name('social.show');

require __DIR__ . '/auth.php';