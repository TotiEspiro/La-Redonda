<?php

namespace App\Http\Controllers;

use App\Models\Intention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IntentionController extends Controller
{
    // Mostrar formulario de intenciones
    public function create()
    {
        return view('intenciones');
    }

    // Guardar intención en la base de datos
    public function store(Request $request)
    {
        $validated = $request->validate([
            'intentionType' => 'required|in:salud,intenciones,accion-gracias,difuntos',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            // 'message' => 'required|string|min:10|max:1000', // REMOVED - este campo no existe en el formulario
        ]);

        try {
            $intention = Intention::create([
                'type' => $validated['intentionType'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'message' => 'Intención de ' . $validated['intentionType'], // Mensaje por defecto
                'user_id' => Auth::id(), // Si el usuario está logueado
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Intención enviada exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar la intención: ' . $e->getMessage()
            ], 500);
        }
    }
}