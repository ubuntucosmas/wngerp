@extends('layouts.master')
@section('title', 'View Item Template')

@section('content')
<div class="container-fluid p-2">
    <div class="mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                <li class="breadcrumb-item"><a href="{{ route('templates.templates.index') }}">Item Templates</a></li>
                <li class="breadcrumb-item active" aria-current="page">View Template</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 m-0">Item Template Details</h1>
            <div>
                <a href="{{ route('templates.templates.edit', $itemTemplate) }}" class="btn btn-outline-primary me-2">
                    <i class="bi bi-pencil"></i> Edit Template
                </a>
                <a href="{{ route('templates.templates.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Templates
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark-text me-2"></i>Template Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Template Name</label>
                            <p class="form-control-plaintext">{{ $itemTemplate->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Category</label>
                            <p class="form-control-plaintext">{{ $itemTemplate->category ? $itemTemplate->category->name : 'No Category' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Description</label>
                            <p class="form-control-plaintext">{{ $itemTemplate->description ?: 'No description provided' }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Estimated Cost</label>
                            <p class="form-control-plaintext">
                                {{ $itemTemplate->estimated_cost ? 'KSh ' . number_format($itemTemplate->estimated_cost, 2) : 'Not specified' }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <!-- <label class="form-label fw-bold">Status</label>
                            <p class="form-control-plaintext">
                                <span class="badge {{ $itemTemplate->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $itemTemplate->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p> -->
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Created</label>
                            <p class="form-control-plaintext">{{ $itemTemplate->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Particulars Section -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-list-check me-2"></i>Template Particulars ({{ $itemTemplate->particulars->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($itemTemplate->particulars->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Particular</th>
                                        <th>Unit</th>
                                        <th>Default Quantity</th>
                                        <th>Comment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($itemTemplate->particulars as $index => $particular)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $particular->particular }}</td>
                                            <td>{{ $particular->unit ?: '-' }}</td>
                                            <td>{{ $particular->default_quantity }}</td>
                                            <td>{{ $particular->comment ?: '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="mt-3 text-muted">No particulars defined for this template.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('templates.templates.edit', $itemTemplate) }}" class="btn btn-primary">
                            <i class="bi bi-pencil me-2"></i>Edit Template
                        </a>
                        
                        <form action="{{ route('templates.templates.duplicate', $itemTemplate) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-success w-100">
                                <i class="bi bi-files me-2"></i>Duplicate Template
                            </button>
                        </form>
                        
                        <a href="{{ route('templates.templates.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
            </div>

            <!-- Template Statistics -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>Template Stats
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-1">{{ $itemTemplate->particulars->count() }}</h4>
                                <small class="text-muted">Particulars</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-1">{{ $itemTemplate->is_active ? 'Active' : 'Inactive' }}</h4>
                            <small class="text-muted">Status</small>
                        </div>
                    </div>
                    
                    @if($itemTemplate->estimated_cost)
                        <hr>
                        <div class="text-center">
                            <h5 class="text-info mb-1">KSh {{ number_format($itemTemplate->estimated_cost, 2) }}</h5>
                            <small class="text-muted">Estimated Cost</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 