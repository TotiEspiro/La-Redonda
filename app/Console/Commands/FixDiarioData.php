<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class FixDiarioData extends Command
{
    protected $signature = 'diario:fix-data';
    protected $description = 'Fix corrupted diario data';

    public function handle()
    {
        $users = User::whereNotNull('diario_data')->get();
        
        foreach ($users as $user) {
            $this->info("Processing user: {$user->name} (ID: {$user->id})");
            
            $diarioData = $user->diario_data;
            $fixedEntries = [];
            
            foreach ($diarioData as $entry) {
                // Si el título está encriptado, desencriptarlo
                if (isset($entry['title']) && $this->isEncrypted($entry['title'])) {
                    try {
                        $entry['title'] = Crypt::decryptString($entry['title']);
                        $this->info("  - Fixed encrypted title");
                    } catch (\Exception $e) {
                        $this->warn("  - Could not decrypt title");
                    }
                }
                
                // Si el contenido está encriptado, desencriptarlo
                if (isset($entry['content']) && $this->isEncrypted($entry['content'])) {
                    try {
                        $entry['content'] = Crypt::decryptString($entry['content']);
                        $this->info("  - Fixed encrypted content");
                    } catch (\Exception $e) {
                        $this->warn("  - Could not decrypt content");
                    }
                }
                
                $fixedEntries[] = $entry;
            }
            
            // Guardar datos corregidos
            $user->diario_data = $fixedEntries;
            $user->save();
            
            $this->info("  Fixed {$user->name}'s diario data");
        }
        
        $this->info('✅ All diario data fixed!');
    }
    
    private function isEncrypted($value)
    {
        // Verificar si parece estar encriptado (formato Laravel encryption)
        return is_string($value) && str_contains($value, 'eyJpdiI6') && strlen($value) > 50;
    }
}