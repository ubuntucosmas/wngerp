<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionTask extends Model
{
    protected $fillable = [
        'production_id',
        'title',
        'description',
        'status',
        'due_date',
        'assigned_to'
    ];

    protected $casts = [
        'due_date' => 'date',
        'status' => 'string'
    ];

    protected $attributes = [
        'title' => 'Untitled Task'
    ];

    public function production(): BelongsTo
    {
        return $this->belongsTo(Production::class);
    }

    public function getStatusBadgeAttribute()
    {
        $statusColors = [
            'pending' => 'secondary',
            'in_progress' => 'warning',
            'completed' => 'success'
        ];

        return '<span class="badge bg-' . $statusColors[$this->status] . '">' . ucfirst(str_replace('_', ' ', $this->status)) . '</span>';
    }
}
