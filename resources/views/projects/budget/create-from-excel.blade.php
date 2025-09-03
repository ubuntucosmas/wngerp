@extends('layouts.master')

@section('title', 'Import Budget from Excel')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            @if(isset($enquiry))
                                <li class="breadcrumb-item"><a href="{{ route('enquiries.index') }}">Enquiries</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('enquiries.files', $enquiry) }}">{{ $enquiry->project_name }}</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('enquiries.budget.index', $enquiry) }}">Budget</a></li>
                            @else
                                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}">{{ $project->name }}</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('budget.index', $project) }}">Budget</a></li>
                            @endif
                            <li class="breadcrumb-item active">Import from Excel</li>
                        </ol>
                    </nav>
                    <h2 class="mb-0">Import Budget from Excel</h2>
                    <p class="text-muted">Upload an Excel file to automatically create budget items</p>
                </div>
                <div>
                    @if(isset($enquiry))
                        <a href="{{ route('enquiries.budget.index', $enquiry) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back to Budget
                        </a>
                    @else
                        <a href="{{ route('budget.index', $project) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back to Budget
                        </a>
                    @endif
                </div>
            </div>

            <!-- Instructions Card -->
            <div class="card border-info mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Excel Import Instructions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Required Excel Structure:</h6>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-check-circle text-success me-2"></i><strong>Production Items</strong> sheet</li>
                                <li><i class="bi bi-check-circle text-success me-2"></i><strong>Materials for Hire</strong> sheet</li>
                                <li><i class="bi bi-check-circle text-success me-2"></i><strong>Labour Items</strong> sheet</li>
                                <li><i class="bi bi-check-circle text-success me-2"></i><strong>Other Items</strong> sheet</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Required Columns:</h6>
                            <ul class="list-unstyled small">
                                <li><strong>Production Items:</strong> Item Name, Particular, Unit, Quantity, Unit Price</li>
                                <li><strong>Materials for Hire:</strong> Item Name, Particular, Unit, Quantity, Unit Price</li>
                                <li><strong>Labour Items:</strong> Category, Particular, Unit, Quantity, Unit Price</li>
                                <li><strong>Other Items:</strong> Category, Particular, Unit, Quantity, Unit Price</li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('budget.download-template') }}" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-download me-2"></i>Download Excel Template
                        </a>
                    </div>
                </div>
            </div>

            <!-- Upload Form -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark-excel me-2"></i>
                        Upload Budget Excel File
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($enquiry))
                        <form action="{{ route('enquiries.budget.import-excel', $enquiry) }}" method="POST" enctype="multipart/form-data">
                    @else
                        <form action="{{ route('budget.import-excel', $project) }}" method="POST" enctype="multipart/form-data">
                    @endif
                        @csrf
                        
                        <!-- File Upload -->
                        <div class="mb-4">
                            <label for="excel_file" class="form-label fw-bold">
                                <i class="bi bi-file-earmark-excel me-2"></i>Excel File
                            </label>
                            <input type="file" 
                                   class="form-control @error('excel_file') is-invalid @enderror" 
                                   id="excel_file" 
                                   name="excel_file" 
                                   accept=".xlsx,.xls"
                                   required>
                            @error('excel_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Supported formats: .xlsx, .xls (Max size: 10MB)
                            </div>
                        </div>

                        <!-- Budget Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label fw-bold">
                                        <i class="bi bi-calendar-event me-2"></i>Start Date
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('start_date') is-invalid @enderror" 
                                           id="start_date" 
                                           name="start_date" 
                                           value="{{ old('start_date') }}"
                                           required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label fw-bold">
                                        <i class="bi bi-calendar-check me-2"></i>End Date
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('end_date') is-invalid @enderror" 
                                           id="end_date" 
                                           name="end_date" 
                                           value="{{ old('end_date') }}"
                                           required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="approved_by" class="form-label fw-bold">
                                        <i class="bi bi-person-check me-2"></i>Approved By
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('approved_by') is-invalid @enderror" 
                                           id="approved_by" 
                                           name="approved_by" 
                                           value="{{ old('approved_by', auth()->user()->name) }}"
                                           required>
                                    @error('approved_by')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="approved_departments" class="form-label fw-bold">
                                        <i class="bi bi-building me-2"></i>Approved Departments
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('approved_departments') is-invalid @enderror" 
                                           id="approved_departments" 
                                           name="approved_departments" 
                                           value="{{ old('approved_departments') }}"
                                           placeholder="e.g., Finance, Operations"
                                           required>
                                    @error('approved_departments')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            @if(isset($enquiry))
                                <a href="{{ route('enquiries.budget.index', $enquiry) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-2"></i>Cancel
                                </a>
                            @else
                                <a href="{{ route('budget.index', $project) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-2"></i>Cancel
                                </a>
                            @endif
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-upload me-2"></i>Import Budget from Excel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Preview Section (will be populated via JavaScript) -->
            <div id="preview-section" class="card mt-4" style="display: none;">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="bi bi-eye me-2"></i>File Preview
                    </h6>
                </div>
                <div class="card-body">
                    <div id="preview-content">
                        <!-- Preview content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('excel_file');
    const previewSection = document.getElementById('preview-section');
    
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Show file information
            const fileInfo = `
                <div class="d-flex align-items-center">
                    <i class="bi bi-file-earmark-excel text-success me-3" style="font-size: 2rem;"></i>
                    <div>
                        <h6 class="mb-1">${file.name}</h6>
                        <small class="text-muted">Size: ${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        File selected successfully. Click "Import Budget from Excel" to process the file.
                    </div>
                </div>
            `;
            
            document.getElementById('preview-content').innerHTML = fileInfo;
            previewSection.style.display = 'block';
        } else {
            previewSection.style.display = 'none';
        }
    });
});
</script>
@endsection