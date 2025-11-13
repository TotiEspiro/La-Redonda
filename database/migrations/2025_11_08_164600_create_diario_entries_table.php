<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('diario_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('title_encrypted'); 
            $table->text('content_encrypted');
            $table->enum('type', ['texto', 'mapa_conceptual', 'lista', 'reflexion'])->default('texto');
            $table->string('color')->default('#3b82f6');
            $table->boolean('is_favorite')->default(false);
            $table->timestamps();
            
            // Índices para mejor performance
            $table->index(['user_id', 'type']);
            $table->index(['user_id', 'is_favorite']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('diario_entries');
    }
};