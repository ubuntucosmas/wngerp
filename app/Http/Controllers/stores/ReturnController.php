<?php

namespace App\Http\Controllers\stores;
use App\Http\Controllers\Controller;

use App\Models\Inventory;
use App\Models\ReturnItem; // Assuming the model for the returns table is called ReturnItem
use Illuminate\Http\Request;

use Illuminate\Support\Str;

class ReturnController extends Controller
{

    public function index()
{
    
    $returns = ReturnItem::orderBy('return_date', 'desc')->paginate(10);;
    return view('inventory.returns', compact('returns'));
}
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'sku' => 'required|string|exists:inventory,sku',
            'item_name' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string',
            'return_date' => 'required|date',
        ]);

        // Save the return record
        ReturnItem::create([
            'sku' => $request->sku,
            'item_name' => Str::title($request->item_name),// Capitalize each word
            'quantity' => $request->quantity,
            'reason' => $request->reason ? ucfirst(strtolower($request->reason)) : null, // Capitalize first letter
            'return_date' => $request->return_date,
        ]);

        // Update inventory
        $inventory = Inventory::where('sku', $request->sku)->firstOrFail();

        if (!$inventory || $inventory->quantity_checked_out == 0) {
            return back()->withErrors(['sku' => 'This item was never checked out.']);
        }
        
        //$totalReturned = ReturnItem::where('sku', $request->sku)->sum('quantity');
        $remainingReturnable = $inventory->quantity_checked_out;// - $totalReturned;
        
        if ($request->quantity > $remainingReturnable) {
            return back()->withErrors(['error' => 'Return quantity exceeds the checked-out amount.']);
        }

        $inventory->stock_on_hand += $request->quantity; // Add returns to stock
        $inventory->total_value = $inventory->stock_on_hand * $inventory->unit_price; // Recalculate total value
        $inventory->returns += $request->quantity; // Update returns column
        $inventory->quantity_checked_out -= $request->quantity; // updates the quantity checked out on inventory table
        $inventory->save();

        return redirect()->back()->with('success', 'Return processed successfully.');
    }
}
