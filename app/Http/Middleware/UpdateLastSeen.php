<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastSeen
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            \Log::info('UpdateLastSeen сработал для пользователя: ' . Auth::id());
            Auth::user()->update(['last_seen' => now()]);
        }
        return $next($request);
    }
}