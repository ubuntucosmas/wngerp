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
        // Check if this project was converted from an enquiry
        $enquirySource = $project->enquirySource;
        
        if ($enquirySource) {
            // For converted projects, get data from enquiry source
            $enquiryLog = EnquiryLog::where('enquiry_id', $enquirySource->id)->first();
        } else {
            // For regular projects, get data from project
        $enquiryLog = EnquiryLog::where('project_id', $project->id)->first();
        }

        return view('projects.enquiry-log.show', compact('project', 'enquiryLog'));
    }

    /**
     * Show form to create enquiry log.
     */
    public function create(Project $project)
    {
        $enquiry = $project->enquiry;
        return view('projects.enquiry-log.create', compact('project', 'enquiry'));
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
        
        $enquiry = $project->enquiry;
        return view('projects.enquiry-log.edit', compact('project', 'enquiryLog', 'enquiry'));
    }

    /**
     * Update enquiry log.
     */
    public function update(Request $request, Project $project, EnquiryLog $enquiryLog)
    {
        \Log::info('Project enquiry log update started', [
            'project_id' => $project->id,
            'enquiry_log_id' => $enquiryLog->id,
            'request_data' => $request->all(),
            'user_id' => auth()->id()
        ]);

        try {
            $data = $request->validate([
                'venue' => 'required|string|max:255',
                'date_received' => 'required|date',
                'client_name' => 'required|string|max:255',
                'project_scope_summary' => 'required|string',
                'contact_person' => 'nullable|string|max:255',
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
            
            \Log::info('Project enquiry log updated successfully', [
                'project_id' => $project->id,
                'enquiry_log_id' => $enquiryLog->id,
                'updated_data' => $data
            ]);
        
            return redirect()->route('projects.enquiry-log.show', $project)
                ->with('success', 'Enquiry log updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Project enquiry log validation failed', [
                'project_id' => $project->id,
                'enquiry_log_id' => $enquiryLog->id,
                'validation_errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Project enquiry log update failed', [
                'project_id' => $project->id,
                'enquiry_log_id' => $enquiryLog->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_data' => $request->all()
            ]);
            return back()->with('error', 'Failed to update enquiry log: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Delete enquiry log.
     */
    public function destroy(Project $project, EnquiryLog $enquiryLog)
    {
        // Ensure the enquiry log belongs to the specified project
        // if ($enquiryLog->project_id !== $project->id) {
        //     abort(404);
        // }

        $enquiryLog->delete();

        return redirect()->route('projects.show', $project)
            ->with('success', 'Enquiry log deleted successfully.');
    }

    public function downloadEnquiryLog(Project $project)
    {
        // Check if this project was converted from an enquiry
        $enquirySource = $project->enquirySource;
        
        if ($enquirySource) {
            // For converted projects, get data from enquiry source
            $enquiryLog = EnquiryLog::where('enquiry_id', $enquirySource->id)->firstOrFail();
        } else {
            // For regular projects, get data from project
        $enquiryLog = EnquiryLog::where('project_id', $project->id)->firstOrFail();
        }

        $data = [
            'project' => $project,
            'enquiryLog' => $enquiryLog,
        ];

        $pdf = PDF::loadView('projects.templates.enquiry-log', $data);

        return $pdf->download('enquiry-log-' . $project->name . '.pdf');
    }

    public function printEnquiryLog(Project $project)
    {
        // Check if this project was converted from an enquiry
        $enquirySource = $project->enquirySource;
        
        if ($enquirySource) {
            // For converted projects, get data from enquiry source
            $enquiryLog = EnquiryLog::where('enquiry_id', $enquirySource->id)->firstOrFail();
        } else {
            // For regular projects, get data from project
        $enquiryLog = EnquiryLog::where('project_id', $project->id)->firstOrFail();
        }

         $data = [
            'project' => $project,
            'enquiryLog' => $enquiryLog,
        ];

        $pdf = PDF::loadView('projects.templates.enquiry-log', $data);

        return $pdf->stream('enquiry-log-' . $project->name . '.pdf');
    }
    
}
