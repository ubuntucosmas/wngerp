<?php

namespace App\Http\Controllers\projects;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
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
    use AuthorizesRequests;

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
        // Check if user can view projects
        $this->authorize('viewAny', Project::class);

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
        // Check if user can view projects
        $this->authorize('viewAny', Project::class);

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
        // Check if user can create projects
        $this->authorize('create', Project::class);
    
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
    
        return redirect()->route('projects.index')->with('success', 'Project created successfully!');
    }

    public function convertFromEnquiry(Enquiry $enquiry)
    {
        // This method is deprecated - conversion now happens automatically when all 4 phases are completed
        return redirect()->back()->with('error', 'Conversion happens automatically when all 4 phases are completed.');
    }



    public function assignProjectOfficer(Request $request, Project $project)
    {
        // Check if user can assign project officers
        $this->authorize('assignOfficer', $project);

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
        
        // Check if user can delete this project
        $this->authorize('delete', $project);
        
        // Check if this project was converted from an enquiry
        $enquiry = Enquiry::where('converted_to_project_id', $project->id)->first();
        
        if ($enquiry) {
            // Revert the project back to an enquiry
            $enquiry->update([
                'converted_to_project_id' => null,
            ]);
            
            // Transfer any project phases back to the enquiry
            $project->phases()->update([
                'phaseable_id' => $enquiry->id,
                'phaseable_type' => Enquiry::class,
            ]);
            
            // Transfer material lists back to the enquiry
            $project->materialLists()->update([
                'project_id' => null,
                'enquiry_id' => $enquiry->id,
            ]);
            
            // Delete the project
            $project->delete();
            
            return redirect()->route('enquiries.index')->with('success', 'Project deleted successfully and reverted back to enquiry.');
        } else {
            // If no associated enquiry, just delete the project normally
            $project->delete();
            return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
        }
    }

    /**
     * Show trashed projects
     */
    public function trashed(Request $request)
    {
        // Check if user can view trashed projects (using a dummy project for policy check)
        $this->authorize('viewAny', Project::class);

        $query = Project::onlyTrashed()->with(['projectManager', 'projectOfficer'])
            ->orderBy('deleted_at', 'desc');

        // Search functionality
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('project_id', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('client_name', 'like', "%{$search}%")
                    ->orWhere('venue', 'like', "%{$search}%");
            });
        }

        $projects = $query->paginate(10)->withQueryString();
        $users = User::where('role', 'po')->get();
        $clients = Client::all();
        $enquiryprojects = Enquiry::all();
        $viewType = 'trashed';

        return view('projects.index', compact('projects', 'enquiryprojects', 'users', 'clients', 'viewType'));
    }

    /**
     * Restore a soft deleted project
     */
    public function restore($id)
    {
        $project = Project::onlyTrashed()->findOrFail($id);
        
        // Check if user can restore this project
        $this->authorize('restore', $project);

        $project->restore();

        return redirect()->route('projects.trashed')->with('success', 'Project restored successfully.');
    }

    /**
     * Permanently delete a project
     */
    public function forceDelete($id)
    {
        $project = Project::onlyTrashed()->findOrFail($id);
        
        // Check if user can permanently delete this project
        $this->authorize('forceDelete', $project);
        
        // Check if this project was converted from an enquiry
        $enquiry = Enquiry::where('converted_to_project_id', $project->id)->first();
        
        if ($enquiry) {
            // Revert the project back to an enquiry before permanent deletion
            $enquiry->update([
                'converted_to_project_id' => null,
            ]);
            
            // Transfer any project phases back to the enquiry
            $project->phases()->update([
                'phaseable_id' => $enquiry->id,
                'phaseable_type' => Enquiry::class,
            ]);
            
            // Transfer material lists back to the enquiry
            $project->materialLists()->update([
                'project_id' => null,
                'enquiry_id' => $enquiry->id,
            ]);
        }
        
        // Permanently delete the project
        $project->forceDelete();
        
        return redirect()->route('projects.trashed')->with('success', 'Project permanently deleted.');
    }
}
