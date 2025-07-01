<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logistics extends Model
{
    protected $fillable = [
        'project_id',
        'vehicle_number',
        'driver_name',
        'contact',
        'departure_time',
        'expected_arrival',
        'special_instructions',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function items()
    {
        return $this->hasMany(LogisticsItem::class);
    }
}
