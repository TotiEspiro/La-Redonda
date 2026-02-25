<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TestUserSeeder extends Seeder
{
    /**
     * Ejecuta el seeder para crear usuarios de prueba pre-verificados.
     */
    public function run(): void
    {
        // 1. Buscamos el rol de usuario básico
        $userRole = Role::where('slug', 'usuario')->first();

        // 2. Definimos los datos de los usuarios de prueba
        $testUsers = [
            [
                'name' => 'Carina Carballido',
                'email' => 'carinacarballido@davinci.com',
            ],
            [
                'name' => 'Cecilia María Feijoo',
                'email' => 'ceciliafeijoo@davinci.com',
            ],
            [
                'name' => 'Santiago Gallino',
                'email' => 'santiagogallino@davinci.com',
            ],
            [
                'name' => 'Usuario Prueba 4',
                'email' => 'prueba@davinci.com',
            ],
        ];

        foreach ($testUsers as $userData) {
            // Verificamos si el usuario ya existe para no duplicarlo
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('12345678'), // Contraseña común para pruebas
                    'age' => rand(18, 50),
                    'onboarding_completed' => true, // Saltamos el onboarding
                    'email_verified_at' => Carbon::now(), // Saltamos la verificación de mail
                    'last_login_at' => Carbon::now(),
                ]
            );

            // 3. Asignamos el rol si el usuario se acaba de crear o no tiene roles
            if ($userRole && $user->roles()->count() === 0) {
                $user->roles()->attach($userRole->id);
            }
        }

        $this->command->info('¡5 usuarios de prueba creados con éxito!');
        $this->command->warn('Contraseña para todos: password123');
    }
}