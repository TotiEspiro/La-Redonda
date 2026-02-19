<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            // Agregamos la columna slug después del ID
            if (!Schema::hasColumn('roles', 'slug')) {
                $table->string('slug')->unique()->after('id')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};