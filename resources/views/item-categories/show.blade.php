@extends('layouts.master')
@section('title', 'View Item Category')

@section('content')
<div class="container-fluid p-3">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1 text-dark fw-bold">
                <i class="bi bi-folder me-2 text-primary"></i>{{ $itemCategory->name }}
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('projects.index') }}" class="text-decoration-none">Projects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('templates.templates.index') }}" class="text-decoration-none">Templates</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('templates.categories.index') }}" class="text-decoration-none">Categories</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $itemCategory->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('templates.categories.edit', $itemCategory) }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-pencil me-1"></i>Edit
            </a>
            <a href="{{ route('templates.categories.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Category Details -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2 text-primary"></i>Category Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold text-muted">Category Name</label>
                            <p class="mb-0 fw-bold">{{ $itemCategory->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold text-muted">Status</label>
                            <p class="mb-0">
                                <span class="badge bg-success-subtle text-success">Active</span>
                            </p>
                        </div>
                        @if($itemCategory->description)
                            <div class="col-12 mb-3">
                                <label class="form-label small fw-semibold text-muted">Description</label>
                                <p class="mb-0">{{ $itemCategory->description }}</p>
                            </div>
                        @endif
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold text-muted">Created By</label>
                            <p class="mb-0">
                                <i class="bi bi-person-circle me-1"></i>{{ $itemCategory->creator->name }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-semibold text-muted">Created Date</label>
                            <p class="mb-0">
                                <i class="bi bi-calendar me-1"></i>{{ $itemCategory->created_at->format('M d, Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Templates in this Category -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark-text me-2 text-primary"></i>Templates ({{ $itemCategory->templates->count() }})
                    </h5>
                    <a href="{{ route('templates.templates.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>Add Template
                    </a>
                </div>
                <div class="card-body">
                    @if($itemCategory->templates->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Template Name</th>
                                        <th>Items</th>
                                        <th>Cost</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($itemCategory->templates as $template)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $template->name }}</div>
                                                @if($template->description)
                                                    <small class="text-muted">{{ Str::limit($template->description, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-primary-subtle text-primary">
                                                    {{ $template->particulars->count() }} items
                                                </span>
                                            </td>
                                            <td>
                                                @if($template->total_estimated_cost)
                                                    <span class="text-success fw-semibold">KSh {{ number_format($template->total_estimated_cost, 0) }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $template->is_active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                                    {{ $template->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('templates.templates.show', $template) }}" 
                                                       class="btn btn-outline-primary btn-sm">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('templates.templates.edit', $template) }}" 
                                                       class="btn btn-outline-warning btn-sm">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-file-earmark-text display-4 text-muted"></i>
                            <h6 class="mt-3 text-muted">No templates in this category</h6>
                            <p class="text-muted mb-3">Create the first template for this category.</p>
                            <a href="{{ route('templates.templates.create') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-circle me-2"></i>Create Template
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning me-2 text-primary"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('templates.categories.edit', $itemCategory) }}" class="btn btn-primary">
                            <i class="bi bi-pencil me-2"></i>Edit Category
                        </a>
                        <a href="{{ route('templates.templates.create') }}" class="btn btn-outline-primary">
                            <i class="bi bi-plus-circle me-2"></i>Add Template
                        </a>
                        <a href="{{ route('templates.categories.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back to Categories
                        </a>
                    </div>
                </div>
            </div>

            <!-- Category Statistics -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up me-2 text-primary"></i>Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-1">{{ $itemCategory->templates->count() }}</h4>
                                <small class="text-muted">Templates</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-1">{{ $itemCategory->templates->where('is_active', true)->count() }}</h4>
                            <small class="text-muted">Active</small>
                        </div>
                    </div>
                    
                    @if($itemCategory->templates->sum('total_estimated_cost') > 0)
                        <hr>
                        <div class="text-center">
                            <h5 class="text-info mb-1">KSh {{ number_format($itemCategory->templates->sum('total_estimated_cost'), 0) }}</h5>
                            <small class="text-muted">Total Estimated Cost</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.bg-primary-subtle {
    background-color: rgba(13, 110, 253, 0.1);
}

.bg-success-subtle {
    background-color: rgba(25, 135, 84, 0.1);
}

.bg-secondary-subtle {
    background-color: rgba(108, 117, 125, 0.1);
}

.card {
    border-radius: 0.75rem;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}
</style>
@endpush 