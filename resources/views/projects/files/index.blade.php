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
        padding: 2rem;
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

</style>

<div class="container mt-1 mb-1">
        <div class="project-header text-center">
        <h2>{{ $project->name }}</h2>
        <p class="lead">Project Files & Documents</p>
    </div>
    <hr>
        <div class="row mt-1">
        @if (empty($fileTypes))
            <div class="col-12">
                <div class="alert-minimal" role="alert">
                    <i class="bi bi-info-circle"></i>
                    <span>No project files or documents have been linked to this project yet.</span>
                </div>
            </div>
        @else
            @foreach ($fileTypes as $fileType)
                <div class="col-lg-3 col-md-6 mb-1 d-flex align-items-stretch">
                    <div class="file-card w-100">
                        <div class="file-card-icon">
                            <i class="bi {{ $fileType['name'] == 'Booking Order' ? 'bi-receipt-cutoff' : 'bi-file-earmark-zip' }}"></i>
                        </div>
                        <h3 class="file-card-title">{{ $fileType['name'] }}</h3>
                        <p class="file-card-description">
                            Access and manage the {{ strtolower($fileType['name']) }} for this project.
                        </p>
                        <a href="{{ $fileType['route'] }}" class="btn btn-minimal">
                            <span>Manage</span>
                            <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
