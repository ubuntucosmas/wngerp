<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{

    protected $fillable = [
        'task_id',
        'file_path',
        'file_name',
    ];
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function getFileUrl()
    {
        return asset('storage/' . $this->file_path);
    }
}
