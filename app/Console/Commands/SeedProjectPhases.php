<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Models\ProjectPhase;
use Illuminate\Support\Facades\DB;

class SeedProjectPhases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:seed-phases';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed project phases for all projects';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('Starting to seed project phases...');
            
            // Truncate the table first
            DB::table('project_phases')->truncate();
            $this->info('Cleared existing phases');
            
            $phases = config('project_process_phases');
            $this->info('Loaded ' . count($phases) . ' phases from config');
            
            $projects = Project::all();
            $this->info('Found ' . $projects->count() . ' projects');
            
            $totalPhasesCreated = 0;
            
            foreach ($projects as $project) {
                $this->info('Processing project: ' . $project->name . ' (ID: ' . $project->id . ')');
                
                foreach ($phases as $phase) {
                    ProjectPhase::create([
                        'project_id' => $project->id,
                        'name' => $phase['name'],
                        'icon' => $phase['icon'] ?? null,
                        'summary' => $phase['summary'] ?? null,
                        'description' => $phase['summary'] ?? null,
                        'status' => $phase['status'],
                        'start_date' => null,
                        'end_date' => null,
                    ]);
                    $totalPhasesCreated++;
                }
            }
            
            $this->info('Successfully created ' . $totalPhasesCreated . ' phases');
            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
    }
} 