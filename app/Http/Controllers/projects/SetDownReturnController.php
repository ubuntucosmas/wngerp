<?php

namespace App\Http\Controllers\projects;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\SetDownReturn;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Exception;

class SetDownReturnController extends Controller
{
    /**
     * Display set down & return documents for the project
     */
    public function index(Project $project)
    {
        $reports = $project->setDownReturns()->latest()->get();
        return view('projects.setdown.index', compact('project', 'reports'));
    }

    /**
     * Store a newly created set down & return report
     */
    public function store(\Illuminate\Http\Request $request, \App\Models\Project $project)
    {
        \Illuminate\Support\Facades\DB::enableQueryLog();
        \Illuminate\Support\Facades\Log::info('Set Down & Return store method called', ['request' => $request->all()]);
        
        try {
            $validated = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'google_drive_link' => [
                    'required', 
                    'url',
                ],
            ]);
            
            Log::info('Set Down & Return validation passed', ['validated' => $validated]);

            $report = new SetDownReturn([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'google_drive_link' => $validated['google_drive_link'],
                'uploaded_by' => Auth::id(),
            ]);
            
            $saved = $project->setDownReturns()->save($report);
            
            Log::info('Set Down & Return queries executed:', DB::getQueryLog());
            Log::info('Set Down & Return report saved status:', ['saved' => $saved, 'report' => $report->toArray()]);

            if ($saved) {
                return redirect()->back()->with('success', 'Set Down & Return document added successfully.');
            } else {
                throw new \Exception('Failed to save set down & return document to database');
            }
        } catch (Exception $e) {
            Log::error('Error creating set down & return document', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to save set down & return document. Please try again. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified set down & return report
     */
    public function destroy(Project $project, SetDownReturn $setDownReturn)
    {
        // Check if the authenticated user is authorized to delete
        if (Auth::user()->cannot('delete', $setDownReturn)) {
            return redirect()->back()->with('error', 'You are not authorized to delete this set down & return document.');
        }
        
        $setDownReturn->delete();
        return redirect()->back()->with('success', 'Set Down & Return document deleted successfully.');
    }
}
