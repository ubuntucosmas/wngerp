<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogisticsItem extends Model
{
    protected $fillable = [
        'logistics_id',
        'description',
        'quantity',
        'unit',
        'notes',
        'loaded',
    ];

    public function logistics()
    {
        return $this->belongsTo(Logistics::class);
    }
}
