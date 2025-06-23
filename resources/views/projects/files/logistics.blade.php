@extends('layouts.master')

@section('title', 'Logistics - ' . $project->name)
@section('navbar-title', 'Logistics')

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
                <li class="breadcrumb-item active" aria-current="page">Logistics</li>
            </ol>
        </nav>
    </div>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Logistics Management</h2>
            <p class="text-muted mb-0">Manage loading sheets, booking orders, and logistics operations</p>
        </div>
    </div>

    <!-- Files Grid -->
    <div class="row">
        <!-- Loading Sheet Card -->
        <div class="col-lg-6 col-md-6 mb-4">
            <a href="{{ route('projects.logistics.loading-sheet', $project) }}" class="text-decoration-none">
                <div class="file-card h-100">
                    <div class="d-flex align-items-start">
                        <div class="file-card-icon me-3">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="file-card-title">Loading Sheet</h3>
                            <p class="file-card-description">
                                Create and manage loading sheets for equipment and materials
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="badge bg-light text-dark">Logistics</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Booking Orders Card -->
        <div class="col-lg-6 col-md-6 mb-4">
            <a href="{{ route('projects.logistics.booking-orders.index', $project) }}" class="text-decoration-none">
                <div class="file-card h-100">
                    <div class="d-flex align-items-start">
                        <div class="file-card-icon me-3">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="file-card-title">Booking Orders</h3>
                            <p class="file-card-description">
                                Manage booking orders, schedules, and related documentation
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="badge bg-light text-dark">Logistics</span>
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
        flex-shrink: 0;
    }
    
    .file-card-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #212529;
    }
    
    .file-card-description {
        color: #6c757d;
        margin-bottom: 0;
        font-size: 0.9rem;
        line-height: 1.5;
    }
    
    .breadcrumb {
        background: transparent;
        padding: 0.5rem 0;
        margin-bottom: 0;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        content: ">";
    }
    
    .breadcrumb-item a {
        color: #6c757d;
        text-decoration: none;
    }
    
    .breadcrumb-item.active {
        color: #0d6efd;
    }
</style>
@endsection
