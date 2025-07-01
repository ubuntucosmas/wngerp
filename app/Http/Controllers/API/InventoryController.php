<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Get all inventory items for dropdown
     */
    public function index(Request $request)
    {
        $query = Inventory::query()
            ->select('item_name as name', 'unit_of_measure')
            ->distinct('item_name')
            ->orderBy('item_name');

        // Optional search parameter
        if ($search = $request->input('q')) {
            $query->where('item_name', 'like', "%{$search}%");
        }

        // Get results with pagination
        $items = $query->get()
            ->map(function ($item) {
                return [
                    'name' => $item->name,
                    'unit_of_measure' => $item->unit_of_measure
                ];
            });

        return response()->json($items);
    }
}
