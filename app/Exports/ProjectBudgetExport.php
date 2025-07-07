<?php

namespace App\Exports;

use App\Models\ProjectBudget;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ProjectBudgetExport implements FromView
{
    protected $budget;

    public function __construct(ProjectBudget $budget)
    {
        $this->budget = $budget;
    }

    public function view(): View
    {
        $project = $this->budget->project;
        $grouped = $this->budget->items->groupBy('category');
        return view('exports.project-budget', [
            'budget' => $this->budget,
            'project' => $project,
            'grouped' => $grouped,
        ]);
    }
} 