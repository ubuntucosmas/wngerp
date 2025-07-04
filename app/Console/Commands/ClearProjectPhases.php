<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearProjectPhases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:clear-phases';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all data from project_phases table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $count = DB::table('project_phases')->count();
            $this->info("Found {$count} existing phases");
            
            DB::table('project_phases')->truncate();
            $this->info('Successfully cleared project_phases table');
            
            $newCount = DB::table('project_phases')->count();
            $this->info("Table now contains {$newCount} phases");
            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
} 