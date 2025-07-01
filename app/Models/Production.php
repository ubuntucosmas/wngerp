<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Production extends Model
{
    protected $fillable = [
        'project_id',
        'job_number',
        'project_title',
        'client_name',
        'briefing_date',
        'briefed_by',
        'delivery_date',
        'production_team',
        'materials_required',
        'key_instructions',
        'special_considerations',
        'files_received',
        'additional_notes',
        'status',
        'status_notes'
    ];

    protected $casts = [
        'briefing_date' => 'date',
        'delivery_date' => 'date',
        'files_received' => 'boolean',
        'status' => 'string'
    ];

    protected $dates = [
        'briefing_date',
        'delivery_date'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function tasks()
    {
        return $this->hasMany(ProductionTask::class);
    }

    public function getStatusBadgeAttribute()
    {
        $statusColors = [
            'pending' => 'secondary',
            'approved' => 'success',
            'rejected' => 'danger',
            'completed' => 'primary'
        ];

        return '<span class="badge bg-' . $statusColors[$this->status] . '">' . ucfirst(str_replace('_', ' ', $this->status)) . '</span>';
    }

    public function getFilesReceivedAttribute($value)
    {
        return (bool) $value;
    }

    public function setFilesReceivedAttribute($value)
    {
        $this->attributes['files_received'] = (bool) $value;
    }
}
