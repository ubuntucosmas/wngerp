<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetItem extends Model
{
    protected $fillable = [
        'project_budget_id', 'category', 'item_name', 'template_id', 'particular', 'unit',
        'quantity', 'unit_price', 'budgeted_cost', 'comment'
    ];

    public function budget()
    {
        return $this->belongsTo(ProjectBudget::class, 'project_budget_id');
    }

    public function template()
    {
        return $this->belongsTo(\App\Models\ItemTemplate::class, 'template_id');
    }
}
