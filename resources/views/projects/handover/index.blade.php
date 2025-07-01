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
                        <li class="breadcrumb-item active">Client Handover</li>
                    </ol>
                </div>
                <h4 class="page-title">Client Handover - {{ $project->name }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-between align-items-center mb-3">
                        <div class="col-auto">
                            <h4 class="header-title mb-0">Handover Documents</h4>
                            <p class="text-muted mb-0">Manage client handover documents and sign-offs</p>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addHandoverModal">
                                <i class="fas fa-plus me-1"></i> Add Document
                            </button>
                        </div>
                    </div>

                    @if($reports->isEmpty())
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-clipboard-check text-primary" style="font-size: 4rem; opacity: 0.7;"></i>
                            </div>
                            <h4 class="mb-2">No Handover Documents Yet</h4>
                            <p class="text-muted mb-4">Get started by adding your first handover document</p>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addHandoverModal">
                                <i class="fas fa-plus me-1"></i> Add Document
                            </button>
                        </div>
                    @else
                        <!-- Handover Documents Table -->
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
                                                <form action="{{ route('projects.handover.destroy', ['project' => $project, 'handoverReport' => $report]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this document?')">
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

<!-- Add Handover Document Modal -->
<div class="modal fade" id="addHandoverModal" tabindex="-1" aria-labelledby="addHandoverModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('projects.handover.store', $project) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addHandoverModalLabel">Add Handover Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="google_drive_link" class="form-label">Google Drive Link <span class="text-danger">*</span></label>
                        <input type="url" class="form-control @error('google_drive_link') is-invalid @enderror" id="google_drive_link" name="google_drive_link" value="{{ old('google_drive_link') }}" required>
                        <div class="form-text">Paste the link to the Google Drive document</div>
                        @error('google_drive_link')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Document</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .phase-card {
        border: 1px solid #e3e6f0;
        border-radius: 0.35rem;
        transition: all 0.3s ease;
    }
    .phase-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    .phase-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(12, 45, 72, 0.1);
        border-radius: 50%;
        color: #0c2d48;
    }
    .phase-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: #0c2d48;
    }
    .phase-description {
        font-size: 0.75rem;
        min-height: 36px;
    }
</style>
@endpush
