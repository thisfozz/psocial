<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FriendRequestController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Middleware\UpdateLastSeen;
use App\Http\Controllers\SocialController;


Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware(['auth', UpdateLastSeen::class])->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::post('/friend-request/{toUserId}', [FriendRequestController::class, 'sendRequest'])->name('friend-request.send');
    Route::post('/friend-request/accept/{requestId}', [FriendRequestController::class, 'acceptRequest'])->name('friend-request.accept');
    Route::post('/friend-request/decline/{requestId}', [FriendRequestController::class, 'declineRequest'])->name('friend-request.decline');
    Route::post('/friend-request/cancel/{requestId}', [FriendRequestController::class, 'cancelRequest'])->name('friend-request.cancel');
    Route::post('/friends/remove/{id}', [FriendRequestController::class, 'removeFriend'])->name('friends.remove');

    Route::post('/posts', [PostController::class, 'store'])->name('post-publish');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('post.destroy');
    Route::post('/posts/{post}/toggleLike', [PostController::class, 'toggleLike'])->name('post.toggleLike');

    Route::get('/search-users', [UserController::class, 'search'])->name('search-users');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/broadcast', [MessageController::class, 'broadcast'])->name('broadcast');
    Route::post('/receive', [MessageController::class, 'receive'])->name('receive');
    Route::post('/messages/set-friend', [MessageController::class, 'setFriend'])->name('messages.setFriend');

    Route::get('/social/{id}', [SocialController::class, 'show'])->name('social.show');
});