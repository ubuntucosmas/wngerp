<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'item', 'material', 'specification',
        'unit', 'quantity', 'notes', 'design_reference', 'approved_by',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
