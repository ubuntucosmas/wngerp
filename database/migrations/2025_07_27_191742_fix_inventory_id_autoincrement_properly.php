<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, let's check if there are any duplicate IDs and fix them
        $duplicates = DB::select('SELECT id, COUNT(*) as count FROM inventory GROUP BY id HAVING count > 1');
        
        if (!empty($duplicates)) {
            // Remove duplicates, keeping only the first occurrence
            foreach ($duplicates as $duplicate) {
                $records = DB::select('SELECT * FROM inventory WHERE id = ? ORDER BY created_at ASC', [$duplicate->id]);
                // Keep the first record, delete the rest
                for ($i = 1; $i < count($records); $i++) {
                    DB::delete('DELETE FROM inventory WHERE id = ? AND created_at = ?', [$records[$i]->id, $records[$i]->created_at]);
                }
            }
        }
        
        // Check if primary key exists and drop it if it does
        $primaryKeyExists = DB::select("SHOW KEYS FROM inventory WHERE Key_name = 'PRIMARY'");
        if (!empty($primaryKeyExists)) {
            DB::statement('ALTER TABLE inventory DROP PRIMARY KEY');
        }
        
        // Add primary key and auto-increment to id field
        DB::statement('ALTER TABLE inventory ADD PRIMARY KEY (id)');
        DB::statement('ALTER TABLE inventory MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        
        // Set the auto-increment value to start from the next available ID
        $maxId = DB::scalar('SELECT MAX(id) FROM inventory') ?? 0;
        DB::statement("ALTER TABLE inventory AUTO_INCREMENT = " . ($maxId + 1));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is irreversible, but we can document what was changed
        // The id field was modified to be auto-incrementing
    }
};