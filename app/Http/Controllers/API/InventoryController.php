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

    /**
     * Get inventory items for hire only
     */
    public function hireItems(Request $request)
    {
        $query = Inventory::query()
            ->select('inventory.item_name as name', 'inventory.unit_of_measure')
            ->join('categories', 'inventory.category_id', '=', 'categories.id')
            ->where('categories.category_name', 'Hire')
            ->distinct('inventory.item_name')
            ->orderBy('inventory.item_name');

        // Optional search parameter
        if ($search = $request->input('q')) {
            $query->where('inventory.item_name', 'like', "%{$search}%");
        }

        $items = $query->get()
            ->map(function ($item) {
                return [
                    'name' => $item->name,
                    'unit_of_measure' => $item->unit_of_measure
                ];
            });

        return response()->json($items);
    }

    /**
     * Get inventory items for particulars (consumable, hire, electricals)
     */
    public function particularsItems(Request $request)
    {
        $query = Inventory::query()
            ->select('inventory.item_name as name', 'inventory.unit_of_measure')
            ->join('categories', 'inventory.category_id', '=', 'categories.id')
            ->whereIn('categories.category_name', ['Consumables', 'Hire', 'Electricals'])
            ->distinct('inventory.item_name')
            ->orderBy('inventory.item_name');

        // Optional search parameter
        if ($search = $request->input('q')) {
            $query->where('inventory.item_name', 'like', "%{$search}%");
        }

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
