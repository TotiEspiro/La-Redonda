<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evangelio_diario', function (Blueprint $table) {
            $table->id();
            $table->text('contenido');
            $table->string('referencia');
            $table->date('fecha')->unique();
            $table->timestamps();
        });

        // Insertar registro inicial
        DB::table('evangelio_diario')->insert([
            'contenido' => '"Porque tanto amó Dios al mundo que dio a su Hijo único, para que todo el que crea en él no perezca, sino que tenga vida eterna. Porque Dios no envió a su Hijo para juzgar al mundo, sino para que el mundo se salve por él."',
            'referencia' => 'Juan 3:16-18',
            'fecha' => now()->format('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('evangelio_diario');
    }
};