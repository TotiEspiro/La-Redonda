<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupAgeSeeder extends Seeder
{
    public function run()
    {
        $grupos = [
            [
                'name' => 'Catequesis Familiar',
                'category' => 'catequesis',
                'description' => 'Preparación para la primera comunión y formación familiar.',
                'min_age' => 6,
                'max_age' => 12,
                'is_active' => true
            ],
            [
                'name' => 'Grupo Acutis',
                'category' => 'acutis',
                'description' => 'Espacio de fe y amistad para pre-adolescentes.',
                'min_age' => 11,
                'max_age' => 15,
                'is_active' => true
            ],
            [
                'name' => 'Jóvenes de La Redonda',
                'category' => 'jovenes',
                'description' => 'Encuentros, misiones y formación para jóvenes adultos.',
                'min_age' => 18,
                'max_age' => 35,
                'is_active' => true
            ],
            [
                'name' => 'Coro Parroquial',
                'category' => 'coro',
                'description' => 'Acompañamiento musical de las celebraciones litúrgicas.',
                'min_age' => 15,
                'max_age' => 99,
                'is_active' => true
            ],
            [
                'name' => 'Cáritas y Comedor',
                'category' => 'caridad_comedor',
                'description' => 'Servicio solidario y asistencia a los más necesitados.',
                'min_age' => 18,
                'max_age' => 99,
                'is_active' => true
            ],
            [
                'name' => 'Grupo Santa Ana',
                'category' => 'santa_ana',
                'description' => 'Espacio de oración y encuentro para abuelas.',
                'min_age' => 60,
                'max_age' => 99,
                'is_active' => true
            ]
        ];

        foreach ($grupos as $data) {
            // updateOrCreate busca por 'category'. Si existe, lo actualiza; si no, lo crea.
            Group::updateOrCreate(
                ['category' => $data['category']], 
                $data
            );
        }
    }
}