<?php

namespace App\Services;

use App\Models\Project;
use App\Models\CloseOutReport;
use Carbon\Carbon;

class CloseOutReportGeneratorService
{
    /**
     * Generate a close-out report from project data
     */
    public function generateFromProject(Project $project): CloseOutReport
    {
        // Load all available relationships with proper error handling
        $project->load([
            'client',
            'projectOfficer',
            'projectManager',
            'materialLists.items',
            'budgets.items',
            'phases',
            'setupReports',
            'handoverReports',
            'setDownReturns',
            'archivalReports',
            'siteSurveys',
            'production',
            'quotes'
        ]);

        $reportData = $this->extractProjectData($project);
        
        return $project->closeOutReports()->create(array_merge($reportData, [
            'status' => 'draft',
            'created_by' => auth()->id() ?? 1, // Fallback to user ID 1 if no auth
        ]));
    }

    /**
     * Extract and format data from project relationships
     */
    private function extractProjectData(Project $project): array
    {
        return [
            // Section 1: Project Information
            'project_title' => $project->name,
            'client_name' => $this->getClientName($project),
            'project_code' => $project->project_id,
            'project_officer' => $this->getProjectOfficerName($project),
            'set_up_date' => $project->start_date,
            'set_down_date' => $project->end_date,
            'site_location' => $project->venue,

            // Section 2: Project Scope Summary
            'scope_summary' => $this->generateScopeSummary($project),

            // Section 3: Procurement & Inventory (using available data)
            'materials_requested_notes' => $this->getMaterialsRequested($project),
            'items_sourced_externally' => 'To be updated from procurement records',
            'store_issued_items' => 'To be updated from inventory records',
            'inventory_returns_balance' => 'To be updated from inventory records',
            'procurement_challenges' => 'No procurement challenges recorded',

            // Section 4: Fabrication & Quality Control
            'production_start_date' => $this->getProductionStartDate($project),
            'packaging_labeling_status' => 'To be updated',
            'qc_findings_resolutions' => 'To be updated from QC records',
            'production_challenges' => 'No production challenges recorded',

            // Section 5: On-Site Setup Summary
            'setup_dates' => $this->getSetupDates($project),
            'estimated_setup_time' => $this->getEstimatedSetupTime($project),
            'actual_setup_time' => $this->getActualSetupTime($project),
            'team_composition' => 'To be updated from team records',
            'onsite_challenges' => 'No onsite challenges recorded',
            'client_interactions' => 'To be updated from client feedback',
            'safety_issues' => 'No safety issues reported',

            // Section 6: Client Handover
            'handover_date' => $project->end_date,
            'client_signoff_status' => $this->getClientSignoffStatus($project),
            'client_feedback_qr' => $this->getClientFeedbackQR($project),
            'post_handover_adjustments' => 'No post-handover adjustments recorded',

            // Section 7: Set-Down & Debrief Summary
            'condition_of_items_returned' => 'To be updated from return records',
            'site_clearance_status' => $this->getSiteClearanceStatus($project),
            'debrief_notes' => 'To be updated with debrief information',

            // Section 8: Attachments Checklist (basic detection)
            'att_deliverables_ppt' => false,
            'att_cutlist' => false,
            'att_site_survey' => $project->siteSurveys && $project->siteSurveys->count() > 0,
            'att_project_budget' => $this->hasProjectBudget($project),
            'att_mrf_or_material_list' => $this->hasMaterialList($project),
            'att_qc_checklist' => false,
            'att_setup_setdown_checklists' => $project->setupReports && $project->setupReports->count() > 0,
            'att_client_feedback_form' => false,

            // Legacy fields for compatibility
            'project_client_details' => $this->getProjectClientDetails($project),
            'budget_vs_actual_summary' => $this->getBudgetVsActualSummary($project),
            'issues_encountered' => 'No issues recorded',
            'client_feedback_summary' => 'No client feedback available',
            'po_recommendations' => $this->getPORecommendations($project),
        ];
    }

    // Helper methods for data extraction

    private function getClientName(Project $project): ?string
    {
        if ($project->client_name) {
            return $project->client_name;
        }
        
        if ($project->client && $project->client->FullName) {
            return $project->client->FullName;
        }
        
        return 'N/A';
    }

    private function getProjectOfficerName(Project $project): ?string
    {
        if ($project->projectOfficer && $project->projectOfficer->name) {
            return $project->projectOfficer->name;
        }
        
        return 'N/A';
    }

    private function generateScopeSummary(Project $project): string
    {
        $summary = "Project: {$project->name}\n";
        $summary .= "Description: {$project->description}\n";
        
        if ($project->phases && $project->phases->count() > 0) {
            $summary .= "\nProject Phases:\n";
            foreach ($project->phases->take(5) as $phase) {
                $summary .= "- {$phase->name} ({$phase->status})\n";
            }
        }

        return $summary;
    }

    private function getMaterialsRequested(Project $project): string
    {
        if (!$project->materialLists || $project->materialLists->count() === 0) {
            return 'No material requests found.';
        }

        $materials = [];
        foreach ($project->materialLists as $materialList) {
            $materials[] = "Material List ID: {$materialList->id} - Status: " . ($materialList->status ?? 'N/A');
            // Add more details if available in your MaterialList model
        }

        return implode("\n", $materials) ?: 'No specific materials listed.';
    }

    private function getExternalSourcing(Project $project): string
    {
        // For now, return placeholder text since expenses relationship may not exist
        // This can be updated when the expenses relationship is available
        return 'External sourcing information to be updated from project records.';
    }

    private function getStoreIssuedItems(Project $project): string
    {
        // For now, return placeholder text since inventory relationship may not exist
        // This can be updated when the inventory relationship is available
        return 'Store-issued items information to be updated from inventory records.';
    }

    private function getInventoryBalance(Project $project): string
    {
        // For now, return placeholder text since inventory relationship may not exist
        // This can be updated when the inventory relationship is available
        return 'Inventory balance information to be updated from inventory records.';
    }

    private function getProcurementChallenges(Project $project): string
    {
        // Placeholder for procurement challenges
        // This can be updated when notes/issues relationship is available
        return 'No procurement challenges recorded.';
    }

    private function getProductionStartDate(Project $project): ?Carbon
    {
        // Look for production-related phase
        if ($project->phases) {
            $productionPhase = $project->phases->filter(function ($phase) {
                return stripos($phase->name, 'production') !== false || 
                       stripos($phase->name, 'fabrication') !== false;
            })->first();
            
            if ($productionPhase && $productionPhase->start_date) {
                return $productionPhase->start_date;
            }
        }
        return $project->start_date;
    }



    private function getSetupDates(Project $project): string
    {
        // Use setup reports if available, otherwise use project start date
        if ($project->setupReports && $project->setupReports->count() > 0) {
            $dates = $project->setupReports->pluck('created_at')->map(function ($date) {
                return $date->format('M d, Y');
            });
            return $dates->implode(', ');
        }
        
        return $project->start_date ? $project->start_date->format('M d, Y') : 'N/A';
    }

    private function getEstimatedSetupTime(Project $project): string
    {
        // Calculate from project timeline
        if ($project->start_date && $project->end_date) {
            $days = $project->start_date->diffInDays($project->end_date);
            return $days . ' days (estimated)';
        }
        return 'Not specified';
    }

    private function getActualSetupTime(Project $project): string
    {
        if ($project->start_date && $project->end_date) {
            $days = $project->start_date->diffInDays($project->end_date);
            return $days . ' days';
        }
        return 'Not completed';
    }

    private function getClientSignoffStatus(Project $project): string
    {
        return $project->status === 'completed' || $project->status === 'closed' ? 'Signed off' : 'Pending';
    }

    private function getClientFeedbackQR(Project $project): string
    {
        // Generate QR code URL or reference
        return "QR Code: " . url("/projects/{$project->id}/feedback");
    }

    private function getSiteClearanceStatus(Project $project): string
    {
        return $project->status === 'completed' || $project->status === 'closed' ? 'Site cleared' : 'Pending clearance';
    }

    // Simple attachment detection methods
    private function hasProjectBudget(Project $project): bool
    {
        return $project->budgets && $project->budgets->count() > 0;
    }

    private function hasMaterialList(Project $project): bool
    {
        return $project->materialLists && $project->materialLists->count() > 0;
    }

    // Legacy compatibility methods
    private function getProjectClientDetails(Project $project): string
    {
        $details = "Project: {$project->name}\n";
        $details .= "Client: " . $this->getClientName($project) . "\n";
        $details .= "Location: {$project->venue}\n";
        $details .= "Duration: " . ($project->start_date ? $project->start_date->format('M d, Y') : 'N/A');
        $details .= " to " . ($project->end_date ? $project->end_date->format('M d, Y') : 'N/A');
        
        return $details;
    }

    private function getBudgetVsActualSummary(Project $project): string
    {
        if ($project->budgets && $project->budgets->count() > 0) {
            $budgeted = $project->budgets->sum('amount');
            return "Budgeted Amount: {$budgeted}\nActual expenses to be updated from expense records.";
        }
        return 'Budget information not available.';
    }

    private function getPORecommendations(Project $project): string
    {
        $recommendations = [];
        
        // Budget performance recommendation
        if ($project->budgets && $project->budgets->count() > 0) {
            $budgeted = $project->budgets->sum('amount');
            $recommendations[] = "Budget allocated: {$budgeted}. Monitor actual expenses against budget.";
        }
        
        // Timeline performance
        if ($project->start_date && $project->end_date) {
            $actualDays = $project->start_date->diffInDays($project->end_date);
            $recommendations[] = "Project duration: {$actualDays} days. Review timeline efficiency for future projects.";
        }
        
        // General recommendation
        $recommendations[] = "Ensure all project documentation is properly archived.";
        
        return implode("\n", $recommendations);
    }
}