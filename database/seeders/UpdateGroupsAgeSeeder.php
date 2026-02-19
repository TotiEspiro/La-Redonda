<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;

class UpdateGroupsAgeSeeder extends Seeder
{
    /**
     * Configura los grupos parroquiales organizados por categorías y rangos de edad.
     * Si el grupo no existe, lo crea. Si existe, actualiza los datos.
     */
    public function run()
    {
        $config = [
            // --- CATEQUESIS ---
            'catequesis_ninos' => [
                'nombre' => 'Catequesis Jovenes',
                'min' => 6,
                'max' => 12,
                'descripcion' => 'Preparación para la comunión y formación inicial.'
            ],
            'catequesis_adolescentes' => [
                'nombre' => 'Catequesis Adolescentes',
                'min' => 13,
                'max' => 17,
                'descripcion' => 'Formación para la Confirmación y perseverancia.'
            ],
            'catequesis_adultos' => [
                'nombre' => 'Catequesis Adultos',
                'min' => 18,
                'max' => 99,
                'descripcion' => 'Iniciación cristiana para adultos.'
            ],

            // --- JÓVENES ---
            'juveniles' => [
                'nombre' => 'Juveniles',
                'min' => 13,
                'max' => 17,
                'descripcion' => 'Grupo de pertenencia y amistad para adolescentes.'
            ],
            'acutis' => [
                'nombre' => 'Acutis',
                'min' => 18,
                'max' => 24,
                'descripcion' => 'Jóvenes universitarios y trabajadores.'
            ],
            'juan_pablo' => [
                'nombre' => 'Juan Pablo II',
                'min' => 25,
                'max' => 35,
                'descripcion' => 'Jóvenes adultos en camino de fe.'
            ],
            'coro' => [
                'nombre' => 'Coro Parroquial',
                'min' => 18,
                'max' => 99,
                'descripcion' => 'Ministerio de música para las celebraciones.'
            ],
            'misioneros' => [
                'nombre' => 'Grupo Misionero',
                'min' => 18,
                'max' => 99,
                'descripcion' => 'Llevando la palabra de Dios a las misiones.'
            ],

            // --- MAYORES ---
            'santa_ana' => [
                'nombre' => 'Santa Ana',
                'min' => 60,
                'max' => 99,
                'descripcion' => 'Espacio de encuentro para mujeres mayores.'
            ],
            'san_joaquin' => [
                'nombre' => 'San Joaquín',
                'min' => 60,
                'max' => 99,
                'descripcion' => 'Espacio de encuentro para hombres mayores.'
            ],
            'costureras' => [
                'nombre' => 'Costureras',
                'min' => 60,
                'max' => 99,
                'descripcion' => 'Servicio de costura y caridad.'
            ],
            'ardillas' => [
                'nombre' => 'Ardillas',
                'min' => 50,
                'max' => 99,
                'descripcion' => 'Grupo de oración y recreación para adultos.'
            ],

            // --- ESPECIALES / MÁS GRUPOS ---
            'caridad' => [
                'nombre' => 'Noche de Caridad',
                'min' => 18,
                'max' => 99,
                'descripcion' => 'Visita a enfermos y necesitados.'
            ],
            'comedor' => [
                'nombre' => 'Comedor',
                'min' => 18,
                'max' => 99,
                'descripcion' => 'Servicio de alimentación a la comunidad.'
            ],
            'caritas' => [
                'nombre' => 'Cáritas',
                'min' => 18,
                'max' => 99,
                'descripcion' => 'Asistencia social parroquial.'
            ],
        ];

        foreach ($config as $slug => $data) {
            Group::updateOrCreate(
                ['category' => $slug],
                [
                    'name'        => $data['nombre'],
                    'min_age'     => $data['min'],
                    'max_age'     => $data['max'],
                    'description' => $data['descripcion'],
                ]
            );
        }

        $this->command->info('Grupos organizados por categorías y edades actualizados.');
    }
}