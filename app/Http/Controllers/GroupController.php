<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\GroupMaterial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{
    /**
     * Display a listing of all categories (NO grupos)
     */
    public function index()
    {
        // Solo muestra las categorías, no carga grupos
        return view('grupos.index');
    }

    /**
     * Display groups for catequesis category.
     */
    public function catequesis()
    {
        $categoria = 'Catequesis';
        $descripcion = 'Grupos de formación en la fe para todas las edades. Desde la primera comunión hasta la catequesis de adultos.';
        $groups = Group::where('category', 'catequesis')->where('is_active', true)->get();

        return view('grupos.categoria', compact('groups', 'categoria', 'descripcion'));
    }

    /**
     * Display groups for jovenes category.
     */
    public function jovenes()
    {
        $categoria = 'Jóvenes';
        $descripcion = 'Grupos y actividades para jóvenes entre 11 y 35 años. Espacios de encuentro, formación y servicio.';
        $groups = Group::where('category', 'jovenes')->where('is_active', true)->get();

        return view('grupos.categoria', compact('groups', 'categoria', 'descripcion'));
    }

    /**
     * Display groups for mayores category.
     */
    public function mayores()
    {
        $categoria = 'Mayores';
        $descripcion = 'Grupos para la tercera edad. Espacios de comunidad, oración y actividades recreativas.';
        $groups = Group::where('category', 'mayores')->where('is_active', true)->get();

        return view('grupos.categoria', compact('groups', 'categoria', 'descripcion'));
    }

    /**
     * Display groups for especiales category.
     */
    public function especiales()
    {
        $categoria = 'Más Grupos';
        $descripcion = 'Ministerios y grupos especializados en diferentes áreas de servicio y formación.';
        $groups = Group::where('category', 'especiales')->where('is_active', true)->get();

        return view('grupos.categoria', compact('groups', 'categoria', 'descripcion'));
    }

    /**
     * Display the specified group.
     */
    public function show(Group $group)
    {
        // Obtener el nombre de la categoría para mostrar
        $categoryNames = [
            'catequesis' => 'Catequesis',
            'jovenes' => 'Jóvenes', 
            'mayores' => 'Mayores',
            'especiales' => 'Más Grupos'
        ];

        $categoryName = $categoryNames[$group->category] ?? 'Grupo';

        return view('grupos.index', compact('group', 'categoryName'));
    }

    // =========================================================================
    // NUEVAS FUNCIONES PARA GESTIÓN DE MATERIALES DE GRUPOS PARROQUIALES
    // =========================================================================

    /**
     * Vista del dashboard del grupo para AdminGrupoParroquial
     */
    public function groupDashboard($groupRole)
    {
        $user = Auth::user();
        
        // Verificar que el usuario tiene permisos para este grupo
        if (!$this->userCanManageGroup($user, $groupRole)) {
            return redirect()->route('home')->with('error', 'No tienes permisos para gestionar este grupo.');
        }

        $materials = GroupMaterial::where('group_role', $groupRole)
                                ->latest()
                                ->paginate(10);

        $groupName = $this->getGroupName($groupRole);

        return view('grupos.dashboard', compact('materials', 'groupRole', 'groupName'));
    }

    /**
     * Vista para miembros del grupo (solo ver materiales)
     */
    public function groupMaterials($groupRole)
    {
        $user = Auth::user();
        
        // Verificar que el usuario pertenece al grupo
        if (!$user->hasRole($groupRole) && !$user->isAdmin() && !$user->isSuperAdmin()) {
            return redirect()->route('home')->with('error', 'No perteneces a este grupo.');
        }

        $materials = GroupMaterial::forGroup($groupRole)->latest()->paginate(12);
        $groupName = $this->getGroupName($groupRole);

        return view('grupos.materials', compact('materials', 'groupRole', 'groupName'));
    }

    /**
     * Subir nuevo material
     */
    public function uploadMaterial(Request $request, $groupRole)
    {
        $user = Auth::user();
        
        if (!$this->userCanManageGroup($user, $groupRole)) {
            return response()->json(['error' => 'No tienes permisos para subir material a este grupo.'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:10240' // Máximo 10MB
        ]);

        $file = $request->file('file');
        $fileType = $this->getFileType($file->getClientOriginalExtension());
        
        // Guardar archivo
        $filePath = $file->store("group-materials/{$groupRole}", 'public');

        // Crear registro en la base de datos
        $material = GroupMaterial::create([
            'user_id' => $user->id,
            'group_role' => $groupRole,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $fileType,
            'file_size' => $file->getSize()
        ]);

        return response()->json([
            'success' => true,
            'material' => $material,
            'message' => 'Material subido correctamente'
        ]);
    }

    /**
     * Eliminar material
     */
    public function deleteMaterial($id)
    {
        $material = GroupMaterial::findOrFail($id);
        $user = Auth::user();

        if (!$this->userCanManageGroup($user, $material->group_role)) {
            return response()->json(['error' => 'No tienes permisos para eliminar este material.'], 403);
        }

        // Eliminar archivo físico
        Storage::disk('public')->delete($material->file_path);

        $material->delete();

        return response()->json([
            'success' => true,
            'message' => 'Material eliminado correctamente'
        ]);
    }

    /**
     * Descargar material - CORREGIDO
     */
    public function downloadMaterial($id)
    {
        $material = GroupMaterial::where('is_active', true)->findOrFail($id);
        $user = Auth::user();

        // Verificar que el usuario puede ver este material
        if (!$user->hasRole($material->group_role) && !$user->isAdmin() && !$user->isSuperAdmin()) {
            return redirect()->back()->with('error', 'No tienes acceso a este material.');
        }

        // Verificar que el archivo existe
        if (!Storage::disk('public')->exists($material->file_path)) {
            return redirect()->back()->with('error', 'El archivo no existe.');
        }

        return Storage::disk('public')->download($material->file_path, $material->file_name);
    }

    // =========================================================================
    // FUNCIONES HELPER
    // =========================================================================

    /**
     * Helper: Verificar si usuario puede gestionar el grupo
     */
    private function userCanManageGroup($user, $groupRole)
    {
        return ($user->hasRole('admin_grupo_parroquial') && $user->hasRole($groupRole)) || 
               $user->isAdmin() || 
               $user->isSuperAdmin();
    }

    /**
     * Helper: Obtener nombre del grupo
     */
    private function getGroupName($groupRole)
    {
        $groupNames = [
            'catequesis' => 'Catequesis',
            'juveniles' => 'Juveniles',
            'acutis' => 'Acutis',
            'juan_pablo' => 'Juan Pablo',
            'coro' => 'Coro',
            'san_joaquin' => 'San Joaquín',
            'santa_ana' => 'Santa Ana',
            'ardillas' => 'Ardillas',
            'costureras' => 'Costureras',
            'misioneros' => 'Misioneros',
            'caridad_comedor' => 'Caridad y Comedor'
        ];

        return $groupNames[$groupRole] ?? $groupRole;
    }

    /**
     * Helper: Determinar tipo de archivo
     */
    private function getFileType($extension)
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'];
        $videoExtensions = ['mp4', 'avi', 'mov', 'wmv'];
        $audioExtensions = ['mp3', 'wav', 'ogg', 'm4a'];
        
        if (in_array($extension, $imageExtensions)) return 'image';
        if (in_array($extension, $videoExtensions)) return 'video';
        if (in_array($extension, $audioExtensions)) return 'audio';
        if ($extension === 'pdf') return 'pdf';
        if (in_array($extension, ['doc', 'docx'])) return 'doc';
        if (in_array($extension, ['xls', 'xlsx'])) return 'xls';
        if (in_array($extension, ['ppt', 'pptx'])) return 'ppt';
        if ($extension === 'zip') return 'zip';
        
        return 'other';
    }
}