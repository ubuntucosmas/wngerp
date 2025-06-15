<?php

namespace App\Http\Controllers\projects;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SiteSurvey;
use Illuminate\Http\Request;  

class SiteSurveyController extends Controller
{
    public function create(Project $project)
    {
        $siteSurvey = new SiteSurvey(); // Create empty instance for the form
        return view('projects.site-survey.create', compact('project', 'siteSurvey'));
    }

    public function store(Request $request, Project $project)
    {
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
            'prepared_date' => 'required|date',
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
        return view('projects.site-survey.show', compact('project', 'siteSurvey'));
    }

    public function edit(Project $project, SiteSurvey $siteSurvey)
    {
        $project->load('teamMembers');
        return view('projects.site-survey.edit', compact('project', 'siteSurvey'));
    }

    public function update(Request $request, Project $project, SiteSurvey $siteSurvey)
    {
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
        $siteSurvey->delete();
        return redirect()->route('projects.site-survey.show', [$project, $siteSurvey])
            ->with('success', 'Site survey deleted successfully!');
    }
}