<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemTemplateParticular extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_template_id',
        'particular',
        'unit',
        'default_quantity',
        'unit_price',
        'comment',
    ];

    protected $casts = [
        'default_quantity' => 'decimal:2',
    ];

    /**
     * Get the template that owns this particular.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(ItemTemplate::class, 'item_template_id');
    }
}
