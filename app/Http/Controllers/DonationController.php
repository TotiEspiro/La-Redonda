<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\User;
use App\Notifications\NuevaDonacion; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DonationController extends Controller
{
    /**
     * Muestra la vista de donaciones.
     */
    public function create() 
    { 
        return view('donaciones'); 
    }

    /**
     * Procesa la donación y dispara la notificación Push y de Base de Datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:100',
            'frequency' => 'required|in:once,weekly,biweekly,monthly',
            'card_holder' => 'required|string|max:255',
            'card_number' => 'required|string|min:16',
            'email' => 'required|email|max:255',
        ]);

        try {
            // 1. Guardar el registro en la base de datos
            $donation = Donation::create([
                'amount' => $validated['amount'],
                'frequency' => $validated['frequency'],
                'card_holder' => $validated['card_holder'],
                'card_last_four' => substr($validated['card_number'], -4),
                'email' => $validated['email'],
                'user_id' => Auth::id(), 
                'status' => 'completed',
            ]);

            // 2. Notificar al usuario (Push + Campana)
            if (Auth::check()) {
                try {
                    Auth::user()->notify(new NuevaDonacion($donation));
                } catch (\Exception $e) {
                    // Evitamos error 500 si fallan las llaves VAPID o OpenSSL
                    Log::warning("Donación guardada, pero falló la notificación Push: " . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true, 
                'message' => '¡Muchas gracias! Tu donación ha sido procesada exitosamente.'
            ]);

        } catch (\Exception $e) {
            Log::error("Error crítico en Donación: " . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Hubo un problema al procesar la donación.'
            ], 500);
        }
    }
}