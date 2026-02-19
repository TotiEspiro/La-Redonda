<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega el campo de contraseña a la tabla de grupos.
     * Al estar en un hosting, usamos nullable para no romper datos existentes.
     */
    public function up(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->string('group_password')->nullable()->after('category');
        });
    }

    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn('group_password');
        });
    }
};