<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

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
        return view('projects.quotes.create', compact('project'));
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
                'items' => 'required|array|min:1',
                'items.*.description' => 'required|string',
                'items.*.days' => 'nullable|integer|min:1',
                'items.*.quantity' => 'required|numeric|min:0.01',
                'items.*.unit_price' => 'required|numeric|min:0',
            ]);

            $quote = $project->quotes()->create([
                'customer_name' => $data['customer_name'],
                'customer_location' => $data['customer_location'] ?? null,
                'attention' => $data['attention'] ?? null,
                'quote_date' => $data['quote_date'] ?? now(),
                'project_start_date' => $data['project_start_date'] ?? null,
                'reference' => $data['reference'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                $quote->lineItems()->create([
                    'description' => $item['description'],
                    'days' => $item['days'] ?? 1,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total' => $item['quantity'] * $item['unit_price'],
                ]);
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
        $subtotal = $quote->lineItems->sum(function($item) {
            return $item->quantity * $item->unit_price;
        });
    
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
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.days' => 'nullable|integer|min:1',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // if ($quote->project_id !== $project->id) {
        //     abort(404, 'Quote does not belong to the given project.');
        // }

        $quote->update([
            'customer_name' => $data['customer_name'],
            'customer_location' => $data['customer_location'] ?? null,
            'attention' => $data['attention'] ?? null,
            'quote_date' => $data['quote_date'],
            'project_start_date' => $data['project_start_date'] ?? null,
            'reference' => $data['reference'] ?? null,
        ]);

        $quote->lineItems()->delete();

        foreach ($data['items'] as $item) {
            $quote->lineItems()->create([
                'description' => $item['description'],
                'days' => $item['days'] ?? 1,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        DB::commit();

        return redirect()
            ->route('quotes.show', ['project' => $project->id, 'quote' => $quote->id])
            ->with('success', 'Quote updated successfully!');

    } catch (\Exception $e) {
        DB::rollBack();

        // Optional: dump full error for debugging (disable in production)
        // dd($e);

        // More detailed logging
        Log::error('Quote update failed', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        // Show full error message back to the user (optional: only in debug mode)
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
}
