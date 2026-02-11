<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class SuperAdminConfidentialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Aseguramos que el rol exista
        $superRole = Role::firstOrCreate(
            ['name' => 'superadmin'],
            ['display_name' => 'Super Administrador del Sistema']
        );

        // 2. Creamos al usuario maestro
        // Este usuario podrá entrar a todo pero no aparecerá en las listas de miembros
        $admin = User::updateOrCreate(
            ['email' => 'adminredonda@redonda.org.ar'],
            [
                'name' => 'Coordinación General',
                'password' => Hash::make('LaRedonda2026*'), // Cambiar tras el primer login
                'age' => 99,
                'onboarding_completed' => true,
                'notify_announcements' => true
            ]
        );

        // 3. Le asignamos el rol de SuperAdmin
        $admin->roles()->syncWithoutDetaching([$superRole->id]);

        $this->command->info('Super Administrador Invisible creado con éxito.');
        $this->command->info('Email: maestro_parroquial@redonda.org.ar');
    }
}