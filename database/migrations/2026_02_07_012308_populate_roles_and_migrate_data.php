<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Insertar los roles faltantes
        $this->insertMissingRoles();

        // 2. Migrar los datos de users.role a user_roles
        $this->migrateExistingRoles();
    }

    public function down()
    {
        // En un down() normalmente revertiríamos los cambios, pero en este caso
        // es mejor no hacer nada para no perder datos
    }

    private function insertMissingRoles()
    {
        $roles = [
            ['name' => 'superadmin', 'display_name' => 'Super Administrador', 'description' => 'Acceso completo al sistema', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'admin', 'display_name' => 'Administrador', 'description' => 'Administración del sistema', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'catequesis_ninos', 'display_name' => 'Catequesis Jovenes', 'description' => 'Responsable de catequesis para infantes', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'catequesis_adolescentes', 'display_name' => 'Catequesis Adolescentes', 'description' => 'Responsable de catequesis para adolescentes', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'catequesis_adultos', 'display_name' => 'Catequesis Adultos', 'description' => 'Responsable de catequesis para adultos', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'juveniles', 'display_name' => 'Juveniles', 'description' => 'Responsable de grupo juvenil', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'acutis', 'display_name' => 'Acutis', 'description' => 'Responsable de grupo Acutis', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'juan_pablo', 'display_name' => 'Juan Pablo', 'description' => 'Responsable de grupo Juan Pablo', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'coro', 'display_name' => 'Coro', 'description' => 'Responsable del coro', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'san_joaquin', 'display_name' => 'San Joaquín', 'description' => 'Responsable de grupo San Joaquín', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'santa_ana', 'display_name' => 'Santa Ana', 'description' => 'Responsable de grupo Santa Ana', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ardillas', 'display_name' => 'Ardillas', 'description' => 'Responsable de grupo Ardillas', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'costureras', 'display_name' => 'Costureras', 'description' => 'Responsable de grupo Costureras', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'misioneros', 'display_name' => 'Misioneros', 'description' => 'Responsable de grupo Misionero', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'caridad', 'display_name' => 'Caridad', 'description' => 'Responsable de lcomedor', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'comedor', 'display_name' => ' Comedor', 'description' => 'Responsable de noche de caridad', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'user', 'display_name' => 'Usuario', 'description' => 'Usuario básico', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($roles as $role) {
            // Verificar si el rol ya existe antes de insertar
            $existingRole = DB::table('roles')->where('name', $role['name'])->first();
            if (!$existingRole) {
                DB::table('roles')->insert($role);
                echo "Insertado rol: " . $role['name'] . "\n";
            } else {
                echo "Rol ya existe: " . $role['name'] . "\n";
            }
        }
    }

    private function migrateExistingRoles()
    {
        // Obtener todos los usuarios con sus roles actuales
        $users = DB::table('users')->get();
        
        foreach ($users as $user) {
            // Buscar el rol correspondiente en la tabla roles
            $role = DB::table('roles')->where('name', $user->role)->first();
            
            if ($role) {
                // Verificar si ya existe esta relación
                $existingRelation = DB::table('user_roles')
                    ->where('user_id', $user->id)
                    ->where('role_id', $role->id)
                    ->first();
                
                if (!$existingRelation) {
                    // Insertar en la tabla user_roles
                    DB::table('user_roles')->insert([
                        'user_id' => $user->id,
                        'role_id' => $role->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    echo "Migrado usuario {$user->id} con rol {$user->role}\n";
                } else {
                    echo "Relación ya existe para usuario {$user->id} con rol {$user->role}\n";
                }
            } else {
                echo "Rol no encontrado para usuario {$user->id}: {$user->role}\n";
            }
        }
    }
};