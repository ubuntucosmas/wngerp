<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class MaterialList extends Model
{
    protected $fillable = [
        'project_id',
        'enquiry_id',
        'start_date',
        'end_date',
        'approved_by',
        'approved_departments',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['date_range', 'item_counts'];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::deleting(function ($materialList) {
            // Delete all related records when a material list is deleted
            $materialList->productionItems->each->delete();
            $materialList->materialsHire->each->delete();
            $materialList->labourItems->each->delete();
        });
    }

    /**
     * Get the project that owns the material list.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class)->withDefault();
    }

    /**
     * Get the enquiry that owns the material list.
     */
    public function enquiry(): BelongsTo
    {
        return $this->belongsTo(Enquiry::class)->withDefault();
    }


    /**
     * Get the production items for the material list.
     */
    public function productionItems(): HasMany
    {
        return $this->hasMany(ProductionItem::class)->with('particulars');
    }

    /**
     * Get the materials for hire.
     */
    public function materialsHire()
    {
        return $this->hasMany(MaterialListItem::class, 'material_list_id')
            ->where('category', 'Materials for Hire')
            ->orderBy('item_name');
    }

    /**
     * Get the labour items for the material list.
     */
    public function labourItems(): HasMany
    {
        return $this->hasMany(LabourItem::class)->orderBy('category')->orderBy('item_name');
    }

    public function projectBudget()
    {
        return $this->hasOne(ProjectBudget::class);
    }

    /**
     * Get the formatted date range attribute.
     */
    public function getDateRangeAttribute(): string
    {
        if ($this->start_date && $this->end_date) {
            return $this->start_date->format('M d, Y') . ' - ' . $this->end_date->format('M d, Y');
        }
        
        return 'N/A';
    }

    /**
     * Get the item counts for the material list.
     */
    public function getItemCountsAttribute(): array
    {
        return [
            'production_items' => $this->productionItems->count(),
            'materials_hire' => $this->materialsHire->count(),
            'labour_items' => $this->labourItems->count(),
            'outsourced_items' => $this->labourItems->where('category', 'Outsourced')->count(),
        ];
    }

    /**
     * Scope a query to only include material lists within a date range.
     */
    public function scopeDateRange(Builder $query, $startDate, $endDate): Builder
    {
        return $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('start_date', '<=', $startDate)
                              ->where('end_date', '>=', $endDate);
                    });
    }

    /**
     * Scope a query to only include material lists for a specific project.
     */
    public function scopeForProject(Builder $query, $projectId): Builder
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Scope a query to only include material lists for a specific enquiry.
     */
    public function scopeForEnquiry(Builder $query, $enquiryId): Builder
    {
        return $query->where('enquiry_id', $enquiryId);
    }

    // Alias for materialsHire to maintain backward compatibility
    public function items()
    {
        return $this->materialsHire();
    }

}
