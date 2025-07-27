@extends('layouts.master')

@section('title', isset($enquiry) ? 'Enquiry Files' : 'Project Files')
@section('navbar-title', isset($enquiry) ? 'Enquiry Files' : 'Project Files')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

    :root {
        --primary-accent: #007bff;
        --primary-accent-light: #e6f0ff;
        --text-primary: #212529;
        --text-secondary: #6c757d;
        --background-color: #f8f9fa;
        --card-background: #ffffff;
        --card-border-color: #e9ecef;
        --tooltip-bg: #2c3e50;
        --tooltip-text: #ffffff;
    }

    body {
        background-color: var(--background-color);
        color: var(--text-primary);
        font-family: 'Poppins', sans-serif;
    }

    .container {
        max-width: 1200px;
    }

    .project-header {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: var(--background-color);
        padding-top: 1.5rem;
        padding-bottom: 1rem;
    }

    .project-header h2 {
        font-weight: 600;
        color: var(--text-primary);
    }

    .project-header .lead {
        color: var(--text-secondary);
        font-size: 1.1rem;
    }

    .project-header hr {
        border-color: var(--card-border-color);
        width: 80px;
    }

    .file-card {
        background: var(--card-background);
        border: 1px solid var(--card-border-color);
        border-radius: 12px;
        padding: 3px;
        margin-bottom: 1rem;
        text-align: center;
        transition: border-color 0.3s ease;
        /* Removed box-shadow for a flatter, more minimal look */
    }

    .file-card:hover {
        border-color: var(--primary-accent);
    }

    .file-card-icon {
        font-size: 3rem; /* Slightly smaller */
        color: var(--primary-accent);
        margin-bottom: 1.5rem;
    }

    .file-card-title {
        font-size: 1.25rem; /* Slightly smaller */
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .file-card-description {
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
        min-height: 40px; /* Keep this for alignment */
        font-size: 0.95rem;
    }

    .btn-minimal {
        background-color: transparent;
        border: none;
        color: var(--primary-accent);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        text-transform: none; /* More minimal */
        letter-spacing: 0; /* More minimal */
        transition: background-color 0.3s ease;
        display: inline-flex;
        align-items: center;
    }

    .btn-minimal:hover {
        background-color: #e9ecef;
        color: var(--primary-accent);
    }
    
    .alert-minimal {
        background-color: #e9ecef;
        border: 1px solid #dee2e6;
        color: var(--text-secondary);
        border-radius: 12px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .alert-minimal i {
        font-size: 1.5rem;
        color: var(--text-secondary);
    }

    .phase-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        transition: all 0.2s ease;
        height: 100%;
        position: relative;
        cursor: pointer;
        display: flex;
        flex-direction: column;
    }
    .phase-card:hover {
        border-color: #0d6efd;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        transform: translateY(-3px);
    }
    
    /* Expandable Summary Styles */
    .summary-content {
        margin: 0 -0.5rem -0.5rem -0.5rem;
    }
    
    .summary-toggle {
        background: none;
        border: none;
        color: #0d6efd;
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        margin-top: 0.5rem;
        cursor: pointer;
        align-self: flex-start;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .summary-toggle:hover {
        text-decoration: underline;
    }
    
    .summary-toggle i {
        transition: transform 0.3s ease;
    }
    
    /* Tooltip Bubble Styles */
    .tooltip-bubble {
        position: absolute;
        bottom: calc(100% - 10px);  /* Changed from +10px to -10px to lower the bubble */
        left: 50%;
        transform: translateX(-50%);
        background-color: #2c3e50;
        color: white;
        padding: 10px 15px;
        border-radius: 8px;
        font-size: 0.85rem;
        width: 240px;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s ease;
        z-index: 1000;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        pointer-events: none;
    }
    
    .phase-card:hover .tooltip-bubble {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(-5px);
    }
    
    .tooltip-bubble::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -8px;
        border-width: 8px;
        border-style: solid;
        border-color: #2c3e50 transparent transparent transparent;
    }
    
    .tooltip-title {
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 5px;
        color: #fff;
    }
    
    .tooltip-content {
        font-size: 0.8rem;
        line-height: 1.4;
        color: rgba(255, 255, 255, 0.9);
    }
    .phase-icon {
        color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.1);
        width: 2rem;
        height: 2rem;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .phase-title {
        font-size: 0.85rem;
        font-weight: 500;
        color: #212529;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .phase-description {
        font-size: 0.75rem;
        line-height: 1.3;
        min-height: 2.6rem;
        margin-bottom: 0.5rem;
    }
    .col-lg-2_4 {
        flex: 0 0 auto;
        width: 20%;
    }
    @media (max-width: 1199.98px) {
        .col-lg-2_4 {
            width: 33.333333%;
        }
    }
    @media (max-width: 767.98px) {
        .col-lg-2_4 {
            width: 50%;
        }
    }
    @media (max-width: 575.98px) {
        .col-lg-2_4 {
            width: 100%;
        }
    }
    
    /* Phase Status Dropdown Styles */
    .phase-status-dropdown {
        font-size: 0.75rem !important;
        min-width: 120px !important;
        border-radius: 6px !important;
        font-weight: 500 !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
    }
    
    .phase-status-dropdown:focus {
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25) !important;
        border-color: #0d6efd !important;
    }
    
    .phase-status-dropdown:disabled {
        opacity: 0.7 !important;
        cursor: not-allowed !important;
    }
    
    /* Ensure selected option is visible */
    .phase-status-dropdown option:checked {
        font-weight: bold !important;
    }
    
    /* Dynamic background colors based on status */
    .phase-status-dropdown[data-status="Not Started"] {
        background-color: #0d6efd !important;
        color: white !important;
        border-color: #0d6efd !important;
    }
    
    .phase-status-dropdown[data-status="In Progress"] {
        background-color: #fd7e14 !important;
        color: white !important;
        border-color: #fd7e14 !important;
    }
    
    .phase-status-dropdown[data-status="Completed"] {
        background-color: #28a745 !important;
        color: white !important;
        border-color: #28a745 !important;
    }
    
    /* Status indicator badge */
    .status-indicator {
        position: absolute;
        top: -5px;
        right: -5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid white;
        z-index: 3;
    }
    
    .toast-notification {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    }

    /* Enhanced Summary Styles */
    .bg-light-success {
        background-color: rgba(40, 167, 69, 0.1) !important;
        border-color: rgba(40, 167, 69, 0.2) !important;
    }

    .bg-light-secondary {
        background-color: rgba(108, 117, 125, 0.1) !important;
        border-color: rgba(108, 117, 125, 0.2) !important;
    }

    .summary-content .progress {
        background-color: rgba(0, 0, 0, 0.1);
    }

    .summary-content .progress-bar {
        transition: width 0.3s ease;
    }

    .summary-content .border {
        border-width: 1px !important;
    }

    .summary-content .rounded {
        border-radius: 6px !important;
    }

    .summary-content .p-2 {
        padding: 0.75rem !important;
    }

    .summary-content .mb-3 {
        margin-bottom: 1rem !important;
    }

    .summary-content .mt-3 {
        margin-top: 1rem !important;
    }

    .summary-content .pt-2 {
        padding-top: 0.5rem !important;
    }

    .summary-content .border-top {
        border-top: 1px solid rgba(0, 0, 0, 0.1) !important;
    }

    /* Scrollable Summary Styles */
    .summary-content {
        max-height: 300px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: rgba(0, 0, 0, 0.3) transparent;
    }

    .summary-content::-webkit-scrollbar {
        width: 6px;
    }

    .summary-content::-webkit-scrollbar-track {
        background: transparent;
    }

    .summary-content::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.3);
        border-radius: 3px;
    }

    .summary-content::-webkit-scrollbar-thumb:hover {
        background-color: rgba(0, 0, 0, 0.5);
    }

    .summary-content {
        scroll-behavior: smooth;
    }
</style>

<div class="px-3 mx-10 w-100">
    <!-- Navigation Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    @if(isset($enquiry))
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.index') }}">Enquiries</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $enquiry->project_name }} - Files & Phases</li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $project->name }} - Files & Phases</li>
                    @endif
                </ol>
            </nav>
            <h2 class="mb-0">Files & Phases</h2>
        </div>
        <a href="{{ route('enquiries.index') }}" class="btn btn-primary">
            <i class="bi bi-arrow-left me-2"></i>Back to Enquiries
        </a>
    </div>

<div class="project-header d-flex justify-content-between align-items-center flex-wrap gap-2 px-3 py-2 rounded shadow-sm" style="background: #0c2d48; color: #b1d4e0;">
    <div class="text-start flex-fill">
        <small class="text-white">{{ isset($enquiry) ? 'Enquiry' : 'Project' }} Name</small>
        <h5 class="mb-1">{{ isset($enquiry) ? $enquiry->project_name : $project->name }}</h5>
    </div>
    
    <div class="text-center flex-fill">
        <small class="text-white">{{ isset($enquiry) ? 'Enquiry' : 'Project' }} ID</small>    
        <h6 class="mb-1">{{ isset($enquiry) ? ($enquiry->formatted_id ?? $enquiry->id) : $project->project_id }}</h6>
    </div>

    <div class="text-center flex-fill">
        <small class="text-white">Location</small>   
        <p class="mb-1 fw-semibold">{{ isset($enquiry) ? $enquiry->venue : $project->venue }}</p>
    </div>

    <div class="text-end flex-fill">
        <small class="text-white">Client</small>
        <h5 class="mb-1">{{ isset($enquiry) ? $enquiry->client_name : $project->client_name }}</h5>
    </div>
</div>

{{-- Overall Progress Bar (at the top, or wherever appropriate) --}}
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-1">
        <small class="fw-medium">Overall Progress</small>
        <small class="text-muted">
            {{ $totalPhases > 0 ? round((($done ?? 0) / $totalPhases) * 100) : 0 }}%
            ({{ $done ?? 0 }} of {{ $totalPhases }} phases done)
        </small>
    </div>
    <div class="progress" style="height: 10px;">
        <div class="progress-bar bg-success" style="width: {{ $totalPhases > 0 ? (($done ?? 0) / $totalPhases) * 100 : 0 }}%"></div>
        </div>
</div>
    <hr>
    
    <!-- Phase Folders Section -->
    <div class="mb-4">
        <h4 class="mb-3 text-primary">{{ isset($enquiry) ? 'Enquiry' : 'Project' }} Phases</h4>
        <p class="text-muted mb-0">Manage {{ isset($enquiry) ? 'enquiry' : 'project' }} phases and related files</p>
        @php
            $budgetPhase = isset($enquiry) ? $enquiry->phases()->where('name', 'Budget & Quotation')->first() : $project->phases()->where('name', 'Budget & Quotation')->first();
            $showAllPhases = $budgetPhase && $budgetPhase->status === 'Completed';
            
            // Check if this is a converted project
            $isConvertedProject = isset($project) && $project->enquirySource;
        @endphp
        
        @if($isConvertedProject)
            <div class="alert alert-success alert-sm mb-3">
                <i class="bi bi-check-circle me-2"></i>
                <strong>Converted Project:</strong> This project was converted from an enquiry. The first 4 phases are completed from the enquiry phase, and the remaining phases are ready for project execution.
            </div>
        @elseif(!$showAllPhases)
            <div class="alert alert-info alert-sm mb-3">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Progressive Phase System:</strong> Complete the "Budget & Quotation" phase to unlock the remaining {{ isset($enquiry) ? 'enquiry' : 'project' }} phases.
            </div>
        @endif
        <div class="row g-2">
            @php
                $phases = isset($enquiry) ? $enquiry->getDisplayablePhases() : $project->getDisplayablePhases();
                $configPhases = collect(config('project_process_phases'));
            @endphp
            @foreach ($phases as $phase)
                @php
                    $statusColors = [
                        'Completed' => ['bg' => '#28a745', 'text' => '#fff', 'border' => '#28a745'],
                        'In Progress' => ['bg' => '#fd7e14', 'text' => '#fff', 'border' => '#fd7e14'],
                        'Not Started' => ['bg' => '#0d6efd', 'text' => '#fff', 'border' => '#0d6efd'],
                    ];
                    $color = $statusColors[$phase->status] ?? $statusColors['Not Started'];
                    $config = $configPhases->firstWhere('name', $phase->name);
                    $icon = $phase->icon ?? ($config['icon'] ?? 'bi-folder');
                    $summary = $phase->summary ?? ($config['summary'] ?? 'No summary available for this phase.');
                @endphp
                <div class="col-lg-2_4 col-md-4 col-sm-6 mb-2">
                    <div class="phase-card h-100 p-3 d-flex flex-column justify-content-between position-relative">
                        <!-- Tooltip Bubble (Phase Details) -->
                        <div class="tooltip-bubble">
                            @if($phase->name == 'Client Engagement & Briefing')
                                <div class="tooltip-title">Client Engagement & Briefing</div>
                                <div class="tooltip-content">
                                    • Enquiry log form should be filled and saved<br>
                                    • Site survey form should be completed<br>
                                    • Client requirements documented<br>
                                    • Initial project scope defined
                                </div>
                            @elseif($phase->name == 'Design & Concept Development')
                                <div class="tooltip-title">Design & Concept Development</div>
                                <div class="tooltip-content">
                                    • Design concepts and mockups created<br>
                                    • Client feedback collected<br>
                                    • Design assets organized<br>
                                    • Final design approved
                                </div>
                            @elseif($phase->name == 'Project Material List')
                                <div class="tooltip-title">Project Material List</div>
                                <div class="tooltip-content">
                                    • Production materials listed<br>
                                    • Materials for hire identified<br>
                                    • Labour requirements defined<br>
                                    • Quantities and specifications detailed
                                </div>
                            @elseif($phase->name == 'Logistics')
                                <div class="tooltip-title">Logistics</div>
                                <div class="tooltip-content">
                                    • Delivery schedules planned<br>
                                    • Transport arrangements made<br>
                                    • Site access coordinated<br>
                                    • Equipment logistics organized
                                </div>
                            @elseif($phase->name == 'Budget & Quotation')
                                <div class="tooltip-title">Budget & Quotation</div>
                                <div class="tooltip-content">
                                    • Detailed budget prepared<br>
                                    • Quotation documents created<br>
                                    • Client pricing finalized<br>
                                    • Budget approval obtained
                                </div>
                            @elseif($phase->name == 'Event Setup & Execution')
                                <div class="tooltip-title">Event Setup & Execution</div>
                                <div class="tooltip-content">
                                    • Setup reports completed<br>
                                    • Event execution monitored<br>
                                    • On-site coordination managed<br>
                                    • Quality control maintained
                                </div>
                            @elseif($phase->name == 'Client Handover')
                                <div class="tooltip-title">Client Handover</div>
                                <div class="tooltip-content">
                                    • Handover reports prepared<br>
                                    • Client sign-off obtained<br>
                                    • Final deliverables confirmed<br>
                                    • Project completion documented
                                </div>
                            @elseif($phase->name == 'Set Down & Return')
                                <div class="tooltip-title">Set Down & Return</div>
                                <div class="tooltip-content">
                                    • Equipment dismantled<br>
                                    • Materials returned<br>
                                    • Site cleaned up<br>
                                    • Return logistics managed
                                </div>
                            @elseif($phase->name == 'Production')
                                <div class="tooltip-title">Production</div>
                                <div class="tooltip-content">
                                    • Job briefs created<br>
                                    • Production workflows managed<br>
                                    • Quality standards maintained<br>
                                    • Production timeline tracked
                                </div>
                            @elseif($phase->name == 'Archival & Reporting')
                                <div class="tooltip-title">Archival & Reporting</div>
                                <div class="tooltip-content">
                                    • Final project reports created<br>
                                    • Project archives organized<br>
                                    • Lessons learned documented<br>
                                    • Project closure completed
                                </div>
                            @else
                                <div class="tooltip-title">{{ $phase->name }}</div>
                                <div class="tooltip-content">
                                    Phase details and requirements will be defined as this phase is developed.
                                </div>
                            @endif
                        </div>
                        <!-- Top: Icon and Title -->
                        <div class="d-flex align-items-center mb-2">
                                <div class="phase-icon me-2">
                                    <i class="bi {{ $icon }} fs-5"></i>
                                </div>
                            <h5 class="phase-title mb-0 flex-grow-1">{{ $phase->name }}</h5>
                            @if($phase->skipped)
                                <span class="badge bg-warning text-dark ms-2" data-bs-toggle="tooltip" title="{{ $phase->skip_reason ? 'Reason: ' . $phase->skip_reason : 'This phase was skipped.' }}">
                                    <i class="bi bi-skip-forward-circle"></i> Skipped
                                </span>
                            @elseif($phase->status == 'Completed')
                                <span class="badge bg-success ms-2"><i class="bi bi-check-circle"></i> Completed</span>
                            @elseif($phase->status == 'In Progress')
                                <span class="badge bg-warning text-dark ms-2"><i class="bi bi-hourglass-split"></i> In Progress</span>
                                    @else
                                <span class="badge bg-primary ms-2"><i class="bi bi-clock"></i> Not Started</span>
                                    @endif
                                </div>
                        <!-- Middle: Description and Open Button -->
                        <div class="mb-2">
                            <p class="phase-description small text-muted mb-2">
                                {{ $summary }}
                            </p>
                            @if($phase->name == 'Client Engagement & Briefing')
                            <a href="{{ isset($enquiry) ? route('enquiries.files.client-engagement', $enquiry) : route('projects.files.client-engagement', $project) }}" class="btn btn-sm btn-outline-primary w-100">
                                <span>Open</span>
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @elseif($phase->name == 'Design & Concept Development')
                            <a href="{{ isset($enquiry) ? route('enquiries.files.design-concept', $enquiry) : route('projects.files.design-concept', $project) }}" class="btn btn-sm btn-outline-primary w-100">
                                <span>Open</span>
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @elseif($phase->name == 'Project Material List')
                            <a href="{{ isset($enquiry) ? route('enquiries.material-list.index', $enquiry) : route('projects.material-list.index', $project) }}" class="btn btn-sm btn-outline-primary w-100">
                                <span>Open</span>
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @elseif($phase->name == 'Logistics')
                            <a href="{{ isset($enquiry) ? route('enquiries.logistics.index', $enquiry) : route('projects.logistics.index', $project) }}" class="btn btn-sm btn-outline-primary w-100">
                                <span>Open</span>
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @elseif($phase->name == 'Budget & Quotation')
                            <a href="{{ isset($enquiry) ? route('enquiries.files.quotation', $enquiry) : route('projects.quotation.index', $project) }}" class="btn btn-sm btn-outline-primary w-100">
                                <span>Open</span>
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                            @elseif($phase->name == 'Event Setup & Execution')
                            <a href="{{ isset($enquiry) ? route('enquiries.files.setup', $enquiry) : route('projects.files.setup', $project) }}" class="btn btn-sm btn-outline-primary w-100">
                                <span>Open</span>
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @elseif($phase->name == 'Client Handover')
                            <a href="{{ isset($enquiry) ? route('enquiries.handover.index', $enquiry) : route('projects.handover.index', $project) }}" class="btn btn-sm btn-outline-primary w-100">
                                <span>Open</span>
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @elseif($phase->name == 'Set Down & Return')
                            <a href="{{ isset($enquiry) ? route('enquiries.set-down-return.index', $enquiry) : route('projects.set-down-return.index', $project) }}" class="btn btn-sm btn-outline-primary w-100">
                                <span>Open</span>
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @elseif($phase->name == 'Production')
                            <a href="{{ isset($enquiry) ? route('enquiries.production.index', $enquiry) : route('projects.production.index', $project) }}" class="btn btn-sm btn-outline-primary w-100">
                                <span>Open</span>
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @elseif($phase->name == 'Archival & Reporting')
                            <a href="{{ isset($enquiry) ? route('enquiries.files.archival', $enquiry) : route('projects.archival.index', $project) }}" class="btn btn-sm btn-outline-primary w-100">
                                <span>Open</span>
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @else
                            <button class="btn btn-sm btn-outline-secondary w-100" disabled>
                                <span>Coming Soon</span>
                            </button>
                        @endif
                        </div>
                        <!-- Bottom: Status/Skip Controls -->
                        <div class="mt-auto pt-2 border-top d-flex flex-column align-items-stretch gap-2">
                            @if(!$phase->skipped)
                                <form method="POST" action="{{ route('phases.skip', ['phaseId' => $phase->id]) }}" class="d-flex align-items-center gap-2">
                                    @csrf
                                    <input type="text" name="skip_reason" class="form-control form-control-sm flex-grow-1" placeholder="Reason (optional)" />
                                    <button type="submit" class="btn btn-info btn-sm">Skip</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('phases.unskip', ['phaseId' => $phase->id]) }}" class="d-flex align-items-center gap-2">
                                    @csrf
                                    <button type="submit" class="btn btn-info btn-sm">Unskip</button>
                                    @if($phase->skip_reason)
                                        <span class="text-primary fw-semibold ms-2">Reason: {{ $phase->skip_reason }}</span>
                                    @endif
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@push('scripts')
<script>
function toggleSummary(button) {
    const content = button.nextElementSibling;
    const icon = button.querySelector('i');
    const span = button.querySelector('span');
    const isVisible = content.style.display !== 'none';
    
    if (isVisible) {
        // Hide the summary
        content.style.display = 'none';
        icon.classList.remove('bi-chevron-up');
        icon.classList.add('bi-chevron-down');
        span.textContent = 'View Summary';
    } else {
        // Show the summary
        content.style.display = 'block';
        icon.classList.remove('bi-chevron-down');
        icon.classList.add('bi-chevron-up');
        span.textContent = 'Hide Summary';
    }
}

// Close summary when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.summary-toggle') && !event.target.closest('.summary-content')) {
        document.querySelectorAll('.summary-content.expanded').forEach(content => {
            content.classList.remove('expanded');
            const button = content.previousElementSibling;
            button.classList.remove('expanded');
            button.querySelector('span').textContent = 'View Summary';
        });
    }
});

// Simple form submission - no JavaScript needed!
// The form submits automatically when dropdown changes

function updateProjectProgressBar(progress, projectId) {
    const progressBars = document.querySelectorAll(`[data-project-id="${projectId}"] .progress-bar`);
    const progressTexts = document.querySelectorAll(`[data-project-id="${projectId}"] .progress-text`);
    
    let bgColor;
    if (progress >= 80) {
        bgColor = '#28a745'; // Green
    } else if (progress >= 40) {
        bgColor = '#fd7e14'; // Orange
    } else {
        bgColor = '#0d6efd'; // Blue
    }

    progressBars.forEach(bar => {
        bar.style.width = `${progress}%`;
        bar.setAttribute('aria-valuenow', progress);
        bar.style.backgroundColor = bgColor;
    });
    
    // Update progress text
    progressTexts.forEach(text => {
        text.textContent = `${progress}%`;
    });
    
    // If we're on the projects index page, update the specific project row
    const projectRow = document.querySelector(`tr[data-project-id="${projectId}"]`);
    if (projectRow) {
        const rowProgressBar = projectRow.querySelector('.progress-bar');
        const rowProgressText = projectRow.querySelector('.progress-text');
        
        if (rowProgressBar) {
            rowProgressBar.style.width = `${progress}%`;
            rowProgressBar.setAttribute('aria-valuenow', progress);
        }
        
        if (rowProgressText) {
            rowProgressText.textContent = `${progress}%`;
        }
    }
}

function showToast(message, type) {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        z-index: 9999;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        background: ${type === 'success' ? '#28a745' : '#dc3545'};
    `;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

// Update dropdown styling on page load
document.addEventListener('DOMContentLoaded', function() {
    const dropdowns = document.querySelectorAll('.phase-status-dropdown');
    
    dropdowns.forEach(dropdown => {
        const selectedStatus = dropdown.value;
        updateDropdownStyle(dropdown, selectedStatus);
    });
});

function updateDropdownStyle(dropdown, status) {
    const statusColors = {
        'Completed': { bg: '#28a745', text: '#fff', border: '#28a745' },
        'In Progress': { bg: '#fd7e14', text: '#fff', border: '#fd7e14' },
        'Not Started': { bg: '#0d6efd', text: '#fff', border: '#0d6efd' }
    };
    
    const color = statusColors[status] || statusColors['Not Started'];
    dropdown.style.background = color.bg;
    dropdown.style.color = color.text;
    dropdown.style.borderColor = color.border;
    dropdown.setAttribute('data-status', status);
}

document.addEventListener('DOMContentLoaded', function() {
    // All functionality is now handled by the toggleSummary function
    // No additional event listeners needed
});
</script>
@endpush

@endsection
