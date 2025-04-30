<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

Broadcast::routes();

Route::middleware('auth')->group(function (){
    Broadcast::channel('private-chat.{userA}.{userB}', function (User $user, $userA, $userB){
        $userA = (int) $userA;
        $userB = (int) $userB;
    
        return $user->id === $userA || $user->id === $userB;
    });
});