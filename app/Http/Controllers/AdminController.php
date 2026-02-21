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
     * Dashboard principal con estadísticas y avisos recientes.
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
     * Gestión de Usuarios - Listado con paginación.
     */
    public function users()
    {
        // Se cambia el límite de paginación a 15 usuarios por página
        $users = User::with('roles')->latest()->paginate(15);
        $allRoles = Role::withCount('users')->get();
        return view('admin.users.index', compact('users', 'allRoles'));
    }

    /**
     * Actualización dinámica de roles.
     * Esta versión busca por 'slug' y 'name' para asegurar la sincronización 
     * tras la migración de la columna slug.
     */
    public function updateUserRoles(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Protección de seguridad para el Super Administrador
        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Los privilegios del Super Administrador son permanentes y no pueden ser modificados.');
        }

        // Recolectamos los datos enviados desde los dos grupos de checkboxes de la vista
        $basicRoles = $request->input('basic_roles', []); // admin, usuario
        $groupRoles = $request->input('roles', []);       // catequesis_ninos, admin_acutis, etc.
        
        // Fusionamos en una lista única de identificadores (slugs/names)
        $requestedIdentifiers = array_filter(array_unique(array_merge($basicRoles, $groupRoles)));

        try {
            // Buscamos los roles en la BD comparando contra slug o name por seguridad
            $rolesInDb = Role::whereIn('slug', $requestedIdentifiers)
                             ->orWhereIn('name', $requestedIdentifiers)
                             ->get();

            $roleIds = $rolesInDb->pluck('id')->toArray();

            // Sincronizamos la relación: elimina los anteriores y agrega los nuevos IDs
            $user->roles()->sync($roleIds);
            
            // Verificamos si hubo algún identificador que no se encontró en la base de datos
            $foundIdentifiers = $rolesInDb->pluck('slug')->merge($rolesInDb->pluck('name'))->unique()->toArray();
            $missing = array_diff($requestedIdentifiers, $foundIdentifiers);

            if (count($missing) > 0) {
                return back()->with('success', 'Se actualizaron los roles, pero algunos no existen en la BD y fueron omitidos: ' . implode(', ', $missing));
            }

            return back()->with('success', 'Roles y permisos de comunidad actualizados correctamente.');
        } catch (\Exception $e) {
            Log::error("Error actualizando roles de usuario {$id}: " . $e->getMessage());
            return back()->with('error', 'Error técnico al intentar guardar los cambios.');
        }
    }

    /**
     * Eliminación de usuario con restricciones.
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->isSuperAdmin() || $user->id === Auth::id()) {
            return back()->with('error', 'No se puede eliminar a este usuario por razones de seguridad.');
        }

        $user->delete();
        return back()->with('success', 'El usuario ha sido eliminado correctamente.');
    }

    /**
     * Gestión de Intenciones de Misa.
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
            return back()->with('success', 'Intención eliminada con éxito.');
        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo eliminar la intención.');
        }
    }

    public function deleteAllIntentions()
    {
        try {
            Intention::query()->delete();
            return back()->with('success', 'La lista de intenciones ha sido vaciada.');
        } catch (\Exception $e) {
            return back()->with('error', 'Ocurrió un error al intentar vaciar la lista.');
        }
    }

    /**
     * Listado histórico de Donaciones.
     */
    public function donations()
    {
        $donations = Donation::latest()->paginate(15);
        return view('admin.donations.index', compact('donations'));
    }

    /**
     * Generación de vista para impresión de intenciones.
     */
    public function printIntentions(Request $request)
    {
        // Ajuste de zona horaria para reportes precisos
        config(['app.timezone' => 'America/Argentina/Buenos_Aires']);
        
        $intentions = Intention::when($request->type, function($query, $type) {
                return $query->where('type', $type);
            })->when($request->date, function($query, $date) {
                return $query->whereDate('created_at', $date);
            })->latest()->get();

        return view('admin.intentions.print', compact('intentions'));
    }
}