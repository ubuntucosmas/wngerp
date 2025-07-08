<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionParticular extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_item_id',    // FK to ProductionItem
        'particular',            //  Add this
        'unit',
        'quantity',
        'comment',
    ];

    public function item()
    {
        return $this->belongsTo(ProductionItem::class, 'production_item_id');
    }
}
