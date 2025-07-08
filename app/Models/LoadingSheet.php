<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoadingSheet extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'vehicle_number',
        'driver_name',
        'loading_point',
        'unloading_point',
        'loading_date',
        'unloading_date',
        'special_instructions',
        'items',
    ];

    protected $casts = [
        'loading_date' => 'date',
        'unloading_date' => 'date',
        'items' => 'array',
    ];

    /**
     * Get the project that owns the loading sheet.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
