<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up()
    {
        // Insertar usuario SuperAdmin (cambia el email por el tuyo)
        DB::table('users')->insert([
            'name' => 'Super Administrador',
            'email' => 'superadmin@laredonda.com', // Cambia por tu email
            'password' => Hash::make('admin123'), // Cambia la contraseña
            'role' => 'superadmin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        DB::table('users')->where('role', 'superadmin')->delete();
    }
};