<?php

namespace App\Http\Controllers\stores;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Category;
use App\Models\NewStock;
use App\Models\Log;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Exports\InventoryExport;
use App\Imports\InventoryImport;
use Maatwebsite\Excel\Facades\Excel;

class InventoryController extends Controller
{

    public function export()
    {
        return Excel::download(new InventoryExport, 'inventory.xlsx');
    }

    //=====================================================================================================================//

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
    
        try {
            Excel::import(new InventoryImport, $request->file('file'));
    
            return redirect()->route('inventory.index')->with('success', 'Inventory data imported successfully!');
        } catch (\Exception $e) {
            return redirect()->route('inventory.index')->with('error', 'Failed to import inventory data: ' . $e->getMessage());
        }
    }

//===============================================================================================================================//
    public function dashboard()
    {
        //dd(auth()->user()->role); // for debbugging
        $userid =auth()->user()->id; // for debbugging

        // Calculate total items in inventory
        $totalItems = Inventory::count();

        // Calculate total stock on hand (SOH)
        $totalStock = Inventory::sum('stock_on_hand');

        // Calculate total inventory value
        $totalValue = Inventory::sum('total_value');
        
    // Get inventory data grouped by month
        $now = Carbon::now();

    // grouping by month and summing up data
         $monthlyData = Inventory::selectRaw('
            MONTH(order_date) as month,
            SUM(stock_on_hand) as total_stock,
            SUM(quantity_checked_in) as total_checked_in,
            SUM(quantity_checked_out) as total_checked_out
        ')
        ->whereYear('order_date', $now->year)
        ->groupByRaw('MONTH(order_date)')
        ->get()
        ->keyBy('month'); // Returns a collection keyed by month number (1-12)

            // Fetch three most recent logs
        $recentLogs = Log::latest()->take(3)->get();


        // Pass data to the dashboard view
        return view('inventory.dashboard', compact( 'monthlyData', 'recentLogs'));
    }


//==========================================================================================================================//

    public function index(Request $request)
    {
        // Fetch query parameters for filters
    $query = Inventory::query();

    $deletedItems = Inventory::onlyTrashed()->with('category')->paginate(10); // Soft-deleted items

    // Apply filters
    if ($request->filled('sku')) {
        $query->where('sku', 'like', '%' . $request->sku . '%');
    }

    if ($request->filled('item_name')) {
        $query->where('item_name', 'like', '%' . $request->item_name . '%');
    }

    if ($request->filled('category_name')) {
        $query->whereHas('category', function ($q) use ($request) {
            $q->where('category_name', 'like', '%' . $request->category_name . '%');
        });
    }
        // Paginate results
        $items = $query->with('category')->orderBy('created_at', 'desc')->paginate(10);
        
            // Fetch SKUs and item names as key-value pairs
        $skus = Inventory::pluck('item_name', 'sku');

        $skus_returns = Inventory::where('quantity_checked_out', '>', 0)
        ->pluck('item_name', 'sku'); // returns [sku => item_name]


        return view('inventory.index', compact('items', 'skus', 'skus_returns', 'deletedItems'));
    }

    public function create() {
        // You don't necessarily need this if using modals for creation.
        return view('inventory.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'description' => 'nullable',
        ]);

        Inventory::create($request->all());
        return redirect()->route('inventory.index')->with('success', 'Item added successfully!');
    }

    public function show($id) {
        $item = Inventory::findOrFail($id);
        return view('inventory.show', compact('item'));
    }

    public function edit($id) {
        $item = Inventory::findOrFail($id);
        return view('inventory.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'sku' => 'required|string',
            'item_name' => 'required|string',
            'unit_of_measure' => 'nullable|string',
            'supplier' => 'nullable|string',
            'unit_price' => 'nullable|numeric',
            'order_date' => 'nullable|date',
            'category_id' => 'nullable|integer|exists:categories,id', // Validate category ID
        ]);
    
        // Find the item by ID
        $item = Inventory::findOrFail($id);
    
        // Capture the original details for logging
        $originalDetails = "Original Data: SKU: {$item->sku}, Item Name: {$item->item_name}, Quantity: {$item->stock_on_hand}, Supplier: {$item->supplier}";
    
        // Update the item details
        $item->sku = $validated['sku'];
        $item->item_name = $validated['item_name'];
        $item->unit_of_measure = $validated['unit_of_measure'] ?? $item->unit_of_measure;
        $item->supplier = $validated['supplier'] ?? $item->supplier;
        $item->unit_price = $validated['unit_price'] ?? $item->unit_price;
        $item->order_date = $validated['order_date'] ?? $item->order_date;
        $item->category_id = $validated['category_id'] ?? $item->category_id;
    
        $item->save();
    
        // Log the action in the logs table
        Log::create([
            'action' => 'Update Inventory Item',
            'performed_by' => auth()->user()->name, // Capture the authenticated user's name
            'details' => "{$originalDetails}, Updated Data: SKU: {$item->sku}, Item Name: {$item->item_name}, Quantity: {$item->stock_on_hand}, Supplier: {$item->supplier}",
        ]);
    
        // Redirect back with success message
        return redirect()->back()->with('success', 'Inventory item updated successfully.');
    }


    // Soft deleting
    public function softDelete($id)
    {
        $stock = Inventory::findOrFail($id);
        $stock->delete(); // Soft delete
        return redirect()->route('inventory.index')->with('success', 'Item soft-deleted.');
    }

    public function trash()
    {
        $deletedItems = Inventory::onlyTrashed()->with('category')->paginate(10);
        return view('inventory.trash', compact('deletedItems'));
    }


    public function restore($id)
{
    $item = Inventory::onlyTrashed()->findOrFail($id);
    $item->restore();

    return redirect()->back()->with('success', 'Item restored successfully.');
}

    public function forceDelete($id)
    {
        // Find the inventory item by ID
        $item = Inventory::onlyTrashed()->findOrFail($id);
    
        // Capture item details before deletion for logging
        $itemDetails = "SKU: {$item->sku}, Item Name: {$item->item_name}, Quantity: {$item->stock_on_hand}";
    
        // Delete the item
        $item->forceDelete();
    
        // Log the deletion action
        Log::create([
            'action' => 'Delete Inventory Item',
            'performed_by' => auth()->user()->name, // Capture the authenticated user's name
            'details' => "Deleted inventory item: {$itemDetails}",
        ]);
    
        // Redirect with success message
        return redirect()->route('inventory.index')->with('success', 'Item deleted successfully!');
    }



// ==============================================================================================================================//
    public function showCheckIn(Request $request)
    {
        $stocks = Inventory::with('category')->orderBy('updated_at', 'desc')->paginate(10); 

        $inventoryItems = Inventory::all();
            // Fetch SKUs and item names as key-value pairs
        $skus = Inventory::select('id', 'sku', 'item_name')->get();


        return view('inventory.checkin', compact('stocks', 'skus'));
    }

//===================================================================================================================================//
    //  Handle the Check-In Submission
    public function checkIn(Request $request)
    {
        //dd($request->id);
        $request->validate([
            'id' => 'required|exists:inventory,id',
            'quantity_in' => 'required|integer|min:1',
            'check_in_date' => 'required|date',
        ]);
        

        // Get the inventory item
        
        $stock = Inventory::findOrFail($request->id);
        $quantityIn = $request->input('quantity_in');
        $checkInDate = Carbon::parse($request->input('check_in_date'));

        // Update stock values
        $stock->stock_on_hand += $quantityIn;
        $stock->total_value = $stock->stock_on_hand * $stock->unit_price; // Recalculate total value
        $stock->quantity_checked_in += $quantityIn;
        $stock->updated_at = $checkInDate;
        $stock->save();


            // Log the action
        Log::create([
            'action' => 'Stock Check-In',
            'performed_by' => auth()->user()->name, // or ID
            'details' => "Checked in {$quantityIn} items for SKU: {$stock->sku}",
        ]);


        return redirect()->route('inventory.checkin')->with('success', 'Stock successfully checked in.');
    }

    
    //===========================================================================================================================//
    public function defectives() {
        return view('inventory.defectives'); 
    }

    //============================================================================================================================//
    public function returns()
    {
       
        //$returns = \App\Models\Return::all(); 

        return view('inventory.returns');
    }

    //=================================================================================================================================
    public function newstock() {

        // Fetch all categories from the database
        $categories = Category::all();

        
        // Fetch inventory data, order by creation date in descending order
        $stocks = Inventory::with('category')->orderBy('created_at', 'desc')->paginate(10);

        return view('inventory.newstock', compact('stocks', 'categories')); 
    }
   
    public function storeNewStock(Request $request)
{
    // Validate the request data
    $request->validate([
        'item_name' => 'required|string|max:255',
        'category_id' => 'required|integer|exists:categories,id', // Validate category ID
        'unit_of_measure' => 'required|string|max:50',
        'quantity' => 'required|integer|min:1',
        'supplier' => 'required|string|max:255',
        'unit_price' => 'required|numeric|min:0',
    ]);

    // Generate SKU dynamically
    $latestId = Inventory::max('id') + 1;
    $category = Category::find($request->category_id); // Fetch the category by ID
    $sku = strtoupper(substr($category->category_name, 0, 3)) . '-' . str_pad($latestId, 3, '0', STR_PAD_LEFT);

    // Create a new inventory record
    Inventory::create([
        'sku' => $sku,
        'item_name' => Str::title($request->item_name), // Capitalize each word
        'category_id' => $request->category_id, // Store category ID as foreign key
        'unit_of_measure' => $request->unit_of_measure,
        'stock_on_hand' => $request->quantity,
        'quantity_checked_in' => $request->quantity,
        'quantity_checked_out' => 0,
        'returns' => 0,
        'supplier' => Str::title($request->supplier), // Capitalize each word
        'unit_price' => $request->unit_price,
        'total_value' => $request->quantity * $request->unit_price,
        'order_date' => now(),
    ]);

    // Log the entry in the NewStock table
    NewStock::create([
        'sku' => $sku,
        'item_name' => $request->item_name,
        'quantity' => $request->quantity,
        'supplier' => $request->supplier,
        'added_on' => now(),
    ]);

    // Log the action in the logs table
    Log::create([
        'action' => 'Add New Stock',
        'performed_by' => auth()->user()->name, // Capture the authenticated user's name
        'details' => "Added new stock: {$request->item_name}, SKU: {$sku}, Quantity: {$request->quantity}, Category: {$category->category_name}",
    ]);

    return redirect()->route('inventory.newstock')->with('success', 'New stock added successfully.');
}


}