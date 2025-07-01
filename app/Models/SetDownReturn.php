<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SetDownReturn extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'google_drive_link',
        'uploaded_by',
    ];

    /**
     * Get the project that owns the set down return report.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who uploaded the set down return report.
     */
    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
