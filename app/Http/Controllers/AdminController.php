<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Intention;
use App\Models\Donation;
use App\Models\Announcement;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Dashboard principal con estadísticas y avisos
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_intentions' => Intention::count(),
            'total_donations' => Donation::count(),
            'total_announcements' => Announcement::count(),
            'recent_intentions' => Intention::latest()->take(5)->get(),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_announcements' => Announcement::latest()->take(5)->get(),
        ];

        $announcements = Announcement::where('is_active', true)
            ->orderBy('order')
            ->latest()
            ->get();

        return view('admin.dashboard', compact('stats', 'announcements'));
    }

    /**
     * Gestión de Usuarios - Listado principal
     */
    public function users()
    {
        $users = User::with('roles')->latest()->paginate(10);
        $allRoles = Role::withCount('users')->get();
        return view('admin.users.index', compact('users', 'allRoles'));
    }

    /**
     * Actualizar roles (Versión Dinámica)
     * Procesa tanto los roles básicos como los roles de grupos enviados desde la vista.
     */
    public function updateUserRoles(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Protección para que nadie le quite el acceso al Super Admin
        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Los privilegios del Super Administrador son permanentes.');
        }

        // Obtenemos los slugs de los roles marcados en los arrays del formulario
        $basicRoles = $request->input('basic_roles', []); // admin, usuario, etc.
        $groupRoles = $request->input('roles', []);       // catequesis_ninos, admin_acutis, etc.
        
        // Combinamos todos los slugs en una sola lista única
        $allSlugsToSync = array_unique(array_merge($basicRoles, $groupRoles));

        try {
            // Buscamos los IDs de los roles en la base de datos usando los slugs
            $roleIds = Role::whereIn('slug', $allSlugsToSync)->pluck('id')->toArray();
            
            // Sincronizamos la relación (borra lo anterior y pone lo nuevo)
            $user->roles()->sync($roleIds);
            
            return back()->with('success', 'Roles y permisos de comunidad actualizados correctamente.');
        } catch (\Exception $e) {
            Log::error("Error actualizando roles de usuario {$id}: " . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al intentar guardar los cambios.');
        }
    }

    /**
     * Eliminar usuario con protecciones de seguridad
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // No permitir eliminarse a uno mismo ni eliminar al Superadmin
        if ($user->isSuperAdmin() || $user->id === Auth::id()) {
            return back()->with('error', 'No se puede eliminar este usuario por seguridad.');
        }

        $user->delete();
        return back()->with('success', 'Usuario eliminado de la base de datos.');
    }

    /**
     * Gestión de Intenciones
     */
    public function intentions()
    {
        $intentions = Intention::latest()->paginate(15);
        return view('admin.intentions.index', compact('intentions'));
    }

    /**
     * Eliminar una intención específica
     */
    public function deleteIntention($id)
    {
        try {
            Intention::findOrFail($id)->delete();
            return back()->with('success', 'Intención eliminada.');
        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo eliminar la intención.');
        }
    }

    /**
     * Vaciar toda la tabla de intenciones
     */
    public function deleteAllIntentions()
    {
        try {
            Intention::query()->delete();
            return back()->with('success', 'Lista de intenciones vaciada correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Ocurrió un error al intentar vaciar la lista.');
        }
    }

    /**
     * Listado de Donaciones (Integración con Mercado Pago / Manual)
     */
    public function donations()
    {
        $donations = Donation::latest()->paginate(15);
        return view('admin.donations.index', compact('donations'));
    }

    /**
     * Vista de impresión de intenciones con filtros de fecha y tipo
     */
    public function printIntentions(Request $request)
    {
        // Ajustamos zona horaria para la consulta
        config(['app.timezone' => 'America/Argentina/Buenos_Aires']);
        
        $intentions = Intention::when($request->type, function($query, $type) {
                return $query->where('type', $type);
            })->when($request->date, function($query, $date) {
                return $query->whereDate('created_at', $date);
            })->latest()->get();

        return view('admin.intentions.print', compact('intentions'));
    }
}