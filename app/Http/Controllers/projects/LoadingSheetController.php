<?php

namespace App\Http\Controllers\projects;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\LoadingSheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\PDF;

class LoadingSheetController extends Controller
{
    // use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public function index(Project $project)
    {
        // Check if user can view this project
        // $this->authorize('view', $project);

        $loadingsheet = $project->LoadingSheets()->latest()->first();
        
        return view('projects.loadingsheet.index', [
            'project' => $project,
            'loadingsheet' => $loadingsheet ? $loadingsheet->toArray() : null,
        ]);
    }

    public function store(Request $request, Project $project)
    {
        // Check if user can edit this project (not just view)
        // $this->authorize('edit', $project);

        $validated = $request->validate([
            'vehicle_number' => 'required|string|max:50',
            'driver_name' => 'required|string|max:100',
            'loading_point' => 'required|string|max:255',
            'unloading_point' => 'required|string|max:255',
            'loading_date' => 'required|date',
            'unloading_date' => 'required|date|after_or_equal:loading_date',
            'special_instructions' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit' => 'required|string|max:20',
            'items.*.notes' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();
            
            // Create or update the loading sheet
            $loadingsheet = $project->loadingSheets()->updateOrCreate(
                ['id' => $request->input('loading_sheet_id')],
                $validated
            );

            DB::commit();

            return redirect()->route('projects.logistics.loading-sheet', $project)
                ->with('success', 'Loading sheet saved successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to save loading sheet: ' . $e->getMessage());
        }
    }

    public function print(Project $project)
    {
        // Check if user can view this project
        $this->authorize('view', $project);

        $loadingsheet = $project->loadingSheets()->latest()->first();
        if (!$loadingsheet) {
            return redirect()->back()->with('error', 'No loading sheet found');
        }

        return PDF::loadView('projects.loadingsheet.pdf', [
            'project' => $project,
            'loadingsheet' => $loadingsheet,
        ])->stream();
    }

    public function download(Project $project)
    {
        // Check if user can view this project
        // $this->authorize('view', $project);

        $loadingsheet = $project->loadingSheets()->latest()->first();
        if (!$loadingsheet) {
            return redirect()->back()->with('error', 'No loading sheet found');
        }

        $pdf = PDF::loadView('projects.loadingsheet.pdf', [
            'project' => $project,
            'loadingsheet' => $loadingsheet,
        ]);

        return $pdf->download('loading-sheet-' . $project->id . '-' . date('Y-m-d') . '.pdf');
    }

    public function show(Project $project, $id)
    {
        $loadingsheet = $project->loadingSheets()->findOrFail($id);
        return response()->json($loadingsheet);
    }
}
