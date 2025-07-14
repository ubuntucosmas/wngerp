<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'created_by',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function quoteLineItems(): HasMany
    {
        return $this->hasMany(QuoteLineItem::class, 'template_id');
    }

    /**
     * Get the category that owns this template.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ItemCategory::class, 'category_id');
    }

    /**
     * Get the user who created this template.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the particulars for this template.
     */
    public function particulars(): HasMany
    {
        return $this->hasMany(ItemTemplateParticular::class, 'item_template_id');
    }

    /**
     * Scope a query to only include active templates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the total estimated cost including all particulars.
     */
    public function getTotalEstimatedCostAttribute()
    {
        return $this->particulars->sum(function ($particular) {
            return $particular->unit_price * $particular->default_quantity;
        });
    }

    /**
     * Get the template with its particulars loaded.
     */
    public function withParticulars()
    {
        return $this->load('particulars');
    }
}
