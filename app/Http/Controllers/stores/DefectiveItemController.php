<?php

namespace App\Http\Controllers\stores;
use App\Http\Controllers\Controller;

use App\Models\DefectiveItem;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DefectiveItemController extends Controller
{

    public function index()
    {
        $defectiveItems = DefectiveItem::orderBy('date_reported', 'desc')->get();
        return view('inventory.defectives', compact('defectiveItems'));
    }
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'sku' => 'required|string|exists:inventory,sku',
            'quantity' => 'required|integer|min:1',
            'defect_type' => 'required|string|max:255',
            'reported_by' => 'required|string|max:255',
            'date_reported' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        // Retrieve inventory item
        $inventory = Inventory::where('sku', $request->sku)->firstOrFail();
        $defectiveQty = $request->quantity;

            // 🔐 Check to prevent negative stock
        if ($defectiveQty > $inventory->stock_on_hand) {
            return back()->withErrors(['error' => 'Cannot remove more than available stock.']);
        }

        // Subtract defective quantity from stock_on_hand
        $inventory->stock_on_hand -= $request->quantity;
        $inventory->total_value = $inventory->stock_on_hand * $inventory->unit_price; // Recalculate total value
        $inventory->save();

        // Create defective item entry
        DefectiveItem::create([
            'sku' => $request->sku,
            'item_name' => $inventory->item_name,
            'quantity' => $request->quantity,
            'defect_type' => $request->defect_type,
            'reported_by' => Str::title($request->reported_by), // Capitalize each word
            'date_reported' => $request->date_reported,
            'remarks' => $request->remarks ? ucfirst(strtolower($request->remarks)) : null, // Capitalize first letter
            'status' => 'Pending', // Default status
        ]);

        return redirect()->back()->with('success', 'Defective item logged successfully.');
    }
}