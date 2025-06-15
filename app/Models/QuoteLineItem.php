<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteLineItem extends Model
{
    protected $fillable = [
        'quote_id',
        'description',
        'days',
        'quantity',
        'unit_price',
        'total',
    ];

    protected $casts = [
        'days' => 'integer',
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    protected static function booted()
    {
        static::saving(function ($lineItem) {
            $lineItem->total = $lineItem->quantity * $lineItem->unit_price;
        });
    }
}
