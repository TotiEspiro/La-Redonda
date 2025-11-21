<?php

namespace App\Http\Controllers;

use App\Models\DiarioEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserHomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'grupo_parroquial']);
    }

    // Home del usuario con el diario - SOLO sus propias entradas
    public function index()
    {
        $user = Auth::user();
        $entries = DiarioEntry::where('user_id', $user->id)
                            ->latest()
                            ->paginate(12);
        
        return view('diario.index', compact('entries'));
    }

    // Crear nueva entrada
    public function createEntry(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:texto,mapa_conceptual,lista,reflexion',
            'color' => 'nullable|string|max:7',
            'is_favorite' => 'boolean'
        ]);

        try {
            $entry = DiarioEntry::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'content' => $request->content,
                'type' => $request->type,
                'color' => $request->color ?? '#3b82f6',
                'is_favorite' => $request->is_favorite ?? false
            ]);

            return response()->json([
                'success' => true,
                'entry' => [
                    'id' => $entry->id,
                    'title' => $entry->title,
                    'type' => $entry->type,
                    'color' => $entry->color,
                    'is_favorite' => $entry->is_favorite,
                    'created_at' => $entry->created_at,
                    'excerpt' => $entry->excerpt
                ],
                'message' => 'Entrada creada correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la entrada: ' . $e->getMessage()
            ], 500);
        }
    }

    // Obtener entrada para editar - SOLO si pertenece al usuario
    public function getEntry($id)
    {
        try {
            $entry = DiarioEntry::where('user_id', Auth::id())
                               ->where('id', $id)
                               ->firstOrFail();

            $response = [
                'id' => $entry->id,
                'title' => $entry->title,
                'type' => $entry->type,
                'color' => $entry->color,
                'is_favorite' => $entry->is_favorite,
                'created_at' => $entry->created_at,
                'updated_at' => $entry->updated_at
            ];

            // Para tipos especiales, devolver el contenido decodificado
            if ($entry->type === 'lista') {
                try {
                    $response['content'] = json_decode($entry->content, true) ?? [];
                } catch (\Exception $e) {
                    $response['content'] = [];
                }
            } else if ($entry->type === 'mapa_conceptual') {
                try {
                    $response['content'] = json_decode($entry->content, true) ?? ['nodes' => []];
                } catch (\Exception $e) {
                    $response['content'] = ['nodes' => []];
                }
            } else {
                $response['content'] = $entry->content;
            }

            return response()->json($response);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar la entrada: ' . $e->getMessage()
            ], 404);
        }
    }

    // Actualizar entrada
    public function updateEntry(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:texto,mapa_conceptual,lista,reflexion',
            'color' => 'nullable|string|max:7',
            'is_favorite' => 'boolean'
        ]);

        try {
            $entry = DiarioEntry::where('user_id', Auth::id())
                               ->where('id', $id)
                               ->firstOrFail();

            $entry->update([
                'title' => $request->title,
                'content' => $request->content,
                'type' => $request->type,
                'color' => $request->color ?? $entry->color,
                'is_favorite' => $request->is_favorite ?? $entry->is_favorite
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Entrada actualizada correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la entrada: ' . $e->getMessage()
            ], 500);
        }
    }

    // Eliminar entrada - SOLO si pertenece al usuario
    public function deleteEntry($id)
    {
        try {
            $entry = DiarioEntry::where('user_id', Auth::id())
                               ->where('id', $id)
                               ->firstOrFail();

            $entry->delete();

            return response()->json([
                'success' => true,
                'message' => 'Entrada eliminada correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la entrada: ' . $e->getMessage()
            ], 500);
        }
    }

    // Toggle favorito - SOLO si pertenece al usuario
    public function toggleFavorite($id)
    {
        try {
            $entry = DiarioEntry::where('user_id', Auth::id())
                               ->where('id', $id)
                               ->firstOrFail();

            $entry->update([
                'is_favorite' => !$entry->is_favorite
            ]);

            return response()->json([
                'success' => true,
                'is_favorite' => $entry->is_favorite,
                'message' => $entry->is_favorite ? 'Agregado a favoritos' : 'Removido de favoritos'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar favorito: ' . $e->getMessage()
            ], 500);
        }
    }

    // Método adicional para obtener entradas por tipo
    public function getEntriesByType($type)
    {
        try {
            $entries = DiarioEntry::where('user_id', Auth::id())
                                ->where('type', $type)
                                ->latest()
                                ->paginate(12);

            return view('diario.index', compact('entries'));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar las entradas: ' . $e->getMessage()
            ], 500);
        }
    }
}