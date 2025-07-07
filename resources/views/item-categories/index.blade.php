@extends('layouts.master')
@section('title', 'Item Categories')

@section('content')
<div class="container-fluid p-2">
    <div class="mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                <li class="breadcrumb-item"><a href="{{ route('templates.templates.index') }}">Item Templates</a></li>
                <li class="breadcrumb-item active" aria-current="page">Categories</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 m-0">Item Categories</h1>
            <a href="{{ route('templates.categories.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> New Category
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        @forelse($categories as $category)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">{{ $category->name }}</h5>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('templates.categories.show', $category) }}">
                                        <i class="bi bi-eye"></i> View
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('templates.categories.edit', $category) }}">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('templates.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        @if($category->description)
                            <p class="card-text text-muted">{{ Str::limit($category->description, 100) }}</p>
                        @endif
                        
                        <div class="mb-2">
                            <span class="badge bg-primary">{{ $category->templates_count }} {{ Str::plural('template', $category->templates_count) }}</span>
                        </div>

                        <div class="small text-muted">
                            <div><i class="bi bi-person"></i> {{ $category->creator->name }}</div>
                            <div><i class="bi bi-calendar"></i> {{ $category->created_at->format('M d, Y') }}</div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="{{ route('templates.templates.index', ['category' => $category->id]) }}" class="btn btn-sm btn-outline-primary w-100">
                            View Templates
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-folder fs-1 text-muted"></i>
                    <h5 class="mt-3">No categories found</h5>
                    <p class="text-muted">Create your first category to get started.</p>
                    <a href="{{ route('templates.categories.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Create Category
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($categories->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection 