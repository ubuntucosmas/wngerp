<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemTemplate;
use App\Models\ItemCategory;
use App\Models\ItemTemplateParticular;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ItemTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = ItemTemplate::with(['category', 'particulars'])
            ->orderBy('category_id')
            ->orderBy('name')
            ->paginate(15);

        $categories = ItemCategory::withActiveTemplates()
            ->orderBy('name')
            ->get();

        return view('item-templates.index', compact('templates', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ItemCategory::orderBy('name')->get();
        return view('item-templates.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:item_categories,id',
            'name' => 'required|string|max:255|unique:item_templates,name,NULL,id,category_id,' . $request->category_id,
            'description' => 'nullable|string',
            'estimated_cost' => 'nullable|numeric|min:0',
            'particulars' => 'required|array|min:1',
            'particulars.*.particular' => 'required|string|max:255',
            'particulars.*.unit' => 'nullable|string|max:50',
            'particulars.*.default_quantity' => 'required|numeric|min:0',
            'particulars.*.comment' => 'nullable|string',
        ], [
            'name.unique' => 'A template with this name already exists in the selected category.',
        ]);

        DB::beginTransaction();

        try {
            // Create the template
            $template = ItemTemplate::create([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'description' => $validated['description'],
                'estimated_cost' => $validated['estimated_cost'],
                'created_by' => Auth::id(),
                'is_active' => true,
            ]);

            // Create the particulars
            foreach ($validated['particulars'] as $particular) {
                $template->particulars()->create([
                    'particular' => $particular['particular'],
                    'unit' => $particular['unit'],
                    'default_quantity' => $particular['default_quantity'],
                    'comment' => $particular['comment'],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('templates.templates.index')
                ->with('success', 'Template created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create template. ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ItemTemplate $itemTemplate)
    {
        $itemTemplate->load(['category', 'particulars', 'creator']);
        return view('item-templates.show', compact('itemTemplate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ItemTemplate $itemTemplate)
    {
        $categories = ItemCategory::orderBy('name')->get();
        $itemTemplate->load('particulars');
        return view('item-templates.edit', compact('itemTemplate', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ItemTemplate $itemTemplate)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:item_categories,id',
            'name' => 'required|string|max:255|unique:item_templates,name,' . $itemTemplate->id . ',id,category_id,' . $request->category_id,
            'description' => 'nullable|string',
            'estimated_cost' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'particulars' => 'required|array|min:1',
            'particulars.*.particular' => 'required|string|max:255',
            'particulars.*.unit' => 'nullable|string|max:50',
            'particulars.*.default_quantity' => 'required|numeric|min:0',
            'particulars.*.comment' => 'nullable|string',
        ], [
            'name.unique' => 'A template with this name already exists in the selected category.',
        ]);

        DB::beginTransaction();

        try {
            // Update the template
            $itemTemplate->update([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'description' => $validated['description'],
                'estimated_cost' => $validated['estimated_cost'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            // Delete existing particulars
            $itemTemplate->particulars()->delete();

            // Create new particulars
            foreach ($validated['particulars'] as $particular) {
                $itemTemplate->particulars()->create([
                    'particular' => $particular['particular'],
                    'unit' => $particular['unit'],
                    'default_quantity' => $particular['default_quantity'],
                    'comment' => $particular['comment'],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('templates.templates.index')
                ->with('success', 'Template updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update template. ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemTemplate $itemTemplate)
    {
        try {
            $itemTemplate->delete();
            return redirect()
                ->route('templates.templates.index')
                ->with('success', 'Template deleted successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete template. ' . $e->getMessage()]);
        }
    }

    /**
     * Get templates for a specific category (AJAX endpoint).
     */
    public function getTemplatesByCategory($categoryId)
    {
        $templates = ItemTemplate::where('category_id', $categoryId)
            ->where('is_active', true)
            ->with('particulars')
            ->orderBy('name')
            ->get();

        return response()->json($templates);
    }

    /**
     * Get all templates as JSON (AJAX endpoint for material list).
     */
    public function getAllTemplates()
    {
        $templates = ItemTemplate::with(['category', 'particulars'])
            ->where('is_active', true)
            ->orderBy('category_id')
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $templates]);
    }

    /**
     * Duplicate a template.
     */
    public function duplicate(ItemTemplate $itemTemplate)
    {
        DB::beginTransaction();

        try {
            // Create a copy of the template
            $newTemplate = $itemTemplate->replicate();
            $newTemplate->name = $itemTemplate->name . ' (Copy)';
            $newTemplate->created_by = Auth::id();
            $newTemplate->save();

            // Copy the particulars
            foreach ($itemTemplate->particulars as $particular) {
                $newParticular = $particular->replicate();
                $newParticular->item_template_id = $newTemplate->id;
                $newParticular->save();
            }

            DB::commit();

            return redirect()
                ->route('templates.templates.edit', $newTemplate)
                ->with('success', 'Template duplicated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to duplicate template. ' . $e->getMessage()]);
        }
    }
}
