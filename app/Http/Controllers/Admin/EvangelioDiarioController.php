<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EvangelioDiario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EvangelioDiarioController extends Controller
{
    /**
     * Mostrar el formulario para editar el evangelio del día.
     */
    public function editar()
    {
        // Obtener el evangelio de hoy o crear uno vacío
        $evangelio = EvangelioDiario::where('fecha', today())->first();

        if (!$evangelio) {
            $evangelio = new EvangelioDiario([
                'fecha' => today(),
                'contenido' => '',
                'referencia' => ''
            ]);
        }

        return view('admin.evangelio-diario.editar', compact('evangelio'));
    }

    /**
     * Actualizar el evangelio del día en la base de datos.
     */
    public function actualizar(Request $request)
    {
        $validado = $request->validate([
            'contenido' => 'required|string|max:1000',
            'referencia' => 'required|string|max:100'
        ]);

        try {
            // Buscar o crear el registro para hoy
            $evangelio = EvangelioDiario::where('fecha', today())->first();

            if ($evangelio) {
                // Actualizar existente
                $evangelio->update($validado);
                $mensaje = 'Evangelio actualizado exitosamente';
            } else {
                // Crear nuevo
                $validado['fecha'] = today();
                EvangelioDiario::create($validado);
                $mensaje = 'Evangelio creado exitosamente';
            }

            Log::info('Evangelio del día actualizado:', $validado);

            return redirect()->route('admin.evangelio-diario.editar')
                ->with('success', $mensaje);

        } catch (\Exception $e) {
            Log::error('Error al actualizar evangelio:', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Error al actualizar el evangelio: ' . $e->getMessage())
                ->withInput();
        }
    }
}
