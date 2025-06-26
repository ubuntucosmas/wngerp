<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\MaterialList;
use App\Models\ProductionItem;
use App\Models\ProductionParticular;
use App\Models\BudgetItem;
use Illuminate\Support\Facades\DB;

class MaterialListController extends Controller
{
    /**
     * Display a listing of the material lists for the project.
     */
    public function index(Project $project)
    {
        $materialLists = $project->materialLists()
            ->withCount(['productionItems', 'materialsHire', 'labourItems'])
            ->latest()
            ->paginate(15);
            
        return view('projects.material-list.index', compact('project', 'materialLists'));
    }

    /**
     * Display the specified material list.
     */
    public function show(Project $project, $materialList)
    {
        // Find the material list with the given ID
        $materialList = MaterialList::findOrFail($materialList);
        
        // Verify the material list belongs to the project
        if ($materialList->project_id !== $project->id) {
            abort(404);
        }
        
        // Eager load all necessary relationships
        $materialList->load([
            'productionItems.particulars',
            'materialsHire',
            'labourItems'
        ]);
        
        // Group labour items by category for the view
        $labourItemsByCategory = $materialList->labourItems->groupBy('category');
        
        return view('projects.material-list.show', [
            'project' => $project,
            'materialList' => $materialList,
            'labourItemsByCategory' => $labourItemsByCategory
        ]);
    }

    /**
     * Show the form for creating a new material list.
     */
    public function create(Project $project)
    {
        return view('projects.material-list.create', [
            'project' => $project,
            'materialList' => new MaterialList([
                'start_date' => now(),
                'end_date' => now()->addWeek(),
            ])
        ]);
    }

    /**
     * Store a newly created material list in storage.
     */
    public function store(Request $request, Project $project)
    {
        try {
            // Log the incoming request data for debugging
            \Log::info('Material List Creation Request:', $request->all());
            
            $validated = $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'approved_by' => 'required|string|max:255',
                'approved_departments' => 'required|string|max:255',
                'materials_hire' => 'sometimes|array',
                'materials_hire.*.particular' => 'nullable|string|max:255',
                'materials_hire.*.unit' => 'nullable|string|max:50',
                'materials_hire.*.quantity' => 'nullable|numeric|min:0',
                'production_items' => 'sometimes|array',
                'production_items.*.item_name' => 'nullable|string|max:255',
                'production_items.*.particulars' => 'sometimes|array',
                'production_items.*.particulars.*.particular' => 'nullable|string|max:255',
                'production_items.*.particulars.*.unit' => 'nullable|string|max:50',
                'production_items.*.particulars.*.quantity' => 'nullable|numeric|min:0',
                'items' => 'sometimes|array',
                'items.*' => 'array',
                'items.*.*.particular' => 'nullable|string|max:255',
                'items.*.*.unit' => 'nullable|string|max:50',
                'items.*.*.quantity' => 'nullable|numeric|min:0',
            ]);

            \Log::info('Validation passed', ['validated' => $validated]);

            DB::beginTransaction();

            try {
                // Create the material list
                $materialListData = [
                    'start_date' => $validated['start_date'],
                    'end_date' => $validated['end_date'],
                    'approved_by' => $validated['approved_by'],
                    'approved_departments' => $validated['approved_departments'],
                    'project_id' => $project->id,
                ];
                \Log::info('Creating material list with data:', $materialListData);
                
                $materialList = $project->materialLists()->create($materialListData);
                \Log::info('Material list created', ['id' => $materialList->id]);

                // Save materials for hire
                $materialsHire = $request->input('materials_hire', []);
                \Log::info('Saving materials for hire', ['count' => count($materialsHire)]);
                $this->saveMaterialsHire($materialList, $materialsHire);
                
                // Save production items
                $productionItems = $request->input('production_items', []);
                \Log::info('Saving production items', ['count' => count($productionItems)]);
                $this->saveProductionItems($materialList, $productionItems);
                
                // Save labour items
                $labourItems = $request->input('items', []);
                \Log::info('Saving labour items', ['count' => count($labourItems)]);
                $this->saveLabourItems($materialList, $labourItems);
                
                DB::commit();
                \Log::info('Material list creation completed successfully');

                return redirect()
                    ->route('projects.material-list.show', [$project, $materialList])
                    ->with('success', 'Material list created successfully!');
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Error in material list creation transaction: ' . $e->getMessage());
                \Log::error('Stack trace: ' . $e->getTraceAsString());
                
                return back()
                    ->withInput()
                    ->withErrors([
                        'error' => 'Failed to create material list. Error: ' . $e->getMessage()
                    ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error in material list creation', ['errors' => $e->errors()]);
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Unexpected error in material list creation: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()
                ->withInput()
                ->withErrors([
                    'error' => 'An unexpected error occurred. Please try again. Error: ' . $e->getMessage()
                ]);
        }
    }

    public function edit(Project $project, MaterialList $materialList)
    {
        // Ensure the material list belongs to the project
        if ($materialList->project_id !== $project->id) {
            abort(404, 'Material list not found for this project');
        }

        $materialList->load([
            'productionItems.particulars',
            'materialsHire',
            'labourItems' => function($query) {
                $query->orderBy('category')->orderBy('item_name');
            }
        ]);
        
        // Group labour items by category for the view
        $labourItemsByCategory = $materialList->labourItems->groupBy('category');
        
        return view('projects.material-list.edit', [
            'project' => $project,
            'materialList' => $materialList,
            'labourItemsByCategory' => $labourItemsByCategory
        ]);
    }

    /**
     * Update the specified material list in storage.
     */
    public function update(Request $request, Project $project, MaterialList $materialList)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'approved_by' => 'required|string|max:255',
            'approved_departments' => 'required|string|max:255',
            'materials_hire' => 'sometimes|array',
            'materials_hire.*.particular' => 'required_with:materials_hire|string|max:255',
            'materials_hire.*.unit' => 'required_with:materials_hire.*.particular|string|max:50',
            'materials_hire.*.quantity' => 'required_with:materials_hire.*.particular|numeric|min:0',
            'production_items' => 'sometimes|array',
            'production_items.*.item_name' => 'required_with:production_items|string|max:255',
            'production_items.*.particulars' => 'sometimes|array',
            'production_items.*.particulars.*.particular' => 'required_with:production_items.*.particulars|string|max:255',
            'production_items.*.particulars.*.unit' => 'required_with:production_items.*.particulars.*.particular|string|max:50',
            'production_items.*.particulars.*.quantity' => 'required_with:production_items.*.particulars.*.particular|numeric|min:0',
            'items' => 'sometimes|array',
            'items.*' => 'array',
            'items.*.*.particular' => 'required_with:items|string|max:255',
            'items.*.*.unit' => 'required_with:items.*.*.particular|string|max:50',
            'items.*.*.quantity' => 'required_with:items.*.*.particular|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Update main material list data
            $materialList->update([
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'approved_by' => $validated['approved_by'],
                'approved_departments' => $validated['approved_departments'],
            ]);

            // Delete existing items
            $materialList->materialsHire()->delete();
            $materialList->productionItems()->delete();
            $materialList->labourItems()->delete();

            // Save materials for hire
            $this->saveMaterialsHire($materialList, $request->input('materials_hire', []));
            
            // Save production items
            $this->saveProductionItems($materialList, $request->input('production_items', []));
            
            // Save labour items
            $this->saveLabourItems($materialList, $request->input('items', []));
            
            DB::commit();

            return redirect()
                ->route('projects.material-list.show', [$project, $materialList])
                ->with('success', 'Material list updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to update material list: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update material list. Please try again.']);
        }
    }

    public function destroy(Project $project, MaterialList $materialList)
    {
        $materialList->delete();
        return redirect()->route('projects.material-list.index', $project)->with('success', 'Material list deleted successfully!');
    }
    
    /**
     * Save materials for hire
     */
    protected function saveMaterialsHire(MaterialList $materialList, array $materialsHire)
    {
        if (empty($materialsHire)) {
            return;
        }
        
        $materialsData = [];
        foreach ($materialsHire as $item) {
            if (!empty($item['particular'])) {
                $materialsData[] = [
                    'category' => 'Materials for Hire',
                    'particular' => $item['particular'],
                    'unit' => $item['unit'] ?? null,
                    'quantity' => $item['quantity'] ?? 0,
                    'comment' => $item['comment'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        
        if (!empty($materialsData)) {
            // Use materialsHire relationship which is already scoped to 'Materials for Hire'
            $materialList->materialsHire()->createMany($materialsData);
        }
    }
    
    /**
     * Save production items
     */
    protected function saveProductionItems(MaterialList $materialList, array $productionItems)
    {
        if (empty($productionItems)) {
            return;
        }
        
        foreach ($productionItems as $item) {
            if (empty($item['item_name'])) {
                continue;
            }
            
            // Create the main production item
            $productionItem = $materialList->productionItems()->create([
                'item_name' => $item['item_name'],
            ]);
            
            // Add particulars if they exist
            if (!empty($item['particulars'])) {
                $particulars = [];
                foreach ($item['particulars'] as $particular) {
                    if (!empty($particular['particular'])) {
                        $particulars[] = [
                            'particular' => $particular['particular'],
                            'unit' => $particular['unit'] ?? null,
                            'quantity' => $particular['quantity'] ?? 0,
                            'comment' => $particular['comment'] ?? null,
                            'design_reference' => $particular['design_reference'] ?? null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
                
                if (!empty($particulars)) {
                    $productionItem->particulars()->createMany($particulars);
                }
            }
        }
    }
    
    /**
     * Save labor items
     */
    protected function saveLabourItems(MaterialList $materialList, array $labourItems)
    {
        if (empty($labourItems)) {
            return;
        }
        
        $labourData = [];
        
        foreach ($labourItems as $category => $items) {
            if (!is_array($items)) {
                continue;
            }
            
            foreach ($items as $item) {
                if (!empty($item['particular'])) {
                    $labourData[] = [
                        'category' => $category,
                        'particular' => $item['particular'],
                        'unit' => $item['unit'] ?? null,
                        'quantity' => $item['quantity'] ?? 0,
                        'comment' => $item['comment'] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }
        
        if (!empty($labourData)) {
            // Use labourItems relationship
            $materialList->labourItems()->createMany($labourData);
        }
    }
}
