@extends('layouts.master')

@section('title', 'Add Materials')

@section('content')
<div class="container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
            <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}">{{ $project->name }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('projects.files.materials', $project) }}">Materials</a></li>
            <li class="breadcrumb-item active">Add Materials</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Add Materials for: <strong>{{ $project->name }}</strong></h4>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('projects.files.materials.store', $project) }}" method="POST">
                @csrf
                <div id="materials-container">
                    <div class="material-group mb-4 border p-3">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="item" class="form-label">Item Name *</label>
                                <input type="text" class="form-control" name="item" id="item" required>
                            </div>
                        </div>

                        <div class="materials-list">
                            <h5>Materials</h5>
                            <div class="material-entry border p-3 mb-3">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label">Material *</label>
                                        <input type="text" class="form-control" name="materials[0][material]" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Specification</label>
                                        <input type="text" class="form-control" name="materials[0][specification]">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Unit</label>
                                        <input type="text" class="form-control" name="materials[0][unit]">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Quantity</label>
                                        <input type="number" step="0.01" class="form-control" name="materials[0][quantity]">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Notes</label>
                                        <input type="text" class="form-control" name="materials[0][notes]">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Design Reference (URL)</label>
                                        <input type="url" class="form-control" name="materials[0][design_reference]">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Approved By</label>
                                        <input type="text" class="form-control" name="materials[0][approved_by]">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-secondary add-material">
                            <i class="bi bi-plus"></i> Add Another Material
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                    <a href="{{ route('projects.files.materials', $project) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Save Materials
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let materialIndex = 1;

        // Add new material row
        document.querySelector('.add-material').addEventListener('click', function() {
            const materialsList = document.querySelector('.materials-list');
            const newMaterial = document.querySelector('.material-entry').cloneNode(true);
            
            // Update indices
            const newContent = newMaterial.innerHTML.replace(/\[0\]/g, `[${materialIndex}]`);
            newMaterial.innerHTML = newContent;
            
            // Clear input values
            const inputs = newMaterial.querySelectorAll('input');
            inputs.forEach(input => input.value = '');
            
            // Add remove button
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn btn-sm btn-outline-danger remove-material mt-2';
            removeBtn.innerHTML = '<i class="bi bi-trash"></i> Remove';
            newMaterial.appendChild(removeBtn);
            
            materialsList.appendChild(newMaterial);
            materialIndex++;
        });

        // Remove material row
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-material')) {
                const materialEntry = e.target.closest('.material-entry');
                if (document.querySelectorAll('.material-entry').length > 1) {
                    materialEntry.remove();
                } else {
                    alert('At least one material is required');
                }
            }
        });
    });
</script>
@endpush

@endsection
