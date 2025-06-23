<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectBudget;
use App\Models\BudgetItem;
use Illuminate\Support\Facades\DB;

class ProjectBudgetController extends Controller
{
    public function index(Project $project)
    {
        $budgets = ProjectBudget::where('project_id', $project->id)->with('items')->get();
        return view('projects.budget.index', compact('project', 'budgets'));
    }

    public function create(Project $project)
    {
        $categories = [
            'Workshop labour' => ['Technicians', 'Carpenter', 'CNC', 'Welders', 'Project Officer','Meals'],
            'Site' => ['Technicians', 'Pasters', 'Electricians','Off loaders','Project Officer','Meals'],
            'Set down' => ['Technicians', 'Off loaders', 'Electricians', 'Meals'],
            'Logistics' => ['Delivery to site', 'Delivery from site', 'Team transport to and from site set up', 'Team transport to and from set down','Materials Collection'],
        ];

        return view('projects.budget.create', compact('project', 'categories'));
    }

    public function show(Project $project, ProjectBudget $budget)
    {
        return view('projects.budget.show', compact('project', 'budget'));
    }

    public function store(Request $request, Project $project)
    {
        if (!auth()->user()->hasRole('po|pm|super-admin')) {
            alert()->error('Only Project Officers, Project Managers and Super Admins can submit budgets.');
        }
    
        DB::beginTransaction();
    
        try {
            $items = $request->input('items', []);
            $totalCost = 0;
    
            // Calculate total from all items
            foreach ($items as $category => $group) {
                foreach ($group as $item) {
                    $totalCost += $item['budgeted_cost'] ?? 0;
                }
            }
    
            // Calculate invoice and profit
            $invoice = round($totalCost * 1.16, 2); // Add 16% VAT
            $profit = $invoice - $totalCost;
    
            $budget = ProjectBudget::create([
                'project_id' => $project->id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'budget_total' => $totalCost,
                'invoice' => $invoice,
                'profit' => $profit,
                'approved_by' => $request->approved_by,
                'approved_departments' => $request->approved_departments,
                'status' => 'draft', // Default
            ]);
    
            // Save each item with proper category
            foreach ($items as $category => $group) {
                foreach ($group as $item) {
                    BudgetItem::create([
                        'project_budget_id' => $budget->id,
                        'category' => $category,
                        'particular' => $item['particular'] ?? '',
                        'unit' => $item['unit'] ?? '',
                        'quantity' => $item['quantity'] ?? 0,
                        'unit_price' => $item['unit_price'] ?? 0, // Optional if PO can input this
                        'budgeted_cost' => $item['budgeted_cost'] ?? 0,
                        'comment' => $item['comment'] ?? '',
                    ]);
                }
            }
    
            DB::commit();
            return redirect()->route('budget.index', $project)->with('success', 'Budget submitted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to save budget: ' . $e->getMessage())->withInput();
        }
    }
    

public function edit(Project $project, ProjectBudget $budget)
{
    if (!auth()->user()->hasAnyRole(['finance', 'accounts', 'super-admin'])) {
        abort(403, 'Only Finance or Accounts can edit budgets.');
    }

    $items = $budget->items()->get()->groupBy('category');

    return view('projects.budget.edit', compact('project', 'budget', 'items'));
}

public function update(Request $request, Project $project, ProjectBudget $budget)
{
    if (!auth()->user()->hasAnyRole(['finance', 'accounts', 'super-admin'])) {
        abort(403, 'Not authorized.');
    }

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
            'budget_total' => $total,
            'invoice' => $invoice,
            'profit' => $profit,
            'approved_by' => $request->approved_by,
            'approved_departments' => $request->approved_departments,
            'status' => $request->status ?? 'approved',
        ]);

        DB::commit();
        return redirect()->route('budget.show', [$project, $budget])->with('success', 'Budget updated successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Failed to update: ' . $e->getMessage());
    }
}

public function destroy(Project $project, ProjectBudget $budget)
{
    try {
        $budget->delete();
        return redirect()->route('budget.index', $project)->with('success', 'Budget deleted successfully.');
    } catch (\Exception $e) {
        return redirect()->route('budget.index', $project)->with('error', 'Failed to delete budget: ' . $e->getMessage());
    }
}



    
}
