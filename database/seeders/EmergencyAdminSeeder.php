<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EmergencyAdminSeeder extends Seeder
{
    /**
     * Crea un nuevo Super Admin y elimina el anterior para resetear el acceso.
     */
    public function run()
    {
        // 1. Definir los datos del nuevo administrador
        $newEmail = 'admin_nuevo@redonda.org.ar'; // Puedes cambiarlo
        $newPass = 'AccesoTotal2025*'; // Contraseña temporal segura

        // 2. Buscar el rol de superadmin (asegúrate de que el nombre sea exacto)
        $superAdminRole = Role::where('name', 'superadmin')->first();

        if (!$superAdminRole) {
            $this->command->error('No se encontró el rol "superadmin" en la base de datos.');
            return;
        }

        // 3. Crear el nuevo usuario
        $newAdmin = User::updateOrCreate(
            ['email' => $newEmail],
            [
                'name' => 'Super Administrador Nuevo',
                'password' => Hash::make($newPass),
                'age' => 99,
                'onboarding_completed' => true,
                'notify_announcements' => true
            ]
        );

        // 4. Asignarle el rol (y quitarle cualquier otro rol de grupo si lo tuviera)
        $newAdmin->roles()->sync([$superAdminRole->id]);

        $this->command->info("--------------------------------------------------");
        $this->command->info("NUEVO SUPER ADMIN CREADO EXITOSAMENTE");
        $this->command->info("Email: {$newEmail}");
        $this->command->info("Password: {$newPass}");
        $this->command->info("--------------------------------------------------");

        // 5. BORRAR EL ANTERIOR (Opcional, pero solicitado)
        // Buscamos usuarios que tengan el rol superadmin pero que NO sean el que acabamos de crear
        $oldAdmins = User::whereHas('roles', function($q) {
            $q->where('name', 'superadmin');
        })->where('id', '!=', $newAdmin->id)->get();

        foreach ($oldAdmins as $old) {
            $this->command->warn("Eliminando Super Admin antiguo: {$old->email}");
            // Eliminamos sus relaciones en la tabla pivot antes de borrarlo
            $old->roles()->detach();
            $old->delete();
        }
    }
}