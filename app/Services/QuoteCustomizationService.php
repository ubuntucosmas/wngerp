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
        $productionItems = $budgetItems->where('category', 'Materials - Production');
        
        return [
            'raw_categories' => $this->groupByOriginalCategories($budgetItems),
            'suggested_quote_items' => $this->generateSuggestedQuoteItems($budgetItems),
            'cost_summary' => $this->calculateCostSummary($budgetItems),
            'customization_options' => $this->getCustomizationOptions(),
            'production_alternatives' => $productionItems->isNotEmpty() ? 
                $this->generateProductionItemAlternatives($productionItems) : [],
            'production_summary' => [
                'total_production_items' => $productionItems->groupBy('item_name')->count(),
                'total_particulars' => $productionItems->count(),
                'total_production_cost' => $productionItems->sum('budgeted_cost')
            ]
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
        
        // Special handling for production items - group by item_name first
        $productionItems = $items->where('category', 'Materials - Production');
        $otherItems = $items->where('category', '!=', 'Materials - Production');
        
        // Handle production items - group by item_name (booth, stage, etc.)
        if ($productionItems->isNotEmpty()) {
            $productionGroups = $productionItems->groupBy('item_name');
            
            foreach ($productionGroups as $itemName => $itemParticulars) {
                $suggestions[] = [
                    'suggested_description' => $this->generateProductionItemDescription($itemName, $itemParticulars),
                    'total_internal_cost' => $itemParticulars->sum('budgeted_cost'),
                    'suggested_quote_price' => $this->calculateSuggestedPrice($itemParticulars),
                    'profit_margin_percentage' => $this->calculateProfitMargin($itemParticulars),
                    'source_items' => $itemParticulars->pluck('id')->toArray(),
                    'customizable' => true,
                    'category_type' => 'Event Production & Setup',
                    'item_name' => $itemName,
                    'particulars_count' => $itemParticulars->count()
                ];
            }
        }
        
        // Handle other categories normally
        $grouped = $otherItems->groupBy(function($item) {
            return $this->determineQuoteCategory($item->category, $item->particular);
        });
        
        foreach ($grouped as $quoteCategory => $categoryItems) {
            $suggestions[] = [
                'suggested_description' => $this->generateStandardDescription($quoteCategory, $categoryItems),
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
     * Generate description for a specific production item (booth, stage, etc.)
     */
    private function generateProductionItemDescription(string $itemName, Collection $particulars): string
    {
        $particularCount = $particulars->count();
        
        if ($particularCount === 1) {
            $particular = $particulars->first();
            return "{$itemName} - {$particular->particular}" . 
                   ($particular->comment ? " ({$particular->comment})" : "");
        }
        
        // Multiple particulars - create comprehensive description
        $mainParticulars = $particulars->take(3)->pluck('particular')->toArray();
        $remainingCount = $particularCount - 3;
        
        $description = $itemName . " including " . implode(', ', $mainParticulars);
        
        if ($remainingCount > 0) {
            $description .= " and {$remainingCount} additional components";
        }
        
        // Add total quantity information if relevant
        $totalQuantity = $particulars->sum('quantity');
        if ($totalQuantity > $particularCount) {
            $description .= " (Total: {$totalQuantity} units)";
        }
        
        return $description;
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
        // For production items, group by item_name to create better descriptions
        if ($category === 'Event Production & Setup') {
            return $this->generateProductionDescription($items);
        }
        
        // For other categories, use the existing logic but improved
        return $this->generateStandardDescription($category, $items);
    }
    
    /**
     * Generate descriptions specifically for production items
     */
    private function generateProductionDescription(Collection $items): string
    {
        // Group items by item_name (e.g., Booth, Stage, etc.)
        $groupedByItemName = $items->groupBy('item_name');
        
        $descriptions = [];
        
        foreach ($groupedByItemName as $itemName => $itemParticulars) {
            $particularCount = $itemParticulars->count();
            
            if ($particularCount === 1) {
                // Single particular - use the particular name
                $descriptions[] = $itemParticulars->first()->particular;
            } else {
                // Multiple particulars - create a comprehensive description
                $mainParticulars = $itemParticulars->take(2)->pluck('particular')->toArray();
                $remainingCount = $particularCount - 2;
                
                if ($remainingCount > 0) {
                    $descriptions[] = $itemName . " (" . implode(', ', $mainParticulars) . " and {$remainingCount} other components)";
                } else {
                    $descriptions[] = $itemName . " (" . implode(' and ', $mainParticulars) . ")";
                }
            }
        }
        
        // Create final description
        $totalItems = count($descriptions);
        
        if ($totalItems === 1) {
            return "Event production and setup for " . $descriptions[0];
        } elseif ($totalItems === 2) {
            return "Event production and setup including " . implode(' and ', $descriptions);
        } else {
            $mainItems = array_slice($descriptions, 0, 2);
            $remainingCount = $totalItems - 2;
            return "Event production and setup including " . implode(', ', $mainItems) . " and {$remainingCount} additional production elements";
        }
    }
    
    /**
     * Generate descriptions for non-production categories
     */
    private function generateStandardDescription(string $category, Collection $items): string
    {
        // Group by item_name first, then by particular if no item_name
        $groupedItems = $items->groupBy(function($item) {
            return $item->item_name ?: $item->particular;
        });
        
        $itemNames = $groupedItems->keys()->take(3)->toArray();
        $totalGroups = $groupedItems->count();
        $additionalCount = $totalGroups - 3;
        
        $mainItemsText = implode(', ', $itemNames);
        $additionalText = $additionalCount > 0 ? " and {$additionalCount} additional items" : "";
        $additionalServicesText = $additionalCount > 0 ? " and {$additionalCount} additional services" : "";
        
        $descriptions = [
            'Equipment & Materials' => "Equipment rental and materials including {$mainItemsText}{$additionalText}",
            'Professional Services' => "Professional services including {$mainItemsText}{$additionalServicesText}",
            'On-Site Services' => "On-site services and support including {$mainItemsText}{$additionalServicesText}",
            'Event Breakdown & Cleanup' => "Event breakdown and cleanup services including {$mainItemsText}{$additionalServicesText}",
            'Transportation & Logistics' => "Transportation and logistics services including {$mainItemsText}{$additionalServicesText}",
            'Specialized Services' => "Specialized services including {$mainItemsText}{$additionalServicesText}"
        ];
        
        return $descriptions[$category] ?? "Services including {$mainItemsText}{$additionalText}";
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
                'production_items' => 'Group production items by main item (booth, stage, etc.)',
                'summary' => 'High-level service packages only'
            ],
            'pricing_strategies' => [
                'cost_plus' => 'Cost plus fixed margin',
                'value_based' => 'Value-based pricing',
                'competitive' => 'Market competitive pricing',
                'package_deal' => 'Package deal pricing'
            ],
            'description_styles' => [
                'technical' => 'Technical specifications with particulars',
                'business' => 'Business-friendly descriptions',
                'creative' => 'Creative and engaging descriptions',
                'detailed_production' => 'Detailed production breakdown by item'
            ],
            'production_item_handling' => [
                'consolidated' => 'Combine all particulars under main item name',
                'itemized' => 'Show each particular as separate line item',
                'grouped_with_details' => 'Group by item with particular details in description'
            ]
        ];
    }
    
    /**
     * Generate alternative quote structures for production items
     */
    public function generateProductionItemAlternatives(Collection $productionItems): array
    {
        $alternatives = [];
        
        // Group by item_name
        $itemGroups = $productionItems->groupBy('item_name');
        
        foreach ($itemGroups as $itemName => $particulars) {
            $alternatives[] = [
                'item_name' => $itemName,
                'total_cost' => $particulars->sum('budgeted_cost'),
                'particular_count' => $particulars->count(),
                'options' => [
                    'consolidated' => [
                        'description' => $this->generateProductionItemDescription($itemName, $particulars),
                        'line_items' => 1,
                        'detail_level' => 'summary'
                    ],
                    'itemized' => [
                        'description' => "Individual {$itemName} components",
                        'line_items' => $particulars->count(),
                        'detail_level' => 'detailed',
                        'items' => $particulars->map(function($particular) {
                            return [
                                'description' => $particular->particular,
                                'quantity' => $particular->quantity,
                                'unit' => $particular->unit,
                                'cost' => $particular->budgeted_cost,
                                'comment' => $particular->comment
                            ];
                        })->toArray()
                    ],
                    'grouped_with_details' => [
                        'description' => $itemName,
                        'line_items' => 1,
                        'detail_level' => 'moderate',
                        'sub_items' => $particulars->pluck('particular')->toArray()
                    ]
                ]
            ];
        }
        
        return $alternatives;
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