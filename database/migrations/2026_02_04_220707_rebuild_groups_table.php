<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        // 1. Borramos la tabla que tiene la restricción corrupta
        Schema::dropIfExists('groups');

        // 2. La creamos de nuevo perfectamente
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category', 100)->unique(); // VARCHAR(100) es más que suficiente
            $table->text('description')->nullable();
            $table->integer('min_age')->default(0);
            $table->integer('max_age')->default(99);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('groups');
    }
};