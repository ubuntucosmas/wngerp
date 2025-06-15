<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EnquiryLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'project_name',
        'venue',
        'date_received',
        'client_name',
        'project_scope_summary',
        'contact_person',
        'status',
        'assigned_to',
        'follow_up_notes',
    ];

    protected $casts = [
        'project_scope_summary' => 'array',
        'date_received' => 'date',
    ];

    /**
     * Get the project that owns the enquiry log.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
