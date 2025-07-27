<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Models\Enquiry;

class TestEnquiryRelationship extends Command
{
    protected $signature = 'test:enquiry-relationship {project_id?}';
    protected $description = 'Test the enquiry relationship for a project';

    public function handle()
    {
        $projectId = $this->argument('project_id') ?? 102;
        
        $this->info("Testing enquiry relationship for Project ID: {$projectId}");
        
        $project = Project::find($projectId);
        if (!$project) {
            $this->error("Project with ID {$projectId} not found!");
            return 1;
        }
        
        $this->line("Project found: {$project->name}");
        
        // Test direct query
        $enquiry = Enquiry::where('converted_to_project_id', $project->id)->first();
        $this->line("Direct query result: " . ($enquiry ? "Enquiry ID {$enquiry->id} found" : "No enquiry found"));
        
        // Test relationship
        $enquirySource = $project->enquirySource;
        $this->line("Relationship result: " . ($enquirySource ? "Enquiry ID {$enquirySource->id} found" : "No enquiry found"));
        
        // Check budget
        $budget = $project->budgets()->first();
        if ($budget) {
            $this->line("Budget found: ID {$budget->id}, Enquiry ID: " . ($budget->enquiry_id ?? 'NULL') . ", Project ID: " . ($budget->project_id ?? 'NULL'));
        } else {
            $this->line("No budgets found for this project");
        }
        
        return 0;
    }
} 