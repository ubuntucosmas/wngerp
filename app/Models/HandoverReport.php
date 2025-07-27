<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HandoverReport extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'acknowledgment_date' => 'date',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'client_name',
        'contact_person',
        'acknowledgment_date',
        'client_comments',
        'uploaded_by',
    ];

    /**
     * Get the project that owns the handover report.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who uploaded the handover report.
     */
    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getFormattedDateAttribute()
    {
        return $this->acknowledgment_date ? $this->acknowledgment_date->format('Y-m-d') : null;
    }

    public function getAcknowledgmentDateAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value) : null;
    }
}
