<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
    Schema::table('groups', function (Blueprint $table) {
        if (!Schema::hasColumn('groups', 'min_age')) {
            $table->integer('min_age')->default(0);
        }
        if (!Schema::hasColumn('groups', 'max_age')) {
            $table->integer('max_age')->default(99);
        }
    });
}
};
