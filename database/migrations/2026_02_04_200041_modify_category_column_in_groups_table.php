<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('groups', function (Blueprint $table) {
            // Cambiamos el tipo a string para que acepte cualquier slug
            $table->string('category')->change(); 
        });
    }

    public function down() {
        Schema::table('groups', function (Blueprint $table) {
            // Si antes era enum, aquí volvería a serlo (opcional)
            $table->enum('category', ['catequesis', 'otros'])->change();
        });
    }
};