<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;


    protected $fillable = [
        'phase_id',
        'user_id',        // Creator
        'assigned_to',    // Assignee
        'name',
        'status',
        'description',
        'start_date',
        'due_date',
        'comment',
        'file',
    ];

    /**
     * Relationships
     */

    // A task belongs to a phase
    public function phase()
    {
        return $this->belongsTo(Phase::class);
    }

    // A task is created by a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A task is assigned to a user
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // A task has many deliverables
    public function deliverables()
    {
        return $this->hasMany(Deliverable::class);
    }

    // A task has many comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // A task can have many attachments
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    /**
     * Accessors / Helpers
     */

    public function isCompleted(): bool
    {
        return $this->status === 'Completed';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'In Progress';
    }

    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }
}
