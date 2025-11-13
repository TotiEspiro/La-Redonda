<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntentionsTable extends Migration
{
    public function up()
    {
        Schema::create('intentions', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // salud, intenciones, accion-gracias, difuntos
            $table->string('name');
            $table->string('email');
            $table->text('message');
            $table->string('status')->default('pending'); // pending, read, completed
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('intentions');
    }
}