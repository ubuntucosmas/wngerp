<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\Project;
use App\Models\ProductionItem;
use App\Models\MaterialListItem;
use App\Models\LabourItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\QuoteExport;

class QuoteController extends Controller
{
    public function index(Project $project)
    {
        $quotes = Quote::where('project_id', $project->id)
            ->with('lineItems')
            ->latest('quote_date')
            ->paginate(10)
            ->appends(['project' => $project->id]);

        return view('projects.quotes.index', compact('quotes', 'project'));
    }

    public function create(Project $project)
    {
        // Fetch all production items and their particulars for this project
        $productionItems = ProductionItem::whereHas('materialList', function($q) use ($project) {
            $q->where('project_id', $project->id);
        })->with('particulars')->get();
        // Fetch all materials for hire for this project
        $materialsForHire = MaterialListItem::whereHas('materialList', function($q) use ($project) {
            $q->where('project_id', $project->id);
        })->where('category', 'Materials for Hire')->get();
        // Fetch all labour items for this project
        $labourItems = LabourItem::whereHas('materialList', function($q) use ($project) {
            $q->where('project_id', $project->id);
        })->get();
        return view('projects.quotes.create', compact('project', 'productionItems', 'materialsForHire', 'labourItems'));
    }

    public function store(Request $request, Project $project)
    {
        DB::beginTransaction();
        
        try {
            $data = $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_location' => 'nullable|string|max:255',
                'attention' => 'nullable|string|max:255',
                'quote_date' => 'nullable|date',
                'project_start_date' => 'nullable|date',
                'reference' => 'nullable|string|max:255',
                'items' => 'nullable|array',
                'items.*.description' => 'nullable|string',
                'items.*.days' => 'nullable|integer|min:1',
                'items.*.quantity' => 'nullable|numeric|min:0.01',
                'items.*.unit_price' => 'nullable|numeric|min:0',
                'items.*.profit_margin' => 'nullable|numeric|min:0|max:100',
                'items.*.total_cost' => 'nullable|numeric|min:0',
                'items.*.quote_price' => 'nullable|numeric|min:0',
                'items.*.particulars' => 'nullable|array',
                'items.*.particulars.*.particular' => 'nullable|string',
                'items.*.particulars.*.unit' => 'nullable|string',
                'items.*.particulars.*.quantity' => 'nullable|numeric|min:0.01',
                'items.*.particulars.*.unit_price' => 'nullable|numeric|min:0',
                'items.*.particulars.*.total_cost' => 'nullable|numeric|min:0',
                'items.*.particulars.*.profit_margin' => 'nullable|numeric|min:0|max:100',
                'items.*.particulars.*.quote_price' => 'nullable|numeric|min:0',
                'items.*.particulars.*.comment' => 'nullable|string',
                // Materials for Hire
                'hire_items' => 'nullable|array',
                'hire_items.*.description' => 'nullable|string',
                'hire_items.*.days' => 'nullable|integer|min:1',
                'hire_items.*.quantity' => 'nullable|numeric|min:0.01',
                'hire_items.*.unit_price' => 'nullable|numeric|min:0',
                'hire_items.*.profit_margin' => 'nullable|numeric|min:0|max:100',
                'hire_items.*.total_cost' => 'nullable|numeric|min:0',
                'hire_items.*.quote_price' => 'nullable|numeric|min:0',
                'hire_items.*.comment' => 'nullable|string',
            ]);

            $quote = $project->quotes()->create([
                'customer_name' => $data['customer_name'],
                'customer_location' => $data['customer_location'] ?? null,
                'attention' => $data['attention'] ?? null,
                'quote_date' => $data['quote_date'] ?? now(),
                'project_start_date' => $data['project_start_date'] ?? null,
                'reference' => $data['reference'] ?? null,
            ]);

            // Handle regular items (flat structure)
            if (!empty($data['items'])) {
            foreach ($data['items'] as $item) {
                    // Skip if this is a production item with particulars
                    if (isset($item['particulars'])) {
                        continue;
                    }
                    
                    // Only process items with description (regular items)
                    if (!empty($item['description'])) {
                        $profitMargin = $item['profit_margin'] ?? 0;
                        $totalCost = $item['quantity'] * $item['unit_price'];
                        $quotePrice = $totalCost * (1 + ($profitMargin / 100));
                        
                $quote->lineItems()->create([
                    'description' => $item['description'],
                    'days' => $item['days'] ?? 1,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                            'profit_margin' => $profitMargin,
                            'total_cost' => $totalCost,
                            'quote_price' => $quotePrice,
                            'total' => $quotePrice,
                        ]);
                    }
                }
            }

            // Handle production items (nested structure)
            if (!empty($data['items'])) {
                foreach ($data['items'] as $item) {
                    if (isset($item['particulars']) && is_array($item['particulars'])) {
                        foreach ($item['particulars'] as $particular) {
                            $profitMargin = $particular['profit_margin'] ?? 0;
                            $totalCost = $particular['quantity'] * $particular['unit_price'];
                            $quotePrice = $totalCost * (1 + ($profitMargin / 100));
                            $itemName = $item['item_name'] ?? 'Production Item';
                            $comment = $particular['comment'] ?? '';
                            $fullComment = 'Item Name: ' . $itemName;
                            if (!empty($comment)) {
                                $fullComment .= ' | ' . $comment;
                            }
                            $quote->lineItems()->create([
                                'description' => $particular['particular'],
                                'days' => 1,
                                'quantity' => $particular['quantity'],
                                'unit_price' => $particular['unit_price'],
                                'profit_margin' => $profitMargin,
                                'total_cost' => $totalCost,
                                'quote_price' => $quotePrice,
                                'total' => $quotePrice,
                                'comment' => $fullComment,
                            ]);
                        }
                    }
                }
            }

            // Add Materials for Hire as line items
            if (!empty($data['hire_items'])) {
                foreach ($data['hire_items'] as $hire) {
                    $profitMargin = $hire['profit_margin'] ?? 0;
                    $totalCost = ($hire['quantity'] ?? 1) * ($hire['unit_price'] ?? 0);
                    $quotePrice = $totalCost * (1 + ($profitMargin / 100));
                    $quote->lineItems()->create([
                        'description' => $hire['description'] ?? '',
                        'days' => $hire['days'] ?? 1,
                        'quantity' => $hire['quantity'] ?? 1,
                        'unit_price' => $hire['unit_price'] ?? 0,
                        'profit_margin' => $profitMargin,
                        'total_cost' => $totalCost,
                        'quote_price' => $quotePrice,
                        'total' => $quotePrice,
                        'comment' => $hire['comment'] ?? '',
                    ]);
                }
            }

            DB::commit();
            
            return redirect()
                ->route('quotes.show', ['project' => $project->id, 'quote' => $quote->id])
                ->with('success', 'Quote created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating quote: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Failed to create quote. Please try again.');
        }
    }

    public function show(Project $project, Quote $quote)
    {
        $subtotal = $quote->lineItems->sum('quote_price');
    
        $vatRate = 0.16; // 16% VAT - you can make this configurable
        $vatAmount = $subtotal * $vatRate;
        $total = $subtotal + $vatAmount;
    
        return view('projects.quotes.show', compact('project', 'quote', 'subtotal', 'vatRate', 'vatAmount', 'total'));
    }

    public function edit(Project $project, Quote $quote)
    {
        $quote->load('lineItems');
        return view('projects.quotes.edit', compact('quote', 'project'));
    }

    public function update(Request $request, Project $project, Quote $quote)
{
    DB::beginTransaction();

    try {
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_location' => 'nullable|string|max:255',
            'attention' => 'nullable|string|max:255',
            'quote_date' => 'required|date',
            'project_start_date' => 'nullable|date',
            'reference' => 'nullable|string|max:255',
                'items' => 'nullable|array',
                'items.*.description' => 'nullable|string',
            'items.*.days' => 'nullable|integer|min:1',
                'items.*.quantity' => 'nullable|numeric|min:0.01',
                'items.*.unit_price' => 'nullable|numeric|min:0',
                'items.*.profit_margin' => 'nullable|numeric|min:0|max:100',
                'items.*.total_cost' => 'nullable|numeric|min:0',
                'items.*.quote_price' => 'nullable|numeric|min:0',
                'items.*.particulars' => 'nullable|array',
                'items.*.particulars.*.particular' => 'nullable|string',
                'items.*.particulars.*.unit' => 'nullable|string',
                'items.*.particulars.*.quantity' => 'nullable|numeric|min:0.01',
                'items.*.particulars.*.unit_price' => 'nullable|numeric|min:0',
                'items.*.particulars.*.total_cost' => 'nullable|numeric|min:0',
                'items.*.particulars.*.profit_margin' => 'nullable|numeric|min:0|max:100',
                'items.*.particulars.*.quote_price' => 'nullable|numeric|min:0',
                'items.*.particulars.*.comment' => 'nullable|string',
                // Materials for Hire
                'hire_items' => 'nullable|array',
                'hire_items.*.description' => 'nullable|string',
                'hire_items.*.days' => 'nullable|integer|min:1',
                'hire_items.*.quantity' => 'nullable|numeric|min:0.01',
                'hire_items.*.unit_price' => 'nullable|numeric|min:0',
                'hire_items.*.profit_margin' => 'nullable|numeric|min:0|max:100',
                'hire_items.*.total_cost' => 'nullable|numeric|min:0',
                'hire_items.*.quote_price' => 'nullable|numeric|min:0',
                'hire_items.*.comment' => 'nullable|string',
            ]);

        $quote->update([
            'customer_name' => $data['customer_name'],
            'customer_location' => $data['customer_location'] ?? null,
            'attention' => $data['attention'] ?? null,
            'quote_date' => $data['quote_date'],
            'project_start_date' => $data['project_start_date'] ?? null,
            'reference' => $data['reference'] ?? null,
        ]);

        $quote->lineItems()->delete();

            // Handle regular items (flat structure)
            if (!empty($data['items'])) {
        foreach ($data['items'] as $item) {
                    // Skip if this is a production item with particulars
                    if (isset($item['particulars'])) {
                        continue;
                    }
                    
                    // Only process items with description (regular items)
                    if (!empty($item['description'])) {
                        $profitMargin = $item['profit_margin'] ?? 0;
                        $totalCost = $item['quantity'] * $item['unit_price'];
                        $quotePrice = $totalCost * (1 + ($profitMargin / 100));
                        
            $quote->lineItems()->create([
                'description' => $item['description'],
                'days' => $item['days'] ?? 1,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                            'profit_margin' => $profitMargin,
                            'total_cost' => $totalCost,
                            'quote_price' => $quotePrice,
                            'total' => $quotePrice,
                        ]);
                    }
                }
            }

            // Handle production items (nested structure)
            if (!empty($data['items'])) {
                foreach ($data['items'] as $item) {
                    if (isset($item['particulars']) && is_array($item['particulars'])) {
                        foreach ($item['particulars'] as $particular) {
                            $profitMargin = $particular['profit_margin'] ?? 0;
                            $totalCost = $particular['quantity'] * $particular['unit_price'];
                            $quotePrice = $totalCost * (1 + ($profitMargin / 100));
                            $itemName = $item['item_name'] ?? 'Production Item';
                            $comment = $particular['comment'] ?? '';
                            $fullComment = 'Item Name: ' . $itemName;
                            if (!empty($comment)) {
                                $fullComment .= ' | ' . $comment;
                            }
                            $quote->lineItems()->create([
                                'description' => $particular['particular'],
                                'days' => 1,
                                'quantity' => $particular['quantity'],
                                'unit_price' => $particular['unit_price'],
                                'profit_margin' => $profitMargin,
                                'total_cost' => $totalCost,
                                'quote_price' => $quotePrice,
                                'total' => $quotePrice,
                                'comment' => $fullComment,
                            ]);
                        }
                    }
                }
            }

            // Add Materials for Hire as line items
            if (!empty($data['hire_items'])) {
                foreach ($data['hire_items'] as $hire) {
                    $profitMargin = $hire['profit_margin'] ?? 0;
                    $totalCost = ($hire['quantity'] ?? 1) * ($hire['unit_price'] ?? 0);
                    $quotePrice = $totalCost * (1 + ($profitMargin / 100));
                    $quote->lineItems()->create([
                        'description' => $hire['description'] ?? '',
                        'days' => $hire['days'] ?? 1,
                        'quantity' => $hire['quantity'] ?? 1,
                        'unit_price' => $hire['unit_price'] ?? 0,
                        'profit_margin' => $profitMargin,
                        'total_cost' => $totalCost,
                        'quote_price' => $quotePrice,
                        'total' => $quotePrice,
                        'comment' => $hire['comment'] ?? '',
                    ]);
                }
        }

        DB::commit();

        return redirect()
            ->route('quotes.show', ['project' => $project->id, 'quote' => $quote->id])
            ->with('success', 'Quote updated successfully!');

    } catch (\Exception $e) {
        DB::rollBack();

        Log::error('Quote update failed', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        return back()
            ->withInput()
            ->with('error', 'Failed to update quote: ' . $e->getMessage());
    }
}

    public function destroy(Project $project, Quote $quote)
    {
        try {
            // Ensure the quote belongs to the project
            if ($quote->project_id !== $project->id) {
                abort(404);
            }
            
            $quote->delete();
            
            return redirect()
                ->route('quotes.index', $project->id)
                ->with('success', 'Quote deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting quote: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete quote. Please try again.');
        }
    }

    public function downloadQuote(Project $project)
    {
        $quote = $project->quotes()->latest()->first();

        if (!$quote) {
            abort(404, 'No quote found for this project.');
        }
        $pdf = Pdf::loadView('projects.templates.quote', compact('project', 'quote'));
        return $pdf->download('quote-' . $project->id . '.pdf');
    }
    
    public function printQuote(Project $project)
    {
        $quote = $project->quotes()->latest()->first();

        if (!$quote) {
            abort(404, 'No quote found for this project.');
        }

        $pdf = Pdf::loadView('projects.templates.quote', compact('project', 'quote'));
        return $pdf->stream('quote-' . $project->id . '.pdf');
    }

    /**
     * Export the quote to Excel.
     */
    public function exportExcel(Project $project, Quote $quote)
    {
        // Verify the quote belongs to the project
        if ($quote->project_id !== $project->id) {
            abort(404);
        }

        $fileName = 'quote-' . $project->project_id . '-' . $quote->id . '.xlsx';
        return Excel::download(new QuoteExport($quote), $fileName);
    }
}
