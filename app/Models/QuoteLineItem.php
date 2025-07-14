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
        'profit_margin',
        'quote_price',
        'total_cost',
        'comment',
    ];

    protected $casts = [
        'days' => 'integer',
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total' => 'decimal:2',
        'profit_margin' => 'decimal:2',
        'quote_price' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(\App\Models\ItemTemplate::class, 'template_id');
    }

    protected static function booted()
    {
        static::saving(function ($lineItem) {
            $lineItem->total_cost = $lineItem->quantity * $lineItem->unit_price;
            $lineItem->quote_price = $lineItem->total_cost * (1 + ($lineItem->profit_margin / 100));
            $lineItem->total = $lineItem->quote_price; // Update total to be the quote price
        });
    }
}
