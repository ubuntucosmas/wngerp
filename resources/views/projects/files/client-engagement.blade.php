@extends('layouts.master')

@section('title', 'Client Engagement - ' . $project->name)
@section('navbar-title', 'Client Engagement')

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
                <li class="breadcrumb-item active" aria-current="page">Client Engagement</li>
            </ol>
        </nav>
    </div>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Client Engagement & Briefing</h2>
            <p class="text-muted mb-0">Files and documents related to client engagement</p>
        </div>
    </div>

    <!-- Files Grid -->
    <div class="row">
        @foreach($files as $file)
            <div class="col-lg-6 col-md-6 mb-4">
                <a href="{{ $file['route'] }}" class="text-decoration-none">
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
                                    <small class="text-muted">Updated {{ $file['updated_at']->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
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
@endsection
