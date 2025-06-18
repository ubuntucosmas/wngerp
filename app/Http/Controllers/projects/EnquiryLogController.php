<?php

namespace App\Http\Controllers\projects;
use App\Http\Controllers\Controller;

use App\Models\EnquiryLog;
use App\Models\Project;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class EnquiryLogController extends Controller
{
    /**
     * Show the enquiry log for a given project.
     */
    public function show(Project $project)
    {
        $enquiryLog = EnquiryLog::where('project_id', $project->id)->first();

        return view('projects.enquiry-log.show', compact('project', 'enquiryLog'));
    }

    /**
     * Show form to create enquiry log.
     */
    public function create(Project $project)
    {
        return view('projects.enquiry-log.create', compact('project'));
    }

    /**
     * Store enquiry log.
     */
    public function store(Request $request, Project $project)
    {
        $data = $request->validate([
            'venue' => 'required|string|max:255',
            'date_received' => 'required|date',
            'client_name' => 'required|string|max:255',
            'project_scope_summary' => 'required|string',
            'contact_person' => 'nullable|string|max:255',
            'status' => 'required|in:Open,Quoted,Approved,Declined',
            'assigned_to' => 'nullable|string|max:255',
            'follow_up_notes' => 'nullable|string',
        ]);
    
        $data['project_id'] = $project->id;
        $data['project_name'] = $project->name;
        $data['project_scope_summary'] = json_encode(
            array_filter(array_map('trim', explode(',', $data['project_scope_summary'])))
        );
    
        $enquiryLog = EnquiryLog::create($data);
    
        return redirect()->route('projects.enquiry-log.show', $project)
                         ->with('success', 'Enquiry Log created successfully.');
    }
    

    /**
     * Show form to edit enquiry log. 
     */
    public function edit(Project $project, EnquiryLog $enquiryLog)
    {
        // Ensure the enquiry log belongs to the specified project
        // if ($enquiryLog->project_id !== $project->id) {
        //     abort(404);
        // }
        
        return view('projects.enquiry-log.edit', compact('project', 'enquiryLog'));
    }

    /**
     * Update enquiry log.
     */
    public function update(Request $request, Project $project, EnquiryLog $enquiryLog)
    {
        $data = $request->validate([
            'venue' => 'required|string|max:255',
            'date_received' => 'required|date',
            'client_name' => 'required|string|max:255',
            'project_scope_summary' => 'required|string',
            'contact_person' => 'nullable|string|max:255',
            'status' => 'required|in:Open,Quoted,Approved,Declined',
            'assigned_to' => 'nullable|string|max:255',
            'follow_up_notes' => 'nullable|string',
        ]);
    
        // Ensure the project_id is set to the current project
        $data['project_id'] = $project->id;
        $data['project_name'] = $project->name;
        $data['project_scope_summary'] = json_encode(
            array_filter(array_map('trim', explode(',', $data['project_scope_summary'])))
        );
    
        $enquiryLog->update($data);
    
        return redirect()->route('projects.enquiry-log.show', $project)
            ->with('success', 'Enquiry log updated successfully.');
    }

    /**
     * Delete enquiry log.
     */
    public function destroy(Project $project, EnquiryLog $enquiryLog)
    {
        // Ensure the enquiry log belongs to the specified project
        if ($enquiryLog->project_id !== $project->id) {
            abort(404);
        }

        $enquiryLog->delete();

        return redirect()->route('projects.show', $project)
            ->with('success', 'Enquiry log deleted successfully.');
    }

    public function downloadEnquiryLog(Project $project)
    {
        $enquiryLog = EnquiryLog::where('project_id', $project->id)->firstOrFail();

        $data = [
            'project' => $project,
            'enquiryLog' => $enquiryLog,
        ];

        $pdf = PDF::loadView('projects.templates.enquiry-log', $data);

        return $pdf->download('enquiry-log-' . $project->name . '.pdf');
    }

    public function printEnquiryLog(Project $project)
    {
        $enquiryLog = EnquiryLog::where('project_id', $project->id)->firstOrFail();

         $data = [
            'project' => $project,
            'enquiryLog' => $enquiryLog,
        ];

        $pdf = PDF::loadView('projects.templates.enquiry-log', $data);

        return $pdf->stream('enquiry-log-' . $project->name . '.pdf');
    }
    
}
