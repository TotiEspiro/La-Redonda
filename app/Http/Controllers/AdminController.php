<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Intention;
use App\Models\Donation;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Dashboard principal
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

        return view('admin.dashboard', compact('stats'));
    }

    // Gestión de Usuarios
    public function users()
    {
        $users = User::with('roles')->latest()->paginate(10);
        $allRoles = Role::all();
        return view('admin.users.index', compact('users', 'allRoles'));
    }

    // Actualizar roles múltiples de usuario
    public function updateUserRoles(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Prevenir que se modifiquen los roles del superadmin (excepto por otro superadmin)
        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            return back()->with('error', 'No puedes modificar los roles de un Super Administrador.');
        }

        $selectedRoles = $request->input('roles', []);
        
        // Sincronizar roles
        $roleIds = Role::whereIn('name', $selectedRoles)->pluck('id')->toArray();
        $user->roles()->sync($roleIds);

        return back()->with('success', 'Roles actualizados correctamente.');
    }

    // Eliminar usuario
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        // Evitar que el usuario se elimine a sí mismo
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propio usuario.');
        }

        // Prevenir eliminar superadmin (excepto por otro superadmin)
        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            return back()->with('error', 'No puedes eliminar un Super Administrador.');
        }

        $user->delete();
        return back()->with('success', 'Usuario eliminado correctamente.');
    }

    // Gestión de Intenciones
    public function intentions()
    {
        $intentions = Intention::latest()->paginate(10);
        return view('admin.intentions.index', compact('intentions'));
    }

    // Imprimir intenciones
    public function printIntentions(Request $request)
    {
        // Establecer zona horaria de Argentina
        config(['app.timezone' => 'America/Argentina/Buenos_Aires']);
        
        $intentions = Intention::when($request->type, function($query, $type) {
                return $query->where('type', $type);
            })->when($request->date, function($query, $date) {
                return $query->whereDate('created_at', $date);
            })->latest()->get();

        return view('admin.intentions.print', compact('intentions'));
    }

    // Eliminar intención
    public function deleteIntention($id)
    {
        $intention = Intention::findOrFail($id);
        $intention->delete();

        return back()->with('success', 'Intención eliminada correctamente.');
    }

    // Gestión de Donaciones
    public function donations()
    {
        $donations = Donation::latest()->paginate(10);
        return view('admin.donations.index', compact('donations'));
    }

    public function getIntention($id)
    {
        $intention = Intention::findOrFail($id);
        return response()->json([
            'name' => $intention->name,
            'email' => $intention->email,
            'type' => $intention->type,
            'formatted_type' => $intention->formatted_type,
            'message' => $intention->message,
            'created_at' => $intention->created_at,
        ]);
    }

    // Gestión de Anuncios (redirige al AnnouncementController)
    public function announcements()
    {
        return redirect()->route('admin.announcements.index');
    }

    // Vista rápida de anuncios en dashboard
    public function getAnnouncementsStats()
    {
        $stats = [
            'total' => Announcement::count(),
            'active' => Announcement::where('is_active', true)->count(),
            'inactive' => Announcement::where('is_active', false)->count(),
        ];

        return response()->json($stats);
    }
}