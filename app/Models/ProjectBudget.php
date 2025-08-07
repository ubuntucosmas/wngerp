<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectBudget extends Model
{
    protected $fillable = [
        'project_id', 'enquiry_id', 'start_date', 'end_date', 'budget_total', 'invoice',
        'profit', 'approved_by', 'approved_departments', 'status', 'approved_at', 'material_list_id'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(BudgetItem::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }
    public function materialList()
    {
        return $this->belongsTo(MaterialList::class);
    }

    public function quote()
    {
        return $this->hasOne(Quote::class);
    }

    public function getBudgetTotalAttribute($value)
    {
        // If the stored value is null or 0, calculate from items
        if (!$value || $value == 0) {
            return $this->items->sum('budgeted_cost');
        }
        return $value;
    }
}
