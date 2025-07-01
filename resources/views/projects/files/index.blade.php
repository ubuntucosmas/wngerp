@extends('layouts.master')

@section('title', 'Project Files')
@section('navbar-title', 'Project Files')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

    :root {
        --primary-accent: #007bff;
        --text-primary: #212529;
        --text-secondary: #6c757d;
        --background-color: #f8f9fa;
        --card-background: #ffffff;
        --card-border-color: #e9ecef;
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
    }
    .phase-card:hover {
        border-color: #0d6efd;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05);
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
</style>

<div class="px-3 mx-10 w-100">
<div class="project-header d-flex justify-content-between align-items-center flex-wrap gap-2 px-3 py-2 rounded shadow-sm" style="background: #0c2d48; color: #b1d4e0;">
    <div class="text-start flex-fill">
        <small class="text-white">Project Name</small>
        <h5 class="mb-1">{{ $project->name }}</h5>
    </div>
    
    <div class="text-center flex-fill">
        <small class="text-white">Project ID</small>    
        <h6 class="mb-1">{{ $project->project_id }}</h6>
    </div>

    <div class="text-center flex-fill">
        <small class="text-white">Location</small>   
        <p class="mb-1 fw-semibold">{{ $project->venue }}</p>
    </div>

    <div class="text-end flex-fill">
        <small class="text-white">Client</small>
        <h5 class="mb-1">{{ $project->client_name }}</h5>
    </div>
</div>
    <hr>
    
    <!-- Phase Folders Section -->
    <div class="mb-4">
        <h4 class="mb-3 text-primary">Project Phases</h4>
        <p class="text-muted mb-0">Manage project phases and related files</p>
        <div class="row g-2">
            @php
                $phases = [
                    ['name' => 'Client Engagement & Briefing', 'icon' => 'bi-folder-symlink'],
                    ['name' => 'Design & Concept Development', 'icon' => 'bi-brush'],
                    ['name' => 'Project Material List', 'icon' => 'bi-list-task'],
                    ['name' => 'Budget & Quotation', 'icon' => 'bi-cash-coin'],
                    ['name' => 'Production', 'icon' => 'bi-gear'],
                    ['name' => 'Logistics', 'icon' => 'bi-truck'],
                    ['name' => 'Event Setup & Execution', 'icon' => 'bi-tools'],
                    ['name' => 'Client Handover', 'icon' => 'bi-clipboard-check'],
                    ['name' => 'Set Down & Return', 'icon' => 'bi-arrow-return-left'],
                    ['name' => 'Archival & Reporting', 'icon' => 'bi-archive'],
                    
                ];
            @endphp
            
            @foreach ($phases as $phase)
                <div class="col-lg-2_4 col-md-4 col-sm-6 mb-2">
                    <div class="phase-card h-100 p-2">
                        <div class="d-flex align-items-center mb-1">
                            <div class="phase-icon me-2">
                                <i class="bi {{ $phase['icon'] }} fs-5"></i>
                            </div>
                            <h5 class="phase-title mb-0">{{ $phase['name'] }}</h5>
                        </div>
                        @if($phase['name'] == 'Client Engagement & Briefing')
                            <p class="phase-description small text-muted mb-2">
                                View and manage client engagement documents
                            </p>
                            <a href="{{ route('projects.files.client-engagement', $project) }}" class="btn btn-sm btn-outline-primary w-100">
                                <span>Open</span>
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @elseif($phase['name'] == 'Design & Concept Development')
                            <p class="phase-description small text-muted mb-2">
                                View and manage design assets and mockups
                            </p>
                            <a href="{{ route('projects.files.design-concept', $project) }}" class="btn btn-sm btn-outline-primary w-100">
                                <span>Open</span>
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @elseif($phase['name'] == 'Project Material List')
                            <p class="phase-description small text-muted mb-2">
                                View and manage Project Materials
                            </p>
                            <a href="{{ route('projects.material-list.index', $project) }}" class="btn btn-sm btn-outline-primary w-100">
                                <span>Open</span>
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @elseif($phase['name'] == 'Logistics')
                            <p class="phase-description small text-muted mb-2">
                                View and manage logistics documents
                            </p>
                            <a href="{{ route('projects.logistics.index', $project) }}" class="btn btn-sm btn-outline-primary w-100">
                                <span>Open</span>
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @elseif($phase['name'] == 'Budget & Quotation')
                            <p class="phase-description small text-muted mb-2">
                                View and manage quotation documents
                            </p>
                            <a href="{{ route('projects.quotation.index', ['project' => $project->id]) }}" class="btn btn-sm btn-outline-primary w-100">
                                <span>Open</span>
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>

                            @elseif($phase['name'] == 'Event Setup & Execution')
                            <p class="phase-description small text-muted mb-2">
                                View and manage setup and execution documents
                            </p>
                            <a href="{{ route('projects.files.setup', $project) }}" class="btn btn-sm btn-outline-primary w-100">
                                <span>Open</span>
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @elseif($phase['name'] == 'Client Handover')
                            <p class="phase-description small text-muted mb-2">
                                View and manage client handover documents
                            </p>
                            <a href="{{ route('projects.handover.index', $project) }}" class="btn btn-sm btn-outline-primary w-100">
                                <span>Open</span>
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @elseif($phase['name'] == 'Set Down & Return')
                            <p class="phase-description small text-muted mb-2">
                                View and manage set down and return documents
                            </p>
                            <a href="{{ route('projects.set-down-return.index', $project) }}" class="btn btn-sm btn-outline-primary w-100">
                                <span>Open</span>
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @elseif($phase['name'] == 'Production')
                            <p class="phase-description small text-muted mb-2">
                                Manage job briefs and production workflows
                            </p>
                            <a href="{{ route('projects.production.index', $project) }}" class="btn btn-sm btn-outline-primary w-100">
                                <span>Open</span>
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @elseif($phase['name'] == 'Client Handover')
                            <p class="phase-description small text-muted mb-2">
                                Manage client handover documents and sign-offs
                            </p>
                            <a href="{{ route('projects.handover.index', $project) }}" class="btn btn-sm btn-outline-primary w-100">
                                <span>Open</span>
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @elseif($phase['name'] == 'Archival & Reporting')
                            <p class="phase-description small text-muted mb-2">
                                Access final project reports and archives
                            </p>
                            <a href="{{ route('projects.archival.index', $project) }}" class="btn btn-sm btn-outline-primary w-100">
                                <span>Open</span>
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @else
                            <p class="phase-description small text-muted mb-2">
                                Coming soon
                            </p>
                            <button class="btn btn-sm btn-outline-secondary w-100" disabled>
                                <span>Coming Soon</span>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
