<?php
// database/migrations/xxxx_xx_xx_xxxxxx_drop_diario_entries_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Eliminar las foreign keys primero
        Schema::table('diario_entries', function (Blueprint $table) {
            if (Schema::hasTable('diario_entries')) {
                // Verificar si la constraint existe antes de intentar eliminarla
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexes = $sm->listTableIndexes('diario_entries');
                
                if (array_key_exists('diario_entries_user_id_foreign', $indexes)) {
                    $table->dropForeign(['user_id']);
                }
            }
        });

        // Eliminar la tabla
        Schema::dropIfExists('diario_entries');
    }

    public function down()
    {
        // Recrear la tabla diario_entries (para rollback)
        Schema::create('diario_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->text('title_encrypted');
            $table->text('content_encrypted');
            $table->enum('type', ['texto','mapa_conceptual','lista','reflexion'])->default('texto');
            $table->string('color')->default('#3b82f6');
            $table->boolean('is_favorite')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Índices
            $table->index(['user_id', 'type']);
            $table->index(['user_id', 'is_favorite']);
            $table->index(['user_id', 'created_at']);
        });
    }
};