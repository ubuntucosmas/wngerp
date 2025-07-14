<?php

namespace App\Exports;

use App\Models\ProjectBudget;
use App\Models\Enquiry;
use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ProjectBudgetExport implements FromView
{
    protected $budget;
    protected $enquiry;
    protected $project;

    public function __construct(ProjectBudget $budget, Enquiry $enquiry = null, Project $project = null)
    {
        $this->budget = $budget;
        $this->enquiry = $enquiry;
        $this->project = $project;
    }

    public function view(): View
    {
        $grouped = $this->budget->items->groupBy('category');
        return view('exports.project-budget', [
            'budget' => $this->budget,
            'enquiry' => $this->enquiry,
            'project' => $this->project,
            'grouped' => $grouped,
        ]);
    }
} 