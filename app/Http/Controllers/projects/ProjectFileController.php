<?php

namespace App\Http\Controllers\projects;
use App\Http\Controllers\Controller;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectFileController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['role:pm|po']);
    // }
    
    public function index(Project $project)
    {
        // List of file types for the project
        $fileTypes = [
            ['name' => 'Enquiry', 'route' => route('projects.enquiry-log.show', $project), 'template' => 'enquiry-log-template'],
            ['name' => 'Site Survey', 'route' => route('projects.site-survey.create', $project), 'template' => 'site-survey'],
            ['name' => 'Design Assets', 'route' => route('projects.files.mockups', $project), 'template' => 'mockups'],
           // ['name' => 'Project Material List', 'route' => route('material.index', $project), 'template' => 'material'],
            ['name' => 'Budget', 'route' => route('budget.index', $project), 'template' => 'budget'],
            ['name' => 'Quotation', 'route' => route('quotes.index', $project), 'template' => 'quotes'],
            ['name' => 'Booking Order', 'route' => route('projects.logistics.booking-orders.index', $project), 'template' => 'booking-order-template'],
            ['name' => 'Close-Out Report', 'route' => route('projects.logistics.booking-orders.index', $project), 'template' => 'booking-order-template'],
            
        ];
        
        // Check if site survey exists for this project
        $siteSurvey = \App\Models\SiteSurvey::where('project_id', $project->id)->first();
        if ($siteSurvey) {
            // Update the route to show the existing site survey
            $fileTypes[1]['route'] = route('projects.site-survey.show', [$project, $siteSurvey]);
        }
        return view('projects.files.index', compact('project', 'fileTypes'));
    }

    /**
     * Display mockup files for the project
     */
    public function showMockups(Project $project)
    {
        $designAssets = \App\Models\DesignAsset::where('project_id', $project->id)
            ->with('user')
            ->latest()
            ->get();

        return view('projects.files.mockups', compact('project', 'designAssets'));
    }

    /**
     * Store a newly created design asset in storage.
     */
    public function storeDesignAsset(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'file_url' => 'required|url|max:1000',
            // 'file_type' => 'nullable|string|max:100',
            // 'file_size' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        // Extract filename from URL
        $url = parse_url($validated['file_url']);
        $path = trim($url['path'] ?? '', '/');
        $filename = basename($path);

        $designAsset = $project->designAssets()->create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'file_name' => $filename,
            'file_url' => $validated['file_url'],
            // 'file_type' => $validated['file_type'],
            // 'file_size' => $validated['file_size'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('projects.files.mockups', $project)
            ->with('success', 'Design asset added successfully');
    }

    /**
     * Update the specified design asset in storage.
     */
    public function updateDesignAsset(Request $request, Project $project, $designAssetId)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'file_url' => 'required|url|max:1000',
            // 'file_type' => 'nullable|string|max:100',
            // 'file_size' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        // Find the design asset
        $designAsset = $project->designAssets()->findOrFail($designAssetId);

        // Extract filename from URL
        $url = parse_url($validated['file_url']);
        $path = trim($url['path'] ?? '', '/');
        $filename = basename($path);

        // Update the design asset
        $designAsset->update([
            'name' => $validated['name'],
            'file_name' => $filename,
            'file_url' => $validated['file_url'],
            // 'file_type' => $validated['file_type'],
            // 'file_size' => $validated['file_size'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('projects.files.mockups', $project)
            ->with('success', 'Design asset updated successfully');
    }

    /**
     * Remove the specified design asset from storage.
     */
    public function destroyDesignAsset(Project $project, $designAssetId)
    {
        $designAsset = $project->designAssets()->findOrFail($designAssetId);
        $designAsset->delete();

        return redirect()
            ->route('projects.files.mockups', $project)
            ->with('success', 'Design asset deleted successfully');
    }


    /**
     * Display client engagement files for the project
     */
    public function showClientEngagement(Project $project)
    {
        $siteSurvey = \App\Models\SiteSurvey::where('project_id', $project->id)->first();
        
        $files = [
            [
                'name' => 'Enquiry Log',
                'route' => route('projects.enquiry-log.show', $project),
                'icon' => 'bi-journal-text',
                'description' => 'View and manage client enquiry logs',
                'type' => 'enquiry-log',
                'updated_at' => now()->subDays(2)
            ],
            [
                'name' => 'Site Survey',
                'route' => $siteSurvey 
                    ? route('projects.site-survey.show', [$project, $siteSurvey])
                    : route('projects.site-survey.create', $project),
                'icon' => 'bi-clipboard2-pulse',
                'description' => 'View and manage site survey details',
                'type' => 'site-survey',
                'updated_at' => $siteSurvey ? $siteSurvey->updated_at : now()->subDays(1)
            ],
            // Add more files here as needed
        ];

        return view('projects.files.client-engagement', compact('project', 'files'));
    }

        /**
     * Display design & concept development files for the project
     */
    public function showDesignConcept(Project $project)
    {
        $designAssets = \App\Models\DesignAsset::where('project_id', $project->id)
            ->with('user')
            ->latest()
            ->get();
            
        // $materials = \App\Models\Material::where('project_id', $project->id)
        //     ->with('user')
        //     ->latest()
        //     ->get();

        return view('projects.files.design-concept', compact('project', 'designAssets'));
    }

    /**
     * Display quotation files for the project
     */
    public function showQuotation(Project $project)
    {
        $files = \App\Models\Quote::where('project_id', $project->id)
            ->latest()
            ->get();

        return view('projects.files.quotation', compact('project', 'files'));
    }


    // public function showMaterials(Project $project)
    // {
    //     $materials = $project->materials()->latest()->get()->groupBy('item');
    //     return view('projects.files.material', compact('project', 'materials'));
    // }

    // public function storeMaterial(Request $request, Project $project)
    // {
    //     $validated = $request->validate([
    //         'item' => 'required|string|max:255',
    //         'materials' => 'required|array',
    //         'materials.*.material' => 'required|string|max:255',
    //         'materials.*.specification' => 'nullable|string',
    //         'materials.*.unit' => 'nullable|string|max:50',
    //         'materials.*.quantity' => 'nullable|numeric',
    //         'materials.*.notes' => 'nullable|string',
    //         'materials.*.design_reference' => 'nullable|url',
    //         'materials.*.approved_by' => 'nullable|string|max:255',
    //     ]);

    //     foreach ($validated['materials'] as $mat) {
    //         $project->materials()->create([
    //             'item' => $validated['item'],
    //             'material' => $mat['material'],
    //             'specification' => $mat['specification'] ?? null,
    //             'unit' => $mat['unit'] ?? null,
    //             'quantity' => $mat['quantity'] ?? null,
    //             'notes' => $mat['notes'] ?? null,
    //             'design_reference' => $mat['design_reference'] ?? null,
    //             'approved_by' => $mat['approved_by'] ?? null,
    //         ]);
    //     }

    //     return redirect()->back()->with('success', 'Materials added successfully.');
    // }

    // public function editMaterial(Project $project, Material $material)
    // {
    //     return view('projects.material-list.edit', compact('project', 'material'));
    // }

    // public function updateMaterial(Request $request, Project $project, Material $material)
    // {
    //     $validated = $request->validate([
    //         'item' => 'required|string|max:255',
    //         'material' => 'required|string|max:255',
    //         'specification' => 'nullable|string',
    //         'unit' => 'nullable|string|max:50',
    //         'quantity' => 'nullable|numeric',
    //         'notes' => 'nullable|string',
    //         'design_reference' => 'nullable|url',
    //         'approved_by' => 'nullable|string|max:255',
    //     ]);

    //     $material->update($validated);

    //     return redirect()->route('projects.material-list.show', $project)
    //         ->with('success', 'Material updated successfully.');
    // }

    // public function destroyMaterial(Project $project, Material $material)
    // {
    //     $material->delete();

    //     return redirect()->route('projects.material-list.show', $project)
    //         ->with('success', 'Material deleted successfully.');
    // }


}
