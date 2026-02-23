<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use App\Models\Role;
use App\Models\Announcement;
use App\Notifications\AvisoComunidad; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;

class GroupController extends Controller
{
    /**
     * Normaliza el identificador del grupo (slug).
     */
    private function normalizeSlug($slug)
    {
        $slug = strtolower(trim($slug));
        $slug = str_replace('admin_', '', $slug);
        return str_replace('-', '_', $slug);
    }

    /**
     * Verifica si el usuario actual es un coordinador autorizado para el grupo.
     */
    private function isAuthorizedCoordinator($slug)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user) return false;

        $baseSlug = $this->normalizeSlug($slug);

        if ($user->isAdmin() || $user->isSuperAdmin()) return true;
        if ($user->hasRole('admin_' . $baseSlug)) return true;
        if ($user->hasRole('admin_grupo_parroquial')) return true;

        return false;
    }

    /**
     * DASHBOARD CENTRAL DE USUARIO (HUB)
     */
    public function userDashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $allGroupSlugs = [
            'catequesis_ninos', 'catequesis_adolescentes', 'catequesis_adultos', 
            'acutis', 'juveniles', 'juan_pablo', 'coro', 'misioneros', 
            'santa_ana', 'san_joaquin', 'ardillas', 'costureras', 
            'caridad', 'caritas', 'comedor'
        ];

        $userGroups = $user->roles->filter(function($r) use ($allGroupSlugs) {
            $slug = str_replace('admin_', '', $r->name);
            return in_array($slug, $allGroupSlugs);
        })->unique(fn($r) => str_replace('admin_', '', $r->name));

        $unreadNotifications = $user->unreadNotifications()->take(5)->get();
        $announcements = Announcement::where('is_active', true)->orderBy('order')->latest()->get();

        return view('dashboard', compact('userGroups', 'unreadNotifications', 'allGroupSlugs', 'announcements'));
    }

    /**
     * Panel de Gestión del Grupo (Solo para Coordinadores).
     */
    public function groupDashboard($groupRole)
    {
        $slug = $this->normalizeSlug($groupRole);
        $group = Group::where('category', $slug)->first();
        if (!$group) abort(404);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$this->isAuthorizedCoordinator($slug)) {
            if ($user->hasRole($slug)) {
                if ($group->group_password && !session('group_unlocked_' . $slug)) {
                    return redirect()->route('grupos.verify-form', $groupRole)
                        ->with('info', 'Este grupo requiere una contraseña de acceso.');
                }
                return redirect()->route('grupos.materials', $groupRole);
            }
            return redirect()->route('dashboard')->with('error', 'No tienes permisos de acceso.');
        }

        $groupName = $group->name;
        
        // Obtener los últimos 5 miembros agregados con JOIN
        $latestMembers = User::join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->join('roles', 'user_roles.role_id', '=', 'roles.id')
            ->where('roles.name', $slug)
            ->whereDoesntHave('roles', fn($q) => $q->where('name', 'superadmin'))
            ->select('users.*', 'user_roles.created_at as joined_at_group')
            ->orderBy('user_roles.created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($member) {
                $member->joined_at = Carbon::parse($member->joined_at_group);
                $member->is_active_now = $member->updated_at->diffInMinutes(now()) < 15;
                return $member;
            });
        
        $totalMembers = User::whereHas('roles', fn($q) => $q->where('name', $slug))
            ->whereDoesntHave('roles', fn($q) => $q->where('name', 'superadmin'))
            ->count();
        
        $materials = DB::table('group_materials')->where('group_role', $slug)->orderBy('created_at', 'desc')->take(10)->get()
            ->map(function($m) { $m->created_at = Carbon::parse($m->created_at); return $m; });

        $requests = DB::table('group_requests')
            ->join('users', 'group_requests.user_id', '=', 'users.id')
            ->where('group_requests.group_role', $slug)
            ->where('group_requests.status', 'pending')
            ->select('group_requests.*', 'users.name', 'users.email', 'users.age')
            ->get();

        return view('grupos.dashboard-grupos', compact('group', 'groupName', 'groupRole', 'latestMembers', 'totalMembers', 'materials', 'requests', 'slug'));
    }

    /**
     * VISTA COMPLETA DE MIEMBROS: Con buscador funcional y paginación.
     */
    public function allMembers(Request $request, $groupRole)
    {
        $slug = $this->normalizeSlug($groupRole);
        if (!$this->isAuthorizedCoordinator($slug)) abort(403);
        
        $group = Group::where('category', $slug)->firstOrFail();
        $groupName = $group->name;
        $search = trim($request->input('search'));
        
        $query = User::join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->join('roles', 'user_roles.role_id', '=', 'roles.id')
            ->where('roles.name', $slug)
            ->whereDoesntHave('roles', fn($q) => $q->where('name', 'superadmin'))
            ->select('users.*', 'user_roles.created_at as joined_at_group');

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('users.name', 'LIKE', "%{$search}%")
                  ->orWhere('users.email', 'LIKE', "%{$search}%");
            });
        }

        $members = $query->orderBy('users.name', 'asc')
                         ->paginate(15)
                         ->withQueryString();

        return view('grupos.members', compact('members', 'group', 'groupName', 'groupRole', 'slug', 'search'));
    }

    /**
     * Eliminar todas las solicitudes pendientes masivamente.
     */
    public function deleteAllRequests($groupRole)
    {
        $slug = $this->normalizeSlug($groupRole);
        if (!$this->isAuthorizedCoordinator($slug)) abort(403);

        DB::table('group_requests')
            ->where('group_role', $slug)
            ->where('status', 'pending')
            ->delete();

        return back()->with('success', 'Todas las solicitudes pendientes han sido eliminadas.');
    }

    /**
     * Gestión de Categorías de Grupos.
     */
    public function category($slug)
    {
        $categorySlug = str_replace('-', '_', strtolower(trim($slug)));
        
        $categoryMapping = [
            'catequesis' => [
                'title' => 'Catequesis',
                'desc' => 'Formación sacramental para niños, adolescentes y adultos.',
                'slugs' => ['catequesis_ninos', 'catequesis_adolescentes', 'catequesis_adultos']
            ],
            'jovenes' => [
                'title' => 'Jóvenes',
                'desc' => 'Comunidad y formación para chicos de 11 a 35 años.',
                'slugs' => ['acutis', 'juveniles', 'juan_pablo', 'coro', 'misioneros']
            ],
            'mayores' => [
                'title' => 'Mayores',
                'desc' => 'Espacios de oración y fraternidad para adultos mayores.',
                'slugs' => ['santa_ana', 'san_joaquin', 'ardillas', 'costureras']
            ],
            'especiales' => [
                'title' => 'Más Grupos',
                'desc' => 'Servicio, caridad y misiones especiales de nuestra parroquia.',
                'slugs' => ['caridad', 'caritas', 'comedor']
            ],
            'mas_grupos' => [
                'title' => 'Más Grupos',
                'desc' => 'Servicio, caridad y misiones especiales de nuestra parroquia.',
                'slugs' => ['caridad', 'caritas', 'comedor']
            ]
        ];

        if (!isset($categoryMapping[$categorySlug])) {
             $groups = Group::where('category', 'like', $categorySlug . '%')->where('is_active', true)->get();
             return view('grupos.categoria', [
                'categoria'   => ucfirst(str_replace('_', ' ', $categorySlug)),
                'descripcion' => 'Explora nuestras comunidades parroquiales.',
                'groups'      => $groups,
                'slug'        => $categorySlug
            ]);
        }

        $config = $categoryMapping[$categorySlug];
        $groups = Group::whereIn('category', $config['slugs'])->where('is_active', true)->get();

        return view('grupos.categoria', [
            'categoria'   => $config['title'],
            'descripcion' => $config['desc'],
            'groups'      => $groups,
            'slug'        => $categorySlug
        ]);
    }

    /**
     * Subida de Material.
     */
    public function uploadMaterial(Request $request, $groupRole) {
        $slug = $this->normalizeSlug($groupRole);
        if (!$this->isAuthorizedCoordinator($slug)) return response()->json(['success' => false], 403);
        
        $request->validate([
            'title' => 'required|max:255', 
            'type' => 'required', 
            'file' => 'required|max:512000', 
            'description' => 'nullable|string|max:1000'
        ]);

        try {
            $disk = config('filesystems.default'); 
            $filePath = $request->file('file')->store('materials/' . $slug, $disk);
            
            DB::table('group_materials')->insert([
                'group_role' => $slug, 'title' => $request->title, 'description' => $request->description,
                'type' => $request->type, 'file_path' => $filePath, 'is_active' => true, 
                'created_at' => now(), 'updated_at' => now()
            ]);

            $members = User::whereHas('roles', fn($q) => $q->where('name', $slug))->get();
            if ($members->isNotEmpty()) {
                $group = Group::where('category', $slug)->first();
                $targetUrl = ($group && $group->group_password) 
                    ? route('grupos.verify-form', $groupRole)
                    : route('grupos.materials', $groupRole);

                Notification::send($members, new AvisoComunidad(
                    "Nuevo material en " . ($group->name ?? $slug),
                    "Se ha compartido: " . $request->title,
                    $targetUrl
                ));
            }
            return response()->json(['success' => true, 'message' => 'Material subido correctamente.']);
        } catch (\Exception $e) { 
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500); 
        }
    }

    /**
     * Biblioteca de materiales.
     */
    public function groupMaterials($groupRole)
    {
        $slug = $this->normalizeSlug($groupRole);
        $group = Group::where('category', $slug)->firstOrFail();
        $groupName = $group->name;
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole($slug) && !$this->isAuthorizedCoordinator($slug)) {
            return redirect()->route('dashboard')->with('error', 'Acceso restringido.');
        }

        if ($group->group_password && !session('group_unlocked_' . $slug) && !$this->isAuthorizedCoordinator($slug)) {
            return redirect()->route('grupos.verify-form', $groupRole);
        }
        
        $materials = DB::table('group_materials')->where('group_role', $slug)->where('is_active', true)->orderBy('created_at', 'desc')->paginate(12);

        $materials->getCollection()->transform(function($m) {
            $disk = config('filesystems.default');
            $m->created_at = Carbon::parse($m->created_at);
            try { $m->public_url = Storage::disk($disk)->url($m->file_path); } catch(\Exception $e) { $m->public_url = '#'; }
            
            // CORRECCIÓN: Aseguramos que file_type exista para evitar el error de stdClass
            $m->file_type = property_exists($m, 'type') ? $m->type : 'desconocido';
            $type = strtolower($m->file_type);

            $m->file_icon = match(true) {
                $type === 'pdf' => 'img/icono_pdf.png',
                in_array($type, ['jpg', 'png', 'jpeg', 'image']) => 'img/icono_imagen.png',
                in_array($type, ['mp4', 'mov', 'video']) => 'img/icono_video.png',
                in_array($type, ['mp3', 'wav', 'audio']) => 'img/icono_audio.png',
                default => 'img/icono_docs.png'
            };

            $m->file_size_formatted = '---';
            try {
                if (Storage::disk($disk)->exists($m->file_path)) {
                    $m->file_size_formatted = round(Storage::disk($disk)->size($m->file_path) / 1024 / 1024, 2) . ' MB';
                }
            } catch (\Exception $e) {}
            
            $m->can_preview = in_array($type, ['pdf', 'image', 'jpg', 'png', 'jpeg', 'mp4', 'mov', 'video']);
            return $m;
        });

        return view('grupos.materials', compact('group', 'groupName', 'groupRole', 'materials'));
    }

    /**
     * Ver material.
     */
    public function viewMaterial($id) {
        $m = DB::table('group_materials')->where('id', $id)->first();
        if (!$m) abort(404);
        $disk = config('filesystems.default');
        if (!Storage::disk($disk)->exists($m->file_path)) abort(404);
        if ($disk === 's3' || $disk === 'r2') return redirect(Storage::disk($disk)->url($m->file_path));
        return response()->file(Storage::disk($disk)->path($m->file_path), ['Content-Disposition' => 'inline']);
    }

    /**
     * Descarga de material.
     */
    public function downloadMaterial($id) {
        $m = DB::table('group_materials')->where('id', $id)->first();
        if (!$m) abort(404);
        $disk = config('filesystems.default');
        return Storage::disk($disk)->download($m->file_path, $m->title);
    }

    /**
     * Procesa solicitudes de unión.
     */
    public function handleRequest(Request $request, $requestId) {
        $sol = DB::table('group_requests')->where('id', $requestId)->first();
        if (!$sol || !$this->isAuthorizedCoordinator($sol->group_role)) abort(403);
        $status = ($request->action === 'approve') ? 'approved' : 'rejected';
        DB::table('group_requests')->where('id', $requestId)->update(['status' => $status, 'updated_at' => now()]);
        if ($status === 'approved') { 
            $u = User::find($sol->user_id); 
            $r = Role::where('name', $sol->group_role)->first(); 
            if ($u && $r) { 
                $u->roles()->syncWithoutDetaching([$r->id]); 
                try { $u->notify(new AvisoComunidad("¡Bienvenido!", "Tu solicitud ha sido aceptada.", route('grupos.materials', $sol->group_role))); } catch (\Exception $e) {} 
            } 
        }
        return back()->with('success', 'Procesado.');
    }

    /**
     * Remover miembro.
     */
    public function removeMember($groupRole, $userId) {
        $slug = $this->normalizeSlug($groupRole);
        if (!$this->isAuthorizedCoordinator($slug)) abort(403);
        $u = User::findOrFail($userId); 
        $r = Role::where('name', $slug)->first(); 
        if ($r) $u->roles()->detach($r->id);
        return response()->json(['success' => true]);
    }

    /**
     * Eliminar material.
     */
    public function deleteMaterial($id) {
        $m = DB::table('group_materials')->where('id', $id)->first(); 
        if ($m && $this->isAuthorizedCoordinator($m->group_role)) {
            Storage::disk(config('filesystems.default'))->delete($m->file_path); 
            DB::table('group_materials')->where('id', $id)->delete(); 
            return response()->json(['success' => true]); 
        }
        return response()->json(['success' => false], 403);
    }

    /**
     * Feligrés solicita unirse.
     */
    public function sendRequest(Request $request, $groupRole) {
        $slug = $this->normalizeSlug($groupRole);
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->age) return back()->with('error', 'Completa tu edad.');
        $group = Group::where('category', $slug)->first();
        if ($group && ($user->age < $group->min_age || $user->age > $group->max_age)) return back()->with('error', "Edad no permitida.");
        
        if (DB::table('group_requests')->where('user_id', $user->id)->where('group_role', $slug)->where('status', 'pending')->exists()) {
            return back()->with('info', 'Solicitud pendiente.');
        }

        DB::table('group_requests')->insert(['user_id' => $user->id, 'group_role' => $slug, 'status' => 'pending', 'created_at' => now(), 'updated_at' => now()]);

        // NOTIFICACIÓN A COORDINADORES: Alguien quiere unirse
        $admins = User::whereHas('roles', function($q) use ($slug) {
            $q->whereIn('name', ['superadmin', 'admin', 'admin_' . $slug]);
        })->get();

        if ($admins->isNotEmpty()) {
            Notification::send($admins, new AvisoComunidad(
                "Nueva solicitud",
                "{$user->name} quiere unirse a " . ($group->name ?? $slug),
                route('grupos.dashboard', $slug)
            ));
        }

        return back()->with('success', 'Solicitud enviada.');
    }

    public function index() { return view('grupos.index', ['groups' => Group::where('is_active', true)->get()]); }
    public function completeOnboarding(Request $request) { Auth::user()->update(['onboarding_completed' => true, 'age' => $request->age]); return response()->json(['success' => true]); }
    public function getRecommendedGroups(Request $request) { return response()->json(Group::where('is_active', true)->where('min_age', '<=', $request->age)->where('max_age', '>=', $request->age)->get()); }

    public function showVerifyPassword($groupRole) {
        $slug = $this->normalizeSlug($groupRole);
        $group = Group::where('category', $slug)->firstOrFail();
        if (session('group_unlocked_' . $slug)) return redirect()->route('grupos.materials', $groupRole);
        return view('grupos.verify-password', compact('group', 'groupRole'));
    }

    public function verifyPassword(Request $request, $groupRole) {
        $slug = $this->normalizeSlug($groupRole);
        $group = Group::where('category', $slug)->firstOrFail();
        $request->validate(['password' => 'required|string']);
        if ($request->password === $group->group_password) {
            session(['group_unlocked_' . $slug => true]);
            return redirect()->route('grupos.materials', $groupRole);
        }
        return back()->withErrors(['password' => 'La contraseña es incorrecta.']);
    }

    public function updateGroupPassword(Request $request, $groupRole) {
        $slug = $this->normalizeSlug($groupRole);
        if (!$this->isAuthorizedCoordinator($slug)) abort(403);
        $request->validate(['group_password' => 'nullable|string|min:4|max:255']);
        $group = Group::where('category', $slug)->firstOrFail();
        $group->update(['group_password' => $request->group_password]);
        return back()->with('success', 'Configuración de seguridad actualizada.');
    }
}