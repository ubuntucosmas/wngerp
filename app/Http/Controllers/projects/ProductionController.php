<?php

namespace App\Http\Controllers\projects;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\Project;
use App\Models\Production;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductionController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    /**
     * Display the production dashboard
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function index(Project $project)
    {
        // Check if user can view this project
        $this->authorize('view', $project);

        // Eager load the production with its tasks, ordered by due date
        $production = $project->production()->with(['tasks' => function($query) {
            $query->orderBy('due_date', 'asc');
        }])->first();
        
        if ($production) {
            // Debug the tasks relationship
            Log::info('Production Tasks:', [
                'production_id' => $production->id,
                'tasks_count' => $production->tasks ? $production->tasks->count() : 0,
                'tasks' => $production->tasks ? $production->tasks->toArray() : []
            ]);
            
            // Handle files_received data
            if ($production->files_received === null) {
                $production->files_received = [];
            } elseif (is_bool($production->files_received)) {
                $production->files_received = $production->files_received ? 
                    [['name' => 'Project Files', 'size' => '']] : [];
            } elseif (is_string($production->files_received)) {
                // If it's a JSON string, decode it
                $decoded = json_decode($production->files_received, true);
                $production->files_received = is_array($decoded) ? $decoded : [];
            } elseif (!is_array($production->files_received)) {
                // If it's some other type, initialize as empty array
                $production->files_received = [];
            }
            
            // Ensure tasks is always a collection
            if (!$production->relationLoaded('tasks')) {
                $production->setRelation('tasks', collect());
            }
        } else {
            Log::info('No production record found for project ID: ' . $project->id);
        }
        
        // Debug the data being passed to the view
        $data = [
            'project' => $project->toArray(),
            'production' => $production ? $production->toArray() : null,
            'tasks' => $production && $production->tasks ? $production->tasks->toArray() : []
        ];
        
        Log::info('View Data:', $data);
        
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
        // Check if user can view this project
        $this->authorize('view', $project);

        // Reuse the index method since it contains the same logic
        return $this->index($project);
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
        // Check if user can edit this project (not just view)
        $this->authorize('edit', $project);

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
        // Check if user can edit this project (not just view)
        $this->authorize('edit', $project);

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
     * Update an existing job brief
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @param  \App\Models\Production  $production
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateJobBrief(Request $request, Project $project, Production $production)
    {
        // Check if user can edit this project (not just view)
        $this->authorize('edit', $project);

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
            'tasks.*.title' => 'required|string|max:255',
            'tasks.*.description' => 'nullable|string',
            'tasks.*.assigned_to' => 'nullable|string|max:255',
            'tasks.*.due_date' => 'required|date',
            'team_lead_name' => 'nullable|string|max:255',
            'team_lead_date' => 'nullable|date',
            'supervisor_name' => 'nullable|string|max:255',
            'supervisor_date' => 'nullable|date',
        ]);

        DB::transaction(function () use ($production, $validated) {
            // Prepare the data for update
            $updateData = [
                'job_number' => $validated['job_number'],
                'client_name' => $validated['client_name'],
                'project_title' => $validated['project_title'],
                'briefing_date' => $validated['briefing_date'],
                'briefed_by' => $validated['briefed_by'],
                'delivery_date' => $validated['delivery_date'],
                'production_team' => $validated['production_team'],
                'materials_required' => $validated['materials_required'] ?? null,
                'key_instructions' => $validated['key_instructions'] ?? null,
                'special_considerations' => $validated['special_considerations'] ?? null,
                'additional_notes' => $validated['additional_notes'] ?? null,
                'team_lead_name' => $validated['team_lead_name'] ?? null,
                'team_lead_date' => $validated['team_lead_date'] ?? null,
                'supervisor_name' => $validated['supervisor_name'] ?? null,
                'supervisor_date' => $validated['supervisor_date'] ?? null,
            ];

            // Handle files_received - ensure it's always an array
            $updateData['files_received'] = $validated['files_received'] 
                ? [['name' => 'Project Files', 'size' => '']] 
                : [];
            
            // Log the files_received data for debugging
            \Log::info('Files received data being saved:', [
                'files_received' => $updateData['files_received'],
                'is_array' => is_array($updateData['files_received'])
            ]);

            // Update the production record
            $production->update($updateData);

            // Handle tasks
            if (isset($validated['tasks'])) {
                // Delete existing tasks
                $production->tasks()->delete();
                
                // Create new tasks
                foreach ($validated['tasks'] as $taskData) {
                    $production->tasks()->create([
                        'title' => $taskData['title'],
                        'description' => $taskData['description'] ?? null,
                        'assigned_to' => $taskData['assigned_to'] ?? null,
                        'due_date' => $taskData['due_date'],
                        'status' => 'pending', // Default status
                    ]);
                }
            }
        });

        return redirect()
            ->route('projects.production.index', $project)
            ->with('success', 'Job brief has been updated successfully.');
    }

    /**
     * Show production files
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function showFiles(Project $project)
    {
        // Check if user can view this project
        $this->authorize('view', $project);

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
        // Check if user can edit this project (not just view)
        $this->authorize('edit', $project);

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
        // Check if user can edit this project (not just view)
        $this->authorize('edit', $project);

        // Delete the production record
        $production->delete();

        return redirect()
            ->route('projects.production.index', $project)
            ->with('success', 'Production record deleted successfully.');
    }

    /**
     * Download the production details as PDF
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function download(Project $project)
    {
        // Check if user can view this project
        $this->authorize('view', $project);

        $production = $project->production;

        if (!$production) {
            abort(404, 'No production details found for this project.');
        }

        $filename = $production->job_number . ' Job Brief.pdf';
        $pdf = Pdf::loadView('projects.production.pdf', compact('project', 'production'));
        return $pdf->download($filename);
    }
    
    /**
     * Display the production details in a printable format
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function print(Project $project)
    {
        // Check if user can view this project
        $this->authorize('view', $project);

        $production = $project->production;

        if (!$production) {
            abort(404, 'No production details found for this project.');
        }

        $filename = $production->job_number . ' Job Brief.pdf';
        $pdf = Pdf::loadView('projects.production.pdf', compact('project', 'production'));
        return $pdf->stream($filename);
    }
};
