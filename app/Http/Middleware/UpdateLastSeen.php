<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastSeen
{
    protected $timeout = 0;
    public function handle(Request $request, Closure $next): Response
    {
        \Log::info('UpdateLastSeen middleware вызван');
        $user = Auth::user();
        if ($user) {
            \Log::info('UpdateLastSeen: найден пользователь', ['user_id' => $user->id]);
            $user->last_seen = now();
            $user->update();
            \Log::info('UpdateLastSeen: last_seen обновлён', ['user_id' => $user->id, 'last_seen' => $user->last_seen]);
        }else{
            \Log::info('UpdateLastSeen: пользователь не найден');
        }
        return $next($request);
    }
}