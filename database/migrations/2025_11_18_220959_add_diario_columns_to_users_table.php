<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_diario_columns_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('diario_data')->nullable()->after('password');
            $table->timestamp('last_diario_entry')->nullable()->after('diario_data');
        });

        // Migrar datos existentes desde diario_entries a users
        if (Schema::hasTable('diario_entries')) {
            $entries = DB::table('diario_entries')->get()->groupBy('user_id');
            
            foreach ($entries as $userId => $userEntries) {
                $diarioData = [];
                
                foreach ($userEntries as $entry) {
                    $diarioData[] = [
                        'id' => $entry->id,
                        'title' => $entry->title_encrypted,
                        'content' => $entry->content_encrypted,
                        'type' => $entry->type,
                        'color' => $entry->color,
                        'is_favorite' => (bool)$entry->is_favorite,
                        'created_at' => $entry->created_at,
                        'updated_at' => $entry->updated_at
                    ];
                }
                
                DB::table('users')
                    ->where('id', $userId)
                    ->update([
                        'diario_data' => Crypt::encryptString(json_encode($diarioData)),
                        'last_diario_entry' => $userEntries->max('created_at')
                    ]);
            }
        }
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['diario_data', 'last_diario_entry']);
        });
    }
};