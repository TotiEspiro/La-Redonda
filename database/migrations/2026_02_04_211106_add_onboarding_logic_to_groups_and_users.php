<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        // 1. Campos adicionales para Usuarios
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'age')) $table->integer('age')->nullable();
            if (!Schema::hasColumn('users', 'onboarding_completed')) $table->boolean('onboarding_completed')->default(false);
        });

        // 2. Tabla de Roles (Si no existe)
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('display_name');
                $table->timestamps();
            });
        }

        // 3. Tabla Intermedia de Roles (user_roles según tu modelo)
        if (!Schema::hasTable('user_roles')) {
            Schema::create('user_roles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('role_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }

        // 4. Tabla de Grupos Parroquiales
        if (!Schema::hasTable('groups')) {
            Schema::create('groups', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('category')->unique(); // Slug identificador
                $table->text('description')->nullable();
                $table->integer('min_age')->default(0);
                $table->integer('max_age')->default(99);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 5. Tabla de Solicitudes de Ingreso
        if (!Schema::hasTable('group_requests')) {
            Schema::create('group_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('group_role'); // Slug del grupo solicitado
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->timestamps();
            });
        }
    }

    public function down() {
        Schema::dropIfExists('group_requests');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('roles');
    }
};