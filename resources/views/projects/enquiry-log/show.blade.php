@extends('layouts.master')

@section('title', 'Enquiry Log')

@push('styles')
<style>
    :root {
        --header-height: 60px;
        --sidebar-width: 250px;
    }
    
    .sticky-header {
        position: sticky;
        top: 0;
        z-index: 1020;
        background: white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 0.5rem 0;
        margin: -0.5rem -1.5rem 1rem;
        padding: 0.5rem 1.5rem;
    }
    
    .compact-card {
        border: 1px solid #e9ecef;
        border-radius: 0.5rem;
        transition: all 0.2s;
    }
    
    .compact-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.05);
    }
    
    .compact-field {
        margin-bottom: 0.5rem;
    }
    
    .compact-field-label {
        font-size: 0.75rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }
    
    .compact-field-value {
        font-size: 0.9375rem;
        font-weight: 500;
        line-height: 1.3;
    }
    
    .compact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .compact-grid-item {
        background: white;
        padding: 0.75rem;
        border-radius: 0.5rem;
        border: 1px solid #e9ecef;
    }
    
    @media (max-width: 768px) {
        .compact-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .content-wrapper {
        max-width: 1600px;
        margin: 0 auto;
        padding: 0 1rem;
    }
    
    .breadcrumb {
        padding: 0.5rem 0;
        margin: 0;
        background: transparent;
        font-size: 0.8125rem;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        font-weight: bold;
    }
    
    .page-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .status-badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
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
                        <li class="breadcrumb-item active">Enquiry Log</li>
                    </ol>
                </nav>
                <h4 class="mb-0 mt-1 d-flex align-items-center">
                    <i class="bi bi-journal-text me-2 text-primary"></i>
                    Enquiry Log For Project {{ $project->name }}
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
                    Enquiry Details
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
                <h5 class="mb-2">No Enquiry Log Found</h5>
                <p class="text-muted mb-4">There is no enquiry log associated with this project.</p>
                <a href="{{ route('projects.enquiry-log.create', $project) }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i> Create Enquiry Log
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
