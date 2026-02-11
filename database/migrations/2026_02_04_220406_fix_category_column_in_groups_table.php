<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('groups', function (Blueprint $table) {
        // Cambiamos la columna a string para que acepte cualquier longitud de texto
        $table->string('category')->change();
    });
}

public function down()
{
    Schema::table('groups', function (Blueprint $table) {
        // Opcional: volver a un tamaño menor si fuera necesario
        $table->string('category', 50)->change();
    });
}
};
