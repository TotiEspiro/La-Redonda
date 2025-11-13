<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckGrupoParroquial
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Verificar si tiene algún rol de grupo parroquial
        $grupoParroquialRoles = [
            'admin_grupo_parroquial', 'catequesis', 'juveniles', 'acutis', 
            'juan_pablo', 'coro', 'san_joaquin', 'santa_ana', 'ardillas', 
            'costureras', 'misioneros', 'caridad_comedor'
        ];

        // Verificar si el usuario tiene al menos un rol de grupo parroquial
        $hasGrupoParroquialRole = $user->hasAnyRole($grupoParroquialRoles);
        
        // Solo permitir acceso si tiene rol de grupo parroquial O es admin/superadmin
        if (!$hasGrupoParroquialRole && !$user->isAdmin() && !$user->isSuperAdmin()) {
            return redirect()->route('home')->with('error', 'No tienes acceso al Diario de La Redonda. Solo miembros de grupos parroquiales pueden acceder.');
        }

        return $next($request);
    }
}