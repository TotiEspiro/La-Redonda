<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $grupos = [
            'catequesis' => 'Catequesis',
            'juveniles' => 'Jóvenes',
            'acutis' => 'Acutis',
            'juan_pablo' => 'Juan Pablo II',
            'coro' => 'Coro',
            'san_joaquin' => 'San Joaquín',
            'santa_ana' => 'Santa Ana',
            'ardillas' => 'Ardillas',
            'costureras' => 'Costureras',
            'misioneros' => 'Misioneros',
            'caridad_comedor' => 'Caridad y Comedor'
        ];

        foreach ($grupos as $key => $label) {
            // 1. Asegurar que el rol base exista (ej. 'catequesis')
            DB::table('roles')->updateOrInsert(
                ['name' => $key],
                [
                    'display_name' => $label,
                    'description' => "Miembro del grupo de $label",
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );

            // 2. Crear el rol de administrador específico (ej. 'admin_catequesis')
            DB::table('roles')->updateOrInsert(
                ['name' => "admin_$key"],
                [
                    'display_name' => "Administrador de $label",
                    'description' => "Permisos de gestión para el grupo de $label",
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }

        // 3. Asegurar que el flag general exista
        DB::table('roles')->updateOrInsert(
            ['name' => 'admin_grupo_parroquial'],
            [
                'display_name' => 'Admin Grupo Parroquial (General)',
                'description' => 'Flag para acceso a funciones administrativas de grupos',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // En este caso es más seguro no borrar roles en el down para evitar
        // dejar usuarios con relaciones huérfanas en la tabla user_roles.
    }
};