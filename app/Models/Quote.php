<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quote extends Model
{
    protected $fillable = [
        'project_id',
        'enquiry_id',
        'customer_name',
        'project_budget_id',
        'customer_location',
        'attention',
        'quote_date',
        'project_start_date',
        'reference',
    ];

    protected $casts = [
        'quote_date' => 'date',
        'project_start_date' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function enquiry(): BelongsTo
    {
        return $this->belongsTo(Enquiry::class);
    }

    public function lineItems(): HasMany
    {
        return $this->hasMany(QuoteLineItem::class);
    }
    
    public function projectBudget()
    {
        return $this->belongsTo(ProjectBudget::class);
    }
}
