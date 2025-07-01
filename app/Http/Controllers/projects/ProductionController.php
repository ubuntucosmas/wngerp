<?php

namespace App\Http\Controllers\projects;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Production;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    /**
     * Display the production dashboard
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function index(Project $project)
    {
        $production = $project->production;
        return view('projects.production.index', compact('project', 'production'));
    }

    /**
     * Show the job brief form
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function showJobBrief(Project $project)
    {
        $production = $project->production;
        return view('projects.production.index', compact('project', 'production'));
    }

    /**
     * Store or update a job brief
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeJobBrief(Request $request, Project $project)
    {
        $validated = $request->validate([
            'job_number' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'project_title' => 'required|string|max:255',
            'briefing_date' => 'required|date',
            'briefed_by' => 'required|string|max:255',
            'delivery_date' => 'required|date|after_or_equal:briefing_date',
            'production_team' => 'required|string',
            'materials_required' => 'nullable|string',
            'files_received' => 'required|boolean',
            'key_instructions' => 'nullable|string',
            'special_considerations' => 'nullable|string',
            'additional_notes' => 'nullable|string',
            'tasks' => 'nullable|array',
            'team_lead_name' => 'nullable|string|max:255',
            'team_lead_date' => 'nullable|date',
            'supervisor_name' => 'nullable|string|max:255',
            'supervisor_date' => 'nullable|date',
        ]);

        DB::transaction(function () use ($project, $validated) {
            $production = $project->production ?? new Production(['project_id' => $project->id]);
            $production->fill($validated);
            $production->save();

            // Handle tasks
            if (isset($validated['tasks'])) {
                $production->tasks()->delete();
                foreach ($validated['tasks'] as $task) {
                    $production->tasks()->create($task);
                }
            }
        });

        return redirect()
            ->route('projects.production.index', $project)
            ->with('success', 'Job brief has been saved successfully.');
    }

    /**
     * Update production status
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Project $project)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,approved,rejected,completed',
            'notes' => 'nullable|string'
        ]);

        $production = $project->production;
        if ($production) {
            $production->status = $validated['status'];
            $production->status_notes = $validated['notes'];
            $production->save();
        }

        return redirect()
            ->route('projects.production.index', $project)
            ->with('success', 'Production status has been updated.');
    }

    /**
     * Show production files
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function showFiles(Project $project)
    {
        $production = $project->production ?? new Production(['project_id' => $project->id]);
        return view('projects.production.files', compact('project', 'production'));
    }

    /**
     * Update production files
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @param  \App\Models\Production  $production
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateFiles(Request $request, Project $project, Production $production)
    {
        $validated = $request->validate([
            // Add validation rules for your production files here
        ]);

        $production->update($validated);

        return redirect()
            ->route('projects.production.files', $project)
            ->with('success', 'Production files have been updated.');
    }

    /**
     * Delete production files
     *
     * @param  \App\Models\Project  $project
     * @param  \App\Models\Production  $production
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyFiles(Project $project, Production $production)
    {
        // Implementation for destroying files
    }

    /**
     * Remove the specified production record from storage.
     *
     * @param  \App\Models\Project  $project
     * @param  \App\Models\Production  $production
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Project $project, Production $production)
    {
        // Ensure the production record belongs to the project
        if ($production->project_id !== $project->id) {
            abort(403, 'Unauthorized action.');
        }

        // Delete the production record
        $production->delete();

        return redirect()
            ->route('projects.production.index', $project)
            ->with('success', 'Production record deleted successfully.');
    }
}
