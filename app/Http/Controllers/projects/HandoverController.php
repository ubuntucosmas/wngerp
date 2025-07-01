<?php

namespace App\Http\Controllers\projects;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\HandoverReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class HandoverController extends Controller
{
    /**
     * Display handover documents for the project
     */
    public function index(Project $project)
    {
        $reports = $project->handoverReports()->latest()->get();
        return view('projects.handover.index', compact('project', 'reports'));
    }

    /**
     * Store a newly created handover report
     */
    public function store(Request $request, Project $project)
    {
        \DB::enableQueryLog();
        \Log::info('Handover store method called', ['request' => $request->all()]);
        
        try {
            $validated = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'google_drive_link' => [
                    'required', 
                    'url',
                ],
            ]);
            
            \Log::info('Handover validation passed', ['validated' => $validated]);

            $report = new HandoverReport([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'google_drive_link' => $validated['google_drive_link'],
                'uploaded_by' => Auth::id(),
            ]);
            
            $saved = $project->handoverReports()->save($report);
            
            \Log::info('Handover queries executed:', \DB::getQueryLog());
            \Log::info('Handover report saved status:', ['saved' => $saved, 'report' => $report->toArray()]);

            if ($saved) {
                return redirect()->back()->with('success', 'Handover document added successfully.');
            } else {
                throw new \Exception('Failed to save handover document to database');
            }
        } catch (\Exception $e) {
            \Log::error('Error creating handover document', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to save handover document. Please try again. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified handover report
     */
    public function destroy(Project $project, HandoverReport $handoverReport)
    {
        // Check if the authenticated user is authorized to delete
        if (Auth::user()->cannot('delete', $handoverReport)) {
            return redirect()->back()->with('error', 'You are not authorized to delete this handover document.');
        }
        
        $handoverReport->delete();
        return redirect()->back()->with('success', 'Handover document deleted successfully.');
    }
}
