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
            ['name' => 'Quotation', 'route' => route('quotes.index', $project), 'template' => 'quotes'],
            ['name' => 'Booking Order', 'route' => route('projects.booking-order.index', $project), 'template' => 'booking-order-template'],
            ['name' => 'Close-Out Report', 'route' => route('projects.booking-order.index', $project), 'template' => 'booking-order-template'],
            
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



    // // Method to handle download of templates
    // public function downloadTemplate(Project $project, $template)
    // {
    //     // Logic for downloading template
    //     return response()->download(storage_path("app/templates/{$template}.docx"));
    // }

    // // Method to handle printing of templates
    // public function printTemplate(Project $project, $template)
    // {
    //     // Logic for printing template
    //     // This could be handled via a PDF generation or a direct print dialog
    //     return response()->file(storage_path("app/templates/{$template}.docx"));
    // }
}
