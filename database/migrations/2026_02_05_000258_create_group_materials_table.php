<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Si por algún motivo ya existía una tabla vieja, la borramos para crearla limpia
        Schema::dropIfExists('group_materials');

        Schema::create('group_materials', function (Blueprint $table) {
            $table->id();
            $table->string('group_role'); // Ejemplo: 'juveniles', 'catequesis'
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type')->default('pdf'); // Esta es la columna que causaba el error
            $table->string('file_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_materials');
    }
};