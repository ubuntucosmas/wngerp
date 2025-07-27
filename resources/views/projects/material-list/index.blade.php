@extends('layouts.master')
@section('title', '{{ isset($enquiry) ? "Enquiry" : "Project" }} Material Lists')

@section('content')
<div class="container-fluid p-0">
    <!-- Header Section -->
    <div class="bg-white border-bottom shadow-sm">
        <div class="container-fluid px-4 py-3">
            <div class="d-flex justify-content-between align-items-center">
    <div>
                    <nav aria-label="breadcrumb" class="mb-2">
                        <ol class="breadcrumb mb-0 small">
                @if(isset($enquiry))
                                <li class="breadcrumb-item"><a href="{{ route('enquiries.index') }}" class="text-decoration-none">Enquiries</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('enquiries.files', $enquiry) }}" class="text-decoration-none">{{ $enquiry->project_name }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Material Lists</li>
                @else
                                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}" class="text-decoration-none">Projects</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}" class="text-decoration-none">{{ $project->name }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Material Lists</li>
                @endif
            </ol>
        </nav>
                    <h4 class="mb-0 fw-bold text-dark">Material Lists</h4>
                    <p class="text-muted small mb-0">Manage project material requirements and specifications</p>
    </div>
                <div class="d-flex gap-2">
                    <a href="{{ (isset($enquiry) && is_object($enquiry) && isset($enquiry->id)) ? route('enquiries.files', $enquiry) : route('projects.files.index', $project) }}" 
                       class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Back
                    </a>
                    <a href="{{ isset($enquiry) ? route('enquiries.material-list.create', $enquiry) : route('projects.material-list.create', $project) }}" 
                       class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>New Material List
                    </a>
                </div>
            </div>
    </div>
</div>

    <!-- Content Section -->
    <div class="container-fluid px-4 py-4">
        @if($materialLists->count())
            <div class="row g-3">
                @foreach($materialLists as $materialList)
                    <div class="col-lg-6 col-xl-4">
                        <div class="card h-100 border-0 shadow-sm hover-shadow">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="flex-grow-1">
                                        <h6 class="card-title mb-1 fw-semibold text-dark">
                                            Material List #{{ $materialList->id }}
                                        </h6>
                                        <p class="text-muted small mb-0">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            {{ $materialList->date_range }}
                                        </p>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ isset($enquiry) ? route('enquiries.material-list.show', [$enquiry, $materialList]) : route('projects.material-list.show', [$project, $materialList]) }}">
                                                    <i class="bi bi-eye me-2"></i>View
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ isset($enquiry) ? route('enquiries.material-list.edit', [$enquiry, $materialList]) : route('projects.material-list.edit', [$project, $materialList]) }}">
                                                    <i class="bi bi-pencil me-2"></i>Edit
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item" href="{{ isset($enquiry) ? route('enquiries.material-list.download', [$enquiry, $materialList]) : route('projects.material-list.download', [$project, $materialList]) }}">
                                                    <i class="bi bi-file-earmark-pdf me-2"></i>Download PDF
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ isset($enquiry) ? route('enquiries.material-list.print', [$enquiry, $materialList]) : route('projects.material-list.print', [$project, $materialList]) }}" target="_blank">
                                                    <i class="bi bi-printer me-2"></i>Print
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ isset($enquiry) ? route('enquiries.material-list.exportExcel', [$enquiry, $materialList]) : route('projects.material-list.exportExcel', [$project, $materialList]) }}">
                                                    <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
                                                </a>
                                            </li>
                                            @if(auth()->user()->hasAnyRole(['pm', 'po', 'super-admin']))
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ isset($enquiry) ? route('enquiries.material-list.destroy', [$enquiry, $materialList]) : route('projects.material-list.destroy', [$project, $materialList]) }}" 
                                                      method="POST" 
                                                      class="d-inline delete-form"
                                                      onsubmit="return confirm('Are you sure you want to delete this material list? This action cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bi bi-trash me-2"></i>Delete
                                            </button>
                                        </form>
                                            </li>
                                    @endif
                                        </ul>
                                    </div>
                                </div>

                                <!-- Stats Row -->
                                <div class="row g-2 mb-3">
                                    <div class="col-3">
                                        <div class="text-center p-2 bg-light rounded">
                                            <!-- <div class="fw-bold text-primary">{{ $materialList->item_counts['production_items'] }}</div> -->
                                            <div class="small text-muted">Production</div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="text-center p-2 bg-light rounded">
                                            <!-- <div class="fw-bold text-success">{{ $materialList->item_counts['materials_hire'] }}</div> -->
                                            <div class="small text-muted">For Hire</div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="text-center p-2 bg-light rounded">
                                            <!-- <div class="fw-bold text-warning">{{ $materialList->item_counts['labour_items'] }}</div> -->
                                            <div class="small text-muted">Labour</div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="text-center p-2 bg-light rounded">
                                            <!-- <div class="fw-bold text-danger">{{ $materialList->item_counts['outsourced_items'] }}</div> -->
                                            <div class="small text-muted">Outsourced</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Details -->
                                <div class="small text-muted mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span><i class="bi bi-person me-1"></i>Prepared by:</span>
                                        <span class="fw-medium">{{ $materialList->approved_by ?? 'N/A' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span><i class="bi bi-building me-1"></i>Departments:</span>
                                        <span class="fw-medium">{{ $materialList->approved_departments ?? 'N/A' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span><i class="bi bi-clock me-1"></i>Created:</span>
                                        <span class="fw-medium">{{ $materialList->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-grid gap-2">
                                    <a href="{{ isset($enquiry) ? route('enquiries.material-list.show', [$enquiry, $materialList]) : route('projects.material-list.show', [$project, $materialList]) }}" 
                                       class="btn btn-primary btn-sm">
                                        <i class="bi bi-eye me-1"></i>View Details
                                    </a>
                                    <a href="{{ isset($enquiry) ? route('enquiries.budget.create', ['enquiry' => $enquiry, 'material_list_id' => $materialList->id]) : route('budget.create', ['project' => $project, 'material_list_id' => $materialList->id]) }}" 
                                       class="btn btn-success btn-sm">
                                        <i class="bi bi-calculator me-1"></i>Create Budget
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($materialLists->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $materialLists->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-clipboard2-x display-1 text-muted"></i>
                </div>
                <h5 class="text-muted mb-2">No Material Lists Found</h5>
                <p class="text-muted mb-4">Get started by creating your first material list for this {{ isset($enquiry) ? 'enquiry' : 'project' }}.</p>
                <a href="{{ isset($enquiry) ? route('enquiries.material-list.create', $enquiry) : route('projects.material-list.create', $project) }}" 
                   class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>Create Material List
                </a>
        </div>
        @endif
    </div>
</div>

<style>
.hover-shadow:hover {
    transform: translateY(-2px);
    transition: all 0.2s ease-in-out;
}

.card {
    transition: all 0.2s ease-in-out;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

.dropdown-item.text-danger:hover {
    background-color: #fef2f2;
    color: #dc2626 !important;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

.delete-form {
    margin: 0;
}

.delete-form button {
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    padding: 0.5rem 1rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced delete confirmation
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (confirm('Are you sure you want to delete this material list? This action cannot be undone and will also delete any associated budget items.')) {
                this.submit();
            }
        });
    });
});
</script>
@endsection
