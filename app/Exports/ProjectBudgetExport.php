<?php

namespace App\Exports;

use App\Models\ProjectBudget;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProjectBudgetExport implements FromCollection, WithHeadings
{
    protected $budget;

    public function __construct(ProjectBudget $budget)
    {
        $this->budget = $budget;
    }

    public function collection()
    {
        return $this->budget->items->map(function ($item) {
            return [
                'Category'    => $item->category,
                'Particular'  => $item->particular,
                'Unit'        => $item->unit,
                'Quantity'    => $item->quantity,
                'Unit Price'  => $item->unit_price,
                'Cost'        => $item->budgeted_cost,
                'Comment'     => $item->comment,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Category',
            'Particular',
            'Unit',
            'Quantity',
            'Unit Price',
            'Cost',
            'Comment',
        ];
    }
} 