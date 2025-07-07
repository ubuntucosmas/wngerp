@extends('layouts.master')
@section('title', 'Item Templates')

@section('content')
<div class="container-fluid p-3">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1 text-dark fw-bold">
                <i class="bi bi-file-earmark-text me-2 text-primary"></i>Item Templates
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('projects.index') }}" class="text-decoration-none">Projects</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Templates</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('templates.categories.create') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-folder-plus me-1"></i>Category
            </a>
            <a href="{{ route('templates.templates.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i>New Template
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

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

    <!-- Search & Filter Bar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="categoryFilter" class="form-label small fw-semibold text-muted mb-1">Category</label>
                    <select class="form-select form-select-sm" id="categoryFilter">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }} ({{ $category->templates_count }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="searchTemplate" class="form-label small fw-semibold text-muted mb-1">Search</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" id="searchTemplate" 
                               placeholder="Search templates by name...">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm w-100" id="clearFilters">
                        <i class="bi bi-x-circle me-1"></i>Clear
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Templates Grid -->
    <div class="row g-3" id="templatesGrid">
        @forelse($templates as $template)
            <div class="col-xl-3 col-lg-4 col-md-6 template-card" data-category="{{ $template->category_id }}" data-name="{{ strtolower($template->name) }}">
                <div class="card h-100 border-0 shadow-sm hover-shadow transition-all">
                    <div class="card-header bg-white border-bottom-0 pb-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-bold text-dark line-clamp-1">{{ $template->name }}</h6>
                                <sptext-dark border small">{{ $template->category->name }}</span>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-link text-primary p-0" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical">Actions</i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                    <li><a class="dropdown-item py-2" href="{{ route('templates.templates.show', $template) }}">
                                        <i class="bi bi-eye me-2 text-primary"></i>View Details
                                    </a></li>
                                    <li><a class="dropdown-item py-2" href="{{ route('templates.templates.edit', $template) }}">
                                        <i class="bi bi-pencil me-2 text-warning"></i>Edit
                                    </a></li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <form action="{{ route('templates.templates.duplicate', $template) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item py-2">
                                                <i class="bi bi-files me-2 text-info"></i>Duplicate
                                            </button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <form action="{{ route('templates.templates.destroy', $template) }}" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this template?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item py-2 text-danger">
                                                <i class="bi bi-trash me-2"></i>Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body pt-2">
                        @if($template->description)
                            <p class="card-text small text-muted mb-3 line-clamp-2">{{ $template->description }}</p>
                        @endif
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-primary small">
                                    <i class="bi bi-list-check me-1"></i>{{ $template->particulars->count() }} items
                                </span>
                                @if($template->estimated_cost)
                                    <span class="bg-success-subtle text-success small">
                                        <i class=""></i>KSh {{ number_format($template->estimated_cost, 0) }}
                                    </span>
                                @endif
                            </div>
                            <!-- <span class="badge {{ $template->is_active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} small">
                                {{ $template->is_active ? 'Active' : 'Inactive' }}
                            </span> -->
                        </div>

                        <div class="d-flex justify-content-between align-items-center small text-muted">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-person-circle me-1"></i>
                                <span class="line-clamp-1">Created by: {{ $template->creator->name }}</span>
                            </div>
                            <div class="text-end">
                                <div class="line-clamp-1">{{ $template->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent border-top-0 pt-0">
                        <a href="{{ route('templates.templates.show', $template) }}" 
                           class="btn btn-outline-primary btn-sm w-100">
                            <i class="bi bi-eye me-1"></i>View Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-file-earmark-text display-1 text-muted"></i>
                    </div>
                    <h5 class="text-muted mb-2">No templates found</h5>
                    <p class="text-muted mb-4">Create your first template to get started with reusable item configurations.</p>
                    <a href="{{ route('templates.templates.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Create Your First Template
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($templates->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $templates->links() }}
        </div>
    @endif
</div>

<!-- Empty State for No Results -->
<div id="noResults" class="col-12" style="display: none;">
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="bi bi-search display-1 text-muted"></i>
        </div>
        <h5 class="text-muted mb-2">No templates found</h5>
        <p class="text-muted mb-4">Try adjusting your search or filter criteria.</p>
        <button type="button" class="btn btn-outline-secondary" id="resetFilters">
            <i class="bi bi-arrow-clockwise me-2"></i>Reset Filters
        </button>
    </div>
</div>
@endsection

@push('styles')
<style>
.hover-shadow:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.transition-all {
    transition: all 0.3s ease;
}

.line-clamp-1 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
}

.line-clamp-2 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.bg-primary-subtle {
    background-color: rgba(13, 110, 253, 0.1);
}

.bg-success-subtle {
    background-color: rgba(25, 135, 84, 0.1);
}

.bg-secondary-subtle {
    background-color: rgba(108, 117, 125, 0.1);
}

.bg-light {
    background-color: #f8f9fa !important;
}

.card {
    border-radius: 0.75rem;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

.form-select-sm, .form-control-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let filterTimeout;
    
    // Category filter
    $('#categoryFilter').on('change', function() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(filterTemplates, 300);
    });

    // Search filter with debouncing
    $('#searchTemplate').on('keyup', function() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(filterTemplates, 300);
    });

    // Clear filters
    $('#clearFilters').on('click', function() {
        $('#categoryFilter').val('');
        $('#searchTemplate').val('');
        filterTemplates();
    });

    // Reset filters from empty state
    $('#resetFilters').on('click', function() {
        $('#categoryFilter').val('');
        $('#searchTemplate').val('');
        filterTemplates();
    });

    function filterTemplates() {
        const categoryId = $('#categoryFilter').val();
        const searchTerm = $('#searchTemplate').val().toLowerCase();
        let visibleCount = 0;

        $('.template-card').each(function() {
            const $card = $(this);
            const cardCategory = $card.data('category');
            const cardName = $card.data('name');
            
            let showCard = true;

            // Category filter
            if (categoryId && cardCategory != categoryId) {
                showCard = false;
            }

            // Search filter
            if (searchTerm && !cardName.includes(searchTerm)) {
                showCard = false;
            }

            if (showCard) {
                $card.show();
                visibleCount++;
            } else {
                $card.hide();
            }
        });

        // Show/hide empty state
        if (visibleCount === 0) {
            $('#templatesGrid').hide();
            $('#noResults').show();
        } else {
            $('#templatesGrid').show();
            $('#noResults').hide();
        }
    }

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
});
</script>
@endpush 