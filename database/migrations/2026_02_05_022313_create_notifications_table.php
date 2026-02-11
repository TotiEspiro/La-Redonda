<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        // Borramos la tabla vieja para crear la oficial de Laravel
        Schema::dropIfExists('notifications');

        // Esta es la estructura estándar que requiere Laravel para via('database') y WebPush
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Laravel usa UUID para notificaciones
            $table->string('type');
            $table->morphs('notifiable'); // Crea 'notifiable_id' y 'notifiable_type'
            $table->text('data'); // Aquí se guarda el JSON con el título, mensaje y link
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        // Mantenemos la columna de preferencia en la tabla users
        if (!Schema::hasColumn('users', 'notify_announcements')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('notify_announcements')->default(true);
            });
        }
    }

    public function down() {
        Schema::dropIfExists('notifications');
    }
};