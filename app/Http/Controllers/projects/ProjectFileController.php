<?php

namespace App\Http\Controllers\projects;
use App\Http\Controllers\Controller;

use App\Models\Project;
use App\Models\Enquiry;
use Illuminate\Http\Request;
use App\Models\ProjectBudget;


class ProjectFileController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['role:pm|po']);
    // }
    
    public function index(Project $project)
    {
        // List of file types for the project
        $fileTypes = [
            ['name' => 'Enquiry', 'route' => route('projects.enquiry-log.show', $project), 'template' => 'enquiry-log-template'],
            ['name' => 'Site Survey', 'route' => route('projects.site-survey.create', $project), 'template' => 'site-survey'],
            ['name' => 'Design Assets', 'route' => route('projects.files.mockups', $project), 'template' => 'mockups'],
           // ['name' => 'Project Material List', 'route' => route('material.index', $project), 'template' => 'material'],
            ['name' => 'Budget', 'route' => route('budget.index', $project), 'template' => 'budget'],
            ['name' => 'Quotation', 'route' => route('quotes.index', $project), 'template' => 'quotes'],
            ['name' => 'Booking Order', 'route' => route('projects.logistics.booking-orders.index', $project), 'template' => 'booking-order-template'],
            ['name' => 'Close-Out Report', 'route' => route('projects.logistics.booking-orders.index', $project), 'template' => 'booking-order-template'],
            
        ];
        
        // Check if site survey exists for this project
        $siteSurvey = \App\Models\SiteSurvey::where('project_id', $project->id)->first();
        if ($siteSurvey) {
            // Update the route to show the existing site survey
            $fileTypes[1]['route'] = route('projects.site-survey.show', [$project, $siteSurvey]);
        }

        // Get phases for this project
        $phases = $project->getDisplayablePhases();
        
        // Get phase completion data for summaries
        $phaseCompletions = $this->getPhaseCompletions($project);
        
        // Auto-update phase statuses based on completion
        $this->updatePhaseStatuses($project, $phaseCompletions);
        
        // Calculate progress
        $totalPhases = $phases->count();
        $completed = $phases->where('status', 'Completed')->count();
        $inProgress = $phases->where('status', 'In Progress')->count();
        
        return view('projects.files.index', compact('project', 'fileTypes', 'phaseCompletions', 'phases', 'totalPhases', 'completed', 'inProgress'));
    }

    /**
     * Get completion data for each phase
     */
    private function getPhaseCompletions(Project $project)
    {
        $completions = [];
        
        // Check if this project was converted from an enquiry
        $enquirySource = $project->enquirySource;

        // Client Engagement & Briefing
        if ($enquirySource) {
            // For converted projects, get data from enquiry source
            $enquiryLog = \App\Models\EnquiryLog::where('enquiry_id', $enquirySource->id)->first();
            $siteSurveys = \App\Models\SiteSurvey::where('enquiry_id', $enquirySource->id)->get();
        } else {
            // For regular projects, get data from project
            $enquiryLog = $project->enquiryLog;
            $siteSurveys = $project->siteSurveys;
        }
        
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
        if ($enquirySource) {
            // For converted projects, get data from enquiry source
            $designAssets = \App\Models\DesignAsset::where('enquiry_id', $enquirySource->id)->get();
        } else {
            // For regular projects, get data from project
            $designAssets = $project->designAssets;
        }
        
        $completions['Design & Concept Development'] = [
            'design_assets' => [
                'completed' => $designAssets->count() > 0,
                'title' => 'Design Assets & Mockups',
                'status' => $designAssets->count() > 0 ? 'Completed (' . $designAssets->count() . ' assets)' : 'Not Started',
                'date' => $designAssets->count() > 0 ? $designAssets->first()->created_at->format('M d, Y') : null,
                'details' => $designAssets->count() > 0 ? [
                    'Total Assets: ' . $designAssets->count(),
                    'Latest Asset: ' . $designAssets->first()->name,
                    'Uploaded by: ' . ($designAssets->first()->user->name ?? 'N/A'),
                    'Last Updated: ' . $designAssets->sortByDesc('updated_at')->first()->updated_at->format('M d, Y')
                ] : ['No design assets found']
            ]
        ];

        // Project Material List
        if ($enquirySource) {
            // For converted projects, get data from enquiry source
            $materialLists = \App\Models\MaterialList::where('enquiry_id', $enquirySource->id)->get();
        } else {
            // For regular projects, get data from project
            $materialLists = $project->materialLists;
        }
        
        $completions['Project Material List'] = [
            'material_list' => [
                'completed' => $materialLists->count() > 0,
                'title' => 'Material List',
                'status' => $materialLists->count() > 0 ? 'Completed (' . $materialLists->count() . ' lists)' : 'Not Started',
                'date' => $materialLists->count() > 0 ? $materialLists->first()->created_at->format('M d, Y') : null,
                'details' => $materialLists->count() > 0 ? [
                    'Total Lists: ' . $materialLists->count(),
                    'Latest List: ' . $materialLists->sortByDesc('created_at')->first()->date_range,
                    'Approved By: ' . ($materialLists->sortByDesc('created_at')->first()->approved_by ?? 'Pending'),
                    'Items Count: ' . $materialLists->sortByDesc('created_at')->first()->item_counts['production_items'] . ' production, ' . 
                                   $materialLists->sortByDesc('created_at')->first()->item_counts['materials_hire'] . ' hire, ' . 
                                   $materialLists->sortByDesc('created_at')->first()->item_counts['labour_items'] . ' labour'
                ] : ['No material lists found']
            ]
        ];

        // LOGISTICS
        $loadingSheets = $project->loadingSheets ?? collect();
        $bookingOrders = $project->bookingOrders ?? collect();
        $completions['Logistics'] = [
            'loading_sheet' => [
                'completed' => $loadingSheets->count() > 0,
                'title' => 'Loading Sheet',
                'status' => $loadingSheets->count() > 0 ? 'Completed (' . $loadingSheets->count() . ' sheets)' : 'Not Started',
                'date' => $loadingSheets->count() > 0 ? $loadingSheets->first()->created_at->format('M d, Y') : null,
                'details' => $loadingSheets->count() > 0 ? [
                    'Total Sheets: ' . $loadingSheets->count(),
                    'Latest Sheet: ' . ($loadingSheets->sortByDesc('created_at')->first()->vehicle_number ?? 'N/A'),
                    'Status: ' . ucfirst($loadingSheets->sortByDesc('created_at')->first()->status ?? 'draft')
                ] : ['No loading sheets found']
                ],
            'booking_order' => [
                'completed' => $bookingOrders->count() > 0,
                'title' => 'Booking Order',
                'status' => $bookingOrders->count() > 0 ? 'Completed (' . $bookingOrders->count() . ' orders)' : 'Not Started',
                'date' => $bookingOrders->count() > 0 ? $bookingOrders->first()->created_at->format('M d, Y') : null,
                'details' => $bookingOrders->count() > 0 ? [
                    'Total Orders: ' . $bookingOrders->count(),
                    'Latest Order: ' . ($bookingOrders->sortByDesc('created_at')->first()->order_number ?? 'N/A'),
                    'Status: ' . ucfirst($bookingOrders->sortByDesc('created_at')->first()->status ?? 'draft')
                ] : ['No booking orders found']
            ]
        ];

        // Budget & Quotation
        if ($enquirySource) {
            // For converted projects, get data from enquiry source
            $budgets = \App\Models\ProjectBudget::where('enquiry_id', $enquirySource->id)->get();
            $quotes = \App\Models\Quote::where('enquiry_id', $enquirySource->id)->get();
        } else {
            // For regular projects, get data from project
            $budgets = \App\Models\ProjectBudget::where('project_id', $project->id)->get();
            $quotes = $project->quotes;
        }
        
        $completions['Budget & Quotation'] = [
            'budget' => [
                'completed' => $budgets->count() > 0,
                'title' => 'Project Budget',
                'status' => $budgets->count() > 0 ? 'Completed (' . $budgets->count() . ' budgets)' : 'Not Started',
                'date' => $budgets->count() > 0 ? $budgets->first()->created_at->format('M d, Y') : null,
                'details' => $budgets->count() > 0 ? [
                    'Total Budgets: ' . $budgets->count(),
                    'Latest Budget: KES ' . number_format($budgets->sortByDesc('created_at')->first()->budget_total, 2),
                    'Status: ' . ucfirst($budgets->sortByDesc('created_at')->first()->status ?? 'draft'),
                    'Approved By: ' . ($budgets->sortByDesc('created_at')->first()->approved_by ?? 'Pending')
                ] : ['No budgets found']
            ],
            'quotes' => [
                'completed' => $quotes->count() > 0,
                'title' => 'Quotation Documents',
                'status' => $quotes->count() > 0 ? 'Completed (' . $quotes->count() . ' quotes)' : 'Not Started',
                'date' => $quotes->count() > 0 ? $quotes->first()->created_at->format('M d, Y') : null,
                'details' => $quotes->count() > 0 ? [
                    'Total Quotes: ' . $quotes->count(),
                    'Latest Quote: ' . ($quotes->sortByDesc('created_at')->first()->quote_number ?? 'N/A'),
                    'Amount: KES ' . number_format($quotes->sortByDesc('created_at')->first()->total_amount ?? 0, 2),
                    'Status: ' . ucfirst($quotes->sortByDesc('created_at')->first()->status ?? 'draft')
                ] : ['No quotes found']
            ]
        ];

        // Event Setup & Execution
        $setupReports = $project->setupReports;
        $completions['Event Setup & Execution'] = [
            'setup_reports' => [
                'completed' => $setupReports->count() > 0,
                'title' => 'Setup Reports',
                'status' => $setupReports->count() > 0 ? 'Completed (' . $setupReports->count() . ' reports)' : 'Not Started',
                'date' => $setupReports->count() > 0 ? $setupReports->first()->created_at->format('M d, Y') : null,
                'details' => $setupReports->count() > 0 ? [
                    'Total Reports: ' . $setupReports->count(),
                    'Latest Report: ' . ($setupReports->sortByDesc('created_at')->first()->report_title ?? 'N/A'),
                    'Setup Date: ' . ($setupReports->sortByDesc('created_at')->first()->setup_date ? $setupReports->sortByDesc('created_at')->first()->setup_date->format('M d, Y') : 'N/A'),
                    'Team Size: ' . ($setupReports->sortByDesc('created_at')->first()->team_size ?? 'N/A')
                ] : ['No setup reports found']
            ]
        ];

        // Client Handover
        $handoverReports = $project->handoverReports;
        $completions['Client Handover'] = [
            'handover_reports' => [
                'completed' => $handoverReports->count() > 0,
                'title' => 'Handover Reports',
                'status' => $handoverReports->count() > 0 ? 'Completed (' . $handoverReports->count() . ' reports)' : 'Not Started',
                'date' => $handoverReports->count() > 0 ? $handoverReports->first()->created_at->format('M d, Y') : null,
                'details' => $handoverReports->count() > 0 ? [
                    'Total Reports: ' . $handoverReports->count(),
                    'Latest Report: ' . ($handoverReports->sortByDesc('created_at')->first()->report_title ?? 'N/A'),
                    'Handover Date: ' . ($handoverReports->sortByDesc('created_at')->first()->handover_date ? $handoverReports->sortByDesc('created_at')->first()->handover_date->format('M d, Y') : 'N/A'),
                    'Client Signature: ' . ($handoverReports->sortByDesc('created_at')->first()->client_signature ? 'Yes' : 'No')
                ] : ['No handover reports found']
            ]
        ];

        // Set Down & Return
        $setDownReturns = $project->setDownReturns;
        $completions['Set Down & Return'] = [
            'set_down_returns' => [
                'completed' => $setDownReturns->count() > 0,
                'title' => 'Set Down & Return Reports',
                'status' => $setDownReturns->count() > 0 ? 'Completed (' . $setDownReturns->count() . ' reports)' : 'Not Started',
                'date' => $setDownReturns->count() > 0 ? $setDownReturns->first()->created_at->format('M d, Y') : null,
                'details' => $setDownReturns->count() > 0 ? [
                    'Total Reports: ' . $setDownReturns->count(),
                    'Latest Report: ' . ($setDownReturns->sortByDesc('created_at')->first()->report_title ?? 'N/A'),
                    'Set Down Date: ' . ($setDownReturns->sortByDesc('created_at')->first()->set_down_date ? $setDownReturns->sortByDesc('created_at')->first()->set_down_date->format('M d, Y') : 'N/A'),
                    'Items Returned: ' . ($setDownReturns->sortByDesc('created_at')->first()->items_returned_count ?? 'N/A')
                ] : ['No set down reports found']
            ]
        ];

        // Production
        $productions = \App\Models\Production::where('project_id', $project->id)->get();
        $completions['Production'] = [
            'production_records' => [
                'completed' => $productions->count() > 0,
                'title' => 'Production Records',
                'status' => $productions->count() > 0 ? 'Completed (' . $productions->count() . ' records)' : 'Not Started',
                'date' => $productions->count() > 0 ? $productions->first()->created_at->format('M d, Y') : null,
                'details' => $productions->count() > 0 ? [
                    'Total Records: ' . $productions->count(),
                    'Latest Record: ' . ($productions->sortByDesc('created_at')->first()->job_title ?? 'N/A'),
                    'Status: ' . ucfirst($productions->sortByDesc('created_at')->first()->status ?? 'pending'),
                    'Assigned To: ' . ($productions->sortByDesc('created_at')->first()->assigned_to ?? 'N/A')
                ] : ['No production records found']
            ]
        ];

        // Archival & Reporting
        $archivalReports = $project->archivalReports;
        $completions['Archival & Reporting'] = [
            'archival_reports' => [
                'completed' => $archivalReports->count() > 0,
                'title' => 'Archival Reports',
                'status' => $archivalReports->count() > 0 ? 'Completed (' . $archivalReports->count() . ' reports)' : 'Not Started',
                'date' => $archivalReports->count() > 0 ? $archivalReports->first()->created_at->format('M d, Y') : null,
                'details' => $archivalReports->count() > 0 ? [
                    'Total Reports: ' . $archivalReports->count(),
                    'Latest Report: ' . ($archivalReports->sortByDesc('created_at')->first()->report_title ?? 'N/A'),
                    'Archive Date: ' . ($archivalReports->sortByDesc('created_at')->first()->archive_date ? $archivalReports->sortByDesc('created_at')->first()->archive_date->format('M d, Y') : 'N/A'),
                    'Project Status: ' . ucfirst($archivalReports->sortByDesc('created_at')->first()->project_status ?? 'N/A')
                ] : ['No archival reports found']
            ]
        ];

        return $completions;
    }

    /**
     * Automatically update phase status based on completion progress
     */
    private function updatePhaseStatuses(Project $project, $completions)
    {
        foreach ($completions as $phaseName => $phaseData) {
            $phase = $project->phases()->where('name', $phaseName)->first();
            if (!$phase) continue;

            $totalItems = count($phaseData);
            $completedItems = 0;

            foreach ($phaseData as $item) {
                if ($item['completed']) {
                    $completedItems++;
                }
            }

            $completionPercentage = $totalItems > 0 ? ($completedItems / $totalItems) * 100 : 0;

            // Determine new status based on completion
            $newStatus = 'Not Started';
            if ($completionPercentage >= 100) {
                $newStatus = 'Completed';
            } elseif ($completionPercentage >= 25) {
                $newStatus = 'In Progress';
            }

            // Only update if status has changed
            if ($phase->status !== $newStatus) {
                $phase->update(['status' => $newStatus]);
            }
        }
    }

    /**
     * Display mockup files for the project
     */
    public function showMockups(Project $project = null, Enquiry $enquiry = null)
    {
        if ($enquiry) {
            $designAssets = \App\Models\DesignAsset::where('enquiry_id', $enquiry->id)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();
            return view('projects.files.mockups', compact('enquiry', 'designAssets'));
        } else {
            // Check if this project was converted from an enquiry
            $enquirySource = $project->enquirySource;
            
            if ($enquirySource) {
                // For converted projects, get data from enquiry source
                $designAssets = \App\Models\DesignAsset::where('enquiry_id', $enquirySource->id)
                    ->with('user')
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                // For regular projects, get data from project
                $designAssets = \App\Models\DesignAsset::where('project_id', $project->id)
                    ->with('user')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
            return view('projects.files.mockups', compact('project', 'designAssets'));
        }
    }

    /**
     * Store a newly created design asset in storage.
     */
    public function storeDesignAsset(Request $request, Project $project = null, Enquiry $enquiry = null)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'file_url' => 'required|url|max:1000',
            'description' => 'nullable|string',
        ]);

        $url = parse_url($validated['file_url']);
        $path = trim($url['path'] ?? '', '/');
        $filename = basename($path);

        $assetData = [
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'file_name' => $filename,
            'file_url' => $validated['file_url'],
            'description' => $validated['description'] ?? null,
        ];

        if ($enquiry) {
            $asset = $enquiry->designAssets()->create($assetData);
            return redirect()->route('enquiries.files.mockups', $enquiry)->with('success', 'Design asset added successfully');
        } else {
            // Check if this project was converted from an enquiry
            $enquirySource = $project->enquirySource;
            
            if ($enquirySource) {
                // For converted projects, store under the enquiry source
                $assetData['enquiry_id'] = $enquirySource->id;
                $asset = \App\Models\DesignAsset::create($assetData);
            } else {
                // For regular projects, store under the project
                $asset = $project->designAssets()->create($assetData);
            }
            return redirect()->route('projects.files.mockups', $project)->with('success', 'Design asset added successfully');
        }
    }

    public function updateDesignAsset(Request $request, Project $project = null, Enquiry $enquiry = null, $designAssetId)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'file_url' => 'required|url|max:1000',
            'description' => 'nullable|string',
        ]);

        $url = parse_url($validated['file_url']);
        $path = trim($url['path'] ?? '', '/');
        $filename = basename($path);

        $assetData = [
            'name' => $validated['name'],
            'file_name' => $filename,
            'file_url' => $validated['file_url'],
            'description' => $validated['description'] ?? null,
        ];

        if ($enquiry) {
            $asset = $enquiry->designAssets()->findOrFail($designAssetId);
            $asset->update($assetData);
            return redirect()->route('enquiries.files.mockups', $enquiry)->with('success', 'Design asset updated successfully');
        } else {
            // Check if this project was converted from an enquiry
            $enquirySource = $project->enquirySource;
            
            if ($enquirySource) {
                // For converted projects, find asset from enquiry source
                $asset = \App\Models\DesignAsset::where('enquiry_id', $enquirySource->id)
                    ->findOrFail($designAssetId);
            } else {
                // For regular projects, find asset from project
                $asset = $project->designAssets()->findOrFail($designAssetId);
            }
            $asset->update($assetData);
            return redirect()->route('projects.files.mockups', $project)->with('success', 'Design asset updated successfully');
        }
    }

    public function destroyDesignAsset(Project $project = null, Enquiry $enquiry = null, $designAssetId)
    {
        if ($enquiry) {
            $asset = $enquiry->designAssets()->findOrFail($designAssetId);
            $asset->delete();
            return redirect()->route('enquiries.files.mockups', $enquiry)->with('success', 'Design asset deleted successfully');
        } else {
            // Check if this project was converted from an enquiry
            $enquirySource = $project->enquirySource;
            
            if ($enquirySource) {
                // For converted projects, find asset from enquiry source
                $asset = \App\Models\DesignAsset::where('enquiry_id', $enquirySource->id)
                    ->findOrFail($designAssetId);
            } else {
                // For regular projects, find asset from project
                $asset = $project->designAssets()->findOrFail($designAssetId);
            }
            $asset->delete();
            return redirect()->route('projects.files.mockups', $project)->with('success', 'Design asset deleted successfully');
        }
    }


    /**
     * Display client engagement files for the project
     */
    public function showClientEngagement(Project $project)
    {
        // Check if this project was converted from an enquiry
        $enquirySource = $project->enquirySource;
        
        if ($enquirySource) {
            // For converted projects, get data from enquiry source
            $existingProjectBrief = \App\Models\EnquiryLog::where('enquiry_id', $enquirySource->id)->first();
            $siteSurvey = \App\Models\SiteSurvey::where('enquiry_id', $enquirySource->id)->first();
        } else {
            // For regular projects, get data from project
            $existingProjectBrief = \App\Models\EnquiryLog::where('project_id', $project->id)->first();
            $siteSurvey = \App\Models\SiteSurvey::where('project_id', $project->id)->first();
        }
        
        $files = [
            [
                'name' => 'Project Brief',
                'route' => $existingProjectBrief 
                    ? route('projects.enquiry-log.show', [$project, $existingProjectBrief])
                    : route('projects.enquiry-log.create', $project),
                'icon' => 'bi-journal-text',
                'description' => $existingProjectBrief 
                    ? 'View existing project brief details'
                    : 'Create new project brief',
                'type' => $existingProjectBrief ? 'Existing-Project-Brief' : 'Project-Brief-Form',
            ],
            [
                'name' => 'Site Survey',
                'route' => $siteSurvey 
                    ? route('projects.site-survey.show', [$project, $siteSurvey])
                    : route('projects.site-survey.create', $project),
                'icon' => 'bi-clipboard2-pulse',
                'description' => $siteSurvey 
                    ? 'View existing site survey details'
                    : 'Create new site survey',
                'type' => $siteSurvey ? 'Existing-Site-Survey' : 'Site-Survey-Form',
            ],
            // Add more files here as needed
        ];

        return view('projects.files.client-engagement', compact('project', 'files'));
    }

        /**
     * Display design & concept development files for the project
     */
    public function showDesignConcept(Project $project)
    {
        // Check if this project was converted from an enquiry
        $enquirySource = $project->enquirySource;
        
        if ($enquirySource) {
            // For converted projects, get data from enquiry source
            $designAssets = \App\Models\DesignAsset::where('enquiry_id', $enquirySource->id)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // For regular projects, get data from project
            $designAssets = \App\Models\DesignAsset::where('project_id', $project->id)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();
        }
            
        // $materials = \App\Models\Material::where('project_id', $project->id)
        //     ->with('user')
        //     ->latest()
        //     ->get();

        return view('projects.files.design-concept', compact('project', 'designAssets'));
    }

    /**
     * Display quotation files for the project
     */
    public function showQuotation(Project $project)
    {
        // Check if this project was converted from an enquiry
        $enquirySource = $project->enquirySource;
        
        if ($enquirySource) {
            // For converted projects, get data from enquiry source
            $files = \App\Models\Quote::where('enquiry_id', $enquirySource->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // For regular projects, get data from project
            $files = \App\Models\Quote::where('project_id', $project->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('projects.files.quotation', compact('project', 'files'));
    }


    // public function showMaterials(Project $project)
    // {
    //     $materials = $project->materials()->latest()->get()->groupBy('item');
    //     return view('projects.files.material', compact('project', 'materials'));
    // }

    // public function storeMaterial(Request $request, Project $project)
    // {
    //     $validated = $request->validate([
    //         'item' => 'required|string|max:255',
    //         'materials' => 'required|array',
    //         'materials.*.material' => 'required|string|max:255',
    //         'materials.*.specification' => 'nullable|string',
    //         'materials.*.unit' => 'nullable|string|max:50',
    //         'materials.*.quantity' => 'nullable|numeric',
    //         'materials.*.notes' => 'nullable|string',
    //         'materials.*.design_reference' => 'nullable|url',
    //         'materials.*.approved_by' => 'nullable|string|max:255',
    //     ]);

    //     foreach ($validated['materials'] as $mat) {
    //         $project->materials()->create([
    //             'item' => $validated['item'],
    //             'material' => $mat['material'],
    //             'specification' => $mat['specification'] ?? null,
    //             'unit' => $mat['unit'] ?? null,
    //             'quantity' => $mat['quantity'] ?? null,
    //             'notes' => $mat['notes'] ?? null,
    //             'design_reference' => $mat['design_reference'] ?? null,
    //             'approved_by' => $mat['approved_by'] ?? null,
    //         ]);
    //     }

    //     return redirect()->back()->with('success', 'Materials added successfully.');
    // }

    // public function editMaterial(Project $project, Material $material)
    // {
    //     return view('projects.material-list.edit', compact('project', 'material'));
    // }

    // public function updateMaterial(Request $request, Project $project, Material $material)
    // {
    //     $validated = $request->validate([
    //         'item' => 'required|string|max:255',
    //         'material' => 'required|string|max:255',
    //         'specification' => 'nullable|string',
    //         'unit' => 'nullable|string|max:50',
    //         'quantity' => 'nullable|numeric',
    //         'notes' => 'nullable|string',
    //         'design_reference' => 'nullable|url',
    //         'approved_by' => 'nullable|string|max:255',
    //     ]);

    //     $material->update($validated);

    //     return redirect()->route('projects.material-list.show', $project)
    //         ->with('success', 'Material updated successfully.');
    // }

    // public function destroyMaterial(Project $project, Material $material)
    // {
    //     $material->delete();

    //     return redirect()->route('projects.material-list.show', $project)
    //         ->with('success', 'Material deleted successfully.');
    // }


}
