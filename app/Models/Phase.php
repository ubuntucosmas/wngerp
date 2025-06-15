<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phase extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'start_date',
        'end_date',
        'description',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function updateStatusFromTasks()
    {
        $taskStatuses = $this->tasks()->pluck('status')->toArray();

        if (empty($taskStatuses)) {
            $this->status = 'Pending';
        } elseif (count(array_unique($taskStatuses)) === 1 && $taskStatuses[0] === 'Pending') {
            $this->status = 'Pending';
        } elseif (in_array('In Progress', $taskStatuses)) {
            $this->status = 'In Progress';
        } elseif (!in_array('In Progress', $taskStatuses) && in_array('Complete', $taskStatuses)) {
            $this->status = 'Complete';
        }

        $this->save();
    }

    public function calculateStatus()
    {
        $tasks = $this->tasks;
        if ($tasks->isEmpty()) {
            $this->update(['status' => 'Pending']);
            return;
        }

        $totalProgress = $tasks->sum('progress') / $tasks->count();
        $status = match (true) {
            $totalProgress >= 100 => 'Complete',
            $totalProgress > 0 => 'In Progress',
            default => 'Pending',
        };

        $this->update(['status' => $status]);
    }
}