<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        // Agregamos columnas de edad a 'groups' solo si no existen
        Schema::table('groups', function (Blueprint $table) {
            if (!Schema::hasColumn('groups', 'min_age')) {
                $table->integer('min_age')->default(0);
            }
            if (!Schema::hasColumn('groups', 'max_age')) {
                $table->integer('max_age')->default(99);
            }
        });

        // Agregamos campos de onboarding a 'users' solo si no existen
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'age')) {
                $table->integer('age')->nullable();
            }
            if (!Schema::hasColumn('users', 'onboarding_completed')) {
                $table->boolean('onboarding_completed')->default(false);
            }
        });
    }

    public function down() {
        // No borramos nada en reversa para proteger el backup
    }
};