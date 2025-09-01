@extends('layouts.master')

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('styles')
<style>
.document-card {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
    border-radius: 0.75rem;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.document-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    border-color: #0d6efd;
}

.document-card .card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    padding: 1.25rem;
}

.document-icon {
    font-size: 2.5rem;
    margin-bottom: 0.75rem;
}

.document-actions {
    margin-top: auto;
    padding-top: 1rem;
    border-top: 1px solid #f0f0f0;
}

.document-actions .btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    font-size: 0.875rem;
}

.document-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.document-actions .btn-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    color: white;
}

.document-actions .btn-info {
    background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%);
    color: white;
}

.document-actions .btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
}

.document-actions .btn-outline-secondary {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    color: #6c757d;
}

.document-actions .btn-outline-secondary:hover {
    background: #e9ecef;
    transform: none;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.document-meta {
    flex: 1;
}

.document-meta .badge {
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.35em 0.65em;
}

.document-description {
    background: #f8f9fa;
    border-left: 3px solid #0dcaf0;
    padding: 0.5rem 0.75rem;
    border-radius: 0.25rem;
    font-style: italic;
}

.card-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #2c3e50;
    line-height: 1.3;
    margin-bottom: 0.75rem;
}

/* User document indicator */
.user-document {
    border-color: #28a745 !important;
    position: relative;
}

.user-document:hover {
    border-color: #20c997 !important;
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.2);
}

.user-badge {
    position: absolute;
    top: -1px;
    right: -1px;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 0 0.75rem 0 0.5rem;
    font-size: 0.65rem;
    font-weight: 600;
    z-index: 10;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.user-badge i {
    margin-right: 0.25rem;
}

/* Enhanced button hover effects */
.document-actions .btn:active {
    transform: translateY(0);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

/* Button loading states */
.document-actions .btn .fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Button pulse effect on hover */
.document-actions .btn:hover {
    animation: pulse 0.6s ease-in-out;
}

@keyframes pulse {
    0% { transform: scale(1) translateY(-2px); }
    50% { transform: scale(1.05) translateY(-2px); }
    100% { transform: scale(1) translateY(-2px); }
}

/* Preview Modal Styles */
#previewModal .modal-dialog {
    max-width: 90vw;
}

#previewModal .modal-body {
    min-height: 400px;
}

#previewModal iframe {
    border: none;
    border-radius: 0.375rem;
}

#previewModal img {
    border-radius: 0.375rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

#previewModal video {
    border-radius: 0.375rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

#previewModal pre {
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
    line-height: 1.5;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .document-actions .btn {
        width: 32px;
        height: 32px;
        font-size: 0.8rem;
    }
    
    .user-badge {
        font-size: 0.6rem;
        padding: 0.2rem 0.4rem;
    }
    
    .document-actions {
        padding-top: 0.75rem;
    }
}

.upload-zone {
    border: 2px dashed #dee2e6;
    border-radius: 0.5rem;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    min-height: 200px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.upload-zone:hover {
    border-color: #0d6efd;
    background-color: #f8f9fa;
}

.upload-zone.dragover {
    border-color: #0d6efd;
    background-color: #e3f2fd;
}

.file-preview {
    max-height: 300px;
    overflow-y: auto;
}

.progress-container {
    display: none;
}

/* Document cards are now using Bootstrap grid system */
.document-card {
    height: 100%;
    min-height: 320px;
}

/* Ensure equal height cards in each row */
.row.g-4 {
    --bs-gutter-x: 1.5rem;
    --bs-gutter-y: 1.5rem;
}

/* Enhanced breadcrumb styling */
.breadcrumb {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 1px solid #dee2e6;
    font-size: 0.9rem;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    font-weight: bold;
    color: #6c757d;
}

.breadcrumb-item.active {
    font-weight: 600;
    color: #495057;
}

.breadcrumb a {
    color: #0d6efd;
    transition: color 0.2s ease;
}

.breadcrumb a:hover {
    color: #0a58ca;
}

/* Enhanced header gradient */
.bg-gradient-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
}

/* Stats cards hover effect */
.bg-primary.bg-opacity-10:hover,
.bg-success.bg-opacity-10:hover,
.bg-danger.bg-opacity-10:hover,
.bg-warning.bg-opacity-10:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Navigation Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-light p-3 rounded shadow-sm">
            <li class="breadcrumb-item">
                <a href="{{ route('projects.index') }}" class="text-decoration-none">
                    <i class="bi bi-house-door me-1"></i>
                    Projects
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('projects.files.index', $project) }}" class="text-decoration-none">
                    <i class="bi bi-folder me-1"></i>
                    {{ Str::limit($project->name, 30) }}
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('projects.files.design-concept', $project) }}" class="text-decoration-none">
                    <i class="bi bi-brush me-1"></i>
                    Design & Concept
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <i class="bi bi-file-earmark-text me-1"></i>
                Phase Documents
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-primary text-white">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1 text-black">
                                <i class="bi bi-folder2-open me-2"></i>
                                {{ $phase->name }} - Documents
                            </h4>
                            <p class="mb-0 opacity-75 text-black">
                                <i class="bi bi-building me-1"></i>
                                Project: {{ $project->name }} ({{ $project->project_id }})
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('projects.files.design-concept', $project) }}" class="btn btn-outline-light btn-sm me-2">
                                <i class="bi bi-arrow-left me-1"></i>
                                Back
                            </a>
                            <button type="button" class="btn btn-light btn-sm me-2" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                <i class="bi bi-cloud-upload me-1"></i>
                                Upload
                            </button>
                            @if($documents->count() > 0)
                            <a href="{{ route('projects.phases.documents.bulk-download', [$project, $phase]) }}" class="btn btn-success btn-sm">
                                <i class="bi bi-download me-1"></i>
                                Download All
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <div class="d-flex align-items-center p-3 bg-primary bg-opacity-10 rounded">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-files display-6 text-primary"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="h4 mb-0 text-primary">{{ $documents->count() }}</div>
                                    <small class="text-muted">Total Documents</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="d-flex align-items-center p-3 bg-success bg-opacity-10 rounded">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-image display-6 text-success"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="h4 mb-0 text-success">{{ $documents->where('document_type', 'image')->count() }}</div>
                                    <small class="text-muted">Images</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="d-flex align-items-center p-3 bg-danger bg-opacity-10 rounded">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-file-pdf display-6 text-danger"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="h4 mb-0 text-danger">{{ $documents->where('document_type', 'pdf')->count() }}</div>
                                    <small class="text-muted">PDFs</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="d-flex align-items-center p-3 bg-warning bg-opacity-10 rounded">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-file-text display-6 text-warning"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="h4 mb-0 text-warning">{{ $documents->whereIn('document_type', ['document', 'spreadsheet', 'presentation'])->count() }}</div>
                                    <small class="text-muted">Office Docs</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Sort Section -->
    @if($documents->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <label class="form-label me-2 mb-0">Filter by type:</label>
                                <select class="form-select form-select-sm" id="fileTypeFilter" style="width: auto;">
                                    <option value="">All Files</option>
                                    <option value="image">Images</option>
                                    <option value="pdf">PDFs</option>
                                    <option value="document">Documents</option>
                                    <option value="spreadsheet">Spreadsheets</option>
                                    <option value="presentation">Presentations</option>
                                    <option value="video">Videos</option>
                                    <option value="audio">Audio</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="d-flex align-items-center justify-content-end">
                                <label class="form-label me-2 mb-0">Sort by:</label>
                                <select class="form-select form-select-sm" id="sortBy" style="width: auto;">
                                    <option value="newest">Newest First</option>
                                    <option value="oldest">Oldest First</option>
                                    <option value="name">Name A-Z</option>
                                    <option value="name-desc">Name Z-A</option>
                                    <option value="size">Size (Largest)</option>
                                    <option value="size-desc">Size (Smallest)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Documents Grid -->
    @if($documents->count() > 0)
    <div class="row g-4" id="documentsGrid">
        @foreach($documents as $document)
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
        <div class="card document-card {{ $document->uploaded_by === auth()->id() ? 'user-document' : '' }}">
            @if($document->uploaded_by === auth()->id())
            <div class="user-badge">
                <i class="bi bi-person-check"></i>
                <small>Your Document</small>
            </div>
            @endif
            <div class="card-body text-center">
                <div class="document-icon">
                    <i class="{{ $document->icon_class }}"></i>
                </div>
                <h6 class="card-title mb-2" title="{{ $document->original_filename }}">
                    {{ Str::limit($document->original_filename, 30) }}
                </h6>
                
                <!-- File Metadata -->
                <div class="document-meta mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="bg-light text-dark">{{ $document->file_size_human }}</span>
                        <span class="badge bg-{{ $document->document_type === 'pdf' ? 'danger' : ($document->document_type === 'image' ? 'success' : 'primary') }}">
                            {{ strtoupper($document->file_extension) }}
                        </span>
                    </div>
                    <small class="text-muted d-block">{{ $document->created_at->format('M d, Y H:i') }}</small>
                    <small class="text-muted d-block">by {{ $document->uploader->name }}</small>
                </div>
                
                @if($document->description)
                <div class="document-description mb-3">
                    <p class="card-text small text-secondary mb-0" title="{{ $document->description }}">
                        <i class="bi bi-quote me-1"></i>
                        {{ Str::limit($document->description, 60) }}
                    </p>
                </div>
                @endif
                <!-- Action Buttons - Icon Only -->
                <div class="document-actions mt-3">
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('projects.phases.documents.download', [$project, $phase, $document]) }}" 
                           class="btn btn-primary btn-sm download-btn" 
                           title="Download {{ $document->original_filename }} ({{ $document->file_size_human }})"
                           data-bs-toggle="tooltip" 
                           data-bs-placement="top"
                           onclick="showDownloadProgress(this)">
                            <i class="bi bi-download"></i>
                        </a>
                        <button type="button" class="btn btn-info btn-sm" 
                                onclick="previewDocument({{ $document->id }}, '{{ $document->original_filename }}', '{{ $document->document_type }}', '{{ $document->file_extension }}', '{{ route('projects.phases.documents.preview', [$project, $phase, $document]) }}', '{{ $document->description }}', '{{ $document->uploader->name }}', '{{ $document->created_at->format('M d, Y H:i') }}', '{{ $document->file_size_human }}', '{{ route('projects.phases.documents.download', [$project, $phase, $document]) }}')"
                                title="Preview {{ $document->original_filename }}"
                                data-bs-toggle="tooltip" 
                                data-bs-placement="top">
                            <i class="bi bi-eye"></i>
                        </button>
                        @if($document->uploaded_by === auth()->id() || auth()->user()->hasRole(['admin', 'super-admin', 'project_manager', 'pm']))
                        <button type="button" class="btn btn-danger btn-sm" 
                                onclick="deleteDocument({{ $document->id }}, '{{ $document->original_filename }}')"
                                title="Permanently delete {{ $document->original_filename }}"
                                data-bs-toggle="tooltip" 
                                data-bs-placement="top">
                            <i class="bi bi-trash"></i>
                        </button>
                        @else
                        <button type="button" class="btn btn-outline-secondary btn-sm" 
                                disabled 
                                title="You can only delete documents you uploaded"
                                data-bs-toggle="tooltip" 
                                data-bs-placement="top">
                            <i class="bi bi-lock"></i>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-folder2-open display-1 text-muted mb-3"></i>
                    <h5 class="text-muted">No Documents Found</h5>
                    <p class="text-muted">Upload documents related to {{ $phase->name }} to get started.</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        <i class="bi bi-cloud-upload me-2"></i>
                        Upload Your First Document
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">
                    <i class="bi bi-cloud-upload me-2"></i>
                    Upload Documents - {{ $phase->name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="uploadForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="description" class="form-label">Description (Optional)</label>
                        <textarea class="form-control" id="description" name="description" rows="2" 
                                  placeholder="Brief description of the documents being uploaded..."></textarea>
                    </div>
                    
                    <div class="upload-zone" id="uploadZone">
                        <i class="bi bi-cloud-upload display-1 text-muted mb-3"></i>
                        <h5>Drag & Drop Files Here</h5>
                        <p class="text-muted">or click to browse files</p>
                        <input type="file" id="fileInput" name="files[]" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.dwg,.dxf,.ai,.psd,.zip,.rar,.mp4,.avi,.mov" style="display: none;">
                        <button type="button" class="btn btn-outline-primary" id="browseBtn">
                            <i class="bi bi-folder2-open me-2"></i>
                            Browse Files
                        </button>
                    </div>
                    
                    <div class="file-preview mt-3" id="filePreview" style="display: none;">
                        <h6>Selected Files:</h6>
                        <div id="fileList"></div>
                    </div>
                    
                    <div class="progress-container mt-3">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                        </div>
                        <small class="text-muted mt-1" id="uploadStatus">Preparing upload...</small>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <small>
                            <strong>Supported formats:</strong> Images (JPG, PNG, GIF), Documents (PDF, DOC, DOCX), 
                            Spreadsheets (XLS, XLSX), Presentations (PPT, PPTX), CAD files (DWG, DXF), 
                            Design files (AI, PSD), Archives (ZIP, RAR), Videos (MP4, AVI, MOV)
                            <br><strong>Max file size:</strong> 50MB per file
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="uploadBtn" disabled>
                        <i class="bi bi-cloud-upload me-2"></i>
                        Upload Documents
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">
                    <i class="bi bi-file-earmark me-2"></i>
                    <span id="previewFileName">Document Preview</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <!-- Loading State -->
                <div id="previewLoading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Loading preview...</p>
                </div>
                
                <!-- Preview Content -->
                <div id="previewContent" style="display: none;">
                    <!-- PDF Preview -->
                    <div id="pdfPreview" style="display: none;">
                        <iframe id="pdfFrame" width="100%" height="600" frameborder="0"></iframe>
                    </div>
                    
                    <!-- Image Preview -->
                    <div id="imagePreview" style="display: none;" class="text-center p-3">
                        <img id="imageElement" class="img-fluid" style="max-height: 70vh;" alt="Document preview">
                    </div>
                    
                    <!-- Text/Code Preview -->
                    <div id="textPreview" style="display: none;" class="p-3">
                        <pre id="textContent" class="bg-light p-3 rounded" style="max-height: 60vh; overflow-y: auto;"></pre>
                    </div>
                    
                    <!-- Video Preview -->
                    <div id="videoPreview" style="display: none;" class="text-center p-3">
                        <video id="videoElement" controls style="max-width: 100%; max-height: 60vh;">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    
                    <!-- Audio Preview -->
                    <div id="audioPreview" style="display: none;" class="text-center p-3">
                        <audio id="audioElement" controls class="w-100">
                            Your browser does not support the audio tag.
                        </audio>
                    </div>
                    
                    <!-- Office Documents Preview -->
                    <div id="officePreview" style="display: none;" class="p-3">
                        <div class="text-center">
                            <iframe id="officeFrame" width="100%" height="600" frameborder="0"></iframe>
                        </div>
                    </div>
                    
                    <!-- Unsupported File Type -->
                    <div id="unsupportedPreview" style="display: none;" class="text-center py-5">
                        <i class="bi bi-file-earmark-x display-1 text-muted mb-3"></i>
                        <h5 class="text-muted">Preview Not Available</h5>
                        <p class="text-muted">This file type cannot be previewed in the browser.</p>
                        <p class="text-muted">Click download to view the file in your default application.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="me-auto">
                    <small class="text-muted" id="previewMeta"></small>
                </div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i>
                    Close
                </button>
                <a href="#" class="btn btn-primary" id="previewDownloadBtn">
                    <i class="bi bi-download me-1"></i>
                    Download
                </a>
                <button type="button" class="btn btn-info" onclick="openInNewTab()" id="openNewTabBtn" style="display: none;">
                    <i class="bi bi-box-arrow-up-right me-1"></i>
                    Open in New Tab
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing upload functionality');
    
    const uploadZone = document.getElementById('uploadZone');
    const fileInput = document.getElementById('fileInput');
    const filePreview = document.getElementById('filePreview');
    const fileList = document.getElementById('fileList');
    const uploadBtn = document.getElementById('uploadBtn');
    const uploadForm = document.getElementById('uploadForm');
    const progressContainer = document.querySelector('.progress-container');
    const progressBar = document.querySelector('.progress-bar');
    const uploadStatus = document.getElementById('uploadStatus');
    
    // Check if all elements exist
    console.log('Elements found:', {
        uploadZone: !!uploadZone,
        fileInput: !!fileInput,
        filePreview: !!filePreview,
        fileList: !!fileList,
        uploadBtn: !!uploadBtn,
        uploadForm: !!uploadForm
    });

    // Drag and drop functionality
    uploadZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadZone.classList.add('dragover');
    });

    uploadZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadZone.classList.remove('dragover');
    });

    uploadZone.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadZone.classList.remove('dragover');
        const files = e.dataTransfer.files;
        handleFiles(files);
    });

    uploadZone.addEventListener('click', function(e) {
        // Only trigger file input if clicking on the zone itself, not the button
        if (e.target === uploadZone || e.target.closest('.upload-zone') === uploadZone && !e.target.closest('button')) {
            fileInput.click();
        }
    });

    // Browse button click handler
    document.getElementById('browseBtn').addEventListener('click', function(e) {
        e.stopPropagation();
        console.log('Browse button clicked');
        fileInput.click();
    });

    fileInput.addEventListener('change', function() {
        console.log('File input changed, files:', this.files.length);
        handleFiles(this.files);
    });

    function handleFiles(files) {
        if (files.length === 0) return;

        // For drag and drop, we need to create a new DataTransfer object
        if (files instanceof FileList) {
            // Files from input element - use directly
            displayFilePreview(files);
        } else {
            // Files from drag and drop - convert to FileList
            const dt = new DataTransfer();
            Array.from(files).forEach(file => dt.items.add(file));
            fileInput.files = dt.files;
            displayFilePreview(fileInput.files);
        }
        
        uploadBtn.disabled = false;
    }

    function displayFilePreview(files) {
        fileList.innerHTML = '';
        filePreview.style.display = 'block';

        Array.from(files).forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'border rounded p-2 mb-2 d-flex justify-content-between align-items-center';
            fileItem.innerHTML = `
                <div>
                    <strong>${file.name}</strong>
                    <small class="text-muted d-block">${formatFileSize(file.size)}</small>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile(${index})">
                    <i class="fas fa-times"></i>
                </button>
            `;
            fileList.appendChild(fileItem);
        });
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    window.removeFile = function(index) {
        const dt = new DataTransfer();
        const files = Array.from(fileInput.files);
        files.splice(index, 1);
        
        files.forEach(file => dt.items.add(file));
        fileInput.files = dt.files;
        
        if (files.length === 0) {
            filePreview.style.display = 'none';
            uploadBtn.disabled = true;
        } else {
            displayFilePreview(fileInput.files);
        }
    };

    // Form submission
    uploadForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        console.log('Form submitted');
        console.log('Files in input:', fileInput.files.length);
        
        if (fileInput.files.length === 0) {
            alert('Please select files to upload');
            return;
        }
        
        const formData = new FormData(this);
        
        // Log form data contents
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
        
        progressContainer.style.display = 'block';
        uploadBtn.disabled = true;
        uploadStatus.textContent = 'Uploading files...';

        fetch('{{ route("projects.phases.documents.store", [$project, $phase]) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            // Handle different response types
            if (response.status === 413) {
                throw new Error('File too large. Please check file size limits.');
            }
            if (response.status === 422) {
                return response.json().then(data => {
                    throw new Error('Validation failed: ' + (data.message || 'Invalid file data'));
                });
            }
            if (response.status === 500) {
                return response.text().then(text => {
                    console.error('Server error response:', text);
                    throw new Error('Server error occurred. Please check server logs.');
                });
            }
            if (!response.ok) {
                return response.text().then(text => {
                    console.error('Error response:', text);
                    throw new Error(`HTTP error! status: ${response.status} - ${text.substring(0, 100)}`);
                });
            }
            
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                return response.text().then(text => {
                    console.error('Non-JSON response:', text);
                    throw new Error('Server returned non-JSON response');
                });
            }
            
            return response.json();
        })
        .then(data => {
            console.log('Upload response:', data);
            if (data.success) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message || 'Files uploaded successfully!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    alert(data.message || 'Files uploaded successfully!');
                    location.reload();
                }
            } else {
                const errorMsg = data.message || 'Upload failed for unknown reason';
                const errors = data.errors || [];
                let fullMessage = errorMsg;
                
                if (errors.length > 0) {
                    fullMessage += '\n\nDetailed errors:\n' + errors.join('\n');
                }
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Upload Failed',
                        text: fullMessage,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                } else {
                    alert(fullMessage);
                }
                
                uploadBtn.disabled = false;
                progressContainer.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Upload error:', error);
            
            let errorMessage = 'Upload failed: ' + error.message;
            
            // Add troubleshooting hints
            if (error.message.includes('413') || error.message.includes('too large')) {
                errorMessage += '\n\nTroubleshooting:\n- Check file size (max 50MB per file)\n- Contact administrator if files are within limits';
            } else if (error.message.includes('500') || error.message.includes('Server error')) {
                errorMessage += '\n\nTroubleshooting:\n- Server configuration issue\n- Check server logs\n- Contact administrator';
            } else if (error.message.includes('Network')) {
                errorMessage += '\n\nTroubleshooting:\n- Check internet connection\n- Try again in a few moments';
            }
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Upload Error',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            } else {
                alert(errorMessage);
            }
            uploadBtn.disabled = false;
            progressContainer.style.display = 'none';
        });
    });

    // Preview modal functionality will be handled by previewDocument function

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Filter and sort functionality
    const fileTypeFilter = document.getElementById('fileTypeFilter');
    const sortBy = document.getElementById('sortBy');
    const documentsGrid = document.getElementById('documentsGrid');

    if (fileTypeFilter && sortBy && documentsGrid) {
        fileTypeFilter.addEventListener('change', filterAndSort);
        sortBy.addEventListener('change', filterAndSort);
    }

    function filterAndSort() {
        const filterValue = fileTypeFilter.value;
        const sortValue = sortBy.value;
        const documentCards = Array.from(documentsGrid.children);

        // Filter documents
        documentCards.forEach(card => {
            const documentType = getDocumentType(card);
            if (filterValue === '' || documentType === filterValue) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });

        // Sort visible documents
        const visibleCards = documentCards.filter(card => card.style.display !== 'none');
        sortDocuments(visibleCards, sortValue);

        // Re-append sorted cards
        visibleCards.forEach(card => documentsGrid.appendChild(card));
    }

    function getDocumentType(card) {
        const badge = card.querySelector('.badge.bg-danger, .badge.bg-success, .badge.bg-primary');
        if (!badge) return '';
        
        const extension = badge.textContent.toLowerCase();
        if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'].includes(extension)) return 'image';
        if (extension === 'pdf') return 'pdf';
        if (['doc', 'docx', 'txt', 'rtf'].includes(extension)) return 'document';
        if (['xls', 'xlsx', 'csv'].includes(extension)) return 'spreadsheet';
        if (['ppt', 'pptx'].includes(extension)) return 'presentation';
        if (['mp4', 'avi', 'mov'].includes(extension)) return 'video';
        if (['mp3', 'wav'].includes(extension)) return 'audio';
        return 'other';
    }

    function sortDocuments(cards, sortValue) {
        cards.sort((a, b) => {
            switch (sortValue) {
                case 'newest':
                    return getUploadDate(b) - getUploadDate(a);
                case 'oldest':
                    return getUploadDate(a) - getUploadDate(b);
                case 'name':
                    return getFileName(a).localeCompare(getFileName(b));
                case 'name-desc':
                    return getFileName(b).localeCompare(getFileName(a));
                case 'size':
                    return getFileSize(b) - getFileSize(a);
                case 'size-desc':
                    return getFileSize(a) - getFileSize(b);
                default:
                    return 0;
            }
        });
    }

    function getFileName(card) {
        const titleElement = card.querySelector('.card-title');
        return titleElement ? titleElement.textContent.trim() : '';
    }

    function getUploadDate(card) {
        const dateElement = card.querySelector('.text-muted');
        if (!dateElement) return new Date(0);
        const dateText = dateElement.textContent.trim();
        return new Date(dateText);
    }

    function getFileSize(card) {
        const sizeElement = card.querySelector('.badge.bg-light');
        if (!sizeElement) return 0;
        const sizeText = sizeElement.textContent.trim();
        // Convert size to bytes for comparison
        const match = sizeText.match(/(\d+(?:\.\d+)?)\s*(B|KB|MB|GB)/);
        if (!match) return 0;
        const value = parseFloat(match[1]);
        const unit = match[2];
        const multipliers = { B: 1, KB: 1024, MB: 1024 * 1024, GB: 1024 * 1024 * 1024 };
        return value * (multipliers[unit] || 1);
    }
});

function showDownloadProgress(button) {
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="bi bi-arrow-clockwise spin"></i>';
    
    // Reset after 3 seconds (download should have started by then)
    setTimeout(() => {
        button.innerHTML = '<i class="bi bi-check-lg"></i>';
        button.className = 'btn btn-success btn-sm download-btn';
        
        setTimeout(() => {
            button.innerHTML = originalContent;
            button.className = 'btn btn-primary btn-sm download-btn';
        }, 2000);
    }, 1000);
}

// Global variables for preview
let currentPreviewUrl = '';
let currentFileName = '';

function previewDocument(documentId, filename, documentType, fileExtension, previewUrl, description, uploader, uploadDate, fileSize, downloadUrl) {
    console.log('Previewing document:', {documentId, filename, documentType, fileExtension});
    
    // Set global variables
    currentPreviewUrl = previewUrl;
    currentFileName = filename;
    
    // Update modal title and metadata
    document.getElementById('previewFileName').textContent = filename;
    document.getElementById('previewMeta').textContent = `${fileSize} • Uploaded by ${uploader} on ${uploadDate}`;
    document.getElementById('previewDownloadBtn').href = downloadUrl;
    
    // Show modal and loading state
    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    modal.show();
    
    showPreviewLoading();
    
    // Hide all preview containers
    hideAllPreviewContainers();
    
    // Determine preview method based on file type
    setTimeout(() => {
        switch (documentType.toLowerCase()) {
            case 'pdf':
                previewPDF(previewUrl);
                break;
            case 'image':
                previewImage(previewUrl, filename);
                break;
            case 'document':
                if (fileExtension.toLowerCase() === 'txt') {
                    previewText(previewUrl);
                } else {
                    previewOfficeDocument(previewUrl, fileExtension);
                }
                break;
            case 'spreadsheet':
            case 'presentation':
                previewOfficeDocument(previewUrl, fileExtension);
                break;
            case 'video':
                previewVideo(previewUrl);
                break;
            case 'audio':
                previewAudio(previewUrl);
                break;
            default:
                showUnsupportedPreview();
                break;
        }
    }, 500);
}

function showPreviewLoading() {
    document.getElementById('previewLoading').style.display = 'block';
    document.getElementById('previewContent').style.display = 'none';
}

function hidePreviewLoading() {
    document.getElementById('previewLoading').style.display = 'none';
    document.getElementById('previewContent').style.display = 'block';
}

function hideAllPreviewContainers() {
    const containers = ['pdfPreview', 'imagePreview', 'textPreview', 'videoPreview', 'audioPreview', 'officePreview', 'unsupportedPreview'];
    containers.forEach(id => {
        document.getElementById(id).style.display = 'none';
    });
}

function previewPDF(url) {
    console.log('Previewing PDF:', url);
    const iframe = document.getElementById('pdfFrame');
    iframe.src = url + '#toolbar=1&navpanes=1&scrollbar=1';
    
    iframe.onload = function() {
        hidePreviewLoading();
        document.getElementById('pdfPreview').style.display = 'block';
        document.getElementById('openNewTabBtn').style.display = 'inline-block';
    };
    
    iframe.onerror = function() {
        console.error('PDF preview failed');
        showUnsupportedPreview();
    };
}

function previewImage(url, filename) {
    console.log('Previewing image:', url);
    const img = document.getElementById('imageElement');
    img.src = url;
    img.alt = filename;
    
    img.onload = function() {
        hidePreviewLoading();
        document.getElementById('imagePreview').style.display = 'block';
        document.getElementById('openNewTabBtn').style.display = 'inline-block';
    };
    
    img.onerror = function() {
        console.error('Image preview failed');
        showUnsupportedPreview();
    };
}

function previewText(url) {
    console.log('Previewing text file:', url);
    fetch(url)
        .then(response => {
            if (!response.ok) throw new Error('Failed to fetch text file');
            return response.text();
        })
        .then(text => {
            document.getElementById('textContent').textContent = text;
            hidePreviewLoading();
            document.getElementById('textPreview').style.display = 'block';
        })
        .catch(error => {
            console.error('Text preview failed:', error);
            showUnsupportedPreview();
        });
}

function previewVideo(url) {
    console.log('Previewing video:', url);
    const video = document.getElementById('videoElement');
    video.src = url;
    
    video.onloadedmetadata = function() {
        hidePreviewLoading();
        document.getElementById('videoPreview').style.display = 'block';
        document.getElementById('openNewTabBtn').style.display = 'inline-block';
    };
    
    video.onerror = function() {
        console.error('Video preview failed');
        showUnsupportedPreview();
    };
}

function previewAudio(url) {
    console.log('Previewing audio:', url);
    const audio = document.getElementById('audioElement');
    audio.src = url;
    
    audio.onloadedmetadata = function() {
        hidePreviewLoading();
        document.getElementById('audioPreview').style.display = 'block';
    };
    
    audio.onerror = function() {
        console.error('Audio preview failed');
        showUnsupportedPreview();
    };
}

function previewOfficeDocument(url, extension) {
    console.log('Previewing office document:', url, extension);
    
    // Try different preview methods for office documents
    const iframe = document.getElementById('officeFrame');
    
    // Method 1: Try Google Docs Viewer
    if (['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'].includes(extension.toLowerCase())) {
        iframe.src = `https://docs.google.com/gview?url=${encodeURIComponent(url)}&embedded=true`;
        
        iframe.onload = function() {
            hidePreviewLoading();
            document.getElementById('officePreview').style.display = 'block';
            document.getElementById('openNewTabBtn').style.display = 'inline-block';
        };
        
        // Fallback after timeout
        setTimeout(() => {
            if (document.getElementById('previewLoading').style.display !== 'none') {
                console.log('Google Docs viewer timeout, showing unsupported');
                showUnsupportedPreview();
            }
        }, 10000);
    } else {
        showUnsupportedPreview();
    }
}

function showUnsupportedPreview() {
    console.log('Showing unsupported preview');
    hidePreviewLoading();
    document.getElementById('unsupportedPreview').style.display = 'block';
    document.getElementById('openNewTabBtn').style.display = 'none';
}

function openInNewTab() {
    if (currentPreviewUrl) {
        window.open(currentPreviewUrl, '_blank');
    }
}

function deleteDocument(documentId, filename) {
    // Create a more user-friendly confirmation dialog
    const confirmMessage = `⚠️ Delete Document\n\nAre you sure you want to permanently delete:\n"${filename}"\n\nThis action cannot be undone.`;
    
    if (!confirm(confirmMessage)) {
        return;
    }
    
    // Show loading state
    const deleteBtn = document.querySelector(`button[onclick*="${documentId}"]`);
    const originalContent = deleteBtn.innerHTML;
    deleteBtn.innerHTML = '<i class="bi bi-arrow-clockwise spin"></i>';
    deleteBtn.disabled = true;
    
    fetch(`{{ route('projects.phases.documents.destroy', [$project, $phase, ':id']) }}`.replace(':id', documentId), {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message briefly before reload
            deleteBtn.innerHTML = '<i class="fas fa-check"></i>';
            deleteBtn.className = 'btn btn-success btn-sm';
            
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            alert('❌ Delete Failed\n\n' + (data.message || 'Unknown error occurred'));
            deleteBtn.innerHTML = originalContent;
            deleteBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Delete error:', error);
        alert('❌ Delete Failed\n\nNetwork error. Please check your connection and try again.');
        deleteBtn.innerHTML = originalContent;
        deleteBtn.disabled = false;
    });
}
</script>
@endpush