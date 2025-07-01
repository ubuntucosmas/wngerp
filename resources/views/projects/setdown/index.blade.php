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
                        <li class="breadcrumb-item active">Set Down & Return</li>
                    </ol>
                </div>
                <h4 class="page-title">Set Down & Return - {{ $project->name }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-between align-items-center mb-3">
                        <div class="col-auto">
                            <h4 class="header-title mb-0">Set Down & Return Documents</h4>
                            <p class="text-muted mb-0">Manage set down and return documentation</p>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSetDownReturnModal">
                                <i class="fas fa-plus me-1"></i> Add Document
                            </button>
                        </div>
                    </div>

                    @if($reports->isEmpty())
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-truck-loading text-primary" style="font-size: 4rem; opacity: 0.7;"></i>
                            </div>
                            <h4 class="mb-2">No Set Down & Return Documents Yet</h4>
                            <p class="text-muted mb-4">Get started by adding your first set down & return document</p>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSetDownReturnModal">
                                <i class="fas fa-plus me-1"></i> Add Document
                            </button>
                        </div>
                    @else
                        <!-- Set Down & Return Documents Table -->
                        <div class="table-responsive">
                            <table class="table table-centered table-striped table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Uploaded By</th>
                                        <th>Date</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $report)
                                        <tr>
                                            <td>
                                                <a href="{{ $report->google_drive_link }}" target="_blank" class="text-primary">
                                                    <i class="fas fa-external-link-alt me-1"></i> {{ $report->title }}
                                                </a>
                                            </td>
                                            <td>{{ Str::limit($report->description, 50) }}</td>
                                            <td>{{ $report->uploadedBy->name }}</td>
                                            <td>{{ $report->created_at->format('M d, Y') }}</td>
                                            <td class="text-end">
                                                <a href="{{ $report->google_drive_link }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-setdown-return" data-id="{{ $report->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
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

<!-- Add Set Down & Return Document Modal -->
<div class="modal fade" id="addSetDownReturnModal" tabindex="-1" aria-labelledby="addSetDownReturnModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSetDownReturnModalLabel">Add Set Down & Return Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('projects.set-down-return.store', $project) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Document Title <span class="text-danger">*</span></label>
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
                        <small class="form-text text-muted">Paste the full URL to the document in Google Drive</small>
                        @error('google_drive_link')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Document</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteSetDownReturnModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this set down & return document? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteSetDownReturnForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Document</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle delete button clicks
        document.querySelectorAll('.delete-setdown-return').forEach(button => {
            button.addEventListener('click', function() {
                const reportId = this.getAttribute('data-id');
                const form = document.getElementById('deleteSetDownReturnForm');
                form.action = `/projects/{{ $project->id }}/set-down-return/${reportId}`;
                
                const modal = new bootstrap.Modal(document.getElementById('deleteSetDownReturnModal'));
                modal.show();
            });
        });

        // Reset form on modal close
        const addModal = document.getElementById('addSetDownReturnModal');
        if (addModal) {
            addModal.addEventListener('hidden.bs.modal', function () {
                const form = this.querySelector('form');
                if (form) {
                    form.reset();
                    // Clear validation errors
                    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                    form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
                }
            });
        }
    });
</script>
@endpush
