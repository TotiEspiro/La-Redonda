<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DiagnoseDiario extends Command
{
    protected $signature = 'diario:diagnose';
    protected $description = 'Diagnose diario issues';

    public function handle()
    {
        $user = User::first();
        
        if (!$user) {
            $this->error('No users found');
            return;
        }

        $this->info('=== DIARIO DIAGNOSIS ===');
        $this->info('User ID: ' . $user->id);
        $this->info('Can access diario: ' . ($user->canAccessDiario() ? 'YES' : 'NO'));
        $this->info('Diario data: ' . ($user->diario_data ? 'EXISTS' : 'NULL'));
        
        if ($user->diario_data) {
            $entries = $user->getDiarioEntries();
            $this->info('Number of entries: ' . count($entries));
            
            if (count($entries) > 0) {
                $this->info('First entry structure:');
                $this->info(json_encode($entries[0], JSON_PRETTY_PRINT));
            }
        }

        // Test creating a new entry
        $this->info('=== TEST CREATION ===');
        try {
            $testData = [
                'title' => 'Test Entry',
                'content' => 'Test content',
                'type' => 'texto',
                'color' => '#3b82f6',
                'is_favorite' => false
            ];
            
            $newEntry = $user->addDiarioEntry($testData);
            $this->info('✅ Entry created successfully: ' . json_encode($newEntry));
            
            // Verify it was saved
            $user->refresh();
            $entriesAfter = $user->getDiarioEntries();
            $this->info('Entries after creation: ' . count($entriesAfter));
            
        } catch (\Exception $e) {
            $this->error('❌ Error creating entry: ' . $e->getMessage());
        }
    }
}