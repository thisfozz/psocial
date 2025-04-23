<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FriendRequestController;

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function (){
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::post('/friend-request/{toUserId}', [FriendRequestController::class, 'sendRequest'])->name('friend-request.send');
    Route::post('/friend-request/accept/{requestId}', [FriendRequestController::class, 'acceptRequest'])->name('friend-request.accept');
    Route::post('/friend-request/decline/{requestId}', [FriendRequestController::class, 'declineRequest'])->name('friend-request.decline');
    Route::post('/friend-request/cancel/{requestId}', [FriendRequestController::class, 'cancelRequest'])->name('friend-request.cancel');
    Route::post('/friends/remove/{id}', [FriendRequestController::class, 'removeFriend'])->name('friends.remove');
});