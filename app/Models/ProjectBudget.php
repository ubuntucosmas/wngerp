<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectBudget extends Model
{
    protected $fillable = [
        'project_id', 'start_date', 'end_date', 'budget_total', 'invoice',
        'profit', 'approved_by', 'approved_departments', 'status', 'approved_at'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(BudgetItem::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
