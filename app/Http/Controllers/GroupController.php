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
     * Normaliza el identificador del grupo.
     */
    private function normalizeSlug($slug)
    {
        $slug = strtolower(trim($slug));
        $slug = str_replace('admin_', '', $slug);
        return str_replace('-', '_', $slug);
    }

    /**
     * Verifica permisos de gestión.
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
        
        // CORRECCIÓN: 'catequesis_ninos' sin la 'ñ' para coincidir con el Seeder y la BD
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
     * Panel de Coordinación con info detallada de miembros.
     */
    public function groupDashboard($groupRole)
    {
        $slug = $this->normalizeSlug($groupRole);
        $group = Group::where('category', $slug)->first();
        if (!$group) abort(404);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$this->isAuthorizedCoordinator($slug)) {
            if ($user->hasRole($slug)) return redirect()->route('grupos.materials', $groupRole);
            return redirect()->route('dashboard')->with('error', 'No tienes permisos de gestión.');
        }

        $groupName = $group->name;
        
        // Miembros con fecha de unión (Pivot) y estado de actividad
        $members = User::whereHas('roles', fn($q) => $q->where('name', $slug))
            ->whereDoesntHave('roles', fn($q) => $q->where('name', 'superadmin'))
            ->with(['roles' => fn($q) => $q->where('name', $slug)])
            ->get()
            ->map(function($member) use ($slug) {
                $role = $member->roles->where('name', $slug)->first();
                $member->joined_at = ($role && $role->pivot->created_at) ? Carbon::parse($role->pivot->created_at) : $member->created_at;
                $member->is_active_now = $member->updated_at->diffInMinutes(now()) < 15;
                return $member;
            })
            ->sortByDesc('joined_at');
        
        $materials = DB::table('group_materials')->where('group_role', $slug)->orderBy('created_at', 'desc')->take(10)->get()
            ->map(function($m) { $m->created_at = Carbon::parse($m->created_at); return $m; });

        $requests = DB::table('group_requests')
            ->join('users', 'group_requests.user_id', '=', 'users.id')
            ->where('group_requests.group_role', $slug)
            ->where('group_requests.status', 'pending')
            ->select('group_requests.*', 'users.name', 'users.email', 'users.age')
            ->get();

        return view('grupos.dashboard-grupos', compact('group', 'groupName', 'groupRole', 'members', 'materials', 'requests', 'slug'));
    }

    /**
     * Categorías de grupos (CORREGIDO: Mapeo exhaustivo para "Más Grupos").
     */
    public function category($slug)
    {
        // Normalizamos el slug para buscar en el mapeo (quitamos guiones)
        $categorySlug = str_replace('-', '_', strtolower(trim($slug)));
        
        $categoryMapping = [
            'catequesis' => [
                'title' => 'Catequesis',
                'desc' => 'Formación sacramental para niños, adolescentes y adultos.',
                // CORRECCIÓN: 'catequesis_ninos' sin la 'ñ'
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
            // Agrupamos todos los posibles nombres para la categoría de caridad
            'especiales' => [
                'title' => 'Más Grupos',
                'desc' => 'Servicio, caridad y misiones especiales de nuestra parroquia.',
                'slugs' => ['caridad', 'caritas', 'comedor']
            ],
            'mas_grupos' => [
                'title' => 'Más Grupos',
                'desc' => 'Servicio, caridad y misiones especiales de nuestra parroquia.',
                'slugs' => ['caridad', 'caritas', 'comedor']
            ],
        ];

        // Si el slug no está en el mapeo manual, buscamos por coincidencia parcial
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
            'categoria'   => $config['title'], // "Más Grupos"
            'descripcion' => $config['desc'],
            'groups'      => $groups,
            'slug'        => $categorySlug
        ]);
    }

    /**
     * Subida de Material (Soporta 500MB + MP3/MP4 + Notificaciones).
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
            $filePath = $request->file('file')->store('materials/' . $slug, 'public');
            DB::table('group_materials')->insert([
                'group_role' => $slug, 'title' => $request->title, 'description' => $request->description,
                'type' => $request->type, 'file_path' => $filePath, 'is_active' => true, 
                'created_at' => now(), 'updated_at' => now()
            ]);

            $members = User::whereHas('roles', fn($q) => $q->where('name', $slug))->get();
            if ($members->isNotEmpty()) {
                $group = Group::where('category', $slug)->first();
                try {
                    Notification::send($members, new AvisoComunidad(
                        "Nuevo material en " . ($group->name ?? $slug),
                        "Se ha compartido: " . $request->title,
                        route('grupos.materials', $groupRole)
                    ));
                } catch (\Exception $e) { Log::warning("Error Push: " . $e->getMessage()); }
            }
            return response()->json(['success' => true, 'message' => 'Material subido y comunidad notificada.']);
        } catch (\Exception $e) { return response()->json(['success' => false], 500); }
    }

    /**
     * Biblioteca con iconos multimedia dinámicos.
     */
    public function groupMaterials($groupRole)
    {
        $slug = $this->normalizeSlug($groupRole);
        $group = Group::where('category', $slug)->firstOrFail();
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole($slug) && !$this->isAuthorizedCoordinator($slug)) {
            return redirect()->route('dashboard')->with('error', 'Acceso restringido.');
        }
        
        $materials = DB::table('group_materials')->where('group_role', $slug)->where('is_active', true)->orderBy('created_at', 'desc')->paginate(12);

        $materials->getCollection()->transform(function($m) {
            $m->created_at = Carbon::parse($m->created_at);
            $m->file_type = $m->type;
            $type = strtolower($m->type);
            
            if ($type === 'pdf') $m->file_icon = 'img/icono_pdf.png';
            elseif (in_array($type, ['jpg', 'png', 'jpeg', 'image'])) $m->file_icon = 'img/icono_imagen.png';
            elseif (in_array($type, ['mp4', 'mov', 'avi', 'video'])) $m->file_icon = 'img/icono_video.png';
            elseif (in_array($type, ['mp3', 'wav', 'audio'])) $m->file_icon = 'img/icono_audio.png';
            else $m->file_icon = 'img/icono_docs.png';

            $m->file_size_formatted = ($m->file_path && Storage::disk('public')->exists($m->file_path)) 
                ? round(Storage::disk('public')->size($m->file_path) / 1024 / 1024, 2) . ' MB' 
                : '---';
            
            $m->can_preview = in_array($type, ['pdf', 'image', 'jpg', 'png', 'jpeg', 'mp4', 'mov', 'video', 'mp3', 'wav', 'audio']);
            return $m;
        });

        return view('grupos.materials', [
            'group' => $group, 'groupName' => $group->name, 'groupRole' => $groupRole, 'materials' => $materials
        ]);
    }

    public function handleRequest(Request $request, $requestId) {
        $sol = DB::table('group_requests')->where('id', $requestId)->first();
        if (!$sol || !$this->isAuthorizedCoordinator($sol->group_role)) abort(403);
        $status = ($request->action === 'approve') ? 'approved' : 'rejected';
        DB::table('group_requests')->where('id', $requestId)->update(['status' => $status, 'updated_at' => now()]);
        if ($status === 'approved') { 
            $u = User::find($sol->user_id); $r = Role::where('name', $sol->group_role)->first(); 
            if ($u && $r) { 
                $u->roles()->syncWithoutDetaching([$r->id]); 
                try { $u->notify(new AvisoComunidad("¡Aceptado!", "Ya eres parte de " . str_replace('_', ' ', $sol->group_role))); } catch (\Exception $e) {} 
            } 
        }
        return back()->with('success', 'Solicitud procesada.');
    }

    public function removeMember($groupRole, $userId) {
        $slug = $this->normalizeSlug($groupRole);
        if (!$this->isAuthorizedCoordinator($slug)) abort(403);
        $u = User::findOrFail($userId); $r = Role::where('name', $slug)->first(); 
        if ($r) $u->roles()->detach($r->id);
        return response()->json(['success' => true]);
    }

    public function deleteMaterial($id) {
        $m = DB::table('group_materials')->where('id', $id)->first(); 
        if ($m && $this->isAuthorizedCoordinator($m->group_role)) {
            if ($m->file_path) Storage::disk('public')->delete($m->file_path); 
            DB::table('group_materials')->where('id', $id)->delete(); 
            return response()->json(['success' => true]); 
        }
        return response()->json(['success' => false], 403);
    }

    public function viewMaterial($id) {
        $m = DB::table('group_materials')->where('id', $id)->first();
        if (!$m || !Storage::disk('public')->exists($m->file_path)) abort(404);
        return response()->file(Storage::disk('public')->path($m->file_path), ['Content-Disposition' => 'inline']);
    }

    public function downloadMaterial($id) {
        $m = DB::table('group_materials')->where('id', $id)->first();
        return ($m && Storage::disk('public')->exists($m->file_path)) ? Storage::disk('public')->download($m->file_path) : abort(404);
    }

    public function sendRequest(Request $request, $groupRole) {
        $slug = $this->normalizeSlug($groupRole);
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->age) return back()->with('error', 'Completa tu edad en el perfil.');
        $group = Group::where('category', $slug)->first();
        if ($group && ($user->age < $group->min_age || $user->age > $group->max_age)) return back()->with('error', "Edad no permitida.");
        DB::table('group_requests')->insert(['user_id' => $user->id, 'group_role' => $slug, 'status' => 'pending', 'created_at' => now(), 'updated_at' => now()]);
        return back()->with('success', 'Solicitud enviada.');
    }

    public function index() { return view('grupos.index', ['groups' => Group::where('is_active', true)->get()]); }
    public function completeOnboarding(Request $request) { Auth::user()->update(['onboarding_completed' => true, 'age' => $request->age]); return response()->json(['success' => true]); }
    public function getRecommendedGroups(Request $request) { return response()->json(Group::where('is_active', true)->where('min_age', '<=', $request->age)->where('max_age', '>=', $request->age)->get()); }
}