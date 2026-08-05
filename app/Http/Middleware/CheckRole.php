<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Si el usuario no está autenticado, lo manda al login
        if (! $request->user()) {
            return redirect('/login');
        }

        // 2. Si el rol del usuario actual no coincide con el rol requerido, deniega el acceso (403)
        if ($request->user()->role !== $role) {
            abort(403, 'Acceso denegado: No tienes permisos para ingresar a este módulo.');
        }

        return $next($request);
    }
}