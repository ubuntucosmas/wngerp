@extends('layouts.master')

@section('title', 'Archival & Reporting - ' . $project->name)
@section('navbar-title', 'Archival & Reporting')

@push('styles')
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
@endpush

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
                <li class="breadcrumb-item active" aria-current="page">Archival & Reporting</li>
            </ol>
        </nav>
    </div>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Archival & Reporting</h2>
            <p class="text-muted mb-0">Final project reports and archival documents</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('projects.close-out-report.index', $project) }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-clipboard-check me-1"></i> Close-Out Report
            </a>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDocumentModal">
                <i class="bi bi-plus-lg me-1"></i> Add Document
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Single Card for Final Report -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Final Project Report</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="file-card-icon me-3">
                            <i class="bi bi-archive"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted mb-0">
                                View and manage the final project report and related documents.
                            </p>
                            @if(isset($reports['final_report']) && $reports['final_report']->count() > 0)
                                @php $latestReport = $reports['final_report']->first(); @endphp
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="badge bg-light text-dark">Final Report</span>
                                    <small class="text-muted">Last updated {{ $latestReport->report_date->diffForHumans() }}</small>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-file-earmark-text" style="font-size: 3rem; color: #6c757d;"></i>
                                    <h5 class="text-muted mt-3">No Final Report Uploaded</h5>
                                    <button type="button" class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#addDocumentModal">
                                        <i class="bi bi-plus-lg me-1"></i> Add Final Report
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Document Modal -->
    <div class="modal fade" id="addDocumentModal" tabindex="-1" aria-labelledby="addDocumentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('projects.archival.store', $project) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addDocumentModalLabel">Add Archival Document</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Document Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                     id="description" name="description" rows="2">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="report_type" class="form-label">Document Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('report_type') is-invalid @enderror" 
                                        id="report_type" name="report_type" required>
                                    <option value="" disabled selected>Select document type</option>
                                    @foreach($reportTypes as $key => $name)
                                        <option value="{{ $key }}" {{ old('report_type') == $key ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('report_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="report_date" class="form-label">Report Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('report_date') is-invalid @enderror" 
                                       id="report_date" name="report_date" value="{{ old('report_date', now()->format('Y-m-d')) }}" required>
                                @error('report_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="google_drive_link" class="form-label">Google Drive Link <span class="text-danger">*</span></label>
                            <input type="url" class="form-control @error('google_drive_link') is-invalid @enderror" 
                                   id="google_drive_link" name="google_drive_link" 
                                   placeholder="https://drive.google.com/..." value="{{ old('google_drive_link') }}" required>
                            <small class="form-text text-muted">Paste the full Google Drive URL to the document</small>
                            @error('google_drive_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">Save Document</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set the document type when clicking on the "Add Document" button for a specific type
            $('[data-bs-target="#addDocumentModal"]').on('click', function() {
                const type = $(this).data('type');
                if (type) {
                    $('#report_type').val(type);
                }
            });

            // Set today's date as default for the date field
            document.getElementById('report_date').valueAsDate = new Date();
        });
    </script>
    @endpush

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteDocumentModal" tabindex="-1" aria-labelledby="deleteDocumentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteDocumentModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteDocumentForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        Are you sure you want to delete this document? This action cannot be undone.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-sm">Delete Document</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
