@extends('layouts.master')

@section('title', 'Design & Concept - ' . (isset($enquiry) ? $enquiry->project_name : $project->name))
@section('navbar-title', 'Design & Concept')

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
                        <li class="breadcrumb-item active" aria-current="page">Design & Concept</li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}">{{ $project->name }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Design & Concept</li>
                    @endif
                </ol>
            </nav>
            <h2 class="mb-1">Design & Concept Development</h2>
            <p class="text-muted mb-0">Files and documents related to design and concept development</p>
        </div>
        <a href="{{ (isset($enquiry) && is_object($enquiry) && isset($enquiry->id)) ? route('enquiries.files', $enquiry) : route('projects.files.index', $project) }}" class="btn btn-primary">
            <i class="bi bi-arrow-left me-2"></i>Back to Files & Phases
        </a>
    </div>

    <!-- Files Grid -->
    <div class="row">
        <!-- Mockups Card -->
        <div class="col-lg-6 col-md-6 mb-4">
            <a href="{{ isset($enquiry) ? route('enquiries.files.mockups', $enquiry) : route('projects.files.mockups', $project) }}" class="text-decoration-none">
                <div class="file-card h-100">
                    <div class="d-flex align-items-start">
                        <div class="file-card-icon me-3">
                            <i class="bi bi-images"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="file-card-title">Design Mockups</h3>
                            <p class="file-card-description">
                                Upload and manage design mockups, concepts, and visual assets
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="badge bg-light text-dark">Design</span>
                                <small class="text-muted">{{ isset($enquiry) ? '0' : $designAssets->count() }} files</small>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        @if(!isset($enquiry))
        <!-- Phase Documents Card -->
        <div class="col-lg-6 col-md-6 mb-4">
            @php
                $designPhase = $project->phases()->where('name', 'Design & Concept Development')->first();
                $documentCount = $designPhase ? $designPhase->activeDocuments()->count() : 0;
            @endphp
            <a href="{{ $designPhase ? route('projects.phases.documents.index', [$project, $designPhase]) : '#' }}" class="text-decoration-none {{ !$designPhase ? 'disabled-link' : '' }}">
                <div class="file-card h-100 {{ !$designPhase ? 'disabled-card' : '' }}">
                    <div class="d-flex align-items-start">
                        <div class="file-card-icon me-3">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="file-card-title">Phase Documents</h3>
                            <p class="file-card-description">
                                Upload and manage all documents related to design and concept development phase
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="badge bg-primary text-white">Documents</span>
                                <small class="text-muted">{{ $documentCount }} files</small>
                            </div>
                            @if(!$designPhase)
                                <small class="text-warning">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Phase not found
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif

    @if(isset($enquiry))
        <div class="col-12">
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Design & Concept Development</strong> functionality will be available once the enquiry is converted to a project.
            </div>
        </div>
    @endif

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
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }
        
        .file-card-icon {
            font-size: 1.75rem;
            color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.1);
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .file-card-title {
            color: #212529;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .file-card-description {
            color: #6c757d;
            font-size: 0.875rem;
            margin-bottom: 0.75rem;
        }
        
        .badge {
            font-size: 0.7rem;
            font-weight: 500;
            padding: 0.35em 0.65em;
        }

        .disabled-link {
            pointer-events: none;
            cursor: not-allowed;
        }

        .disabled-card {
            opacity: 0.6;
            background-color: #f8f9fa;
        }

        .disabled-card:hover {
            transform: none;
            box-shadow: none;
            border-color: #e9ecef;
        }
    </style>
@endsection
