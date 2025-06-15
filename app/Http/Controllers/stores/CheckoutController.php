<?php

namespace App\Http\Controllers\stores;
use App\Http\Controllers\Controller;
use App\Exports\CheckoutExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Checkouts; // Ensure Checkouts model is imported
use App\Models\Inventory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Log;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function exportCheckout()
    {
        return Excel::download(new CheckoutExport, 'checkout_records.xlsx');
    }


    public function index() {
        // Retrieve all checkout records and related inventory items
        $checkouts = Checkouts::with('inventory')->paginate(10);

        // Retrieve all inventory items for the modal dropdown
        $inventoryItems = Inventory::all();

        return view('inventory.checkout', compact('checkouts', 'inventoryItems'));

    }
    public function store(Request $request)
{
    // Validate the incoming data
    $validated = $request->validate([
        'items' => 'required|array', // Array of items
        'items.*.id' => 'required|exists:inventory,id',
        'items.*.quantity' => 'required|integer|min:1',
        'received_by' => 'required|string',
        'check_out_date' => 'required|date',
        'destination' => 'required|string',       
    ]);

    // Generate a unique check_out_id for this batch
    $checkOutId = 'CO-' . strtoupper(Str::random(6));

    // Get the current logged-in user
    $checkedOutBy = auth()->user()->name;

    // Initialize log details
    $logDetails = "Checkout Batch ID: {$checkOutId}, Checked Out By: {$checkedOutBy}, Destination: {$validated['destination']}, Received By: {$validated['received_by']}.";

    // Process each item in the checkout batch
    foreach ($validated['items'] as $item) {
        $inventory = Inventory::findOrFail($item['id']);

        // Check if enough stock is available
        if ($inventory->stock_on_hand < $item['quantity']) {
            return back()->withErrors(['error' => "Not enough stock for item {$inventory->item_name}."]);
        }

        // Update stock_on_hand and checked_out
        $inventory->stock_on_hand -= $item['quantity'];
        $inventory->total_value = $inventory->stock_on_hand * $inventory->unit_price;
        $inventory->quantity_checked_out += $item['quantity'];
        $inventory->save();

        // Append details to log
        $logDetails .= " Item: {$inventory->item_name}, SKU: {$inventory->sku}, Quantity: {$item['quantity']}.";

        // Record the checkout using the Checkouts model
        Checkouts::create([
            'inventory_id' => $item['id'],
            'check_out_id' => $checkOutId, // Same ID for all items in the batch
            'checked_out_by' => $checkedOutBy,
            'received_by' => Str::title($validated['received_by']), // Capitalize each word
            'destination' => $validated['destination'],
            'quantity' => $item['quantity'],
            'check_out_date' => Carbon::parse($validated['check_out_date']),
        ]);
    }

    // Log the checkout action
    Log::create([
        'action' => 'Inventory Checkout',
        'performed_by' => $checkedOutBy, // Capture the authenticated user's name
        'details' => $logDetails, // Include batch and item details
    ]);

    return redirect()->route('inventory.checkout')->with('success', 'Items checked out successfully.');
}
}