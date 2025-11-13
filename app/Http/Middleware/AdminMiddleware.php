<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Debug
        Log::info('AdminMiddleware ejecutándose para: ' . $request->path());
        
        if (!Auth::check()) {
            Log::info('Usuario no autenticado, redirigiendo a login');
            return redirect('/login')->with('error', 'Debes iniciar sesión para acceder al panel de administración.');
        }

        $user = Auth::user();
        
        Log::info('Usuario autenticado:', [
            'id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'isAdmin' => $user->isAdmin()
        ]);

        // Verificar si es admin
        if (!$user->isAdmin()) {
            Log::warning('Usuario no es admin, redirigiendo a home');
            
            // Redirigir a una ruta que NO ejecute este middleware
            return redirect('/')->with('error', 'No tienes permisos para acceder al panel de administración.');
        }

        Log::info('Acceso admin permitido');
        return $next($request);
    }
}