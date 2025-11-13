<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller
{
    // Mostrar formulario de donaciones
    public function create()
    {
        return view('donaciones');
    }

    // Guardar donación en la base de datos
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
            $donation = Donation::create([
                'amount' => $validated['amount'],
                'frequency' => $validated['frequency'],
                'card_holder' => $validated['card_holder'],
                'card_last_four' => substr($validated['card_number'], -4),
                'email' => $validated['email'],
                'user_id' => Auth::id(), // Si el usuario está logueado
                'status' => 'completed',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Donación procesada exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la donación'
            ], 500);
        }
    }
}