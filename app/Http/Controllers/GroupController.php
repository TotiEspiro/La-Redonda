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
        $slug = strtolower($slug);
        $slug = str_replace('admin_', '', $slug);
        return str_replace('-', '_', $slug);
    }

    /**
     * VERIFICACIÓN DE COORDINACIÓN (MAESTRA)
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
            'catequesis', 'acutis', 'juveniles', 'jovenes_adultos', 'coro', 
            'san_joaquin', 'santa_ana', 'ardillas', 'costureras', 'misioneros', 
            'caridad_comedor', 'scouts', 'confirmacion', 'monaguillos', 'liturgia'
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
     * CONSOLA DE COORDINACIÓN
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
        $members = User::whereHas('roles', fn($q) => $q->where('name', $slug))
            ->whereDoesntHave('roles', fn($q) => $q->where('name', 'superadmin'))
            ->get();
        
        $materials = DB::table('group_materials')->where('group_role', $slug)->orderBy('created_at', 'desc')->take(10)->get()
            ->map(function($m) { $m->created_at = Carbon::parse($m->created_at); return $m; });

        $requests = DB::table('group_requests')
            ->join('users', 'group_requests.user_id', '=', 'users.id')
            ->where('group_requests.group_role', $slug)
            ->where('group_requests.status', 'pending')
            ->select('group_requests.*', 'users.name', 'users.email')
            ->get();

        return view('grupos.dashboard-grupos', compact('group', 'groupName', 'groupRole', 'members', 'materials', 'requests'));
    }

    /**
     * BIBLIOTECA DE MATERIALES
     */
    public function groupMaterials($groupRole)
    {
        $slug = $this->normalizeSlug($groupRole);
        $group = Group::where('category', $slug)->firstOrFail();
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole($slug) && !$this->isAuthorizedCoordinator($slug)) {
            return redirect()->route('dashboard')->with('error', 'Acceso exclusivo para miembros.');
        }
        
        $materials = DB::table('group_materials')->where('group_role', $slug)->where('is_active', true)->orderBy('created_at', 'desc')->paginate(12);

        $materials->getCollection()->transform(function($m) {
            $m->created_at = Carbon::parse($m->created_at);
            $m->file_type = $m->type;
            $type = strtolower($m->type);
            if ($type === 'pdf') $m->file_icon = 'img/icono_pdf.png';
            elseif (in_array($type, ['jpg', 'png', 'jpeg'])) $m->file_icon = 'img/icono_imagen.png';
            else $m->file_icon = 'img/icono_docs.png';
            $m->file_size_formatted = ($m->file_path && Storage::disk('public')->exists($m->file_path)) 
                ? round(Storage::disk('public')->size($m->file_path) / 1024 / 1024, 2) . ' MB' 
                : '---';
            $m->can_preview = in_array($type, ['pdf', 'image', 'jpg', 'png', 'jpeg']);
            return $m;
        });

        return view('grupos.materials', [
            'group' => $group, 'groupName' => $group->name, 'groupRole' => $groupRole, 'materials' => $materials
        ]);
    }

    /**
     * ENVIAR SOLICITUD (SEGURIDAD REFORZADA)
     */
    public function sendRequest(Request $request, $groupRole)
    {
        $slug = $this->normalizeSlug($groupRole);
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. REGLA: Perfil Obligatorio (Edad)
        if (!$user->age) {
            return back()->with('error', 'Debes completar tu edad en tu perfil para poder unirte a grupos.');
        }

        // 2. REGLA: Límite de solicitudes pendientes (Máximo 2)
        $pendingCount = DB::table('group_requests')
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        if ($pendingCount >= 2) {
            return back()->with('error', 'Tienes demasiadas solicitudes pendientes (máximo 2). Espera a ser aceptado para pedir otro grupo.');
        }

        // 3. REGLA: Validación de Rango de Edad Automática
        $group = Group::where('category', $slug)->first();
        if ($group) {
            if ($user->age < $group->min_age || $user->age > $group->max_age) {
                return back()->with('error', "Tu edad ({$user->age} años) no coincide con el rango de este grupo ({$group->min_age} a {$group->max_age} años).");
            }
        }

        // 4. Evitar duplicados
        if (DB::table('group_requests')->where('user_id', $user->id)->where('group_role', $slug)->where('status', 'pending')->exists()) {
            return back()->with('info', 'Ya tienes una solicitud pendiente para este grupo.');
        }

        DB::table('group_requests')->insert([
            'user_id' => $user->id,
            'group_role' => $slug,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        try {
            $coordinadores = User::whereHas('roles', function($q) use ($slug) {
                $q->whereIn('name', ['admin', 'superadmin', 'admin_' . $slug]);
            })->get();
            Notification::send($coordinadores, new AvisoComunidad("Nueva Solicitud", "{$user->name} quiere unirse a " . str_replace('_', ' ', $slug)));
        } catch (\Exception $e) { Log::warning("Error notificación Push."); }

        return back()->with('success', '¡Solicitud enviada! Un coordinador la revisará pronto.');
    }

    /**
     * VISTA DE CATEGORÍA
     */
    public function category($slug)
    {
        $categorySlug = strtolower($slug);
        
        $descripciones = [
            'catequesis' => 'Formación sacramental para niños, adolescentes y adultos.',
            'jovenes'    => 'Comunidad, formación y oración para jóvenes de 11 a 35 años.',
            'mayores'    => 'Espacios de oración y fraternidad dedicados a la tercera edad.',
            'especiales' => 'Servicio, caridad y misiones especiales de nuestra parroquia.'
        ];

        $titulos = [
            'catequesis' => 'Catequesis',
            'jovenes'    => 'Jóvenes',
            'mayores'    => 'Mayores',
            'especiales' => 'Más Grupos'
        ];

        // Obtenemos grupos de la DB que coincidan con la categoría
        $groups = Group::where('category', 'like', $categorySlug . '%')->where('is_active', true)->get();

        return view('grupos.categoria', [
            'categoria'   => $titulos[$categorySlug] ?? ucfirst($slug),
            'descripcion' => $descripciones[$categorySlug] ?? 'Explora nuestras comunidades parroquiales.',
            'groups'      => $groups,
            'slug'        => $categorySlug
        ]);
    }

    // --- MÉTODOS COMPLEMENTARIOS ---

    public function handleRequest(Request $request, $requestId) {
        $sol = DB::table('group_requests')->where('id', $requestId)->first();
        if (!$sol || !$this->isAuthorizedCoordinator($sol->group_role)) abort(403);
        $status = ($request->action === 'approve') ? 'approved' : 'rejected';
        DB::table('group_requests')->where('id', $requestId)->update(['status' => $status, 'updated_at' => now()]);
        if ($status === 'approved') { 
            $u = User::find($sol->user_id); $r = Role::where('name', $sol->group_role)->first(); 
            if ($u && $r) { $u->roles()->syncWithoutDetaching([$r->id]); try { $u->notify(new AvisoComunidad("¡Aceptado!", "Ya eres parte de " . str_replace('_', ' ', $sol->group_role))); } catch (\Exception $e) {} } 
        }
        return back()->with('success', 'Solicitud procesada.');
    }

    public function uploadMaterial(Request $request, $groupRole) {
        $slug = $this->normalizeSlug($groupRole);
        if (!$this->isAuthorizedCoordinator($slug)) return response()->json(['success' => false], 403);
        $request->validate(['title' => 'required|max:255', 'type' => 'required', 'file' => 'required|max:153600']);
        try {
            $filePath = $request->file('file')->store('materials/' . $slug, 'public');
            DB::table('group_materials')->insert(['group_role' => $slug, 'title' => $request->title, 'type' => $request->type, 'file_path' => $filePath, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) { return response()->json(['success' => false], 500); }
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

    public function deleteMaterial($id) {
        $m = DB::table('group_materials')->where('id', $id)->first(); 
        if ($m && $this->isAuthorizedCoordinator($m->group_role)) {
            if ($m->file_path) Storage::disk('public')->delete($m->file_path); 
            DB::table('group_materials')->where('id', $id)->delete(); 
            return response()->json(['success' => true]); 
        }
        return response()->json(['success' => false], 403);
    }

    public function removeMember($groupRole, $userId) {
        $slug = $this->normalizeSlug($groupRole);
        if (!$this->isAuthorizedCoordinator($slug)) abort(403);
        $u = User::findOrFail($userId); $r = Role::where('name', $slug)->first(); 
        if ($r) $u->roles()->detach($r->id);
        return response()->json(['success' => true]);
    }

    public function index() { return view('grupos.index', ['groups' => Group::where('is_active', true)->get()]); }
    public function completeOnboarding(Request $request) { Auth::user()->update(['onboarding_completed' => true, 'age' => $request->age]); return response()->json(['success' => true]); }
    public function getRecommendedGroups(Request $request) { return response()->json(Group::where('is_active', true)->where('min_age', '<=', $request->age)->where('max_age', '>=', $request->age)->get()); }
}