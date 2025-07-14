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
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function phaseable()
    {
        return $this->morphTo();
    }
}