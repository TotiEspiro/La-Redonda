<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Group;

class RoleSystemSeeder extends Seeder
{
    /**
     * Crea los roles básicos y genera dinámicamente los roles de administrador 
     * para cada grupo para evitar errores 404 en las rutas.
     */
    public function run()
    {
        // 1. Roles Base
        $rolesBase = [
            'superadmin'  => 'Super Administrador',
            'admin'       => 'Administrador del Sitio',
            'usuario'     => 'Usuario Registrado',
            'miembro'     => 'Miembro de Grupo',
            'admin_grupo' => 'Administrador de Grupo (Global)',
        ];

        foreach ($rolesBase as $slug => $name) {
            Role::updateOrCreate(
                ['slug' => $slug],
                ['name' => $name]
            );
        }

        // 2. Roles Dinámicos para Paneles de Comunidad
        // Esto es lo que soluciona el 404 al acceder a /comunidad/{slug}
        $grupos = Group::all();
        
        foreach ($grupos as $grupo) {
            Role::updateOrCreate(
                ['slug' => 'admin_' . $grupo->category],
                ['name' => 'Administrador ' . $grupo->name]
            );
        }

        $this->command->info('Sistema de roles inicializado correctamente.');
    }
}