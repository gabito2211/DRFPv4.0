<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Maneja la verificación de roles autorizados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Convertir parámetros a enteros para comparar con $user->rol
        $rolesPermitidos = array_map('intval', $roles);

        if (in_array((int)$user->rol, $rolesPermitidos, true)) {
            return $next($request);
        }

        // Redireccionar si no tiene permiso
        return redirect('/inicio')->with('error', 'No tienes permisos para acceder a este módulo.');
    }
}