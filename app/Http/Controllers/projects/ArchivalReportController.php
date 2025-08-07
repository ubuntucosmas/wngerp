<?php

namespace App\Http\Controllers\projects;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\ArchivalReport;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Exception;

class ArchivalReportController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    /**
     * Display archival reports for the project
     */
    public function index(Project $project)
    {
        // Check if user can view this project
        $this->authorize('view', $project);

        $reports = $project->archivalReports()
            ->with('uploadedBy')
            ->latest('report_date')
            ->get()
            ->groupBy('report_type');
            
        $reportTypes = [
            'final_report' => 'Final Project Report',
            'financial_summary' => 'Financial Summary',
            'media_archive' => 'Media Archive',
            'other' => 'Other Documents'
        ];
        
        // Get low stock items for the master layout
        $lowStockItems = collect();
        if (Auth::check() && Auth::user()->department === 'stores') {
            $lowStockItems = Inventory::where('stock_on_hand', '<=', 10)->get();
        }
            
        return view('projects.archival.index', compact('project', 'reports', 'reportTypes', 'lowStockItems'));
    }

    /**
     * Store a newly created archival report
     */
    public function store(Request $request, Project $project)
    {
        // Check if user can edit this project (not just view)
        $this->authorize('edit', $project);

        DB::enableQueryLog();
        Log::info('Archival Report store method called', ['request' => $request->all()]);
        
        try {
            $validated = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'google_drive_link' => ['required', 'url'],
                'report_type' => ['required', 'string', Rule::in([
                    'final_report', 'financial_summary', 'media_archive', 'other'
                ])],
                'report_date' => ['required', 'date'],
            ]);
            
            Log::info('Archival Report validation passed', ['validated' => $validated]);

            $report = new ArchivalReport([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'google_drive_link' => $validated['google_drive_link'],
                'report_type' => $validated['report_type'],
                'report_date' => $validated['report_date'],
                'uploaded_by' => Auth::id(),
            ]);
            
            $saved = $project->archivalReports()->save($report);
            
            Log::info('Archival Report queries executed:', DB::getQueryLog());
            Log::info('Archival Report saved status:', ['saved' => $saved, 'report' => $report->toArray()]);

            if ($saved) {
                return redirect()->back()
                    ->with('success', 'Archival document added successfully.');
            } else {
                throw new Exception('Failed to save archival document to database');
            }
        } catch (Exception $e) {
            Log::error('Error creating archival document', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to save archival document. Please try again. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified archival report
     */
    public function destroy(Project $project, ArchivalReport $archivalReport)
    {
        // Check if user can edit this project (not just view)
        $this->authorize('edit', $project);
        
        try {
            $archivalReport->delete();
            return redirect()->back()
                ->with('success', 'Archival document deleted successfully.');
        } catch (Exception $e) {
            Log::error('Error deleting archival document', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'archival_report_id' => $archivalReport->id
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to delete archival document. Please try again.');
        }
    }
}
