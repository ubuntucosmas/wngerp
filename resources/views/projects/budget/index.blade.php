@extends('layouts.master')

@section('title', '{{ isset($enquiry) ? "Enquiry" : "Project" }} Budgets')

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
                                <li class="breadcrumb-item"><a href="{{ route('enquiries.files.quotation', $enquiry) }}" class="text-decoration-none">Budget & Quotation</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Budgets</li>
                    @else
                                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}" class="text-decoration-none">Projects</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}" class="text-decoration-none">{{ $project->name }}</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('projects.quotation.index', $project) }}" class="text-decoration-none">Budget & Quotation</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Budgets</li>
                    @endif
                </ol>
            </nav>
                    <h4 class="mb-0 fw-bold text-dark">Budgets</h4>
                    <p class="text-muted small mb-0">Manage project budgets and financial planning</p>
        </div>
                <div class="d-flex gap-2">
                    <a href="{{ (isset($enquiry) && is_object($enquiry) && isset($enquiry->id)) ? route('enquiries.files.quotation', $enquiry) : route('projects.quotation.index', $project) }}" 
                       class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Back
            </a>
            @hasanyrole('finance|po|pm|super-admin')
                    <a href="{{ isset($enquiry) ? route('enquiries.budget.create', $enquiry) : (isset($project) ? route('budget.create', $project) : '#') }}" 
                       class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>New Budget
            </a>
            @endhasanyrole
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="container-fluid px-4 py-4">
    @if($budgets->count())
            <div class="row g-3">
                @foreach($budgets as $budget)
                    <div class="col-lg-6 col-xl-4">
                        <div class="card h-100 border-0 shadow-sm hover-shadow">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="flex-grow-1">
                                        <h6 class="card-title mb-1 fw-semibold text-dark">
                                            Budget #{{ $budget->id }}
                                            @if($budget->status)
                                                <span class="badge bg-{{ $budget->status === 'approved' ? 'success' : ($budget->status === 'draft' ? 'warning' : 'secondary') }} ms-2">
                                {{ ucfirst($budget->status) }}
                            </span>
                                            @endif
                                        </h6>
                                        <p class="text-muted small mb-0">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            {{ \Carbon\Carbon::parse($budget->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($budget->end_date)->format('M d, Y') }}
                                        </p>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ isset($enquiry) ? route('enquiries.budget.show', [$enquiry, $budget]) : (isset($project) ? route('budget.show', [$project, $budget]) : '#') }}">
                                                    <i class="bi bi-eye me-2"></i>View
                                                </a>
                                            </li>
                            @hasanyrole('finance|po|pm|super-admin')
                                            <li>
                                                <a class="dropdown-item" href="{{ isset($enquiry) ? route('enquiries.budget.edit', [$enquiry, $budget]) : (isset($project) ? route('budget.edit', [$project, $budget]) : '#') }}">
                                                    <i class="bi bi-pencil me-2"></i>Edit
                                                </a>
                                            </li>
                                            @endhasanyrole
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item" href="{{ isset($enquiry) ? route('enquiries.budget.export', [$enquiry, $budget]) : (isset($project) ? route('budget.export', [$project, $budget]) : '#') }}">
                                                    <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ isset($enquiry) ? route('enquiries.budget.download', [$enquiry, $budget]) : (isset($project) ? route('budget.download', [$project, $budget]) : '#') }}">
                                                    <i class="bi bi-download me-2"></i>Download PDF
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ isset($enquiry) ? route('enquiries.budget.print', [$enquiry, $budget]) : (isset($project) ? route('budget.print', [$project, $budget]) : '#') }}" target="_blank">
                                                    <i class="bi bi-printer me-2"></i>Print
                                                </a>
                                            </li>
                                @if(auth()->user()->hasRole('super-admin'))
                                            <li><hr class="dropdown-divider"></li>
                                            @if($budget->status !== 'approved')
                                            <li>
                                                <form action="{{ isset($enquiry) ? route('enquiries.budget.approve', ['enquiry' => $enquiry->id, 'budget' => $budget->id]) : (isset($project) ? route('budget.approve', ['project' => $project->id, 'budget' => $budget->id]) : '#') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-success">
                                                        <i class="bi bi-check-circle me-2"></i>Approve
                                                    </button>
                                                </form>
                                            </li>
                                            @endif
                                            <li>
                                    <form action="{{ isset($enquiry) ? route('enquiries.budget.destroy', ['enquiry' => $enquiry->id, 'budget' => $budget->id]) : (isset($project) ? route('budget.destroy', ['project' => $project->id, 'budget' => $budget->id]) : '#') }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger delete-budget">
                                                        <i class="bi bi-trash me-2"></i>Delete
                                        </button>
                                    </form>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>

                                <!-- Budget Total -->
                                <div class="text-center mb-3">
                                    <div class="bg-primary bg-opacity-10 rounded p-3">
                                        <div class="h4 fw-bold text-primary mb-0">
                                            KES {{ number_format($budget->budget_total, 2) }}
                                        </div>
                                        <div class="small text-muted">Total Budget</div>
                                    </div>
                                </div>

                                <!-- Details -->
                                <div class="small text-muted mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span><i class="bi bi-person me-1"></i>Prepared by:</span>
                                        <span class="fw-medium">{{ $budget->approved_by ?? 'N/A' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span><i class="bi bi-building me-1"></i>Departments:</span>
                                        <span class="fw-medium">{{ $budget->approved_departments ?? 'N/A' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span><i class="bi bi-clock me-1"></i>Created:</span>
                                        <span class="fw-medium">{{ $budget->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-grid gap-2">
                                    <a href="{{ isset($enquiry) ? route('enquiries.budget.show', [$enquiry, $budget]) : (isset($project) ? route('budget.show', [$project, $budget]) : '#') }}" 
                                       class="btn btn-primary btn-sm">
                                        <i class="bi bi-eye me-1"></i>View Details
                                    </a>
                                    @if(!$budget->quote)
                                        <a href="{{ isset($enquiry) ? route('enquiries.quotes.create', ['enquiry' => $enquiry, 'project_budget_id' => $budget->id]) : route('quotes.create', ['project' => $project, 'project_budget_id' => $budget->id]) }}" 
                                           class="btn btn-success btn-sm">
                                            <i class="bi bi-file-earmark-text me-1"></i>Create Quote
                                        </a>
                                    @else
                                        <a href="{{ isset($enquiry) ? route('enquiries.quotes.show', ['enquiry' => $enquiry, 'quote' => $budget->quote->id]) : route('quotes.show', ['project' => $project, 'quote' => $budget->quote->id]) }}" 
                                           class="btn btn-info btn-sm">
                                            <i class="bi bi-file-earmark-text me-1"></i>View Quote
                                        </a>
                                @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($budgets->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $budgets->links() }}
    </div>
            @endif
    @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-calculator display-1 text-muted"></i>
                </div>
                <h5 class="text-muted mb-2">No Budgets Found</h5>
                <p class="text-muted mb-4">Get started by creating your first budget for this {{ isset($enquiry) ? 'enquiry' : 'project' }}.</p>
                @hasanyrole('finance|po|pm|super-admin')
                <a href="{{ isset($enquiry) ? route('enquiries.budget.create', $enquiry) : (isset($project) ? route('budget.create', $project) : '#') }}" 
                   class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>Create Budget
                </a>
                @endhasanyrole
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

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}
</style>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
        // Delete confirmation
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-budget').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
@endsection