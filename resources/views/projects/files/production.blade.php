@extends('layouts.master')

@section('title', 'Production - ' . $project->name)
@section('navbar-title', 'Production')

@section('content')
<div class="px-3 mx-10 w-100">
    <!-- Back button and breadcrumb -->
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('projects.files.index', $project) }}" class="btn btn-outline-secondary btn-sm me-3">
            <i class="bi bi-arrow-left"></i> Back to Project Files
        </a>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}">Project Files</a></li>
                <li class="breadcrumb-item active" aria-current="page">Production</li>
            </ol>
        </nav>
    </div>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Production</h2>
            <p class="text-muted mb-0">Files and documents related to production management</p>
        </div>
    </div>

    <!-- Files Grid -->
    <div class="row">
        <!-- Job Brief Card -->
        <div class="col-lg-6 col-md-6 mb-4">
            <a href="{{ route('projects.production.job-brief', $project) }}" class="text-decoration-none">
                <div class="file-card h-100">
                    <div class="d-flex align-items-start">
                        <div class="file-card-icon me-3">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="file-card-title">Job Brief</h3>
                            <p class="file-card-description">
                                Create and manage job briefs for production planning and execution
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="badge bg-light text-dark">Production</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Production Schedule Card -->
        <div class="col-lg-6 col-md-6 mb-4">
            <a href="#" class="text-decoration-none">
                <div class="file-card h-100">
                    <div class="d-flex align-items-start">
                        <div class="file-card-icon me-3">
                            <i class="bi bi-calendar-week"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="file-card-title">Production Schedule</h3>
                            <p class="file-card-description">
                                View and manage production timelines and milestones
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="badge bg-light text-dark">Production</span>
                                <small class="text-muted">Coming Soon</small>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Quality Control Card -->
        <div class="col-lg-6 col-md-6 mb-4">
            <a href="#" class="text-decoration-none">
                <div class="file-card h-100">
                    <div class="d-flex align-items-start">
                        <div class="file-card-icon me-3">
                            <i class="bi bi-clipboard-check"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="file-card-title">Quality Control</h3>
                            <p class="file-card-description">
                                Track quality checks and inspections
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="badge bg-light text-dark">Production</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        

        <!-- Production Reports Card -->
        <div class="col-lg-6 col-md-6 mb-4">
            <a href="#" class="text-decoration-none">
                <div class="file-card h-100">
                    <div class="d-flex align-items-start">
                        <div class="file-card-icon me-3">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="file-card-title">Production Reports</h3>
                            <p class="file-card-description">
                                Generate and view production reports and analytics
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="badge bg-light text-dark">Production</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
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
    </style>

@endsection
