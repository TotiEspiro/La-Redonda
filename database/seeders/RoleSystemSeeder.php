<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Group;

class RoleSystemSeeder extends Seeder
{
    /**
     * Crea o actualiza los roles buscando por 'name' para evitar errores de duplicados,
     * y asegura que 'slug' y 'display_name' estén correctamente llenos.
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
            // Buscamos por 'name' que es la columna única que ya existe en tu DB
            Role::updateOrCreate(
                ['name' => $slug], 
                [
                    'slug'         => $slug, // Actualizamos el slug que antes estaba vacío
                    'display_name' => $nombreHumano,
                    'description'  => 'Rol de ' . $nombreHumano
                ]
            );
        }

        // 2. Roles de Miembros y Admins para cada Grupo
        $grupos = Group::all();
        
        foreach ($grupos as $grupo) {
            // Rol de Miembro (ej: catequesis_ninos)
            Role::updateOrCreate(
                ['name' => $grupo->category],
                [
                    'slug'         => $grupo->category,
                    'display_name' => 'Miembro ' . $grupo->name,
                    'description'  => 'Usuario miembro del grupo ' . $grupo->name
                ]
            );

            // Rol de Admin de Grupo (ej: admin_catequesis_ninos)
            $adminSlug = 'admin_' . $grupo->category;
            Role::updateOrCreate(
                ['name' => $adminSlug],
                [
                    'slug'         => $adminSlug,
                    'display_name' => 'Administrador ' . $grupo->name,
                    'description'  => 'Coordinador responsable del grupo ' . $grupo->name
                ]
            );
        }

        $this->command->info('Roles sincronizados correctamente buscando por nombre único.');
    }
}