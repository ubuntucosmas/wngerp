<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProjectBudget;
use App\Models\Project;
use App\Models\Enquiry;

class FixBudgetProjectIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'budgets:fix-project-ids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix incorrect project_id values in budgets table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting budget project ID fix...');
        
        $budgets = ProjectBudget::all();
        $fixed = 0;
        
        foreach ($budgets as $budget) {
            $this->line("Checking budget ID: {$budget->id}");
            
            // If budget has enquiry_id, check if it should have a project_id
            if ($budget->enquiry_id && !$budget->project_id) {
                $enquiry = Enquiry::find($budget->enquiry_id);
                if ($enquiry && $enquiry->converted_to_project_id) {
                    $this->line("  - Budget has enquiry_id {$budget->enquiry_id}, should have project_id {$enquiry->converted_to_project_id}");
                    $budget->project_id = $enquiry->converted_to_project_id;
                    $budget->save();
                    $fixed++;
                }
            }
            
            // If budget has project_id but no enquiry_id, check if it should have enquiry_id
            if ($budget->project_id && !$budget->enquiry_id) {
                $enquiry = Enquiry::where('converted_to_project_id', $budget->project_id)->first();
                if ($enquiry) {
                    $this->line("  - Budget has project_id {$budget->project_id}, should have enquiry_id {$enquiry->id}");
                    $budget->enquiry_id = $enquiry->id;
                    $budget->save();
                    $fixed++;
                }
            }
        }
        
        $this->info("Fixed {$fixed} budgets.");
        
        // Show summary
        $this->info('Budget summary:');
        $this->info('- Total budgets: ' . ProjectBudget::count());
        $this->info('- Budgets with project_id: ' . ProjectBudget::whereNotNull('project_id')->count());
        $this->info('- Budgets with enquiry_id: ' . ProjectBudget::whereNotNull('enquiry_id')->count());
        $this->info('- Budgets with both: ' . ProjectBudget::whereNotNull('project_id')->whereNotNull('enquiry_id')->count());
        
        return 0;
    }
}
