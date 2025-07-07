<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemCategory;
use Illuminate\Support\Facades\Auth;

class ItemCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = ItemCategory::withCount('templates')
            ->with('creator')
            ->orderBy('name')
            ->paginate(15);

        return view('item-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('item-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:item_categories,name',
            'description' => 'nullable|string',
        ]);

        try {
            ItemCategory::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'created_by' => Auth::id(),
            ]);

            return redirect()
                ->route('templates.categories.index')
                ->with('success', 'Category created successfully!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create category. ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ItemCategory $itemCategory)
    {
        $itemCategory->load(['templates.particulars', 'creator']);
        return view('item-categories.show', compact('itemCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ItemCategory $itemCategory)
    {
        return view('item-categories.edit', compact('itemCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ItemCategory $itemCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:item_categories,name,' . $itemCategory->id,
            'description' => 'nullable|string',
        ]);

        try {
            $itemCategory->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
            ]);

            return redirect()
                ->route('templates.categories.index')
                ->with('success', 'Category updated successfully!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update category. ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemCategory $itemCategory)
    {
        try {
            // Check if category has templates
            if ($itemCategory->templates()->count() > 0) {
                return back()->withErrors(['error' => 'Cannot delete category that has templates. Please delete or move the templates first.']);
            }

            $itemCategory->delete();

            return redirect()
                ->route('templates.categories.index')
                ->with('success', 'Category deleted successfully!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete category. ' . $e->getMessage()]);
        }
    }

    /**
     * Get all categories (AJAX endpoint for dropdowns).
     */
    public function getAll()
    {
        $categories = ItemCategory::orderBy('name')->get();
        return response()->json($categories);
    }
}
