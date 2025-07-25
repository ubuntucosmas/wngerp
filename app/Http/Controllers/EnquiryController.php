<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Project;
use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EnquiryController extends Controller
{
    public function index()
    {
        $enquiries = Enquiry::orderBy('date_received', 'desc')->paginate(15);
        $statuses = ['Open', 'Quoted', 'Approved', 'Declined'];
        $users = User::where('role', 'po')->get();
        $clients = Client::all();
        return view('projects.Enquiry.index', compact('enquiries', 'statuses', 'users', 'clients'));
    }

    public function create()
    {
        $statuses = ['Open', 'Quoted', 'Approved', 'Declined'];
        return view('projects.Enquiry.create', compact('statuses'));
    }



    /**
     * Show enquiry files and phases (similar to project files)
     */
    public function files(Enquiry $enquiry)
    {
        // List of file types for the enquiry (same structure as project)
        $fileTypes = [
            ['name' => 'Enquiry Log', 'route' => route('enquiries.enquiry-log.create', $enquiry), 'template' => 'enquiry-log-template'],
            ['name' => 'Site Survey', 'route' => route('enquiries.site-survey.create', $enquiry), 'template' => 'site-survey'],
            ['name' => 'Design Assets', 'route' => '#', 'template' => 'mockups'], // Placeholder for now
            ['name' => 'Project Material List', 'route' => route('enquiries.material-list.index', $enquiry), 'template' => 'material'], // Placeholder for now
            ['name' => 'Budget', 'route' => route('enquiries.budget.index', $enquiry), 'template' => 'budget'],
            ['name' => 'Quotation', 'route' => route('enquiries.quotation.index', $enquiry), 'template' => 'quotes'],
        ];
        
        // Check if site survey exists for this enquiry
        $siteSurvey = $enquiry->siteSurveys()->first();
        if ($siteSurvey) {
            // Update the route to show the existing site survey
            $fileTypes[1]['route'] = route('enquiries.site-survey.show', [$enquiry, $siteSurvey]);
        }

        // Check if enquiry log exists for this enquiry
        $enquiryLog = $enquiry->enquiryLog()->first();
        if ($enquiryLog) {
            // Update the route to show the existing enquiry log
            $fileTypes[0]['route'] = route('enquiries.enquiry-log.show', [$enquiry, $enquiryLog]);
        }

        // Check if material list exists for this enquiry
        $materialList = $enquiry->materialLists()->first();
        if ($materialList) {
            // Update the route to show the existing material list
            $fileTypes[3]['route'] = route('enquiries.material-list.show', [$enquiry, $materialList]);
        }

        // Get phases for this enquiry (same as project)
        $phases = $enquiry->getDisplayablePhases();
        
        // Get phase completion data for summaries (same structure as project)
        $phaseCompletions = $this->getPhaseCompletions($enquiry);
        
        // Auto-update phase statuses based on completion (same as project)
        $this->updatePhaseStatuses($enquiry, $phaseCompletions);
        
        // Calculate progress (same as project)
        $totalPhases = $phases->count();
        $completed = $phases->where('status', 'Completed')->count();
        $inProgress = $phases->where('status', 'In Progress')->count();
        
        return view('projects.files.index', compact('enquiry', 'fileTypes', 'phaseCompletions', 'phases', 'totalPhases', 'completed', 'inProgress'));
    }

    /**
     * Get completion data for each phase (same structure as project)
     */
    private function getPhaseCompletions($enquiry)
    {
        $completions = [];

        // Client Engagement & Briefing
        $enquiryLog = $enquiry->enquiryLog()->first();
        $siteSurveys = $enquiry->siteSurveys;
        $completions['Client Engagement & Briefing'] = [
            'enquiry_log' => [
                'completed' => $enquiryLog ? true : false,
                'title' => 'Enquiry Log Form',
                'status' => $enquiryLog ? 'Completed' : 'Not Started',
                'date' => $enquiryLog ? $enquiryLog->created_at->format('M d, Y') : null,
                'details' => $enquiryLog ? [
                    'Client: ' . ($enquiryLog->client_name ?? 'N/A'),
                    'Contact: ' . ($enquiryLog->contact_person ?? 'N/A'),
                    'Status: ' . ($enquiryLog->status ?? 'N/A'),
                    'Assigned: ' . ($enquiryLog->assigned_to ?? 'N/A')
                ] : ['No enquiry log found']
            ],
            'site_survey' => [
                'completed' => $siteSurveys->count() > 0,
                'title' => 'Site Survey Form',
                'status' => $siteSurveys->count() > 0 ? 'Completed' : 'Not Started',
                'date' => $siteSurveys->count() > 0 ? $siteSurveys->first()->created_at->format('M d, Y') : null,
                'details' => $siteSurveys->count() > 0 ? [
                    'Location: ' . ($siteSurveys->first()->location ?? 'N/A'),
                    'Visit Date: ' . ($siteSurveys->first()->site_visit_date ? $siteSurveys->first()->site_visit_date->format('M d, Y') : 'N/A'),
                    'Project Manager: ' . ($siteSurveys->first()->project_manager ?? 'N/A'),
                    'Client Approval: ' . ($siteSurveys->first()->client_approval ? 'Yes' : 'No')
                ] : ['No site survey found']
            ]
        ];

        // Design & Concept Development
        $designAssets = $enquiry->designAssets;
        $completions['Design & Concept Development'] = [
            'design_assets' => [
                'completed' => $designAssets->count() > 0,
                'title' => 'Design Assets & Mockups',
                'status' => $designAssets->count() > 0 ? 'Completed' : 'Not Started',
                'date' => $designAssets->count() > 0 ? $designAssets->first()->created_at->format('M d, Y') : null,
                'details' => $designAssets->count() > 0 ? [
                    'Total Assets: ' . $designAssets->count(),
                    'Latest Asset: ' . $designAssets->first()->name,
                    'Uploaded By: ' . ($designAssets->first()->user->name ?? 'N/A')
                ] : ['No design assets found']
            ]
        ];

        // Project Material List
        $materialLists = $enquiry->materialLists;
        $completions['Project Material List'] = [
            'material_list' => [
                'completed' => $materialLists->count() > 0,
                'title' => 'Material List',
                'status' => $materialLists->count() > 0 ? 'Completed' : 'Not Started',
                'date' => $materialLists->count() > 0 ? $materialLists->first()->created_at->format('M d, Y') : null,
                'details' => $materialLists->count() > 0 ? [
                    'Total Lists: ' . $materialLists->count(),
                    'Latest List: ' . $materialLists->first()->name,
                    'Items Count: ' . $materialLists->first()->items->count()
                ] : ['No material lists found']
            ]
        ];

        // Budget & Quotation
        $budget = $enquiry->budgets()->first();
        $quote = $enquiry->quotes()->first();

        $completions['Budget & Quotation'] = [
            'budget' => [
                'completed' => $budget ? true : false,
                'title' => 'Project Budget',
                'status' => $budget ? 'Completed' : 'Not Started',
                'date' => $budget ? $budget->created_at->format('M d, Y') : null,
                'details' => $budget ? ['Budget Total: ' . number_format($budget->budget_total, 2)] : ['No budget found']
            ],
            'quotes' => [
                'completed' => $quote ? true : false,
                'title' => 'Quotation Documents',
                'status' => $quote ? 'Completed' : 'Not Started',
                'date' => $quote ? $quote->created_at->format('M d, Y') : null,
                'details' => $quote ? ['Quote Total: ' . number_format($quote->grand_total, 2)] : ['No quotation found']
            ]
        ];

        return $completions;
    }

    /**
     * Update phase statuses based on completion
     */
    private function updatePhaseStatuses($enquiry, $phaseCompletions)
    {
        foreach ($enquiry->phases as $phase) {
            $completions = $phaseCompletions[$phase->name];
            
            if (isset($completions)) {
                $totalItems = count($completions);
                $completedItems = collect($completions)->where('completed', true)->count();
                
                if ($completedItems === $totalItems && $totalItems > 0) {
                    $phase->update(['status' => 'Completed']);
                } elseif ($completedItems > 0) {
                    $phase->update(['status' => 'In Progress']);
                } else {
                    $phase->update(['status' => 'Not Started']);
                }
            }
        }
    }

    public function store(Request $request)
    {
        \Log::info('Enquiry store method called', $request->all());
        
        try {
            $validated = $request->validate([
                'date_received' => 'required|date',
                'expected_delivery_date' => 'nullable|date|after_or_equal:date_received',
                'client_name' => 'required|string|max:255',
                'project_name' => 'nullable|string|max:255',
                'project_deliverables' => 'nullable|string',
                'contact_person' => 'nullable|string|max:255',
                'status' => 'required|in:Open,Quoted,Approved,Declined',
                'assigned_po' => 'nullable|string|max:255',
                'follow_up_notes' => 'nullable|string',
                'project_id' => 'nullable|string|max:255',
                'venue' => 'nullable|string|max:255',
            ]);
            
            \Log::info('Validation passed', $validated);
    
            // Convert datetime-local to date format for database storage
            if ($request->has('date_received')) {
                $validated['date_received'] = Carbon::parse($request->input('date_received'))->format('Y-m-d');
            }
            
            if ($request->has('expected_delivery_date') && $request->input('expected_delivery_date')) {
                $validated['expected_delivery_date'] = Carbon::parse($request->input('expected_delivery_date'))->format('Y-m-d');
            }
            
            \Log::info('Processed data', $validated);
    
            $enquiry = Enquiry::create($validated);
            
            \Log::info('Enquiry created successfully', ['id' => $enquiry->id]);
    
            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Enquiry created successfully.']);
            }
    
            return redirect()->back()->with('success', 'Enquiry created successfully.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', ['errors' => $e->errors()]);
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Error creating enquiry', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }
    

    public function edit(Enquiry $enquiry)
    {
        $statuses = ['Open', 'Quoted', 'Approved', 'Declined'];
        return view('projects.Enquiry.edit', compact('enquiry', 'statuses'));
    }

    public function update(Request $request, Enquiry $enquiry)
    {
        $validated = $request->validate([
            'date_received' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:date_received',
            'client_name' => 'required|string|max:255',
            'project_name' => 'nullable|string|max:255',
            'project_deliverables' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'status' => 'required|in:Open,Quoted,Approved,Declined',
            'assigned_po' => 'nullable|string|max:255',
            'follow_up_notes' => 'nullable|string',
            'project_id' => 'nullable|string|max:255',
            'venue' => 'nullable|string|max:255',
        ]);

        // Convert datetime-local to date format for database storage
        if ($request->has('date_received')) {
            $validated['date_received'] = Carbon::parse($request->input('date_received'))->format('Y-m-d');
        }
        
        if ($request->has('expected_delivery_date') && $request->input('expected_delivery_date')) {
            $validated['expected_delivery_date'] = Carbon::parse($request->input('expected_delivery_date'))->format('Y-m-d');
        }

        $enquiry->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Enquiry updated successfully.']);
        }

        // Redirect back with success message
        return redirect()->back()->with('success', 'Enquiry updated successfully.');
    }

    public function destroy(Enquiry $enquiry)
    {
        $enquiry->delete();
        return redirect()->route('enquiries.index');
    }

    /**
     * Show client engagement files for enquiry
     */
    public function showClientEngagement(Enquiry $enquiry)
    {
        // Check if enquiry log exists for this enquiry
        $existingEnquiryLog = $enquiry->enquiryLog()->first();
        
        // Check if site survey exists for this enquiry
        $existingSiteSurvey = $enquiry->siteSurveys()->first();
        
        // For enquiries, we can show the enquiry details as the client engagement
        $files = [
            [
                'name' => 'Enquiry Log',
                'route' => $existingEnquiryLog 
                    ? route('enquiries.enquiry-log.show', [$enquiry, $existingEnquiryLog])
                    : route('enquiries.enquiry-log.create', $enquiry),
                'icon' => 'bi-journal-text',
                'description' => $existingEnquiryLog 
                    ? 'View existing enquiry log details'
                    : 'Fill out the enquiry log form with detailed information',
                'type' => $existingEnquiryLog ? 'Existing-Enquiry-Log' : 'Enquiry-Log-Form',
            ],
            [
                'name' => 'Site Survey',
                'route' => $existingSiteSurvey 
                    ? route('enquiries.site-survey.show', [$enquiry, $existingSiteSurvey])
                    : route('enquiries.site-survey.create', $enquiry),
                'icon' => 'bi-clipboard2-pulse',
                'description' => $existingSiteSurvey 
                    ? 'View existing site survey details'
                    : 'Complete the site survey form',
                'type' => $existingSiteSurvey ? 'Existing-Site-Survey' : 'Site-Survey-Form',
            ],
        ];

        return view('projects.files.client-engagement', compact('enquiry', 'files'));
    }

    /**
     * Show enquiry log create form for enquiry
     */
    public function createEnquiryLog(Enquiry $enquiry)
    {
        return view('projects.enquiry-log.create', compact('enquiry'));
    }

    /**
     * Show existing enquiry log for enquiry
     */
    public function showEnquiryLog(Enquiry $enquiry, \App\Models\EnquiryLog $enquiryLog)
    {
        return view('projects.enquiry-log.show', compact('enquiry', 'enquiryLog'));
    }

    /**
     * Store enquiry log for enquiry
     */
    public function storeEnquiryLog(Request $request, Enquiry $enquiry)
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
    
        $data['project_name'] = $enquiry->project_name;
        $data['project_scope_summary'] = json_encode(
            array_filter(array_map('trim', explode(',', $data['project_scope_summary'])))
        );
    
        // Create or update the enquiry log
        $enquiryLog = $enquiry->enquiryLog()->updateOrCreate([], $data);
    
        return redirect()->route('enquiries.files', $enquiry)
                         ->with('success', 'Enquiry Log created successfully.');
    }

    /**
     * Show site survey create form for enquiry
     */
    public function createSiteSurvey(Enquiry $enquiry)
    {
        // Create new site survey with default values from enquiry
        $siteSurvey = new \App\Models\SiteSurvey([
            'client_name' => $enquiry->client_name,
            'location' => $enquiry->venue,
            'project_manager' => $enquiry->assigned_po,
            'client_contact_person' => $enquiry->contact_person,
            'project_description' => $enquiry->project_name,
        ]);
        
        // Get team members for enquiries (use assigned PO and any other relevant users)
        $teamMembers = collect();
        if ($enquiry->assigned_po) {
            $teamMembers->push((object)['id' => 1, 'name' => $enquiry->assigned_po]);
        }
        
        // Add some common team members for enquiries
        $commonTeamMembers = [
            ['id' => 2, 'name' => 'Project Manager'],
            ['id' => 3, 'name' => 'Site Supervisor'],
            ['id' => 4, 'name' => 'Technical Lead'],
        ];
        
        foreach ($commonTeamMembers as $member) {
            $teamMembers->push((object)$member);
        }
        
        return view('projects.site-survey.create', [
            'enquiry' => $enquiry,
            'siteSurvey' => $siteSurvey,
            'teamMembers' => $teamMembers
        ]);
    }

    /**
     * Show existing site survey for enquiry
     */
    public function showSiteSurvey(Enquiry $enquiry, \App\Models\SiteSurvey $siteSurvey)
    {
        // Get team members for enquiries (use assigned PO and any other relevant users)
        $teamMembers = collect();
        if ($enquiry->assigned_po) {
            $teamMembers->push((object)['id' => 1, 'name' => $enquiry->assigned_po]);
        }
        
        // Add some common team members for enquiries
        $commonTeamMembers = [
            ['id' => 2, 'name' => 'Project Manager'],
            ['id' => 3, 'name' => 'Site Supervisor'],
            ['id' => 4, 'name' => 'Technical Lead'],
        ];
        
        foreach ($commonTeamMembers as $member) {
            $teamMembers->push((object)$member);
        }
        
        return view('projects.site-survey.show', compact('enquiry', 'siteSurvey', 'teamMembers'));
    }

    /**
     * Store site survey for enquiry
     */
    public function storeSiteSurvey(Request $request, Enquiry $enquiry)
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
            'prepared_date' => 'nullable|date',
            'client_approval_name' => 'nullable|string',
            'client_signature' => 'nullable|string',
            'client_approval_date' => 'nullable|date',
        ]);

        $validated['attendees'] = $request->attendees ?? [];
        $validated['action_items'] = $request->action_items ?? [];

        $enquiry->siteSurveys()->create($validated);
        $siteSurvey = $enquiry->siteSurveys()->latest()->first();

        return redirect()->route('enquiries.site-survey.show', [$enquiry, $siteSurvey])
            ->with('success', 'Site survey created successfully!');
    }

    /**
     * Show design concept files for enquiry
     */
    public function showDesignConcept(Enquiry $enquiry)
    {
        // For enquiries, we can show design concept files
        return view('projects.files.design-concept', compact('enquiry'));
    }

    /**
     * Show quotation files for enquiry
     */
    public function showQuotation(Enquiry $enquiry)
    {
        $project = $enquiry->project; // Get the associated project
        return view('projects.files.quotation', compact('enquiry', 'project'));
    }

    /**
     * Placeholder methods for enquiry functionality (coming soon)
     */
    public function showSetup(Enquiry $enquiry)
    {
        return view('projects.files.setup', compact('enquiry'));
    }

    public function showArchival(Enquiry $enquiry)
    {
        return view('projects.files.archival', compact('enquiry'));
    }

    public function materialList(Enquiry $enquiry)
    {
        $project = $enquiry->project; // Get the associated project
        return view('projects.material-list.index', compact('enquiry', 'project'));
    }

    public function showMaterialList(Enquiry $enquiry, MaterialList $materialList)
    {
        $project = $enquiry->project; // Get the associated project
        $materialLists = $enquiry->materialLists()->get();
        return view('projects.material-list.show', compact('enquiry', 'materialList', 'project'));
    }

    public function logistics(Enquiry $enquiry)
    {
        return view('projects.logistics.index', compact('enquiry'));
    }

    public function quotation(Enquiry $enquiry)
    {
        $quotes = $enquiry->quotes()->latest()->paginate(10); // Fetch quotes for the enquiry
        return view('projects.quotes.index', compact('enquiry', 'quotes'));
    }

    public function handover(Enquiry $enquiry)
    {
        return view('projects.handover.index', compact('enquiry'));
    }

    public function setDownReturn(Enquiry $enquiry)
    {
        return view('projects.setdown.index', compact('enquiry'));
    }

    public function production(Enquiry $enquiry)
    {
        return view('projects.production.index', compact('enquiry'));
    }

    public function editSiteSurvey(Enquiry $enquiry, \App\Models\SiteSurvey $siteSurvey)
    {
        // Get team members for enquiries (use assigned PO and any other relevant users)
        $teamMembers = collect();
        if ($enquiry->assigned_po) {
            $teamMembers->push((object)['id' => 1, 'name' => $enquiry->assigned_po]);
        }
        $commonTeamMembers = [
            ['id' => 2, 'name' => 'Project Manager'],
            ['id' => 3, 'name' => 'Site Supervisor'],
            ['id' => 4, 'name' => 'Technical Lead'],
        ];
        foreach ($commonTeamMembers as $member) {
            $teamMembers->push((object)$member);
        }
        return view('projects.site-survey.edit', [
            'enquiry' => $enquiry,
            'siteSurvey' => $siteSurvey,
            'teamMembers' => $teamMembers
        ]);
    }

    public function updateSiteSurvey(Request $request, Enquiry $enquiry, \App\Models\SiteSurvey $siteSurvey)
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
            'prepared_date' => 'nullable|date',
            'client_approval_name' => 'nullable|string',
            'client_signature' => 'nullable|string',
            'client_approval_date' => 'nullable|date',
        ]);
        $validated['attendees'] = $request->attendees ?? [];
        $validated['action_items'] = $request->action_items ?? [];
        $siteSurvey->update($validated);
        return redirect()->route('enquiries.site-survey.show', [$enquiry, $siteSurvey])
            ->with('success', 'Site survey updated successfully!');
    }

    public function destroySiteSurvey(Enquiry $enquiry, \App\Models\SiteSurvey $siteSurvey)
    {
        $siteSurvey->delete();
        return redirect()->route('enquiries.files', $enquiry)
            ->with('success', 'Site survey deleted successfully!');
    }

    public function showMockups(Enquiry $enquiry)
    {
        $designAssets = $enquiry->designAssets()->with('user')->orderBy('created_at', 'desc')->get();
        return view('projects.files.mockups', compact('enquiry', 'designAssets'));
    }

    /**
     * Show form to edit enquiry log for enquiry
     */
    public function editEnquiryLog(Enquiry $enquiry, \App\Models\EnquiryLog $enquiryLog)
    {
        return view('projects.enquiry-log.edit', compact('enquiry', 'enquiryLog'));
    }

    /**
     * Update enquiry log for enquiry
     */
    public function updateEnquiryLog(Request $request, Enquiry $enquiry, \App\Models\EnquiryLog $enquiryLog)
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
    
        $data['project_scope_summary'] = json_encode(
            array_filter(array_map('trim', explode(',', $data['project_scope_summary'])))
        );
    
        $enquiryLog->update($data);
    
        return redirect()->route('enquiries.enquiry-log.show', [$enquiry, $enquiryLog])
                         ->with('success', 'Enquiry Log updated successfully.');
    }

    /**
     * Destroy enquiry log for enquiry
     */
    public function destroyEnquiryLog(Enquiry $enquiry, \App\Models\EnquiryLog $enquiryLog)
    {
        $enquiryLog->delete();
        return redirect()->route('enquiries.files', $enquiry)
                         ->with('success', 'Enquiry Log deleted successfully.');
    }

    /**
     * Download enquiry log for enquiry
     */
    public function downloadEnquiryLog(Enquiry $enquiry, \App\Models\EnquiryLog $enquiryLog)
    {
        $data = [
            'enquiry' => $enquiry,
            'enquiryLog' => $enquiryLog,
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('projects.templates.enquiry-log', $data);
        $filename = 'enquiry-log-enquiry-' . $enquiry->id . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Print enquiry log for enquiry
     */
    public function printEnquiryLog(Enquiry $enquiry, \App\Models\EnquiryLog $enquiryLog)
    {
        $data = [
            'enquiry' => $enquiry,
            'enquiryLog' => $enquiryLog,
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('projects.templates.enquiry-log', $data);
        $filename = 'enquiry-log-enquiry-' . $enquiry->id . '.pdf';
        return $pdf->stream($filename);
    }

    /**
     * Export enquiry log to Excel
     */
    public function exportEnquiryLog(Enquiry $enquiry, \App\Models\EnquiryLog $enquiryLog)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\EnquiryLogExport($enquiry, $enquiryLog),
            'enquiry-log-enquiry-' . $enquiry->id . '.xlsx'
        );
    }

    /**
     * Download site survey for enquiry
     */
    public function downloadSiteSurvey(Enquiry $enquiry, \App\Models\SiteSurvey $siteSurvey)
    {
        $data = [
            'enquiry' => $enquiry,
            'siteSurvey' => $siteSurvey,
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('projects.templates.site-survey', $data);
        $filename = 'site-survey-enquiry-' . $enquiry->id . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Print site survey for enquiry
     */
    public function printSiteSurvey(Enquiry $enquiry, \App\Models\SiteSurvey $siteSurvey)
    {
        $data = [
            'enquiry' => $enquiry,
            'siteSurvey' => $siteSurvey,
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('projects.templates.site-survey', $data);
        $filename = 'site-survey-enquiry-' . $enquiry->id . '.pdf';
        return $pdf->stream($filename);
    }

    /**
     * Convert enquiry to project when all first 4 phases are completed
     */
    public function convertToProject(Enquiry $enquiry)
    {
        // Check if enquiry is already converted
        if ($enquiry->converted_to_project_id) {
            return back()->with('error', 'This enquiry has already been converted to a project.');
        }

        // Check if all first 4 phases are completed
        if (!$enquiry->areFirstFourPhasesCompleted()) {
            return back()->with('error', 'You can only convert an enquiry to a project when all first 4 phases are completed.');
        }

        try {
            $project = $enquiry->convertToProject();

            if ($project) {
                return redirect()->route('projects.show', $project->id)
                    ->with('success', 'Enquiry converted to project successfully! Project ID: ' . $project->project_id);
            } else {
                return back()->with('error', 'Conversion failed. Please check the enquiry details and try again.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred during conversion: ' . $e->getMessage());
        }
    }
}
