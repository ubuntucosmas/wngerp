<?php

namespace App\Http\Controllers\projects;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\SetupReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SetupController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    /**
     * Display setup & execution files for the project
     */
    public function index(Project $project)
    {
        // Check if user can view this project
        $this->authorize('view', $project);

        $reports = $project->setupReports()->latest()->get();
        return view('projects.setup.index', compact('project', 'reports'));
    }

    public function store(Request $request, Project $project)
    {
        // Check if user can edit this project (not just view)
        $this->authorize('edit', $project);

        // Enable query logging
        \DB::enableQueryLog();
        
        \Log::info('Store method called', ['request' => $request->all()]);
        
        try {
            $validated = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'google_drive_link' => [
                    'required', 
                    'url',
                ],
            ]);
            
            \Log::info('Validation passed', ['validated' => $validated]);

            $report = new SetupReport([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'google_drive_link' => $validated['google_drive_link'],
                'uploaded_by' => Auth::id(),
            ]);
            
            $saved = $project->setupReports()->save($report);
            
            // Log the executed queries
            \Log::info('Queries executed:', \DB::getQueryLog());
            \Log::info('Report saved status:', ['saved' => $saved, 'report' => $report->toArray()]);

            if ($saved) {
                return redirect()->back()->with('success', 'Setup report added successfully.');
            } else {
                throw new \Exception('Failed to save report to database');
            }
        } catch (\Exception $e) {
            \Log::error('Error creating report', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to save report. Please try again. ' . $e->getMessage());
        }
    }

    public function destroy(Project $project, SetupReport $setupReport)
    {
        // Check if user can edit this project (not just view)
        $this->authorize('edit', $project);

        // Check if the authenticated user is authorized to delete
        // if (Auth::user()->hasAnyrole()) {
        //     return redirect()->back()->with('error', 'You are not authorized to delete this report.');
        // }
        
        $setupReport->delete();
        return redirect()->back()->with('success', 'Setup report deleted successfully.');
    }
}
