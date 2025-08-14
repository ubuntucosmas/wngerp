<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectPhase extends Model
{
    protected $table = 'project_phases';

    protected $fillable = [
        'phaseable_id',
        'phaseable_type',
        'name',
        'icon',
        'summary',
        'start_date',
        'end_date',
        'description',
        'status',
        'skipped',
        'skip_reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'skipped' => 'boolean',
    ];

    public function phaseable()
    {
        return $this->morphTo();
    }

    /**
     * Get all documents for this phase.
     */
    public function documents()
    {
        return $this->hasMany(PhaseDocument::class);
    }

    /**
     * Get active documents for this phase.
     */
    public function activeDocuments()
    {
        return $this->hasMany(PhaseDocument::class)->where('is_active', true);
    }
}