<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Group;

class RoleSystemSeeder extends Seeder
{
    /**
     * Crea los roles llenando 'slug', 'name' y 'display_name' para evitar errores de BD.
     */
    public function run()
    {
        // 1. Roles Base del Sistema
        $rolesBase = [
            'superadmin'  => 'Super Administrador',
            'admin'       => 'Administrador del Sitio',
            'usuario'     => 'Usuario Registrado',
        ];

        foreach ($rolesBase as $slug => $nombreHumano) {
            Role::updateOrCreate(
                ['slug' => $slug],
                [
                    'name'         => $slug, // Nombre técnico
                    'display_name' => $nombreHumano, // Nombre para mostrar (el que faltaba)
                    'description'  => 'Rol de ' . $nombreHumano
                ]
            );
        }

        // 2. Roles de Miembros y Admins para cada Grupo
        $grupos = Group::all();
        
        foreach ($grupos as $grupo) {
            // Rol de Miembro (ej: catequesis_ninos)
            Role::updateOrCreate(
                ['slug' => $grupo->category],
                [
                    'name'         => $grupo->category,
                    'display_name' => 'Miembro ' . $grupo->name,
                    'description'  => 'Usuario miembro del grupo ' . $grupo->name
                ]
            );

            // Rol de Admin de Grupo (ej: admin_catequesis_ninos)
            $adminSlug = 'admin_' . $grupo->category;
            Role::updateOrCreate(
                ['slug' => $adminSlug],
                [
                    'name'         => $adminSlug,
                    'display_name' => 'Administrador ' . $grupo->name,
                    'description'  => 'Coordinador responsable del grupo ' . $grupo->name
                ]
            );
        }

        $this->command->info('Roles creados exitosamente con todos los campos requeridos.');
    }
}