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
        
        return view('user.home', compact('entries'));
    }

    // Crear nueva entrada - ENCRIPTADA automáticamente
    public function createEntry(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:texto,mapa_conceptual,lista,reflexion',
            'color' => 'nullable|string|max:7'
        ]);

        // Se crea automáticamente encriptado gracias a los mutators
        $entry = DiarioEntry::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'color' => $request->color ?? '#3b82f6',
            'is_favorite' => false
        ]);

        return response()->json([
            'success' => true,
            'entry' => [
                'id' => $entry->id,
                'title' => $entry->title, // Ya desencriptado
                'type' => $entry->type,
                'color' => $entry->color,
                'is_favorite' => $entry->is_favorite,
                'created_at' => $entry->created_at
            ],
            'message' => 'Entrada creada correctamente'
        ]);
    }

    // Obtener entrada para editar - SOLO si pertenece al usuario
    public function getEntry($id)
    {
        $entry = DiarioEntry::where('user_id', Auth::id())
                           ->where('id', $id)
                           ->firstOrFail();

        return response()->json([
            'id' => $entry->id,
            'title' => $entry->title, // Desencriptado automáticamente
            'content' => $entry->content, // Desencriptado automáticamente
            'type' => $entry->type,
            'color' => $entry->color,
            'is_favorite' => $entry->is_favorite
        ]);
    }

    // Actualizar entrada - ENCRIPTADA automáticamente
    public function updateEntry(Request $request, $id)
    {
        $entry = DiarioEntry::where('user_id', Auth::id())
                           ->where('id', $id)
                           ->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:texto,mapa_conceptual,lista,reflexion',
            'color' => 'nullable|string|max:7'
        ]);

        $entry->update([
            'title' => $request->title, // Se encripta automáticamente
            'content' => $request->content, // Se encripta automáticamente
            'type' => $request->type,
            'color' => $request->color ?? $entry->color
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Entrada actualizada correctamente'
        ]);
    }

    // Eliminar entrada - SOLO si pertenece al usuario
    public function deleteEntry($id)
    {
        $entry = DiarioEntry::where('user_id', Auth::id())
                           ->where('id', $id)
                           ->firstOrFail();

        $entry->delete();

        return response()->json([
            'success' => true,
            'message' => 'Entrada eliminada correctamente'
        ]);
    }

    // Toggle favorito - SOLO si pertenece al usuario
    public function toggleFavorite($id)
    {
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
    }
}