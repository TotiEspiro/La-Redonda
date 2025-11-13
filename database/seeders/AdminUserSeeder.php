<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Verificar si ya existe
        $existingAdmin = User::where('email', 'admin@laredonda.com')->first();
        
        if (!$existingAdmin) {
            User::create([
                'name' => 'Administrador',
                'email' => 'admin@laredonda.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]);
            $this->command->info('Usuario administrador creado!');
        } else {
            $this->command->info('El usuario administrador ya existe');
        }

        $this->command->info('Credenciales: admin@laredonda.com / admin123');
    }
}