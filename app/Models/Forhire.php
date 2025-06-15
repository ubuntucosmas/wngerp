<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Forhire extends Model
{
    protected $fillable = [
        'sku', 'client', 'contacts', 'quantity', 'start_date', 'end_date', 'hire_fee', 'status'
    ];
}
