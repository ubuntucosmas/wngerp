<?php

namespace App\Http\Controllers\projects;

use App\Http\Controllers\Controller;
use App\Models\ProjectPhase;
use Illuminate\Http\Request;

class PhaseStatusController extends Controller
{
    public function updateStatus(Request $request, $phaseId)
    {
        try {
            $phase = ProjectPhase::findOrFail($phaseId);
            
            $request->validate([
                'status' => 'required|in:Not Started,In Progress,Completed'
            ]);

            $phase->update([
                'status' => $request->status
            ]);

            // Calculate updated project progress
            $project = $phase->project;
            $totalPhases = $project->phases->count();
            $completed = $project->phases->where('status', 'Completed')->count();
            $inProgress = $project->phases->where('status', 'In Progress')->count();
            $progress = $totalPhases > 0
                ? round((($completed + 0.5 * $inProgress) / $totalPhases) * 100)
                : 0;

            return response()->json([
                'success' => true,
                'message' => 'Phase status updated successfully',
                'status' => $phase->status,
                'projectProgress' => $progress,
                'projectId' => $project->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update phase status: ' . $e->getMessage()
            ], 500);
        }
    }

    // Simple form submission method - much easier to implement
    public function updateStatusSimple(Request $request, $phaseId)
    {
        try {
            $phase = ProjectPhase::findOrFail($phaseId);
            
            $request->validate([
                'status' => 'required|in:Not Started,In Progress,Completed'
            ]);

            $phase->update([
                'status' => $request->status
            ]);

            // Check if this is an enquiry phase
            if ($phase->enquiry_id) {
                $enquiry = \App\Models\Enquiry::find($phase->enquiry_id);
                
                if ($enquiry && $enquiry->areFirstFourPhasesCompleted()) {
                    // Convert enquiry to project
                    $project = $enquiry->convertToProject();
                    if ($project) {
                        return redirect()->back()->with('success', 'All phases completed! Enquiry has been converted to a project.');
                    }
                }
            } else {
                // Check if this is the Budget & Quotation phase being completed for a project
                if ($phase->name === 'Budget & Quotation' && $request->status === 'Completed') {
                    $project = $phase->project;
                    $project->createRemainingPhases();
                }
            }

            return redirect()->back()->with('success', 'Phase status updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update phase status: ' . $e->getMessage());
        }
    }

    // Direct link method - simplest possible approach
    public function updateStatusDirect($phaseId, $status)
    {
        try {
            $phase = ProjectPhase::findOrFail($phaseId);
            
            if (!in_array($status, ['Not Started', 'In Progress', 'Completed'])) {
                throw new \Exception('Invalid status');
            }

            $phase->update(['status' => $status]);

            return redirect()->back()->with('success', 'Phase status updated to ' . $status . '!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update phase status: ' . $e->getMessage());
        }
    }

    // Manual trigger for testing - create remaining phases
    public function createRemainingPhases($projectId)
    {
        try {
            $project = \App\Models\Project::findOrFail($projectId);
            $result = $project->createRemainingPhases();
            
            if ($result) {
                return redirect()->back()->with('success', 'Remaining phases created successfully!');
            } else {
                return redirect()->back()->with('error', 'Budget & Quotation phase must be completed first.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create remaining phases: ' . $e->getMessage());
        }
    }
} 