<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\ProjectPhase;
use Illuminate\Support\Facades\DB;

class ProjectPhasesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting ProjectPhasesTableSeeder...');
        
        try {
            // First, check how many records exist
            $existingCount = DB::table('project_phases')->count();
            $this->command->info("Found {$existingCount} existing phases to delete");
            
            // Delete all existing data
            DB::table('project_phases')->delete();
            $this->command->info('Deleted all existing phases');
            
            // Reset auto-increment counter
            DB::statement('ALTER TABLE project_phases AUTO_INCREMENT = 1');
            $this->command->info('Reset auto-increment counter');
            
            // Verify table is empty
            $newCount = DB::table('project_phases')->count();
            $this->command->info("Table now contains {$newCount} phases (should be 0)");
            
            $phases = config('project_process_phases');
            $this->command->info('Loaded ' . count($phases) . ' phases from config');
            
            $projects = Project::all();
            $this->command->info('Found ' . $projects->count() . ' projects');
            
            $totalPhasesCreated = 0;
            
            foreach ($projects as $project) {
                $this->command->info('Processing project: ' . $project->name . ' (ID: ' . $project->id . ')');
                
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
            
            $this->command->info('Created ' . $totalPhasesCreated . ' total phases');
            $this->command->info('ProjectPhasesTableSeeder completed successfully!');
            
        } catch (\Exception $e) {
            $this->command->error('Error in ProjectPhasesTableSeeder: ' . $e->getMessage());
            throw $e;
        }
    }
}
