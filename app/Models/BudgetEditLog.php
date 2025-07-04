<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetEditLog extends Model
{
    protected $fillable = [
        'project_budget_id', 'user_id', 'changes'
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function budget()
    {
        return $this->belongsTo(ProjectBudget::class, 'project_budget_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 