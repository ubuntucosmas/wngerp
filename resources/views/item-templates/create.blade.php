@extends('layouts.master')
@section('title', 'Create Item Template')

@section('content')
<div class="container-fluid p-3">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1 text-dark fw-bold">
                <i class="bi bi-plus-circle me-2 text-primary"></i>Create New Template
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('projects.index') }}" class="text-decoration-none">Projects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('templates.templates.index') }}" class="text-decoration-none">Templates</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('templates.templates.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back to Templates
        </a>
    </div>

    <!-- Flash Messages -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>Please fix the following errors:
            <ul class="mb-0 mt-1 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('templates.templates.store') }}" method="POST">
        @csrf
        
        <div class="row">
            <div class="col-lg-10">
                <!-- Template Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2 text-primary"></i>Template Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="category_id" class="form-label fw-semibold">Category *</label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Template Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Template names must be unique within each category.</small>
                            </div>
                            <div class="col-md-12">
                                <label for="description" class="form-label fw-semibold">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="3"
                                          placeholder="Describe the purpose and scope of this template...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Particulars Section -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-list-check me-2 text-primary"></i>Template Particulars
                        </h5>
                        <button type="button" class="btn btn-success btn-sm" id="addParticular">
                            <i class="bi bi-plus-circle me-1"></i>Add Particular
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="particularsContainer">
                            <!-- Particulars will be added here dynamically -->
                        </div>
                        <!-- Empty State -->
                        <div id="emptyParticulars" class="text-center py-4" style="display: none;">
                            <i class="bi bi-list-check display-4 text-muted mb-3"></i>
                            <h6 class="text-muted mb-2">No particulars added yet</h6>
                            <p class="text-muted small mb-0">Click "Add Particular" to start building your template</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-2">
                <!-- Quick Actions -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">
                            <i class="bi bi-lightning me-2 text-primary"></i>Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Create Template
                            </button>
                            <a href="{{ route('templates.templates.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Template Guidelines -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2 text-primary"></i>Guidelines
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="small text-muted">
                            <div class="mb-2">
                                <i class="bi bi-check-circle text-success me-1"></i>
                                <strong>Category:</strong> Choose the appropriate category for your template
                            </div>
                            <div class="mb-2">
                                <i class="bi bi-check-circle text-success me-1"></i>
                                <strong>Name:</strong> Use descriptive, unique names within each category
                            </div>
                            <div class="mb-2">
                                <i class="bi bi-check-circle text-success me-1"></i>
                                <strong>Particulars:</strong> Add all required items with accurate quantities
                            </div>
                            <div class="mb-2">
                                <i class="bi bi-check-circle text-success me-1"></i>
                                <strong>Cost:</strong> Provide realistic cost estimates for budgeting
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
.card {
    border-radius: 0.75rem;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.particular-row {
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.particular-row:hover {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.btn-remove-particular {
    transition: all 0.2s ease;
}

.btn-remove-particular:hover {
    transform: scale(1.1);
}
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    console.log('jQuery loaded and document ready');
    
    let particularIndex = 0;

    // Add initial particular
    console.log('Adding initial particular');
    addParticular();

    // Add new particular
    $('#addParticular').on('click', function() {
        console.log('Add Particular button clicked');
        addParticular();
    });

    // Remove particular
    $(document).on('click', '.remove-particular', function() {
        console.log('Remove particular button clicked');
        if ($('.particular-row').length > 1) {
            $(this).closest('.particular-row').remove();
            updateEmptyState();
            updatePreview();
        } else {
            alert('At least one particular is required.');
        }
    });

    // Form validation
    $('form').on('submit', function(e) {
        console.log('Form submission attempted');
        
        // Check if we have at least one particular
        if ($('.particular-row').length === 0) {
            e.preventDefault();
            alert('At least one particular is required.');
            return false;
        }
        
        // Check if all required fields are filled
        let isValid = true;
        $('.particular-row input[required]').each(function() {
            if (!$(this).val().trim()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return false;
        }
        
        console.log('Form validation passed, submitting...');
    });

    // Update preview when form fields change
    $('#name, #description').on('input', function() {
        updatePreview();
    });

    $(document).on('input', '.particular-row input', function() {
        updatePreview();
    });

    function addParticular() {
        console.log('Adding particular with index:', particularIndex);
        const particularHtml = `
            <div class="particular-row border rounded p-3 mb-3 bg-light">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Particular *</label>
                        <input type="text" name="particulars[${particularIndex}][particular]"
                               class="form-control" required placeholder="e.g., Wood, Screws, Paint">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Unit</label>
                        <input type="text" name="particulars[${particularIndex}][unit]"
                               class="form-control" placeholder="pcs, m, kg">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Quantity *</label>
                        <input type="number" step="0.01" name="particulars[${particularIndex}][default_quantity]"
                               class="form-control" value="1" required min="0">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Unit Price *</label>
                        <input type="number" step="0.01" name="particulars[${particularIndex}][unit_price]"
                               class="form-control" value="0.00" required min="0" readonly>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label fw-semibold">Comment</label>
                        <input type="text" name="particulars[${particularIndex}][comment]"
                               class="form-control" placeholder="Optional notes">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-danger btn-sm remove-particular w-100 btn-remove-particular">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        $('#particularsContainer').append(particularHtml);
        particularIndex++;
        updateEmptyState();
        updatePreview();
        console.log('Particular added successfully. New index:', particularIndex);
        console.log('Total particulars now:', $('.particular-row').length);
    }

    function updateEmptyState() {
        if ($('.particular-row').length === 0) {
            $('#emptyParticulars').show();
        } else {
            $('#emptyParticulars').hide();
        }
    }

    function updatePreview() {
        const name = $('#name').val() || 'Template Name';
        const description = $('#description').val() || 'No description provided';
        let totalCost = 0;
        $('.particular-row').each(function() {
            const quantity = parseFloat($(this).find('input[name*="[default_quantity]"]').val()) || 0;
            const unitPrice = parseFloat($(this).find('input[name*="[unit_price]"]').val()) || 0;
            totalCost += quantity * unitPrice;
        });
        const cost = `KSh ${totalCost.toFixed(2)}`;
        const particularsCount = $('.particular-row').length;

        const previewHtml = `
            <div class="small">
                <h6 class="fw-bold text-dark mb-2">${name}</h6>
                <p class="text-muted mb-2">${description}</p>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-primary-subtle text-primary">
                        <i class="bi bi-list-check me-1"></i>${particularsCount} items
                    </span>
                    <span class="text-muted small">${cost}</span>
                </div>
                <hr class="my-2">
                <div class="small text-muted">
                    <div><i class="bi bi-calendar me-1"></i>Created: ${new Date().toLocaleDateString()}</div>
                    <div><i class="bi bi-person me-1"></i>Status: Draft</div>
                </div>
            </div>
        `;
        
        $('#templatePreview').html(previewHtml);
    }

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
});
</script>
@endpush 