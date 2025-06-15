<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Deliverable extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'item',
        'done',
    ];

    protected $casts = [
        'done' => 'boolean',
    ];

    // A deliverable belongs to a task
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
