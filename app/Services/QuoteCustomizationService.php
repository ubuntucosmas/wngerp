<?php

namespace App\Services;

use App\Models\ProjectBudget;
use App\Models\Quote;
use Illuminate\Support\Collection;

class QuoteCustomizationService
{
    /**
     * Transform budget data into customizable quote structure
     */
    public function prepareBudgetForQuote(ProjectBudget $budget): array
    {
        $budgetItems = $budget->items;
        
        return [
            'raw_categories' => $this->groupByOriginalCategories($budgetItems),
            'suggested_quote_items' => $this->generateSuggestedQuoteItems($budgetItems),
            'cost_summary' => $this->calculateCostSummary($budgetItems),
            'customization_options' => $this->getCustomizationOptions()
        ];
    }
    
    /**
     * Group budget items by original categories for reference
     */
    private function groupByOriginalCategories(Collection $items): array
    {
        return $items->groupBy('category')->map(function($categoryItems, $category) {
            return [
                'category' => $category,
                'total_cost' => $categoryItems->sum('budgeted_cost'),
                'item_count' => $categoryItems->count(),
                'items' => $categoryItems->map(function($item) {
                    return [
                        'id' => $item->id,
                        'particular' => $item->particular,
                        'quantity' => $item->quantity,
                        'unit' => $item->unit,
                        'unit_price' => $item->unit_price,
                        'budgeted_cost' => $item->budgeted_cost,
                        'item_name' => $item->item_name,
                        'comment' => $item->comment
                    ];
                })
            ];
        })->toArray();
    }
    
    /**
     * Generate client-friendly quote items from budget data
     */
    private function generateSuggestedQuoteItems(Collection $items): array
    {
        $suggestions = [];
        
        // Group similar items for consolidation
        $grouped = $items->groupBy(function($item) {
            return $this->determineQuoteCategory($item->category, $item->particular);
        });
        
        foreach ($grouped as $quoteCategory => $categoryItems) {
            $suggestions[] = [
                'suggested_description' => $this->generateClientFriendlyDescription($quoteCategory, $categoryItems),
                'total_internal_cost' => $categoryItems->sum('budgeted_cost'),
                'suggested_quote_price' => $this->calculateSuggestedPrice($categoryItems),
                'profit_margin_percentage' => $this->calculateProfitMargin($categoryItems),
                'source_items' => $categoryItems->pluck('id')->toArray(),
                'customizable' => true,
                'category_type' => $quoteCategory
            ];
        }
        
        return $suggestions;
    }
    
    /**
     * Determine appropriate quote category for client presentation
     */
    private function determineQuoteCategory(string $budgetCategory, string $particular): string
    {
        $categoryMappings = [
            'Materials - Production' => 'Event Production & Setup',
            'Items for Hire' => 'Equipment & Materials',
            'Workshop Labour' => 'Professional Services',
            'Site' => 'On-Site Services',
            'Set Down' => 'Event Breakdown & Cleanup',
            'Logistics' => 'Transportation & Logistics',
            'Outsourced' => 'Specialized Services'
        ];
        
        return $categoryMappings[$budgetCategory] ?? 'Additional Services';
    }
    
    /**
     * Generate client-friendly descriptions
     */
    private function generateClientFriendlyDescription(string $category, Collection $items): string
    {
        $itemCount = $items->count();
        $mainItems = $items->take(3)->pluck('particular')->join(', ');
        $additionalCount = $itemCount - 3;
        $additionalText = $itemCount > 3 ? " and {$additionalCount} additional items" : "";
        $additionalServicesText = $itemCount > 3 ? " and {$additionalCount} additional services" : "";
        
        $descriptions = [
            'Event Production & Setup' => "Event production and setup services including {$mainItems}{$additionalText}",
            'Equipment & Materials' => "Equipment rental and materials including {$mainItems}{$additionalText}",
            'Professional Services' => "Professional services including {$mainItems}{$additionalServicesText}",
            'On-Site Services' => "On-site services and support including {$mainItems}{$additionalServicesText}",
            'Event Breakdown & Cleanup' => "Event breakdown and cleanup services including {$mainItems}{$additionalServicesText}",
            'Transportation & Logistics' => "Transportation and logistics services including {$mainItems}{$additionalServicesText}",
            'Specialized Services' => "Specialized services including {$mainItems}{$additionalServicesText}"
        ];
        
        return $descriptions[$category] ?? "Services including {$mainItems}{$additionalText}";
    }
    
    /**
     * Calculate suggested pricing with profit margins
     */
    private function calculateSuggestedPrice(Collection $items): float
    {
        $totalCost = $items->sum('budgeted_cost');
        $baseMargin = 0.25; // 25% base margin
        
        // Adjust margin based on category complexity
        $categoryMultipliers = [
            'Event Production & Setup' => 1.3,
            'Professional Services' => 1.4,
            'Specialized Services' => 1.5,
            'Equipment & Materials' => 1.2,
            'Transportation & Logistics' => 1.1
        ];
        
        $category = $this->determineQuoteCategory($items->first()->category, $items->first()->particular);
        $multiplier = $categoryMultipliers[$category] ?? 1.25;
        
        return round($totalCost * $multiplier, 2);
    }
    
    /**
     * Calculate profit margin percentage
     */
    private function calculateProfitMargin(Collection $items): float
    {
        $totalCost = $items->sum('budgeted_cost');
        $suggestedPrice = $this->calculateSuggestedPrice($items);
        
        if ($totalCost == 0) return 0;
        
        return round((($suggestedPrice - $totalCost) / $totalCost) * 100, 2);
    }
    
    /**
     * Get customization options for quote creation
     */
    private function getCustomizationOptions(): array
    {
        return [
            'consolidation_levels' => [
                'detailed' => 'Show individual items with descriptions',
                'grouped' => 'Group similar items by category',
                'summary' => 'High-level service packages only'
            ],
            'pricing_strategies' => [
                'cost_plus' => 'Cost plus fixed margin',
                'value_based' => 'Value-based pricing',
                'competitive' => 'Market competitive pricing',
                'package_deal' => 'Package deal pricing'
            ],
            'description_styles' => [
                'technical' => 'Technical specifications',
                'business' => 'Business-friendly descriptions',
                'creative' => 'Creative and engaging descriptions'
            ]
        ];
    }
    
    /**
     * Calculate cost summary for internal reference
     */
    private function calculateCostSummary(Collection $items): array
    {
        return [
            'total_internal_cost' => $items->sum('budgeted_cost'),
            'item_count' => $items->count(),
            'category_breakdown' => $items->groupBy('category')->map(function($categoryItems) {
                return [
                    'cost' => $categoryItems->sum('budgeted_cost'),
                    'count' => $categoryItems->count()
                ];
            })->toArray()
        ];
    }
}