<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectBudget;
use App\Models\BudgetItem;
use Illuminate\Support\Facades\DB;
use App\Exports\ProjectBudgetExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\BudgetEditLog;
use App\Models\Enquiry;

class ProjectBudgetController extends Controller
{
    public function index(Project $project = null, Enquiry $enquiry = null)
    {
        if ($enquiry) {
            $budgets = ProjectBudget::where('enquiry_id', $enquiry->id)->with('items')->get();
            return view('projects.budget.index', compact('enquiry', 'budgets'));
        } else {
            // Check if this project was converted from an enquiry
            $enquirySource = $project->enquirySource;
            
            if ($enquirySource) {
                // For converted projects, get budgets from enquiry source
                $budgets = ProjectBudget::where('enquiry_id', $enquirySource->id)->with('items')->get();
            } else {
                // For regular projects, get budgets from project
                $budgets = ProjectBudget::where('project_id', $project->id)->with('items')->get();
            }
            
            return view('projects.budget.index', compact('project', 'budgets'));
        }
    }

    public function create(Request $request, Project $project = null, Enquiry $enquiry = null)
    {
        $materialList = null;
        if ($request->has('material_list_id')) {
            $materialList = \App\Models\MaterialList::findOrFail($request->input('material_list_id'));
        } else if ($enquiry) {
            $materialList = $enquiry->materialLists()->latest()->first();
        } else if ($project) {
            $materialList = $project->materialLists()->latest()->first();
        }

        $materialItems = [];
        if ($materialList) {
            // Production Items - Only include particulars, not the parent items
            foreach ($materialList->productionItems as $item) {
                foreach ($item->particulars as $particular) {
                    $materialItems[] = [
                        'category' => 'Materials - Production',
                        'item_name' => $item->item_name,
                        'particular' => $particular->particular,
                        'unit' => $particular->unit,
                        'quantity' => $particular->quantity,
                        'comment' => $particular->comment ?? ''
                    ];
                }
            }
            // Materials for Hire
            foreach ($materialList->materialsHire as $hire) {
                $materialItems[] = [
                    'category' => 'Materials for Hire',
                    'particular' => $hire->particular,
                    'unit' => $hire->unit,
                    'quantity' => $hire->quantity,
                    'comment' => $hire->comment ?? ''
                ];
            }
            // Labour Items
            foreach ($materialList->labourItems as $labour) {
                $materialItems[] = [
                    'category' => $labour->category,
                    'particular' => $labour->particular,
                    'unit' => $labour->unit,
                    'quantity' => $labour->quantity,
                    'comment' => $labour->comment ?? ''
                ];
            }
        }
        // Group by category for the Blade
        $grouped = collect($materialItems)->groupBy('category');
        
        if ($enquiry) {
            return view('projects.budget.create', compact('enquiry', 'materialItems', 'grouped', 'materialList'));
        } else {
            return view('projects.budget.create', compact('project', 'materialItems', 'grouped', 'materialList'));
        }
    }

    public function show(Project $project = null, Enquiry $enquiry = null, ProjectBudget $budget)
    {
        $budget->load([
            'items.template.name',
            'items.template.particulars',
            'items.template.category'
        ]);
        
        // Check if this project was converted from an enquiry
        $enquirySource = $project ? $project->enquirySource : null;
        
        if ($enquiry) {
            return view('projects.budget.show', compact('enquiry', 'budget'));
        } else {
            // For converted projects, we need to pass the enquiry source for proper navigation
            if ($enquirySource) {
                return view('projects.budget.show', compact('project', 'budget', 'enquirySource'));
            } else {
                return view('projects.budget.show', compact('project', 'budget'));
            }
        }
    }

    public function store(Request $request, Project $project = null, Enquiry $enquiry = null)
    {
        if (!auth()->user()->hasRole('po|pm|super-admin')) {
            alert()->error('Only Project Officers, Project Managers and Super Admins can submit budgets.');
        }
    
        $productionItems = $request->input('production_items', []);
        foreach ($productionItems as $idx => $prod) {
            if (empty($prod['item_name'])) {
                return back()->with('error', 'Each production item must have an Item Name.')->withInput();
            }
        }
    
        DB::beginTransaction();
    
        try {
            $items = $request->input('items', []);
            $productionItems = $request->input('production_items', []);
            $totalCost = 0;
    
            // Calculate total from all items (other categories)
            foreach ($items as $category => $group) {
                foreach ($group as $item) {
                    $totalCost += $item['budgeted_cost'] ?? 0;
                }
            }
    
            // Add production items to totalCost
            foreach ($productionItems as $prod) {
                if (!isset($prod['particulars'])) continue;
                foreach ($prod['particulars'] as $particular) {
                    $totalCost += $particular['budgeted_cost'] ?? 0;
                }
            }
    
            // Calculate invoice and profit
            $invoice = round($totalCost * 1.16, 2); // Add 16% VAT
            $profit = $invoice - $totalCost;
    
            $budgetData = [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'budget_total' => (float) $totalCost,
                'invoice' => $invoice,
                'profit' => $profit,
                'approved_by' => $request->approved_by,
                'approved_departments' => $request->approved_departments,
                'status' => 'draft', // Default
            ];

            if ($enquiry) {
                $budgetData['enquiry_id'] = $enquiry->id;
            } else {
                $budgetData['project_id'] = $project->id;
            }

            if ($request->has('material_list_id')) {
                $budgetData['material_list_id'] = $request->input('material_list_id');
            }

            $budget = ProjectBudget::create($budgetData);
    
            // Save each item with proper category (other categories)
            foreach ($items as $category => $group) {
                foreach ($group as $item) {
                    BudgetItem::create([
                        'project_budget_id' => $budget->id,
                        'category' => $category,
                        'item_name' => $item['item_name'] ?? null,
                        'particular' => $item['particular'] ?? '',
                        'unit' => $item['unit'] ?? '',
                        'quantity' => $item['quantity'] ?? 0,
                        'unit_price' => $item['unit_price'] ?? 0,
                        'budgeted_cost' => $item['budgeted_cost'] ?? 0,
                        'comment' => $item['comment'] ?? '',
                    ]);
                }
            }
    
            // Save production items (grouped by item_name)
            foreach ($productionItems as $prod) {
                $itemName = $prod['item_name'] ?? null;
                $templateId = $prod['template_id'] ?? null;
                if (!isset($prod['particulars'])) continue;
                foreach ($prod['particulars'] as $particular) {
                    BudgetItem::create([
                        'project_budget_id' => $budget->id,
                        'category' => 'Materials - Production',
                        'item_name' => $itemName,
                        'template_id' => $templateId,
                        'particular' => $particular['particular'] ?? '',
                        'unit' => $particular['unit'] ?? '',
                        'quantity' => $particular['quantity'] ?? 0,
                        'unit_price' => $particular['unit_price'] ?? 0,
                        'budgeted_cost' => $particular['budgeted_cost'] ?? 0,
                        'comment' => $particular['comment'] ?? '',
                    ]);
                }
            }
    
            DB::commit();
            if ($enquiry) {
                return redirect()->route('enquiries.budget.index', $enquiry)->with('success', 'Budget submitted successfully.');
            } else {
                return redirect()->route('budget.index', $project)->with('success', 'Budget submitted successfully.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to save budget: ' . $e->getMessage())->withInput();
        }
    }
    

public function edit(Project $project = null, Enquiry $enquiry = null, ProjectBudget $budget)
{
    if (!auth()->user()->hasAnyRole(['finance', 'accounts', 'super-admin'])) {
        abort(403, 'Only Finance or Accounts can edit budgets.');
    }
    if ($budget->status === 'approved') {
        return redirect()->route('budget.show', [$project, $budget])
            ->with('error', 'Approved budgets cannot be edited.');
    }
    $items = $budget->items()->get()->groupBy('category');
    if ($enquiry) {
        return view('projects.budget.edit', compact('enquiry', 'budget', 'items'));
    } else {
        return view('projects.budget.edit', compact('project', 'budget', 'items'));
    }
}

public function update(Request $request, Project $project = null, Enquiry $enquiry = null, ProjectBudget $budget)
{
    if (!auth()->user()->hasAnyRole(['finance', 'accounts', 'super-admin'])) {
        abort(403, 'Not authorized.');
    }
    if ($budget->status === 'approved') {
        if ($enquiry) {
            return redirect()->route('enquiries.budget.show', [$enquiry, $budget])
                ->with('error', 'Approved budgets cannot be edited.');
        } else {
            return redirect()->route('budget.show', [$project, $budget])
                ->with('error', 'Approved budgets cannot be edited.');
        }
    }

    // Log the edit before making changes
    BudgetEditLog::create([
        'project_budget_id' => $budget->id,
        'user_id' => auth()->id(),
        'changes' => $request->all(),
    ]);

    DB::beginTransaction();

    try {
        $total = 0;
        $items = $request->input('items', []);

        foreach ($items as $category => $group) {
            foreach ($group as $index => $itemData) {
                $isExisting = is_numeric($index);

                $data = [
                    'particular'     => $itemData['particular'] ?? '',
                    'unit'           => $itemData['unit'] ?? '',
                    'quantity'       => $itemData['quantity'] ?? 0,
                    'unit_price'     => $itemData['unit_price'] ?? 0,
                    'budgeted_cost'  => $itemData['budgeted_cost'] ?? 0,
                    'comment'        => $itemData['comment'] ?? '',
                    'category'       => $category,
                ];

                if ($isExisting) {
                    $item = BudgetItem::findOrFail($index);
                    $item->update($data);
                } else {
                    BudgetItem::create(array_merge($data, [
                        'project_budget_id' => $budget->id,
                    ]));
                }

                $total += $data['budgeted_cost'];
            }
        }

        $invoice = round($total * 1.16, 2);
        $profit = $invoice - $total;

        $budget->update([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'budget_total' => (float) $total,
            'invoice' => $invoice,
            'profit' => $profit,
            'approved_by' => $request->approved_by,
            'approved_departments' => $request->approved_departments,
            'status' => $request->status ?? 'approved',
        ]);

        DB::commit();
        if ($enquiry) {
            return redirect()->route('enquiries.budget.show', [$enquiry, $budget])->with('success', 'Budget updated successfully.');
        } else {
            return redirect()->route('budget.show', [$project, $budget])->with('success', 'Budget updated successfully.');
        }
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Failed to update: ' . $e->getMessage());
    }
}

public function destroy(Project $project = null, Enquiry $enquiry = null, ProjectBudget $budget)
{
    try {
        $budget->delete();
        if ($enquiry) {
            return redirect()->route('enquiries.budget.index', $enquiry)->with('success', 'Budget deleted successfully.');
        } else {
            return redirect()->route('budget.index', $project)->with('success', 'Budget deleted successfully.');
        }
    } catch (\Exception $e) {
        if ($enquiry) {
            return redirect()->route('enquiries.budget.index', $enquiry)->with('error', 'Failed to delete budget: ' . $e->getMessage());
        } else {
            return redirect()->route('budget.index', $project)->with('error', 'Failed to delete budget: ' . $e->getMessage());
        }
    }
}

/**
 * Export the specified budget to Excel.
 */
public function export(Project $project = null, Enquiry $enquiry = null, ProjectBudget $budget)
{
    if ($enquiry) {
        return Excel::download(new ProjectBudgetExport($budget, $enquiry, null), 'enquiry_budget_' . $budget->id . '.xlsx');
    } else {
        return Excel::download(new ProjectBudgetExport($budget, null, $project), 'project_budget_' . $budget->id . '.xlsx');
    }
}

/**
 * Approve the specified budget.
 */
public function approve(Project $project = null, Enquiry $enquiry = null, ProjectBudget $budget)
{
    if (!auth()->user()->hasRole('super-admin')) {
        abort(403, 'Only Super Admins can approve budgets.');
    }
    $budget->status = 'approved';
    $budget->approved_by = auth()->user()->name;
    $budget->approved_at = now();
    $budget->save();
    return redirect()->back()->with('success', 'Budget approved successfully!');
}
    
    /**
     * Print the specified budget (render printable view).
     */
    public function print(Project $project = null, Enquiry $enquiry = null, ProjectBudget $budget)
    {
        $budget->load(['items']);
        if ($enquiry) {
            return view('projects.budget.print', compact('enquiry', 'budget'));
        } else {
            return view('projects.budget.print', compact('project', 'budget'));
        }
    }
}
