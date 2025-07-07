@extends('layouts.master')
@section('title', 'Edit Item Template')

@section('content')
<div class="container-fluid p-2">
    <div class="mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                <li class="breadcrumb-item"><a href="{{ route('templates.templates.index') }}">Item Templates</a></li>
                <li class="breadcrumb-item"><a href="{{ route('templates.templates.show', $itemTemplate) }}">{{ $itemTemplate->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Template</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 m-0">Edit Item Template</h1>
            <a href="{{ route('templates.templates.show', $itemTemplate) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Template
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('templates.templates.update', $itemTemplate) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="category_id" class="form-label">Category *</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $itemTemplate->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="name" class="form-label">Template Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $itemTemplate->name) }}" required>
                        <small class="form-text text-muted">Template names must be unique within each category.</small>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-8">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $itemTemplate->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="estimated_cost" class="form-label">Estimated Cost (KSh)</label>
                        <input type="number" step="0.01" class="form-control @error('estimated_cost') is-invalid @enderror" 
                               id="estimated_cost" name="estimated_cost" value="{{ old('estimated_cost', $itemTemplate->estimated_cost) }}">
                        @error('estimated_cost')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', $itemTemplate->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active Template
                            </label>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Particulars Section -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">
                            <i class="bi bi-list-check me-2"></i>Template Particulars
                        </h5>
                        <button type="button" class="btn btn-success btn-sm" id="addParticular">
                            <i class="bi bi-plus-circle"></i> Add Particular
                        </button>
                    </div>

                    <div id="particularsContainer">
                        @foreach($itemTemplate->particulars as $index => $particular)
                            <div class="particular-row border rounded p-3 mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Particular *</label>
                                        <input type="text" name="particulars[{{ $index }}][particular]" 
                                               class="form-control" value="{{ $particular->particular }}" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Unit</label>
                                        <input type="text" name="particulars[{{ $index }}][unit]" 
                                               class="form-control" value="{{ $particular->unit }}" placeholder="e.g., pcs, m, kg">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Quantity *</label>
                                        <input type="number" step="0.01" name="particulars[{{ $index }}][default_quantity]" 
                                               class="form-control" value="{{ $particular->default_quantity }}" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Comment</label>
                                        <input type="text" name="particulars[{{ $index }}][comment]" 
                                               class="form-control" value="{{ $particular->comment }}" placeholder="Optional notes">
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-danger btn-sm remove-particular w-100">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('templates.templates.show', $itemTemplate) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Update Template
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let particularIndex = {{ $itemTemplate->particulars->count() }};

    // Add new particular
    $('#addParticular').on('click', function() {
        addParticular();
    });

    // Remove particular
    $(document).on('click', '.remove-particular', function() {
        if ($('.particular-row').length > 1) {
            $(this).closest('.particular-row').remove();
        } else {
            alert('At least one particular is required.');
        }
    });

    function addParticular() {
        const particularHtml = `
            <div class="particular-row border rounded p-3 mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Particular *</label>
                        <input type="text" name="particulars[${particularIndex}][particular]" 
                               class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Unit</label>
                        <input type="text" name="particulars[${particularIndex}][unit]" 
                               class="form-control" placeholder="e.g., pcs, m, kg">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Quantity *</label>
                        <input type="number" step="0.01" name="particulars[${particularIndex}][default_quantity]" 
                               class="form-control" value="1" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Comment</label>
                        <input type="text" name="particulars[${particularIndex}][comment]" 
                               class="form-control" placeholder="Optional notes">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-danger btn-sm remove-particular w-100">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        $('#particularsContainer').append(particularHtml);
        particularIndex++;
    }
});
</script>
@endpush 