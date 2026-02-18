<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // El orden es vital: primero grupos, luego roles (que dependen de los grupos creados)
        $this->call([
            UpdateGroupsAgeSeeder::class,
            RoleSystemSeeder::class,
            // Aquí puedes llamar a tus otros seeders como AdminUserSeeder si los tienes
        ]);
    }
}