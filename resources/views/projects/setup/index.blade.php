@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}">Files</a></li>
                        <li class="breadcrumb-item active">Setup & Execution</li>
                    </ol>
                </div>
                <h4 class="page-title">Setup & Execution - {{ $project->name }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-between align-items-center mb-3">
                        <div class="col-auto">
                            <h4 class="header-title mb-0">Setup Reports</h4>
                            <p class="text-muted mb-0">Manage setup reports for this project</p>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addReportModal">
                                <i class="fas fa-plus me-1"></i> Add Report
                            </button>
                        </div>
                    </div>

                    @if($reports->isEmpty())
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-folder-open text-primary" style="font-size: 4rem; opacity: 0.7;"></i>
                            </div>
                            <h4 class="mb-2">No Setup Reports Yet</h4>
                            <p class="text-muted mb-4">Get started by adding your first setup report</p>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addReportModal">
                                <i class="fas fa-plus me-1"></i> Add Report
                            </button>
                        </div>
                    @else
                        <!-- Reports Table -->
                        <div class="table-responsive">
                            <table class="table table-centered table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Uploaded By</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $report)
                                        <tr>
                                            <td>{{ $report->title }}</td>
                                            <td>{{ Str::limit($report->description, 50) }}</td>
                                            <td>{{ $report->uploadedBy->name ?? 'N/A' }}</td>
                                            <td>{{ $report->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ $report->google_drive_link }}" class="btn btn-sm btn-primary" target="_blank">
                                                    <i class="fas fa-external-link-alt me-1"></i> View
                                                </a>
                                                <form action="{{ route('projects.setup.destroy', ['project' => $project, 'setupReport' => $report]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this report?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash me-1"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Report Modal -->
<div class="modal fade" id="addReportModal" tabindex="-1" aria-labelledby="addReportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('projects.setup.store', $project) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addReportModalLabel">Add New Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Report Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="2"></textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="google_drive_link" class="form-label">Google Drive Link <span class="text-danger">*</span></label>
                        <input type="url" class="form-control @error('google_drive_link') is-invalid @enderror" id="google_drive_link" name="google_drive_link" placeholder="https://drive.google.com/..." required>
                        <div class="form-text">Paste the full Google Drive shareable link to your report</div>
                        @error('google_drive_link')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        border: none;
        box-shadow: 0 0 35px 0 rgba(154, 161, 171, 0.15);
        border-radius: 10px;
    }
    .btn-primary {
        padding: 0.45rem 1.25rem;
        font-weight: 500;
    }
</style>
@endpush

@push('scripts')
<script>
    // Scripts for handling the modal and form submission will go here
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize any scripts here
    });
</script>
<!-- Dropzone js -->
<script src="{{ asset('assets/vendor/dropzone/min/dropzone.min.js') }}"></script>
<script>
    // Initialize Dropzone
    Dropzone.autoDiscover = false;
    
    $(document).ready(function() {
        // Initialize dropzone
        $("#dropzone").dropzone({
            url: "#", // Update this with your upload URL
            addRemoveLinks: true,
            maxFiles: 1,
            maxFilesize: 5, // MB
            acceptedFiles: '.pdf,.doc,.docx',
            dictDefaultMessage: "Drop files here or click to upload",
            dictRemoveFile: "Remove",
            init: function() {
                this.on("maxfilesexceeded", function(file) {
                    this.removeAllFiles();
                    this.addFile(file);
                });
            },
            success: function(file, response) {
                // Handle successful upload
                console.log("File uploaded:", file);
                // You can add the file to the table here
            },
            error: function(file, message) {
                alert(message);
                this.removeFile(file);
            }
        });
    });
</script>
@endpush

@push('styles')
<!-- Dropzone css -->
<link href="{{ asset('assets/vendor/dropzone/min/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
<style>
    .dropzone {
        min-height: 200px;
        border: 2px dashed #e3e6f0;
        background: #f8f9fc;
        border-radius: 6px;
    }
    .dropzone .dz-message {
        font-size: 24px;
        min-height: 150px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .dropzone .dz-message i {
        font-size: 48px;
        margin-bottom: 20px;
    }
</style>
@endpush

@endsection