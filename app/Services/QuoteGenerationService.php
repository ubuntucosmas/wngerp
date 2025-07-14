<?php

namespace App\Services;

use App\Models\MaterialList;
use App\Models\ProjectBudget;
use App\Models\Quote;
use Illuminate\Support\Facades\DB;

class QuoteGenerationService
{
    public function generateFromMaterialList(MaterialList $materialList): Quote
    {
        return DB::transaction(function () use ($materialList) {
            // 1. Create ProjectBudget from MaterialList
            $budget = $this->createBudgetFromMaterialList($materialList);

            // 2. Create Quote from ProjectBudget
            $quote = $this->createQuoteFromBudget($budget);

            return $quote;
        });
    }

    private function createBudgetFromMaterialList(MaterialList $materialList): ProjectBudget
    {
        $totalCost = 0;

        // Create the budget
        $budget = ProjectBudget::create([
            'project_id' => $materialList->project_id,
            'enquiry_id' => $materialList->enquiry_id,
            'material_list_id' => $materialList->id,
            'start_date' => $materialList->start_date,
            'end_date' => $materialList->end_date,
            'budget_total' => 0, // Will be updated later
            'invoice' => 0,
            'profit' => 0,
            'approved_by' => auth()->user()->name,
            'approved_departments' => auth()->user()->department,
            'status' => 'draft',
        ]);

        // Create budget items from material list items
        foreach ($materialList->productionItems as $item) {
            foreach ($item->particulars as $particular) {
                $cost = $particular->quantity * $particular->unit_price;
                $totalCost += $cost;
                $budget->items()->create([
                    'category' => 'Materials - Production',
                    'item_name' => $item->item_name,
                    'particular' => $particular->particular,
                    'unit' => $particular->unit,
                    'quantity' => $particular->quantity,
                    'unit_price' => $particular->unit_price,
                    'budgeted_cost' => $cost,
                ]);
            }
        }

        foreach ($materialList->materialsHire as $item) {
            $cost = $item->quantity * $item->unit_price;
            $totalCost += $cost;
            $budget->items()->create([
                'category' => 'Materials for Hire',
                'item_name' => $item->item_name,
                'particular' => $item->particular,
                'unit' => $item->unit,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'budgeted_cost' => $cost,
            ]);
        }

        foreach ($materialList->labourItems as $item) {
            $cost = $item->quantity * $item->unit_price;
            $totalCost += $cost;
            $budget->items()->create([
                'category' => $item->category,
                'item_name' => $item->item_name,
                'particular' => $item->particular,
                'unit' => $item->unit,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'budgeted_cost' => $cost,
            ]);
        }

        // Update budget total
        $budget->update([
            'budget_total' => $totalCost,
            'invoice' => $totalCost * 1.16, // Assuming 16% VAT
            'profit' => ($totalCost * 1.16) - $totalCost,
        ]);

        return $budget;
    }

    private function createQuoteFromBudget(ProjectBudget $budget): Quote
    {
        $quote = Quote::create([
            'project_id' => $budget->project_id,
            'enquiry_id' => $budget->enquiry_id,
            'project_budget_id' => $budget->id,
            'customer_name' => $budget->project->client_name ?? $budget->enquiry->client_name,
            'quote_date' => now(),
        ]);

        foreach ($budget->items as $item) {
            $quote->lineItems()->create([
                'description' => $item->particular,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total' => $item->budgeted_cost,
            ]);
        }

        return $quote;
    }
}