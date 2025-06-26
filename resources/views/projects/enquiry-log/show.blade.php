@extends('layouts.master')

@section('title', 'Project Brief')

@push('styles')
<style>
    :root {
        --bg-glass: rgba(255, 255, 255, 0.06);
        --frost-border: rgba(255, 255, 255, 0.12);
        --text-light: #b1d4e0;
        --text-bright: #ffffff;
        --primary-deep: #145da0;
        --primary-dark: #0c2d48;
    }

    body {
        background: linear-gradient(to right, #0c2d48, #145da0);
        color: var(--text-light);
    }

    .content-wrapper {
        max-width: 1400px;
        margin: auto;
        padding: 2rem 1rem;
    }

    .glass-card {
        background: var(--bg-glass);
        border: 1px solid var(--frost-border);
        backdrop-filter: blur(16px);
        border-radius: 1rem;
        padding: 1.5rem;
        color: var(--text-light);
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.25);
        transition: 0.3s ease;
    }

    .glass-card:hover {
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
    }

    .glass-title {
        color: var(--text-bright);
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .badge-glow {
        background: linear-gradient(to right, #2e8bc0, #145da0);
        border: none;
        color: white;
        border-radius: 4px;
        font-size: 0.75rem;
        padding: 0.4rem 0.8rem;
        font-weight: 600;
    }

    .breadcrumb {
        background: transparent;
        font-size: 0.85rem;
        color: var(--text-light);
    }

    .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        color: var(--text-light);
        padding: 0 0.4rem;
    }

    .breadcrumb-item a {
        color: var(--text-bright);
        text-decoration: none;
    }

    .breadcrumb-item.active {
        color: #b1d4e0;
    }

    .section-divider {
        border-bottom: 1px solid var(--frost-border);
        margin: 1.5rem 0;
    }

    .icon-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #ffffff;
        margin-bottom: 1rem;
    }

    .icon-title i {
        font-size: 1.2rem;
        color: #2e8bc0;
    }

    .compact-field-value {
        font-weight: 500;
        font-size: 0.95rem;
        color: #e3f2fd;
    }

    .btn-outline-primary, .btn-outline-secondary {
        border-radius: 30px;
        padding: 0.4rem 1.2rem;
        font-size: 0.8rem;
    }

    .card-body ul {
        padding-left: 1rem;
        margin: 0;
    }

    .card-body ul li {
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .text-muted {
        color: #b0bec5 !important;
    }

    .sticky-header {
        background: #0c2d48;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
</style>
@endpush


@section('content')
<div class="content-wrapper px-3 py-2">
    <!-- Sticky Header -->
    <div class="sticky-header bg-white mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('projects.files.index', ['project' => $project->id]) }}">Project Files</a></li>
                        <li class="breadcrumb-item active">Project Brief</li>
                    </ol>
                </nav>
                <h4 class="mb-0 mt-1 d-flex align-items-center">
                    <i class="bi bi-journal-text me-2 text-primary"></i>
                    Project Brief For Project {{ $project->name }}
                </h4>
            </div>
            <div class="page-actions">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
                @if($enquiryLog)
                    <a href="{{ route('projects.enquiry-log.edit', [$project->id, $enquiryLog->id]) }}" class="btn btn-sm btn-primary d-flex align-items-center">
                        <i class="bi bi-pencil-square me-1"></i> Edit
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if ($enquiryLog)
        <div class="compact-card bg-white p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-info-circle text-primary me-2"></i>
                    Project Brief Details
                </h5>
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-info-circle text-primary me-2"></i>
                    Project ID: {{ $project->project_id }}
                </h5>
                <span class="status-badge">
                    Status:{{ $enquiryLog->status }}
                </span>
            </div>
            <div class="d-flex justify-content-end gap-2 align-items-center mb-4">
                <a href="{{ route('projects.enquiry-log.download', [$project->id, $enquiryLog->id]) }}" class="btn btn-outline-danger btn-sm">Download</a>
                <a href="{{ route('projects.enquiry-log.print', [$project->id, $enquiryLog->id]) }}" class="btn btn-outline-primary btn-sm" target="_blank">Print</a>
            </div>
            
            <div class="row g-3">
                <!-- First Column - Basic Info -->
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted mb-4 d-flex align-items-center">
                                <i class="bi bi-info-circle me-2 text-primary"></i>
                                Basic Information
                            </h6>
                            <hr>
                            <div class="row gy-3">
                                <div class="col-12">
                                    <div class="text-primary small fw-bold text-uppercase">Client</div>
                                    <div class="fs-6 text-dark">{{ $enquiryLog->client_name ?? '—' }}</div>
                                </div>
                                <div class="col-12">
                                    <div class="text-primary small fw-bold text-uppercase">Contact Person</div>
                                    <div class="fs-6 text-dark">{{ $enquiryLog->contact_person ?? '—' }}</div>
                                </div>
                                <div class="col-12">
                                    <div class="text-primary small fw-bold text-uppercase">Assigned To</div>
                                    <div class="fs-6 text-dark">{{ $enquiryLog->assigned_to ?? '—' }}</div>
                                </div>
                                <div class="col-12">
                                    <div class="text-primary small fw-bold text-uppercase">Status</div>
                                    <div class="fs-6 text-dark">{{ $enquiryLog->status ?? '—' }}</div>
                                </div>
                                <div class="col-12">
                                    <div class="text-primary small fw-bold text-uppercase">Status</div>
                                    <div class="badge bg-{{ $enquiryLog->status === 'Approved' ? 'success' : ($enquiryLog->status === 'Declined' ? 'danger' : 'secondary') }}">
                                        {{ $enquiryLog->status ?? '—' }}
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="text-primary small fw-bold text-uppercase">Date Received</div>
                                    <div class="fs-6 text-dark">
                                        {{ $enquiryLog->date_received ? \Carbon\Carbon::parse($enquiryLog->date_received)->format('M d, Y') : '—' }}
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="text-primary small fw-bold text-uppercase">Venue</div>
                                    <div class="fs-6 text-dark">{{ $enquiryLog->venue ?? '—' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Second Column - Follow Up Notes -->
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="compact-card bg-light p-3 h-100">
                        <h6 class="text-uppercase text-muted mb-3 d-flex align-items-center">
                            <i class="bi bi-chat-square-text me-2 text-primary"></i>
                            Follow Up Notes
                        </h6>
                        <div class="bg-white p-3 rounded-2 h-100">
                            @if($enquiryLog->follow_up_notes)
                                <div class="compact-field-value">{{ $enquiryLog->follow_up_notes }}</div>
                            @else
                                <div class="text-muted fst-italic">No notes available</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Third Column - Project Scope -->
                <div class="col-12 col-xl-4">
                    <div class="compact-card bg-light p-3 h-100">
                        <h6 class="text-uppercase text-muted mb-3 d-flex align-items-center">
                            <i class="bi bi-list-check me-2 text-primary"></i>
                            Project Scope Summary
                        </h6>
                        <div class="bg-white p-3 rounded-2 h-100">
                            @php
                                $scopeSummary = $enquiryLog->project_scope_summary ?? '';
                                $scopeItems = is_array($scopeSummary) 
                                    ? $scopeSummary 
                                    : (json_decode($scopeSummary, true) ?: array_filter(explode(',', $scopeSummary)));
                            @endphp
                            @if(is_array($scopeItems) && count($scopeItems) > 0)
                                <ul class="list-unstyled mb-0">
                                    @foreach($scopeItems as $item)
                                        @if(trim($item))
                                            <li class="mb-2 d-flex align-items-start">
                                                <i class="bi bi-check2-circle text-success me-2 mt-1"></i>
                                                <span>{{ trim($item) }}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-muted fst-italic">No scope summary provided</div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Footer with Last Updated -->
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center pt-2 border-top text-muted small">
                        <div>
                            <i class="bi bi-clock-history me-1"></i>
                            Last updated: {{ $enquiryLog->updated_at->diffForHumans() }}
                        </div>
                        <div class="text-end">
                            <a href="{{ route('projects.enquiry-log.edit', [$project, $enquiryLog]) }}" class="text-primary text-decoration-none">
                                <i class="bi bi-pencil-square me-1"></i>Edit Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="compact-card bg-white p-5 text-center">
            <div class="py-4">
                <i class="bi bi-journal-x fs-1 text-muted mb-3 opacity-50"></i>
                <h5 class="mb-2">No Project Brief Found</h5>
                <p class="text-muted mb-4">There is no project brief associated with this project.</p>
                <a href="{{ route('projects.enquiry-log.create', $project) }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i> Create Project Brief
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
