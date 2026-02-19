<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Intention;
use App\Models\Donation;
use App\Models\Announcement;
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
     * Gestión de Usuarios
     */
    public function users()
    {
        $users = User::with('roles')->latest()->paginate(10);
        $allRoles = Role::withCount('users')->get();
        return view('admin.users.index', compact('users', 'allRoles'));
    }

    /**
     * Actualizar roles con protección para Super Admin
     * CORRECCIÓN: Se elimina la asignación automática de 'admin_grupo_parroquial'
     */
    public function updateUserRoles(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Los privilegios del Super Administrador son permanentes.');
        }

        // Roles básicos (admin, user, etc) marcados en los checkboxes de roles generales
        $rolesToSync = $request->input('basic_roles', []);
        
        $grupos = [
            'catequesis_ninos', 'catequesis_adolescentes', 'catequesis_adultos', 
            'acutis', 'juveniles', 'juan_pablo', 'coro', 'misioneros', 
            'santa_ana', 'san_joaquin', 'ardillas', 'costureras', 
            'caridad', 'caritas', 'comedor'
        ];

        foreach ($grupos as $grupo) {
            // Si se marca como miembro
            if ($request->has("member_$grupo")) {
                $rolesToSync[] = $grupo;
            }
            // Si se marca como administrador específico
            if ($request->has("admin_$grupo")) {
                $rolesToSync[] = "admin_$grupo";
                $rolesToSync[] = $grupo; // También lo hacemos miembro por lógica
            }
        }

        try {
            // Buscamos los IDs de los roles únicos seleccionados
            $roleIds = Role::whereIn('name', array_unique($rolesToSync))->pluck('id')->toArray();
            
            // Sincronizamos (esto borra los viejos y pone los nuevos)
            $user->roles()->sync($roleIds);
            
            return back()->with('success', 'Roles y permisos de comunidad actualizados.');
        } catch (\Exception $e) {
            Log::error("Error actualizando roles de usuario {$id}: " . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al intentar guardar los cambios.');
        }
    }

    /**
     * Eliminar usuario
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
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

    public function deleteIntention($id)
    {
        try {
            Intention::findOrFail($id)->delete();
            return back()->with('success', 'Intención eliminada.');
        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo eliminar.');
        }
    }

    public function deleteAllIntentions()
    {
        try {
            Intention::query()->delete();
            return back()->with('success', 'Lista de intenciones vaciada.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al vaciar la lista.');
        }
    }

    /**
     * Listado de Donaciones
     */
    public function donations()
    {
        $donations = Donation::latest()->paginate(15);
        return view('admin.donations.index', compact('donations'));
    }

    /**
     * Imprimir Intenciones
     */
    public function printIntentions(Request $request)
    {
        config(['app.timezone' => 'America/Argentina/Buenos_Aires']);
        $intentions = Intention::when($request->type, function($query, $type) {
                return $query->where('type', $type);
            })->when($request->date, function($query, $date) {
                return $query->whereDate('created_at', $date);
            })->latest()->get();

        return view('admin.intentions.print', compact('intentions'));
    }
}