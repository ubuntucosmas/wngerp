@extends('layouts.master')

@section('title', 'Client Engagement - ' . (isset($enquiry) ? $enquiry->project_name : $project->name))
@section('navbar-title', 'Client Engagement')

@section('content')
<div class="px-3 mx-10 w-100">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    @if(isset($enquiry))
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.index') }}">Enquiries</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.files', $enquiry) }}">{{ $enquiry->project_name }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Client Engagement</li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}">{{ $project->name }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Client Engagement</li>
                    @endif
                </ol>
            </nav>
            <h2 class="mb-1">Client Engagement & Briefing</h2>
            <p class="text-muted mb-0">Files and documents related to client engagement</p>
        </div>
        <a href="{{ (isset($enquiry) && is_object($enquiry) && isset($enquiry->id)) ? route('enquiries.files', $enquiry) : route('projects.files.index', $project) }}" class="btn btn-primary">
            <i class="bi bi-arrow-left me-2"></i>Back to Files & Phases
        </a>
    </div>

    <!-- Files Grid -->
    <div class="row">
        @foreach($files as $file)
            <div class="col-lg-6 col-md-6 mb-4">
                @if(isset($file['disabled']) && $file['disabled'])
                    <div class="file-card h-100 disabled-file">
                        <div class="d-flex align-items-start">
                            <div class="file-card-icon me-3 disabled-icon">
                                <i class="bi {{ $file['icon'] }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="file-card-title text-muted">{{ $file['name'] }}</h3>
                                <p class="file-card-description">
                                    {{ $file['description'] }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="badge bg-secondary">{{ $file['type'] }}</span>
                                    <small class="text-muted">Coming Soon</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif(isset($file['skipped']) && $file['skipped'])
                    <div class="file-card h-100 skipped-file">
                        <div class="d-flex align-items-start">
                            <div class="file-card-icon me-3 skipped-icon">
                                <i class="bi {{ $file['icon'] }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="file-card-title text-warning">{{ $file['name'] }}</h3>
                                <p class="file-card-description">
                                    {{ $file['description'] }}
                                </p>
                                @if($file['skip_reason'])
                                    <p class="text-muted small mb-2">
                                        <strong>Reason:</strong> {{ $file['skip_reason'] }}
                                    </p>
                                @endif
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="badge bg-warning text-dark">Skipped</span>
                                    <form action="{{ isset($enquiry) ? route('enquiries.files.unskip-site-survey', $enquiry) : route('projects.files.unskip-site-survey', $project) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-arrow-counterclockwise"></i> Unskip
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="file-card h-100">
                        <div class="d-flex align-items-start">
                            <div class="file-card-icon me-3">
                                <i class="bi {{ $file['icon'] }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="file-card-title">{{ $file['name'] }}</h3>
                                <p class="file-card-description">
                                    {{ $file['description'] }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="badge bg-light text-dark">{{ $file['type'] }}</span>
                                    @if($file['name'] === 'Site Survey')
                                        <div class="d-flex gap-2">
                                            <a href="{{ $file['route'] }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-arrow-right"></i> Open
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#skipSiteSurveyModal">
                                                <i class="bi bi-skip-forward"></i> Skip
                                            </button>
                                        </div>
                                    @else
                                        <a href="{{ $file['route'] }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-arrow-right"></i> Open
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Skip Site Survey Modal -->
    <div class="modal fade" id="skipSiteSurveyModal" tabindex="-1" aria-labelledby="skipSiteSurveyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="skipSiteSurveyModalLabel">
                        <i class="bi bi-exclamation-triangle text-warning me-2"></i>Skip Site Survey
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ isset($enquiry) ? route('enquiries.files.skip-site-survey', $enquiry) : route('projects.files.skip-site-survey', $project) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted mb-3">
                            Are you sure you want to skip the site survey? This will mark the site survey as completed for the client engagement phase.
                        </p>
                        <div class="mb-3">
                            <label for="site_survey_skip_reason" class="form-label">Reason for skipping (optional)</label>
                            <textarea class="form-control" id="site_survey_skip_reason" name="site_survey_skip_reason" rows="3" placeholder="e.g., No physical site visit required, Client provided all necessary information, etc."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-skip-forward me-2"></i>Skip Site Survey
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .file-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .file-card:hover {
        border-color: #0d6efd;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
    
    .disabled-file {
        opacity: 0.6;
        cursor: not-allowed;
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
    
    .skipped-file {
        background-color: #fff3cd;
        border-color: #ffeaa7;
        opacity: 0.9;
    }
    
    .skipped-file:hover {
        border-color: #fdcb6e;
        box-shadow: 0 0.5rem 1rem rgba(253, 203, 110, 0.1);
    }
    
    .skipped-icon {
        color: #f39c12;
    }
    
    .disabled-file:hover {
        border-color: #dee2e6;
        box-shadow: none;
        transform: none;
    }
    
    .disabled-icon {
        background-color: #e9ecef !important;
        color: #6c757d !important;
    }
    
    .file-card-icon {
        font-size: 2rem;
        color: #0d6efd;
        padding: 1rem;
        background-color: rgba(13, 110, 253, 0.1);
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .file-card-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #212529;
        margin-bottom: 0.5rem;
    }
    
    .file-card-description {
        color: #6c757d;
        margin-bottom: 0;
        font-size: 0.9rem;
    }
    
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
</style>
@endsection
