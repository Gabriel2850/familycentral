<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Check2FA
{
    public function handle(Request $request, Closure $next)
    {
        // Si el usuario intentó iniciar sesión y requiere 2FA
        if (session()->has('2fa_user_id')) {
            return redirect()->route('2fa.challenge');
        }

        return $next($request);
    }
}