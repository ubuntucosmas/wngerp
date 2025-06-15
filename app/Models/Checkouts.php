<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkouts extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'check_out_id', // Unique batch ID
        'checked_out_by',
        'received_by',
        'destination',
        'quantity',
    ];

    // Relationship to Inventory model
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }
}