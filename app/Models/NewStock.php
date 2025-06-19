<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewStock extends Model
{
    use HasFactory;

    // Specify the associated table name if it's different from Laravel's naming convention
    protected $table = 'newstock';

    // Define which attributes can be mass-assigned
    protected $fillable = [
        'sku',
        'item_name',
        'quantity',
        'supplier',
        'added_on',
    ];
}
