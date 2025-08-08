<?php

namespace App\Http\Controllers\projects;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SiteSurvey;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Str;

class SiteSurveyController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public function create(Project $project)
    {
        // Check if user can edit this project (not just view)
        $this->authorize('edit', $project);

        // Load necessary relationships
        $project->load(['client', 'projectManager', 'projectOfficer']);
        
        // Create new site survey with default values from project
        $siteSurvey = new SiteSurvey([
            'client_name' => $project->client_name,
            'location' => $project->venue,
            'project_manager' => $project->projectOfficer ? $project->projectOfficer->name : null,
            'client_contact_person' => $project->contact_person,
            'client_phone' => $project->client->phone ?? null,
            'client_email' => $project->client->email ?? null,
            'project_description' => $project->name,
        ]);
        
        // Get team members (project manager and project officer)
        $teamMembers = collect([$project->projectManager, $project->projectOfficer])
            ->filter()
            ->unique('id');
        
        return view('projects.site-survey.create', [
            'project' => $project,
            'siteSurvey' => $siteSurvey,
            'teamMembers' => $teamMembers
        ]);
    }

    public function store(Request $request, Project $project)
    {
        // Check if user can edit this project (not just view)
        $this->authorize('edit', $project);

        $validated = $request->validate([
            // Basic Info
            'site_visit_date' => 'required|date',
            'project_manager' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'attendees' => 'nullable|array',
            
            // General Info
            'client_contact_person' => 'required|string|max:255',
            'client_phone' => 'required|string|max:20',
            'client_email' => 'nullable|email',
            'project_description' => 'nullable|string',
            'objectives' => 'nullable|string',
            
            // Site Assessment
            'current_condition' => 'nullable|string',
            'existing_branding' => 'nullable|string',
            'access_logistics' => 'nullable|string',
            'parking_availability' => 'nullable|string',
            'size_accessibility' => 'nullable|string',
            'lifts' => 'nullable|string',
            'door_sizes' => 'nullable|string',
            'loading_areas' => 'nullable|string',
            'site_measurements' => 'nullable|string',
            'room_size' => 'nullable|string',
            'constraints' => 'nullable|string',
            'electrical_outlets' => 'nullable|string',
            'food_refreshment' => 'nullable|string',
            
            // Client Requirements
            'branding_preferences' => 'nullable|string',
            'material_preferred' => 'nullable|string',
            'color_scheme' => 'nullable|string',
            'brand_guidelines' => 'nullable|string',
            'special_instructions' => 'nullable|string',
            
            // Project Timeline
            'project_start_date' => 'nullable|date',
            'project_deadline' => 'nullable|date|after_or_equal:project_start_date',
            'milestones' => 'nullable|string',
            
            // Health and Safety
            'safety_conditions' => 'nullable|string',
            'potential_hazards' => 'nullable|string',
            'safety_required' => 'nullable|string',
            
            // Additional Notes
            'additional_notes' => 'nullable|string',
            'special_requests' => 'nullable|string',
            'action_items' => 'nullable|array',
            
            // Signatures
            'prepared_by' => 'required|string|max:255',
            'prepared_signature' => 'nullable|string',
            'prepared_date' => 'nullable|date',
            'client_approval_name' => 'nullable|string',
            'client_signature' => 'nullable|string',
            'client_approval_date' => 'nullable|date',
        ]);

        $validated['project_id'] = $project->id;
        $validated['attendees'] = $request->attendees ?? [];
        $validated['action_items'] = $request->action_items ?? [];
        
        $siteSurvey = SiteSurvey::create($validated);

        return redirect()->route('projects.site-survey.show', [$project, $siteSurvey])
            ->with('success', 'Site survey created successfully!');
    }

    public function show(Project $project, SiteSurvey $siteSurvey)
    {
        // Check if user can view this project
        $this->authorize('view', $project);

        return view('projects.site-survey.show', compact('project', 'siteSurvey'));
    }

    public function edit(Project $project, SiteSurvey $siteSurvey)
    {
        // Check if user can edit this project (not just view)
        $this->authorize('edit', $project);

        // Eager load the project manager and project officer relationships
        $project->load(['projectManager', 'projectOfficer']);
        
        // Get team members (project manager and project officer)
        $teamMembers = collect([$project->projectManager, $project->projectOfficer])
            ->filter()
            ->unique('id');
            
        return view('projects.site-survey.edit', [
            'project' => $project,
            'siteSurvey' => $siteSurvey,
            'teamMembers' => $teamMembers
        ]);
    }

    public function update(Request $request, Project $project, SiteSurvey $siteSurvey)
    {
        // Check if user can edit this project (not just view)
        $this->authorize('edit', $project);

        $validated = $request->validate([
            // Same validation as store
        ]);

        $validated['attendees'] = $request->attendees ?? [];
        $validated['action_items'] = $request->action_items ?? [];

        $siteSurvey->update($validated);

        return redirect()->route('projects.site-survey.show', [$project, $siteSurvey])
            ->with('success', 'Site survey updated successfully!');
    }

    public function destroy(Project $project, SiteSurvey $siteSurvey)
    {
        // Check if user can edit this project (not just view)
        $this->authorize('edit', $project);

        $siteSurvey->delete();
        return redirect()->route('projects.files.client-engagement', $project)
            ->with('success', 'Site survey deleted successfully!');
    }


    public function downloadSiteSurvey(Project $project)
    {
        // Check if user can view this project
        $this->authorize('view', $project);

        // Check if this project was converted from an enquiry
        $enquirySource = $project->enquirySource;
        
        if ($enquirySource) {
            // For converted projects, get data from enquiry source
            $siteSurvey = SiteSurvey::where('enquiry_id', $enquirySource->id)->firstOrFail();
        } else {
            // For regular projects, get data from project
            $siteSurvey = SiteSurvey::where('project_id', $project->id)->firstOrFail();
        }

        $data = [
            'project' => $project,
            'siteSurvey' => $siteSurvey,
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('projects.templates.site-survey', $data);

        $filename = 'site-survey-' . Str::slug($project->name) . '.pdf';
        return $pdf->download($filename);
    }

    public function printSiteSurvey(Project $project)
    {
        // Check if user can view this project
        $this->authorize('view', $project);

        // Check if this project was converted from an enquiry
        $enquirySource = $project->enquirySource;
        
        if ($enquirySource) {
            // For converted projects, get data from enquiry source
            $siteSurvey = SiteSurvey::where('enquiry_id', $enquirySource->id)->firstOrFail();
        } else {
            // For regular projects, get data from project
            $siteSurvey = SiteSurvey::where('project_id', $project->id)->firstOrFail();
        }

        $data = [
            'project' => $project,
            'siteSurvey' => $siteSurvey,
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('projects.templates.site-survey', $data);

        $filename = 'site-survey-' . Str::slug($project->name) . '.pdf';
        return $pdf->stream($filename);
    }
}