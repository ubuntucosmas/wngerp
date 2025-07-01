@extends('layouts.master')

@section('title', 'Setup & Execution - ' . $project->name)
@section('navbar-title', 'Setup & Execution')

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
                <li class="breadcrumb-item active" aria-current="page">Setup & Execution</li>
            </ol>
        </nav>
    </div>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Setup & Execution</h2>
            <p class="text-muted mb-0">Files and documents related to the setup and execution phase</p>
        </div>
    </div>

    <!-- Single File Card for Setup Report -->
    <div class="row">
        <div class="col-lg-6 col-md-6 mb-4">
            <a href="{{ route('projects.setup.index', $project) }}" class="text-decoration-none">
                <div class="file-card h-100">
                    <div class="d-flex align-items-start">
                        <div class="file-card-icon me-3">
                            <i class="bi bi-clipboard-data"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="file-card-title">Setup & Execution</h3>
                            <p class="file-card-description">
                                Manage setup reports, execution plans, and related documents for this project.
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="badge bg-light text-dark">Setup</span>
                                <small class="text-muted">Last updated {{ $uploaded_at ?? 'N/A' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
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
</div>
@endsection
