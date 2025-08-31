<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\Project;
use App\Models\ProductionItem;
use App\Models\MaterialListItem;
use App\Models\LabourItem;
use App\Models\ProjectBudget;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\QuoteExport;
use App\Models\Enquiry;

class QuoteController extends Controller
{
    use AuthorizesRequests;
    
    public function index(Request $request, $projectOrEnquiryId)
    {
        $project = null;
        $enquiry = null;

        if (str_contains($request->route()->getName(), 'enquiries.')) {
            $enquiry = Enquiry::findOrFail($projectOrEnquiryId);
            // Check if user can view this enquiry
            $this->authorize('view', $enquiry);
        } else {
            $project = Project::findOrFail($projectOrEnquiryId);
            // Check if user can view this project
            $this->authorize('view', $project);
        }

        $quotes = collect(); // Initialize as an empty collection
        $parentModel = null;

        if ($enquiry) {
            $quotes = Quote::where('enquiry_id', $enquiry->id)
                ->with('lineItems')
                ->latest('quote_date')
                ->paginate(10)
                ->appends(['enquiry' => $enquiry->id]);
            $parentModel = $enquiry;
        } elseif ($project) {
            $quotes = Quote::where('project_id', $project->id)
                ->with('lineItems')
                ->latest('quote_date')
                ->paginate(10)
                ->appends(['project' => $project->id]);
            $parentModel = $project;
        } else {
            // This case should ideally not be reached if routes are correctly defined
            // and always provide either a project or an enquiry.
            abort(404, 'Quotes cannot be displayed without a project or an enquiry.');
        }

        return view('projects.quotes.index', compact('quotes', 'parentModel', 'project', 'enquiry'));
    }

    public function create(Request $request, $projectOrEnquiryId)
    {
        $project = null;
        $enquiry = null;

        if (str_contains($request->route()->getName(), 'enquiries.')) {
            $enquiry = Enquiry::findOrFail($projectOrEnquiryId);
            // Check if user can view this enquiry
            $this->authorize('view', $enquiry);
        } else {
            $project = Project::findOrFail($projectOrEnquiryId);
            // Check if user can view this project
            $this->authorize('view', $project);
        }

        // Check if user can create quotes using the Quote policy
        $this->authorize('create', Quote::class);

        $budget = null;
        if ($request->has('project_budget_id')) {
            $budget = ProjectBudget::with('items')->findOrFail($request->input('project_budget_id'));
        } else if ($enquiry) {
            $budget = $enquiry->budgets()->latest()->with('items')->first();
        } else if ($project) {
            $budget = $project->budgets()->latest()->with('items')->first();
        }
        
        if (!$budget) {
            return back()->with('error', 'No budget found for this ' . ($enquiry ? 'enquiry' : 'project') . '. Please create a budget first.');
        }
        
        // Use the hybrid customization service
        $customizationService = new \App\Services\QuoteCustomizationService();
        $quoteData = $customizationService->prepareBudgetForQuote($budget);
        
        // Prepare data for the hybrid quote creation view
        $hybridData = [
            'budget' => $budget,
            'raw_categories' => $quoteData['raw_categories'],
            'suggested_items' => $quoteData['suggested_quote_items'],
            'cost_summary' => $quoteData['cost_summary'],
            'customization_options' => $quoteData['customization_options'],
            'total_internal_cost' => $budget->budget_total,
            'suggested_total_price' => collect($quoteData['suggested_quote_items'])->sum('suggested_quote_price')
        ];
        
        if ($enquiry) {
            return view('projects.quotes.create-hybrid', compact('enquiry', 'hybridData'));
        } else {
            return view('projects.quotes.create-hybrid', compact('project', 'hybridData'));
        }
    }

    public function store(Request $request, $projectOrEnquiryId)
    {
        $project = null;
        $enquiry = null;

        if (str_contains($request->route()->getName(), 'enquiries.')) {
            $enquiry = Enquiry::findOrFail($projectOrEnquiryId);
            // Check if user can view this enquiry
            $this->authorize('view', $enquiry);
        } else {
            $project = Project::findOrFail($projectOrEnquiryId);
            // Check if user can view this project
            $this->authorize('view', $project);
        }

        // Check if user can create quotes using the Quote policy
        $this->authorize('create', Quote::class);

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

            $quoteData = [
                'customer_name' => $data['customer_name'],
                'customer_location' => $data['customer_location'] ?? null,
                'attention' => $data['attention'] ?? null,
                'quote_date' => $data['quote_date'] ?? now(),
                'project_start_date' => $data['project_start_date'] ?? null,
                'reference' => $data['reference'] ?? null,
            ];

            if ($enquiry) {
                $quoteData['enquiry_id'] = $enquiry->id;
                // For enquiries, set project_id to null if the enquiry hasn't been converted to a project yet
                $quoteData['project_id'] = $enquiry->project_id ?? null;
                $quote = $enquiry->quotes()->create($quoteData);
            } else {
                $quoteData['project_id'] = $project->id;
                $quote = $project->quotes()->create($quoteData);
            }

            if ($request->has('project_budget_id')) {
                $quote->update(['project_budget_id' => $request->input('project_budget_id')]);
            }

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
            
            if ($enquiry) {
                return redirect()->route('enquiries.quotes.show', ['enquiry' => $enquiry->id, 'quote' => $quote->id])->with('success', 'Quote created successfully!');
            } else {
                return redirect()->route('quotes.show', ['project' => $project->id, 'quote' => $quote->id])->with('success', 'Quote created successfully!');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating quote: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Failed to create quote. Please try again.');
        }
    }

    public function show(Request $request, $projectOrEnquiryId, Quote $quote)
    {
        $project = null;
        $enquiry = null;

        if (str_contains($request->route()->getName(), 'enquiries.')) {
            $enquiry = Enquiry::findOrFail($projectOrEnquiryId);
        } else {
            $project = Project::findOrFail($projectOrEnquiryId);
        }

        $subtotal = $quote->lineItems->sum('quote_price');
    
        $vatRate = 0.16; // 16% VAT - you can make this configurable
        $vatAmount = $subtotal * $vatRate;
        $total = $subtotal + $vatAmount;

        // Calculate quote position for sequential naming
        if ($enquiry) {
            $allQuotes = Quote::where('enquiry_id', $enquiry->id)
                ->orderBy('quote_date', 'desc')
                ->orderBy('created_at', 'desc')
                ->pluck('id')
                ->toArray();
        } else {
            $allQuotes = Quote::where('project_id', $project->id)
                ->orderBy('quote_date', 'desc')
                ->orderBy('created_at', 'desc')
                ->pluck('id')
                ->toArray();
        }
        
        $quotePosition = array_search($quote->id, $allQuotes) + 1;
        $ordinalSuffix = match($quotePosition % 10) {
            1 => $quotePosition % 100 === 11 ? 'th' : 'st',
            2 => $quotePosition % 100 === 12 ? 'th' : 'nd', 
            3 => $quotePosition % 100 === 13 ? 'th' : 'rd',
            default => 'th'
        };
        $quoteName = $quotePosition . $ordinalSuffix . ' Quote';
    
        if ($enquiry) {
            return view('projects.quotes.show', compact('enquiry', 'quote', 'subtotal', 'vatRate', 'vatAmount', 'total', 'quoteName'));
        } else {
            return view('projects.quotes.show', compact('project', 'quote', 'subtotal', 'vatRate', 'vatAmount', 'total', 'quoteName'));
        }
    }

    public function edit(Request $request, $projectOrEnquiryId, Quote $quote)
    {
        $project = null;
        $enquiry = null;

        if (str_contains($request->route()->getName(), 'enquiries.')) {
            $enquiry = Enquiry::findOrFail($projectOrEnquiryId);
            // Check if user can edit this enquiry
            $this->authorize('update', $enquiry);
        } else {
            $project = Project::findOrFail($projectOrEnquiryId);
            // Check if user can edit this project (not just view)
            $this->authorize('edit', $project);
        }

        $quote->load('lineItems');
        if ($enquiry) {
            return view('projects.quotes.edit', compact('quote', 'enquiry'));
        } else {
            return view('projects.quotes.edit', compact('quote', 'project'));
        }
    }

    public function update(Request $request, $projectOrEnquiryId, Quote $quote)
    {
        $project = null;
        $enquiry = null;

        if (str_contains($request->route()->getName(), 'enquiries.')) {
            $enquiry = Enquiry::findOrFail($projectOrEnquiryId);
            // Check if user can edit this enquiry
            $this->authorize('update', $enquiry);
        } else {
            $project = Project::findOrFail($projectOrEnquiryId);
            // Check if user can edit this project (not just view)
            $this->authorize('edit', $project);
        }

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

        if ($enquiry) {
            return redirect()->route('enquiries.quotes.show', ['enquiry' => $enquiry->id, 'quote' => $quote->id])->with('success', 'Quote updated successfully!');
        } else {
            return redirect()->route('quotes.show', ['project' => $project->id, 'quote' => $quote->id])->with('success', 'Quote updated successfully!');
        }

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

    public function destroy(Request $request, $projectOrEnquiryId, Quote $quote)
    {
        $project = null;
        $enquiry = null;

        if (str_contains($request->route()->getName(), 'enquiries.')) {
            $enquiry = Enquiry::findOrFail($projectOrEnquiryId);
            // Check if user can edit this enquiry
            $this->authorize('update', $enquiry);
        } else {
            $project = Project::findOrFail($projectOrEnquiryId);
            // Check if user can edit this project (not just view)
            $this->authorize('edit', $project);
        }

        try {
            // Ensure the quote belongs to the project or enquiry
            // if ($project && $quote->project_id !== $project->id) {
            //     abort(404);
            // }
            // if ($enquiry && $quote->enquiry_id !== $enquiry->id) {
            //     abort(404);
            // }
            
            $quote->delete();
            
            if ($enquiry) {
                return redirect()->route('enquiries.quotes.index', $enquiry->id)->with('success', 'Quote deleted successfully!');
            } else {
                return redirect()->route('quotes.index', $project->id)->with('success', 'Quote deleted successfully!');
            }
        } catch (\Exception $e) {
            Log::error('Error deleting quote: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete quote. Please try again.');
        }
    }

    public function downloadQuote(Request $request, $projectOrEnquiryId, Quote $quote)
    {
        $project = null;
        $enquiry = null;

        if (str_contains($request->route()->getName(), 'enquiries.')) {
            $enquiry = Enquiry::findOrFail($projectOrEnquiryId);
        } else {
            $project = Project::findOrFail($projectOrEnquiryId);
        }

        // Verify the quote belongs to the project or enquiry
        // if ($project && $quote->project_id !== $project->id) {
        //     abort(404);
        // }
        // if ($enquiry && $quote->enquiry_id !== $enquiry->id) {
        //     abort(404);
        // }

        if ($enquiry) {
            $pdf = Pdf::loadView('projects.templates.quote', compact('enquiry', 'quote'));
            return $pdf->download('quote-enquiry-' . $enquiry->id . '-' . $quote->id . '.pdf');
        } else {
            $pdf = Pdf::loadView('projects.templates.quote', compact('project', 'quote'));
            return $pdf->download('quote-project-' . $project->id . '-' . $quote->id . '.pdf');
        }
    }
    
    public function printQuote(Request $request, $projectOrEnquiryId, Quote $quote)
    {
        $project = null;
        $enquiry = null;

        if (str_contains($request->route()->getName(), 'enquiries.')) {
            $enquiry = Enquiry::findOrFail($projectOrEnquiryId);
        } else {
            $project = Project::findOrFail($projectOrEnquiryId);
        }

        // Verify the quote belongs to the project or enquiry
        // if ($project && $quote->project_id !== $project->id) {
        //     abort(404);
        // }
        // if ($enquiry && $quote->enquiry_id !== $enquiry->id) {
        //     abort(404);
        // }

        if ($enquiry) {
            $pdf = Pdf::loadView('projects.templates.quote', compact('enquiry', 'quote'));
            return $pdf->stream('quote-enquiry-' . $enquiry->id . '-' . $quote->id . '.pdf');
        } else {
            $pdf = Pdf::loadView('projects.templates.quote', compact('project', 'quote'));
            return $pdf->stream('quote-project-' . $project->id . '-' . $quote->id . '.pdf');
        }
    }

    /**
     * Export the quote to Excel.
     */
    public function exportExcel(Request $request, $projectOrEnquiryId, Quote $quote)
    {
        $project = null;
        $enquiry = null;

        if (str_contains($request->route()->getName(), 'enquiries.')) {
            $enquiry = Enquiry::findOrFail($projectOrEnquiryId);
        } else {
            $project = Project::findOrFail($projectOrEnquiryId);
        }

        // Verify the quote belongs to the project or enquiry
        // if ($project && $quote->project_id !== $project->id) {
        //     abort(404);
        // }
        // if ($enquiry && $quote->enquiry_id !== $enquiry->id) {
        //     abort(404);
        // }

        $fileName = 'quote-' . ($enquiry ? 'enquiry-' . $enquiry->id : 'project-' . $project->id) . '-' . $quote->id . '.xlsx';
        return Excel::download(new QuoteExport($quote), $fileName);
    }

    /**
     * Approve a quote and notify all users.
     */
    public function approve(Request $request, $projectOrEnquiryId, Quote $quote)
    {
        // Restrict approval to certain roles
        if (!auth()->user()->hasAnyRole(['super-admin', 'admin', 'finance', 'pm'])) {
            return back()->with('error', 'You do not have permission to approve quotes.');
        }
        
        // Check if quote is already approved
        if ($quote->status === Quote::STATUS_APPROVED) {
            return back()->with('warning', 'This quote has already been approved.');
        }
        
        try {
            $notificationCount = $quote->approveAndNotify();
            
            $message = "Quote #{$quote->id} has been approved successfully! ";
            $message .= "Notifications sent to {$notificationCount} users.";
            
            return back()->with('success', $message);
            
        } catch (\Exception $e) {
            \Log::error('Quote approval failed', [
                'quote_id' => $quote->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Failed to approve quote. Please try again.');
        }
    }
}
