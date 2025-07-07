<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'created_by',
    ];

    /**
     * Get the user who created this category.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the templates for this category.
     */
    public function templates(): HasMany
    {
        return $this->hasMany(ItemTemplate::class, 'category_id');
    }

    /**
     * Get the active templates for this category.
     */
    public function activeTemplates(): HasMany
    {
        return $this->hasMany(ItemTemplate::class, 'category_id')->where('is_active', true);
    }

    /**
     * Scope a query to only include active categories with templates.
     */
    public function scopeWithActiveTemplates($query)
    {
        return $query->whereHas('templates', function ($q) {
            $q->where('is_active', true);
        });
    }
}
