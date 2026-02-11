<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        // Campos para el Usuario
        Schema::table('users', function (Blueprint $table) {
            $table->integer('age')->nullable();
            $table->boolean('onboarding_completed')->default(false);
        });

        // Campos para los Grupos
        Schema::table('groups', function (Blueprint $table) {
            $table->integer('min_age')->default(0);
            $table->integer('max_age')->default(99);
        });
    }

    public function down() {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['age', 'onboarding_completed']);
        });
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn(['min_age', 'max_age']);
        });
    }
};