<?php

namespace App\Http\Controllers\projects;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\Phase;
use App\Models\Client;
use App\Models\Enquiry;
use App\Models\Task;
use App\Models\Deliverable;
use Carbon\Carbon;

class ProjectController extends Controller
{

    public function overview()
    {
        $projects = Project::with('phases')->get();

        $total = $projects->count();
        $active = $projects->filter(fn($p) => $p->progress < 100)->count();
        $completed = $projects->filter(fn($p) => $p->progress == 100)->count();
        $avgProgress = $projects->avg('progress');

        $topMoving = $projects->sortByDesc('progress')->take(3);
        //dd($completed);
        return view('projects.overview', compact('total', 'completed', 'active', 'avgProgress', 'topMoving', 'projects'));
    }

    public function index(Request $request)
    {
        // Get the currently authenticated user
        $user = auth()->user();

        // Get optional search and filter query parameters from the request
        $search = $request->input('search');
        $filter = $request->input('filter');

        // Start building the base query to fetch projects along with relationships
        $query = Project::with(['projectManager', 'projectOfficer', 'phases'])
            ->orderBy('created_at', 'desc'); // Most recent projects appear first

        // If the user is a project officer, show only their assigned projects
        if ($user->hasRole('po')) {
            $query->where('project_officer_id', $user->id);
        }

        // Keyword search across multiple fields
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('project_id', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('client_name', 'like', "%{$search}%")
                    ->orWhere('venue', 'like', "%{$search}%");
            });
        }

        // Paginate results and preserve query strings for search/filter
        $projects = $query->paginate(10)->withQueryString();
        $active = $projects->filter(fn($p) => $p->progress < 100)->count();

        // Fetch all users with the 'po' role to display in a filter or assignment dropdown
        $users = User::where('role', 'po')->get();

        // Fetch all clients — likely for filter or project creation UI
        $clients = Client::all();
        $enquiryprojects = Enquiry::all();

        // Add view type for the template
        $viewType = 'assigned';

        // Return the index Blade view with the queried data
        return view('projects.index', compact('projects', 'enquiryprojects', 'users', 'clients', 'viewType'));
    }

    /**
     * Display all projects (for POs to see all projects)
     */
    public function allProjects(Request $request)
    {
        // Get the currently authenticated user
        $user = auth()->user();

        // Start building the base query to fetch all projects
        $query = Project::with(['projectManager', 'projectOfficer', 'phases'])
            ->orderBy('created_at', 'desc');

        // Keyword search across multiple fields
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('project_id', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('client_name', 'like', "%{$search}%")
                    ->orWhere('venue', 'like', "%{$search}%");
            });
        }

        // Paginate results
        $projects = $query->paginate(8)->withQueryString();
        
        // Fetch all users with the 'po' role
        $users = User::where('role', 'po')->get(); 
        $clients = Client::all();
        $enquiryprojects = Enquiry::all();
        
        // Set view type to all
        $viewType = 'all';

        return view('projects.index', compact('projects', 'enquiryprojects', 'users', 'clients', 'viewType'));
    }


    
    public function store(Request $request)
    {
        // Only PMs can create projects
        // if (!auth()->user()->hasAnyRole(['super-admin', 'pm'])) {
        //     abort(403, 'Unauthorized');
        // }
    
        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'client_id' => 'required|exists:clients,ClientID',
            'venue' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'enquiry_id' => 'nullable|exists:enquiries,id', // 👈 Support optional enquiry
        ]);
    
        // Fetch client
        $client = Client::findOrFail($validated['client_id']);
        $clientName = $client->FullName;
    
        // Generate project ID
        $month = now()->format('m');
        $year = now()->format('y');
        $prefix = 'WNG' . $month . $year;
    
        $lastProject = Project::where('project_id', 'like', $prefix . '%')
            ->latest('created_at')
            ->first();
    
        $lastNumber = 0;
        if ($lastProject && preg_match('/WNG\d{4}(\d+)/', $lastProject->project_id, $matches)) {
            $lastNumber = (int) $matches[1];
        }
    
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $projectId = $prefix . $newNumber;
    
        // If enquiry is provided, pull extra data
        $deliverables = null;
        $followUpNotes = null;
        $contactPerson = null;
        $status = 'Initiated'; // default fallback
    
        if (!empty($validated['enquiry_id'])) {
            $enquiry = Enquiry::findOrFail($validated['enquiry_id']);
            $deliverables = $enquiry->project_deliverables;
            $followUpNotes = $enquiry->follow_up_notes;
            $contactPerson = $enquiry->contact_person;
            $status = $enquiry->status ?? 'Initiated';
        }
    
        // Create project
        $project = Project::create([
            'project_id' => $projectId,
            'name' => $validated['name'],
            'client_id' => $validated['client_id'],
            'client_name' => $clientName,
            'venue' => $validated['venue'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'project_manager_id' => auth()->id(),
            'project_officer_id' => null,
            'deliverables' => $deliverables,
            'follow_up_notes' => $followUpNotes,
            'contact_person' => $contactPerson,
            'status' => $status,
        ]);
    
        // Link project to enquiry
        if (isset($enquiry)) {
            $enquiry->converted_to_project_id = $project->id;
            $enquiry->save();
        }
    

        // Create default phases and their tasks
        $defaultPhases = config('project_phases');

        foreach ($defaultPhases as $phaseData) {
            $phase = Phase::create([
                'project_id' => $project->id,
                'title' => $phaseData['title'],
                'start_date' => now()->addDays($phaseData['offsetStart']),
                'end_date' => now()->addDays($phaseData['offsetEnd']),
                'description' => $phaseData['title'] . ' phase of the project',
                'status' => 'Pending',
            ]);

            // Create default tasks for the phase
            foreach ($phaseData['default_tasks'] as $taskData) {
                $task = Task::create([
                    'project_id' => $project->id,
                    'phase_id' => $phase->id,
                    'name' => $taskData['name'],
                    'description' => $taskData['description'],
                    'status' => 'Pending',
                    'assigned_to' => null,
                    'start_date' => $phase->start_date,
                    'end_date' => $phase->end_date,
                ]);
                // Create Deliverables for the task
                foreach ($taskData['deliverables'] as $deliverableText) {
                    Deliverable::create([
                        'task_id' => $task->id,
                        'item' => $deliverableText,
                        'done' => false,
                ]);
            }
        }
    }

    return redirect()->route('projects.index')->with('success', 'Project, phases, and default tasks created successfully!');
    }

    public function convertFromEnquiry(Enquiry $enquiry)
    {
        // Prevent double conversion
        if ($enquiry->converted_to_project_id) {
            return redirect()->back()->with('error', 'This enquiry has already been converted.');
        }

        // Find the client
        $client = Client::where('FullName', $enquiry->client_name)->first();

        if (!$client) {
            return redirect()->back()->with('error', 'Client not found. Please create the client first.');
        }

        // Generate Project ID
        $month = now()->format('m');
        $year = now()->format('y');
        $prefix = 'WNG' . $month . $year;

        $lastProject = Project::where('project_id', 'like', $prefix . '%')->latest('created_at')->first();
        $lastNumber = 0;

        if ($lastProject && preg_match('/WNG\d{4}(\d+)/', $lastProject->project_id, $matches)) {
            $lastNumber = (int)$matches[1];
        }

        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $projectId = $prefix . $newNumber;

        // Find the project officer by name if assigned_po is set
        $projectOfficerId = null;
        if (!empty($enquiry->assigned_po)) {
            $projectOfficer = User::where('name', $enquiry->assigned_po)->first();
            if ($projectOfficer) {
                $projectOfficerId = $projectOfficer->id;
            }
        }

        // Create the project
        $project = Project::create([
            'project_id' => $projectId,
            'name' => $enquiry->project_name ?? 'Project from Enquiry ' . $enquiry->id,
            'client_id' => $client->ClientID,
            'client_name' => $client->FullName,
            'venue' => $enquiry->venue ?? 'TBD',
            'start_date' => $enquiry->expected_delivery_date ?? now(),
            'end_date' => $enquiry->expected_delivery_date ?? now()->addDays(2),
            'project_manager_id' => auth()->id(),
            'project_officer_id' => $projectOfficerId,
            'deliverables' => $enquiry->project_deliverables,
            'follow_up_notes' => $enquiry->follow_up_notes,
            'contact_person' => $enquiry->contact_person,
            'status' => $enquiry->status ?? 'Initiated',
        ]);
        // Create default phases and their tasks
        $defaultPhases = config('project_phases');

        foreach ($defaultPhases as $phaseData) {
            $phase = Phase::create([
                'project_id' => $project->id,
                'title' => $phaseData['title'],
                'start_date' => now()->addDays($phaseData['offsetStart']),
                'end_date' => now()->addDays($phaseData['offsetEnd']),
                'description' => $phaseData['title'] . ' phase of the project',
                'status' => 'Pending',
            ]);

            // Create default tasks for the phase
            foreach ($phaseData['default_tasks'] as $taskData) {
                $task = Task::create([
                    'project_id' => $project->id,
                    'phase_id' => $phase->id,
                    'name' => $taskData['name'],
                    'description' => $taskData['description'],
                    'status' => 'Pending',
                    'assigned_to' => null,
                    'start_date' => $phase->start_date,
                    'end_date' => $phase->end_date,
                ]);
                // Create Deliverables for the task
                foreach ($taskData['deliverables'] as $deliverableText) {
                    Deliverable::create([
                        'task_id' => $task->id,
                        'item' => $deliverableText,
                        'done' => false,
                ]);
            }
        }
    }

        // Update the enquiry
        $enquiry->converted_to_project_id = $project->id;
        $enquiry->save();

        return redirect()->back()->with('success', 'Project created successfully from enquiry!');
    }



    public function assignProjectOfficer(Request $request, Project $project)
    {
        $validated = $request->validate([
            'project_officer_id' => 'required|exists:users,id',
        ]);

        $users = User::where('role', 'po')->get();

        $project->update(['project_officer_id' => $validated['project_officer_id']]);
        return redirect()->route('projects.index')->with('success', 'Officer assigned successfully!');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }
}
