@extends('layouts.master')

@section('title', 'Project Materials - ' . $project->name)

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
            <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}">{{ $project->name }}</a></li>
            <li class="breadcrumb-item active">Materials List</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Materials List</h1>
        <div>
            <a href="{{ route('projects.files.materials.create', $project) }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add Materials
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(count($materials) > 0)
        @foreach($materials as $item => $materialsGroup)
            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $item }}</h5>
                    <div>
                        <a href="#" class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#item-{{ Str::slug($item) }}">
                            <i class="bi bi-arrows-collapse"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body collapse show" id="item-{{ Str::slug($item) }}">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Material</th>
                                    <th>Specification</th>
                                    <th>Unit</th>
                                    <th>Quantity</th>
                                    <th>Notes</th>
                                    <th>Design Reference</th>
                                    <th>Approved By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($materialsGroup as $index => $material)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $material->material }}</td>
                                    <td>{{ $material->specification ?? 'N/A' }}</td>
                                    <td>{{ $material->unit ?? 'N/A' }}</td>
                                    <td>{{ $material->quantity ?? 'N/A' }}</td>
                                    <td>{{ $material->notes ?? 'N/A' }}</td>
                                    <td>
                                        @if($material->design_reference)
                                            <a href="{{ $material->design_reference }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-box-arrow-up-right"></i> View
                                            </a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>{{ $material->approved_by ?? 'Pending' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('projects.files.materials.edit', ['project' => $project->id, 'material' => $material->id]) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('projects.files.materials.destroy', ['project' => $project->id, 'material' => $material->id]) }}" 
                                                  method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this material?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
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
                </div>
            </div>
        @endforeach
    @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No materials have been added yet.
        </div>
    @endif
</div>

@push('styles')
<style>
    .table th {
        white-space: nowrap;
    }
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
</style>
@endpush

@endsection
