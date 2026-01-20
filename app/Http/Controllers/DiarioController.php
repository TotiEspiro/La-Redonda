<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class DiarioController extends Controller
{
    
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->canAccessDiario()) {
            return redirect()->route('home')->with('error', 'No tiene permisos para acceder al diario.');
        }

        // Obtener todas las entradas
        $allEntries = $user->getDiarioEntries();
        
        // Paginación manual
        $page = $request->get('page', 1);
        $perPage = 12;
        $offset = ($page - 1) * $perPage;
        
        $currentPageEntries = array_slice($allEntries, $offset, $perPage);
        
        // Crear paginador
        $paginator = new LengthAwarePaginator(
            $currentPageEntries,
            count($allEntries),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Agregar propiedades necesarias a cada entrada para la vista
        $formattedEntries = collect($currentPageEntries)->map(function($entry) {
            return (object)[
                'id' => $entry['id'] ?? 0,
                'title' => $entry['title'] ?? 'Sin título',
                'content' => $entry['content'] ?? '', 
                'type' => $entry['type'] ?? 'texto',
                'color' => $entry['color'] ?? '#3b82f6',
                'is_favorite' => $entry['is_favorite'] ?? false,
                'created_at' => isset($entry['created_at']) ? \Carbon\Carbon::parse($entry['created_at']) : now(),
                'type_display' => $this->getTypeDisplay($entry['type'] ?? 'texto'),
                'excerpt' => $this->getExcerpt($entry['content'] ?? '')
            ];
        });

        return view('diario.index', [
            'entries' => $formattedEntries,
            'paginator' => $paginator
        ]);
    }

    
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->canAccessDiario()) {
            return response()->json([
                'success' => false,
                'message' => 'No tiene permisos para acceder al diario.'
            ], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:texto,mapa_conceptual,lista,reflexion',
            'color' => 'nullable|string|max:7',
            'is_favorite' => 'nullable|boolean'
        ]);

        try {
            $entry = $user->addDiarioEntry($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Entrada creada correctamente.',
                'entry' => [
                    'id' => $entry['id'],
                    'title' => $request->title,
                    'content' => $request->content, 
                    'type' => $entry['type'] ?? 'texto',
                    'color' => $entry['color'] ?? '#3b82f6',
                    'is_favorite' => $entry['is_favorite'] ?? false,
                    'created_at' => $entry['created_at'] ?? now()->toDateTimeString(),
                    'type_display' => $this->getTypeDisplay($entry['type'] ?? 'texto'),
                    'excerpt' => $this->getExcerpt($request->content) 
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la entrada: ' . $e->getMessage()
            ], 500);
        }
    }

    
    public function show($id)
    {
        $user = Auth::user();
        
        if (!$user->canAccessDiario()) {
            return response()->json([
                'success' => false,
                'message' => 'No tiene permisos para acceder al diario.'
            ], 403);
        }

        $entry = $user->getDiarioEntry($id);
        
        if (!$entry) {
            return response()->json([
                'success' => false,
                'message' => 'Entrada no encontrada.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'entry' => [
                'id' => $entry['id'],
                'title' => $entry['title'] ?? 'Sin título',
                'content' => $entry['content'] ?? '', // ✅ 
                'type' => $entry['type'] ?? 'texto',
                'color' => $entry['color'] ?? '#3b82f6',
                'is_favorite' => $entry['is_favorite'] ?? false,
                'created_at' => $entry['created_at'] ?? now()->toDateTimeString(),
                'type_display' => $this->getTypeDisplay($entry['type'] ?? 'texto')
            ]
        ]);
    }

    
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!$user->canAccessDiario()) {
            return response()->json([
                'success' => false,
                'message' => 'No tiene permisos para acceder al diario.'
            ], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:texto,mapa_conceptual,lista,reflexion',
            'color' => 'nullable|string|max:7',
            'is_favorite' => 'nullable|boolean'
        ]);

        $success = $user->updateDiarioEntry($id, $request->all());

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Entrada actualizada correctamente.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Entrada no encontrada.'
        ], 404);
    }

    
    public function destroy($id)
    {
        $user = Auth::user();
        
        if (!$user->canAccessDiario()) {
            return response()->json([
                'success' => false,
                'message' => 'No tiene permisos para acceder al diario.'
            ], 403);
        }

        $success = $user->deleteDiarioEntry($id);

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Entrada eliminada correctamente.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Entrada no encontrada.'
        ], 404);
    }

    
    public function favorites(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->canAccessDiario()) {
            return redirect()->route('home')->with('error', 'No tiene permisos para acceder al diario.');
        }

        $favoriteEntries = $user->getFavoriteDiarioEntries();
        
        // Paginación para favoritos
        $page = $request->get('page', 1);
        $perPage = 12;
        $offset = ($page - 1) * $perPage;
        
        $currentPageEntries = array_slice($favoriteEntries, $offset, $perPage);
        
        $paginator = new LengthAwarePaginator(
            $currentPageEntries,
            count($favoriteEntries),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $formattedEntries = collect($currentPageEntries)->map(function($entry) {
            return (object)[
                'id' => $entry['id'] ?? 0,
                'title' => $entry['title'] ?? 'Sin título',
                'content' => $entry['content'] ?? '',
                'type' => $entry['type'] ?? 'texto',
                'color' => $entry['color'] ?? '#3b82f6',
                'is_favorite' => $entry['is_favorite'] ?? false,
                'created_at' => isset($entry['created_at']) ? \Carbon\Carbon::parse($entry['created_at']) : now(),
                'type_display' => $this->getTypeDisplay($entry['type'] ?? 'texto'),
                'excerpt' => $this->getExcerpt($entry['content'] ?? '')
            ];
        });
    }

    
    public function search(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->canAccessDiario()) {
            return response()->json([
                'success' => false,
                'message' => 'No tiene permisos para acceder al diario.'
            ], 403);
        }

        $request->validate([
            'q' => 'required|string|min:2'
        ]);

        $results = $user->searchDiarioEntries($request->q);
        
        $formattedResults = collect($results)->map(function($entry) {
            return (object)[
                'id' => $entry['id'] ?? 0,
                'title' => $entry['title'] ?? 'Sin título',
                'content' => $entry['content'] ?? '',
                'type' => $entry['type'] ?? 'texto',
                'color' => $entry['color'] ?? '#3b82f6',
                'is_favorite' => $entry['is_favorite'] ?? false,
                'created_at' => isset($entry['created_at']) ? \Carbon\Carbon::parse($entry['created_at']) : now(),
                'type_display' => $this->getTypeDisplay($entry['type'] ?? 'texto'),
                'excerpt' => $this->getExcerpt($entry['content'] ?? '')
            ];
        });

        return response()->json([
            'success' => true,
            'results' => $formattedResults,
            'count' => count($results)
        ]);
    }

    
    public function toggleFavorite($id)
    {
        $user = Auth::user();
        
        if (!$user->canAccessDiario()) {
            return response()->json([
                'success' => false,
                'message' => 'No tiene permisos para acceder al diario.'
            ], 403);
        }

        $entry = $user->getDiarioEntry($id);
        
        if (!$entry) {
            return response()->json([
                'success' => false,
                'message' => 'Entrada no encontrada.'
            ], 404);
        }

        $success = $user->updateDiarioEntry($id, [
            'title' => $entry['title'] ?? '',
            'content' => $entry['content'] ?? '',
            'type' => $entry['type'] ?? 'texto',
            'color' => $entry['color'] ?? '#3b82f6',
            'is_favorite' => !($entry['is_favorite'] ?? false)
        ]);

        if ($success) {
            return response()->json([
                'success' => true,
                'is_favorite' => !($entry['is_favorite'] ?? false),
                'message' => ($entry['is_favorite'] ?? false) ? 'Entrada removida de favoritos.' : 'Entrada agregada a favoritos.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar la entrada.'
        ], 500);
    }

    
    private function getTypeDisplay($type)
    {
        $types = [
            'texto' => 'Texto',
            'mapa_conceptual' => 'Mapa Conceptual',
            'lista' => 'Lista',
            'reflexion' => 'Reflexión'
        ];
        
        return $types[$type] ?? $type;
    }

    
    private function getExcerpt($content, $length = 100)
    {
        // Limpiar HTML tags si existe
        $text = strip_tags($content);
        if (strlen($text) > $length) {
            return substr($text, 0, $length) . '...';
        }
        return $text;
    }
}