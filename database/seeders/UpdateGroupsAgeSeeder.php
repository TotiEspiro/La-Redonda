<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;
use App\Models\Role;

class UpdateGroupsAgeSeeder extends Seeder
{
    public function run()
    {
        // AJUSTÁ LOS RANGOS DE EDAD AQUÍ ABAJO:
        $config = [
            'catequesis'      => ['nombre' => 'Catequesis Familiar', 'min' => 6,  'max' => 12],
            'acutis'          => ['nombre' => 'Acutis',              'min' => 18, 'max' => 24],
            'juveniles'       => ['nombre' => 'Juveniles',           'min' => 10, 'max' => 17],
            'juan_pablo'       => ['nombre' => 'Juan Pablo',         'min' => 25, 'max' => 35],
            'coro'            => ['nombre' => 'Coro Parroquial',     'min' => 15, 'max' => 99],
            'san_joaquin'     => ['nombre' => 'San Joaquín',         'min' => 60, 'max' => 99],
            'santa_ana'       => ['nombre' => 'Santa Ana',           'min' => 60, 'max' => 99],
            'ardillas'        => ['nombre' => 'Ardillas',            'min' => 50,  'max' => 99],
            'costureras'      => ['nombre' => 'Costureras',          'min' => 60, 'max' => 99],
            'misioneros'      => ['nombre' => 'Misioneros',          'min' => 18, 'max' => 99],
            'caridad_comedor' => ['nombre' => 'Caridad y Comedor',   'min' => 18, 'max' => 99],
            'confirmacion'    => ['nombre' => 'Confirmación',        'min' => 14, 'max' => 18],
            'catequesis_jovenes' => ['nombre' => 'Monaguillos',     'min' => 13,  'max' => 17],
            'catequesis_adultos'        => ['nombre' => 'Liturgia',  'min' => 18, 'max' => 99],
        ];

        foreach ($config as $slug => $data) {
            // updateOrCreate: Si no existe lo crea, si existe lo actualiza
            Group::updateOrCreate(
                ['category' => $slug], 
                [
                    'name'      => $data['nombre'],
                    'min_age'   => $data['min'],
                    'max_age'   => $data['max'],
                    'is_active' => true
                ]
            );

            // Aprovechamos para crear los roles si no existen
            Role::updateOrCreate(['name' => $slug], ['display_name' => 'Miembro ' . $data['nombre']]);
            Role::updateOrCreate(['name' => 'admin_' . $slug], ['display_name' => 'Admin ' . $data['nombre']]);
        }
    }
}