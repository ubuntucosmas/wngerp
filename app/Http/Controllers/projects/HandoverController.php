<?php

namespace App\Http\Controllers\projects;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Project;
use App\Models\HandoverReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HandoverController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    /**
     * Display handover documents for the project
     */
    public function index(Project $project)
    {
        // Check if user can view this project
        $this->authorize('view', $project);

        $reports = $project->handoverReports()->latest()->get();
        return view('projects.handover.index', compact('project', 'reports'));
    }

    public function getHandoverData(Project $project)
    {
        // Check if user can view this project
        $this->authorize('view', $project);

        $reports = $project->handoverReports()->get()->map(function ($report) {
            return [
                'id' => $report->id,
                'client_name' => $report->client_name,
                'contact_person' => $report->contact_person,
                'acknowledgment_date' => $report->formatted_date,
                'client_comments' => $report->client_comments,
            ];
        });
        
        return response()->json(['data' => $reports]);
    }

    /**
     * Store a newly created handover report
     */
    public function store(Request $request, Project $project)
    {
        // Check if user can edit this project (not just view)
        $this->authorize('edit', $project);

        try {
            $validated = $request->validate([
                'client_name' => ['required', 'string', 'max:255'],
                'contact_person' => ['nullable', 'string', 'max:255'],
                'acknowledgment_date' => ['required', 'date'],
                'client_comments' => ['nullable', 'string'],
            ]);

            $report = new HandoverReport([
                'client_name' => $validated['client_name'],
                'contact_person' => $validated['contact_person'] ?? null,
                'acknowledgment_date' => $validated['acknowledgment_date'],
                'client_comments' => $validated['client_comments'] ?? null,
                'uploaded_by' => Auth::id(),
            ]);

            $project->handoverReports()->save($report);

            return response()->json([
                'success' => true,
                'message' => 'Handover acknowledgment saved successfully',
                'report' => $report
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save handover acknowledgment. Please try again. ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Remove the specified handover report
     */
    public function destroy(Project $project, HandoverReport $handoverReport)
    {
        // Check if user can edit this project (not just view)
        $this->authorize('edit', $project);
        
        $handoverReport->delete();
        return redirect()->back()->with('success', 'Handover acknowledgment deleted successfully.');
    }
}
