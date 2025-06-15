<?php

namespace App\Http\Controllers\stores;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Forhire;
use App\Models\Inventory;

class ForHireController extends Controller
{
    public function index()
    {
        $hires = Forhire::all();
        // Fetch SKUs and item names as key-value pairs
        $skus = Inventory::pluck('sku', 'id');
        return view('inventory.forhire', compact('hires', 'skus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|exists:inventory,sku',
            'client' => 'required',
            'contacts' => 'required',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'hire_fee' => 'required|numeric',
        ]);
    
        // Fetch inventory by SKU
        $inventory = Inventory::where('sku', $validated['sku'])->first();
    
        if (!$inventory) {
            return redirect()->back()->with('error', 'Inventory item not found.');
        }
    
        // Check if enough stock is available
        if ($inventory->stock_on_hand < $validated['quantity']) {
            return redirect()->back()->with('error', 'Not enough stock available for hire.');
        }
    
        // Deduct quantity
        $inventory->stock_on_hand -= $validated['quantity'];
        $inventory->save();
    
        // Save hire record
        Forhire::create($validated);
    
        return redirect()->back()->with('success', 'Hire entry added and inventory updated successfully.');
    }
}
