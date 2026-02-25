<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Verificamos si las columnas existen antes de crearlas para evitar errores
            if (!Schema::hasColumn('users', 'notify_community')) {
                $table->boolean('notify_community')->default(true)->after('notify_announcements');
            }
            
            if (!Schema::hasColumn('users', 'notify_donations_intentions')) {
                $table->boolean('notify_donations_intentions')->default(true)->after('notify_community');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['notify_community', 'notify_donations_intentions']);
        });
    }
};