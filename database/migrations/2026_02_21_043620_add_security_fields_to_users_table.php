<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las modificaciones en la tabla de usuarios.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Timestamp para verificar cuándo fue la última vez que entró (lógica de 1 semana)
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('remember_token');
            }
            
            // Campo para almacenar el código de 6 dígitos (hasheado por seguridad)
            if (!Schema::hasColumn('users', 'security_code')) {
                $table->string('security_code')->nullable()->after('last_login_at');
            }
        });
    }

    /**
     * Revierte los cambios si es necesario.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['last_login_at', 'security_code']);
        });
    }
};