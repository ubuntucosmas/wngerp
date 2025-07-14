@extends('layouts.master')

@section('title', isset($enquiry) ? 'Enquiry Design Assets' : 'Project Design Assets')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    @if(isset($enquiry))
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.index') }}">Enquiries</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.files', $enquiry) }}">{{ $enquiry->project_name }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.files.design-concept', $enquiry) }}">Design & Concept</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Design Mockups</li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}">{{ $project->name }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('projects.files.design-concept', $project) }}">Design & Concept</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Design Mockups</li>
                    @endif
                </ol>
            </nav>
            <h2 class="fw-bold mb-0">Design Mockups</h2>
        </div>
        <div class="page-actions">
            <a href="{{ (isset($enquiry) && is_object($enquiry) && isset($enquiry->id)) ? route('enquiries.files.design-concept', $enquiry) : route('projects.files.design-concept', $project) }}" class="btn btn-primary me-2">
                <i class="bi bi-arrow-left me-2"></i>Back to Design & Concept
            </a>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMockupModal">
                <i class="bi bi-plus-lg me-1"></i> Upload Design Asset
            </button>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Design Assets</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addMockupModal">
                <i class="bi bi-plus-lg me-1"></i> Add Design Asset
            </button>
        </div>
        <div class="card-body">
            @php $assets = isset($enquiry) ? $designAssets : $designAssets; @endphp
            @if($assets && $assets->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <!-- <th>File Type</th>
                                <th>Size</th> -->
                                <th>Uploaded By</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assets as $asset)
                            <tr>
                                <td>
                                    <i class="bi bi-file-earmark-{{ str_contains($asset->file_type ?? '', 'image') ? 'image' : 'text' }} me-2"></i>
                                    {{ $asset->name ?? ($asset->file_name ?? 'Untitled') }}
                                    @if(!empty($asset->description))
                                        <p class="text-muted small mb-0">{{ Str::limit($asset->description, 50) }}</p>
                                    @endif
                                </td>
                                <!-- <td>{{ strtoupper(pathinfo($asset->file_name, PATHINFO_EXTENSION)) }}</td>
                                <td>{{ $asset->file_size }}</td> -->
                                <td>{{ $asset->user->name ?? 'N/A' }}</td>
                                <td>{{ $asset->created_at ? \Carbon\Carbon::parse($asset->created_at)->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        @if(!empty($asset->file_url))
                                        <a href="{{ $asset->file_url }}" target="_blank" class="btn btn-outline-primary" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ $asset->file_url }}&export=download" class="btn btn-outline-secondary" title="Download">
                                            <i class="bi bi-download"></i>
                                        </a>
                                        @endif
                                        <form action="{{ isset($enquiry) ? route('enquiries.files.design-assets.destroy', ['enquiry' => $enquiry->id, 'design_asset' => $asset->id]) : route('projects.files.design-assets.destroy', ['project' => $project->id, 'design_asset' => $asset->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-outline-danger delete-asset" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-folder-x display-1 text-muted"></i>
                    <h4 class="mt-3">No Design Assets found</h4>
                    <p class="text-muted">Add your first design asset to get started</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMockupModal">
                        <i class="bi bi-plus-lg me-1"></i> Add Design Asset
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Mockup Modal -->
<div class="modal fade" id="addMockupModal" tabindex="-1" aria-labelledby="addMockupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMockupModalLabel">Add New Design Asset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ isset($enquiry) ? route('enquiries.files.design-assets.store', $enquiry) : route('projects.files.design-assets.store', $project) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Design Asset Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="file_url" class="form-label">Google Drive Link <span class="text-danger">*</span></label>
                        <input type="url" class="form-control @error('file_url') is-invalid @enderror" 
                               id="file_url" name="file_url" 
                               value="{{ old('file_url') }}" 
                               placeholder="https://drive.google.com/file/d/..." required>
                        @error('file_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Paste the shareable link from Google Drive</div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" 
                                  rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Save Design Asset
                    </button>
                </div>
            </form>
            
            @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            @endpush
        </div>
    </div>

<!-- Edit Asset Modal -->
<div class="modal fade" id="editAssetModal" tabindex="-1" aria-labelledby="editAssetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAssetModalLabel">Edit Design Asset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Design Asset Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_file_url" class="form-label">Google Drive Link <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" 
                               id="edit_file_url" name="file_url" 
                               placeholder="https://drive.google.com/file/d/..." required>
                        <div class="form-text">Paste the shareable link from Google Drive</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_file_type" class="form-label">File Type <span class="text-danger">*</span></label>
                                <select class="form-select" id="edit_file_type" name="file_type" required>
                                    <option value="image/jpeg">JPEG Image</option>
                                    <option value="image/png">PNG Image</option>
                                    <option value="application/pdf">PDF Document</option>
                                    <option value="application/msword">Word Document</option>
                                    <option value="application/vnd.ms-excel">Excel Spreadsheet</option>
                                    <option value="application/zip">ZIP Archive</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_file_size" class="form-label">File Size <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control" 
                                           id="edit_file_size" name="file_size" 
                                           step="0.1" min="0" required>
                                    <span class="input-group-text">MB</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" 
                                  id="edit_description" name="description" 
                                  rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Update Design Asset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .btn-group .btn {
        border-radius: 0.25rem !important;
    }
    .btn-group .btn:not(:last-child) {
        margin-right: 0.25rem;
    }
</style>
@endpush

@push('scripts')
<script>
    // Delete confirmation
    document.addEventListener('DOMContentLoaded', function() {
        // Delete confirmation
        document.querySelectorAll('.delete-asset').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Edit asset modal handler
        @if(isset($project) || isset($enquiry))
        document.querySelectorAll('.edit-asset').forEach(button => {
            button.addEventListener('click', function() {
                const modal = document.getElementById('editAssetModal');
                const assetId = this.dataset.id;
                @if(isset($project))
                const projectId = {{ $project->id }};
                const updateUrl = `{{ route('projects.files.design-assets.update', ['project' => ':projectId', 'design_asset' => ':assetId']) }}`
                    .replace(':projectId', projectId)
                    .replace(':assetId', assetId);
                @elseif(isset($enquiry))
                const enquiryId = {{ $enquiry->id }};
                const updateUrl = `{{ route('enquiries.files.design-assets.update', ['enquiry' => ':enquiryId', 'design_asset' => ':assetId']) }}`
                    .replace(':enquiryId', enquiryId)
                    .replace(':assetId', assetId);
                @endif
                
                // Set form action
                const form = modal.querySelector('form');
                form.action = updateUrl;
                
                // Fill form fields
                form.querySelector('#edit_name').value = this.dataset.name || '';
                form.querySelector('#edit_file_url').value = this.dataset.fileUrl || '';
                form.querySelector('#edit_file_type').value = this.dataset.fileType || '';
                form.querySelector('#edit_file_size').value = this.dataset.fileSize || '';
                form.querySelector('#edit_description').value = this.dataset.description || '';
            });
        });
        @endif

        // Auto-detect file type and size in edit modal
        const editFileUrlInput = document.getElementById('edit_file_url');
        if (editFileUrlInput) {
            editFileUrlInput.addEventListener('change', async function() {
                const url = this.value.trim();
                if (!url) return;
                
                // Detect file type
                const lowerUrl = url.toLowerCase();
                const fileTypeSelect = document.getElementById('edit_file_type');
                
                if (lowerUrl.includes('.jpg') || lowerUrl.includes('.jpeg')) {
                    fileTypeSelect.value = 'image/jpeg';
                } else if (lowerUrl.includes('.png')) {
                    fileTypeSelect.value = 'image/png';
                } else if (lowerUrl.includes('.pdf')) {
                    fileTypeSelect.value = 'application/pdf';
                } else if (lowerUrl.includes('.doc') || lowerUrl.includes('.docx')) {
                    fileTypeSelect.value = 'application/msword';
                } else if (lowerUrl.includes('.xls') || lowerUrl.includes('.xlsx')) {
                    fileTypeSelect.value = 'application/vnd.ms-excel';
                } else if (lowerUrl.includes('.zip') || lowerUrl.includes('.rar')) {
                    fileTypeSelect.value = 'application/zip';
                } else {
                    fileTypeSelect.value = 'other';
                }
                
                // Get file size
                try {
                    const response = await fetch(url, { method: 'HEAD' });
                    const contentLength = response.headers.get('content-length');
                    
                    if (contentLength) {
                        const sizeInMB = (contentLength / (1024 * 1024)).toFixed(2);
                        document.getElementById('edit_file_size').value = sizeInMB;
                    }
                } catch (error) {
                    console.error('Error fetching file size:', error);
                }
            });
        }

        // Function to detect file type from URL (for add form)
        function detectFileType(url) {
            const fileTypeSelect = document.getElementById('file_type');
            if (!fileTypeSelect) return;
            
            const lowerUrl = url.toLowerCase();
            
            if (lowerUrl.includes('.jpg') || lowerUrl.includes('.jpeg')) {
                fileTypeSelect.value = 'image/jpeg';
            } else if (lowerUrl.includes('.png')) {
                fileTypeSelect.value = 'image/png';
            } else if (lowerUrl.includes('.pdf')) {
                fileTypeSelect.value = 'application/pdf';
            } else if (lowerUrl.includes('.doc') || lowerUrl.includes('.docx')) {
                fileTypeSelect.value = 'application/msword';
            } else if (lowerUrl.includes('.xls') || lowerUrl.includes('.xlsx')) {
                fileTypeSelect.value = 'application/vnd.ms-excel';
            } else if (lowerUrl.includes('.zip') || lowerUrl.includes('.rar')) {
                fileTypeSelect.value = 'application/zip';
            } else {
                fileTypeSelect.value = 'other';
            }
        }

        // Function to get file size from URL (for add form)
        async function getFileSize(url) {
            try {
                const response = await fetch(url, { method: 'HEAD' });
                const contentLength = response.headers.get('content-length');
                
                if (contentLength) {
                    // Convert bytes to MB and round to 2 decimal places
                    const sizeInMB = (contentLength / (1024 * 1024)).toFixed(2);
                    const fileSizeInput = document.getElementById('file_size');
                    if (fileSizeInput) {
                        fileSizeInput.value = sizeInMB;
                    }
                }
            } catch (error) {
                console.error('Error fetching file size:', error);
            }
        }

        // Add event listener for URL input (add form)
        const fileUrlInput = document.getElementById('file_url');
        if (fileUrlInput) {
            fileUrlInput.addEventListener('change', async function() {
                const url = this.value.trim();
                if (!url) return;
                
                // First detect file type
                detectFileType(url);
                
                // Then try to get file size
                await getFileSize(url);
            });
        }
    });
</script>
@endpush

@endsection
