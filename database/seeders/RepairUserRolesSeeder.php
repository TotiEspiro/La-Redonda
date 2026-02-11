<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RepairUserRolesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Obtener todos los usuarios
        $users = User::all();
        
        $this->command->info('Iniciando reparación de roles para ' . $users->count() . ' usuarios...');

        foreach ($users as $user) {
            // Intentamos obtener el rol desde la columna 'role' (la antigua)
            // Si la columna ya no existe en el modelo, la buscamos directamente en la DB
            $roleName = DB::table('users')->where('id', $user->id)->value('role');

            if ($roleName) {
                // Buscamos el ID del rol en la tabla nueva de roles
                $role = Role::where('name', $roleName)->first();

                if ($role) {
                    // Sincronizamos (esto llena la tabla user_roles sin duplicar)
                    $user->roles()->syncWithoutDetaching([$role->id]);
                    $this->command->info("Usuario {$user->email}: Rol '{$roleName}' asignado correctamente.");
                } else {
                    $this->command->error("Usuario {$user->email}: El rol '{$roleName}' no existe en la tabla roles.");
                }
            } else {
                // Si no tiene rol antiguo, le asignamos 'user' por defecto para que pueda entrar
                $defaultRole = Role::where('name', 'user')->first();
                if ($defaultRole) {
                    $user->roles()->syncWithoutDetaching([$defaultRole->id]);
                }
            }
        }

        $this->command->info('Reparación finalizada.');
    }
}