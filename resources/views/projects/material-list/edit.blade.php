@extends('layouts.master')

@section('title', 'Edit Material')

@section('content')
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
            <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}">{{ $project->name }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('projects.files.materials', $project) }}">Materials</a></li>
            <li class="breadcrumb-item active">Edit Material</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Edit Material for: <strong>{{ $project->name }}</strong></h4>
        </div>
        <div class="card-body">
            <form action="{{ route('projects.files.materials.update', ['project' => $project->id, 'material' => $material->id]) }}" method="POST">
                @csrf
                @method('PUT')
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Item Name</label>
            <input type="text" name="item_name" class="form-control" value="{{ $item->name }}" required>
        </div>

        <div id="material-rows">
            @foreach ($materials as $index => $material)
            <div class="row g-2 mb-2 material-group">
                <div class="col-md-3">
                    <input type="text" name="materials[{{ $index }}][name]" class="form-control" value="{{ $material->name }}" placeholder="Material" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="materials[{{ $index }}][description]" class="form-control" value="{{ $material->description }}" placeholder="Specification/Description">
                </div>
                <div class="col-md-2">
                    <input type="text" name="materials[{{ $index }}][unit]" class="form-control" value="{{ $material->unit }}" placeholder="Unit of Measure">
                </div>
                <div class="col-md-1">
                    <input type="number" name="materials[{{ $index }}][quantity]" class="form-control" value="{{ $material->quantity }}" placeholder="Qty">
                </div>
                <div class="col-md-2">
                    <input type="text" name="materials[{{ $index }}][usage_notes]" class="form-control" value="{{ $material->usage_notes }}" placeholder="Usage Notes">
                </div>
                <div class="col-md-1 d-flex align-items-center">
                    <button type="button" class="btn btn-danger btn-sm remove-row">✕</button>
                </div>
                <div class="col-md-6 mt-2">
                    <input type="url" name="materials[{{ $index }}][design_reference]" class="form-control" value="{{ $material->design_reference }}" placeholder="Google Drive link for Design">
                </div>
            </div>
            @endforeach
        </div>

        <button type="button" class="btn btn-outline-primary btn-sm my-2" id="add-row">+ Add Material</button>

        <button type="submit" class="btn btn-success mt-3">Update Materials</button>
    </form>
</div>

@push('scripts')
<script>
    let counter = {{ count($materials) }};
    document.getElementById('add-row').addEventListener('click', () => {
        const container = document.getElementById('material-rows');
        const row = `
        <div class="row g-2 mb-2 material-group">
            <div class="col-md-3">
                <input type="text" name="materials[${counter}][name]" class="form-control" placeholder="Material" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="materials[${counter}][description]" class="form-control" placeholder="Specification/Description">
            </div>
            <div class="col-md-2">
                <input type="text" name="materials[${counter}][unit]" class="form-control" placeholder="Unit of Measure">
            </div>
            <div class="col-md-1">
                <input type="number" name="materials[${counter}][quantity]" class="form-control" placeholder="Qty">
            </div>
            <div class="col-md-2">
                <input type="text" name="materials[${counter}][usage_notes]" class="form-control" placeholder="Usage Notes">
            </div>
            <div class="col-md-1 d-flex align-items-center">
                <button type="button" class="btn btn-danger btn-sm remove-row">✕</button>
            </div>
            <div class="col-md-6 mt-2">
                <input type="url" name="materials[${counter}][design_reference]" class="form-control" placeholder="Google Drive link for Design">
            </div>
        </div>`;
        container.insertAdjacentHTML('beforeend', row);
        counter++;
    });

    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('.material-group').remove();
        }
    });
</script>
@endpush
@endsection
