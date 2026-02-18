<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Esta migración crea las tablas base necesarias (roles y grupos).
     * Los 'if (!Schema::hasTable)' sirven para que, si ya importaste tu SQL, no dé error.
     */
    public function up() {
        // 1. Tabla de Roles
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('display_name');
                $table->string('description')->nullable();
                $table->timestamps();
            });
        }

        // 2. Tabla de Grupos
        if (!Schema::hasTable('groups')) {
            Schema::create('groups', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('category', 100)->unique(); 
                $table->text('description')->nullable();
                $table->integer('min_age')->default(0);
                $table->integer('max_age')->default(99);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 3. Tabla Intermedia
        if (!Schema::hasTable('user_roles')) {
            Schema::create('user_roles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('role_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }

        // 4. Campos extra en Usuarios
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'age')) $table->integer('age')->nullable();
            if (!Schema::hasColumn('users', 'onboarding_completed')) $table->boolean('onboarding_completed')->default(false);
            if (!Schema::hasColumn('users', 'provider_id')) {
                $table->string('provider_id')->nullable();
                $table->string('provider_name')->nullable();
                $table->string('avatar')->nullable();
            }
        });
    }

    public function down() {
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('roles');
    }
};