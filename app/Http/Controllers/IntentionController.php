<?php

namespace App\Http\Controllers;

use App\Models\Intention;
use App\Models\User;
use App\Notifications\NuevaIntencion; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class IntentionController extends Controller
{
    public function create() 
    { 
        return view('intenciones'); 
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'intentionType' => 'required|in:salud,intenciones,accion-gracias,difuntos',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        try {
            // Guardamos la intención primero
            $intention = Intention::create([
                'type' => $validated['intentionType'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'message' => 'Intención de ' . $validated['intentionType'], 
                'user_id' => Auth::id(), 
                'status' => 'pending',
            ]);

            // Notifica
            if (Auth::check()) {
                try {
                    Auth::user()->notify(new NuevaIntencion($intention));
                } catch (\Exception $e) {
                    Log::warning("La intención se guardó, pero la notificación falló: " . $e->getMessage());
                }
            }

            // 3. Devolvemos éxito siempre que la intención se haya creado
            return response()->json([
                'success' => true, 
                'message' => 'Tu intención ha sido recibida y será rezada en la próxima misa.'
            ]);

        } catch (\Exception $e) {
            // Este catch solo se activa si falla la creación en la base de datos
            Log::error("Error al guardar intención: " . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'No se pudo guardar la intención en este momento.'
            ], 500);
        }
    }
}