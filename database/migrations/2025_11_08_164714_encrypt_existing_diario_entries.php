<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

return new class extends Migration
{
    public function up()
    {
        // Si ya tienes datos, los migramos
        if (Schema::hasTable('diario_entries') && Schema::hasColumn('diario_entries', 'title')) {
            
            $entries = DB::table('diario_entries')->get();
            
            foreach ($entries as $entry) {
                DB::table('diario_entries')
                    ->where('id', $entry->id)
                    ->update([
                        'title_encrypted' => Crypt::encryptString($entry->title),
                        'content_encrypted' => Crypt::encryptString($entry->content),
                    ]);
            }
            
            // Una vez migrados, eliminamos las columnas antiguas
            Schema::table('diario_entries', function (Blueprint $table) {
                $table->dropColumn(['title', 'content']);
            });
        }
    }

    public function down()
    {
        // En caso de rollback, no podemos recuperar los datos fácilmente
        // por eso es importante hacer backup primero
        Schema::table('diario_entries', function (Blueprint $table) {
            $table->string('title')->after('user_id');
            $table->text('content')->after('title_encrypted');
        });
    }
};