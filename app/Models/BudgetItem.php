<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetItem extends Model
{
    protected $fillable = [
        'project_budget_id', 'category', 'particular', 'unit',
        'quantity', 'unit_price', 'budgeted_cost', 'comment'
    ];

    public function budget()
    {
        return $this->belongsTo(ProjectBudget::class, 'project_budget_id');
    }
}
