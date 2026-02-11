<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Mostrar el perfil del usuario.
     * Carga las notificaciones para que se impriman en el historial de actividad.
     */
    public function show()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Obtenemos las últimas 15 notificaciones para la columna derecha de la vista
        $notifications = $user->notifications()->latest()->take(15)->get();
        
        return view('profile.show', compact('user', 'notifications'));
    }

    /**
     * Mostrar el formulario de edición del perfil.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Actualizar los datos del perfil.
     * Valida obligatoriamente la edad para permitir la inscripción en grupos.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'age' => 'required|integer|min:5|max:99', // Requisito para grupos
            'notify_announcements' => 'nullable|boolean' 
        ], [
            'age.required' => 'La edad es obligatoria para validar tu acceso a los grupos parroquiales.',
            'age.integer' => 'Por favor, ingresa un número válido para la edad.',
            'age.min' => 'Debes ingresar una edad real.',
            'email.unique' => 'Este correo electrónico ya está registrado por otra persona.'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Actualizamos los datos y marcamos el onboarding como completado
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'age' => $request->age,
            'notify_announcements' => $request->has('notify_announcements'),
            'onboarding_completed' => true // Al llenar el perfil, ya no es necesario el modal de bienvenida
        ]);

        return redirect()->route('profile.show')->with('success', 'Perfil actualizado correctamente. Ya puedes inscribirte en tus comunidades.');
    }

    /**
     * Mostrar el formulario para cambiar la contraseña.
     */
    public function showChangePassword()
    {
        return view('profile.change-password');
    }

    /**
     * Procesar el cambio de contraseña con validación de seguridad.
     */
    public function changePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'new_password.confirmed' => 'La confirmación de la nueva contraseña no coincide.',
            'new_password.min' => 'La nueva contraseña debe tener al menos 6 caracteres.'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Verificar que la contraseña actual sea correcta
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual que ingresaste es incorrecta.'])->withInput();
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('profile.show')->with('success', 'Contraseña actualizada con éxito.');
    }
}