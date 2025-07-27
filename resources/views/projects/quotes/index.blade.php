@extends('layouts.master')

@section('title', 'Quotes')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    @if(isset($enquiry))
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.index') }}">Enquiries</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.files', $enquiry) }}">{{ $enquiry->project_name }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.files.quotation', $enquiry) }}">Budget & Quotation</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Quotes</li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}">{{ $project->name }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('projects.quotation.index', $project) }}">Budget & Quotation</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Quotes</li>
                    @endif
                </ol>
            </nav>
            <h2 class="mb-0">Quotes</h2>
        </div>
        <div class="page-actions">
            <a href="{{ (isset($enquiry) && is_object($enquiry) && isset($enquiry->id)) ? route('enquiries.files.quotation', $enquiry) : route('projects.quotation.index', $project) }}" class="btn btn-primary me-2">
                <i class="bi bi-arrow-left me-2"></i>Back to Budget & Quotation
            </a>
            <a href="{{ isset($enquiry) ? route('enquiries.quotes.create', $enquiry) : (isset($project) && $project->id ? route('quotes.create', $project) : '#') }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i> Create Quote
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-file-earmark-text text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Quotes</h6>
                            <h4 class="fw-bold mb-0">{{ $quotes->total() ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-currency-dollar text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Value</h6>
                            <h4 class="fw-bold mb-0">KES {{ number_format($quotes->sum(function($quote) { return $quote->lineItems->sum('total'); }), 0) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-calendar-event text-info fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Latest Quote</h6>
                            <h4 class="fw-bold mb-0">{{ $quotes->first() ? $quotes->first()->quote_date->format('M d') : 'N/A' }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-people text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Unique Customers</h6>
                            <h4 class="fw-bold mb-0">{{ $quotes->unique('customer_name')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Quotes Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="fw-semibold mb-0">All Quotes</h5>
                <div class="d-flex gap-2">
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" id="searchInput" placeholder="Search quotes...">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            @if($quotes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="quotesTable">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 py-3 px-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <span>Quote Details</span>
                                    </div>
                                </th>
                                <th class="border-0 py-3">Customer</th>
                                <th class="border-0 py-3">Date</th>
                                <th class="border-0 py-3 text-end">Total Amount</th>
                                <th class="border-0 py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quotes as $quote)
                                @php
                                    $total = $quote->lineItems->sum('total');
                                    $itemCount = $quote->lineItems->count();
                                @endphp
                                <tr class="border-bottom">
                                    <td class="py-3 px-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                                <i class="bi bi-file-earmark-text text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <h6 class="fw-semibold mb-0">Quote #{{ $quote->id }}</h6>
                                                    @if($quote->status === 'approved')
                                                        <span class="badge bg-success">
                                                            <i class="bi bi-check-circle me-1"></i>Approved
                                                        </span>
                                                    @elseif($quote->status === 'rejected')
                                                        <span class="badge bg-danger">
                                                            <i class="bi bi-x-circle me-1"></i>Rejected
                                                        </span>
                                                    @elseif($quote->status === 'waiting_approval')
                                                        <span class="badge bg-warning">
                                                            <i class="bi bi-clock me-1"></i>Pending
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">
                                                            <i class="bi bi-file-earmark me-1"></i>Draft
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="d-flex align-items-center gap-3 text-muted small">
                                                    <span><i class="bi bi-list-ul me-1"></i>{{ $itemCount }} items</span>
                                                    @if($quote->reference)
                                                        <span><i class="bi bi-tag me-1"></i>{{ $quote->reference }}</span>
                                                    @endif
                                                    @if($quote->approved_at)
                                                        <span><i class="bi bi-calendar-check me-1"></i>{{ $quote->approved_at->format('M d, Y') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <div>
                                            <h6 class="fw-semibold mb-1">{{ $quote->customer_name }}</h6>
                                            @if($quote->customer_location)
                                                <small class="text-muted">{{ $quote->customer_location }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <div>
                                            <span class="fw-semibold">{{ $quote->quote_date->format('M d, Y') }}</span>
                                            <br>
                                            <small class="text-muted">{{ $quote->quote_date->diffForHumans() }}</small>
                                        </div>
                                    </td>
                                    <td class="py-3 text-end">
                                        <div>
                                            <h6 class="fw-bold text-success mb-1">KES {{ number_format($total, 2) }}</h6>
                                            <small class="text-muted">VAT included</small>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ isset($enquiry) ? route('enquiries.quotes.show', ['enquiry' => $enquiry->id, 'quote' => $quote->id]) : route('quotes.show', ['project' => $project->id, 'quote' => $quote->id]) }}" 
                                               class="btn btn-sm btn-outline-primary" title="View Quote">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ isset($enquiry) ? route('enquiries.quotes.edit', ['enquiry' => $enquiry->id, 'quote' => $quote->id]) : route('quotes.edit', ['project' => $project->id, 'quote' => $quote->id]) }}" 
                                               class="btn btn-sm btn-outline-secondary" title="Edit Quote">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="{{ isset($enquiry) ? route('enquiries.quotes.print', ['enquiry' => $enquiry->id, 'quote' => $quote->id]) : route('quotes.print', ['project' => $project->id, 'quote' => $quote->id]) }}" 
                                               class="btn btn-sm btn-outline-info" title="Print Quote" target="_blank">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                            <a href="{{ isset($enquiry) ? route('enquiries.quotes.excel', ['enquiry' => $enquiry->id, 'quote' => $quote->id]) : route('quotes.excel', ['project' => $project->id, 'quote' => $quote->id]) }}" 
                                               class="btn btn-sm btn-outline-success" title="Export to Excel">
                                                <i class="bi bi-file-earmark-excel"></i>
                                            </a>
                                            @hasanyrole('super-admin|admin|finance|pm')
                                                @if($quote->status !== 'approved')
                                                    <form action="{{ route('quotes.approve', [isset($enquiry) ? $enquiry->id : $project->id, $quote->id]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Approve Quote" 
                                                                onclick="return confirm('Are you sure you want to approve this quote? This will notify all users.')">
                                                            <i class="bi bi-check-circle"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endhasanyrole
                                            @if(auth()->user()->hasRole('super-admin'))
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-quote" 
                                                        title="Delete Quote" data-quote-id="{{ $quote->id }}" data-project-id="{{ $project->id ?? '' }}" data-enquiry-id="{{ $enquiry->id ?? '' }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center p-4 border-top">
                    <div class="text-muted small">
                        Showing {{ $quotes->firstItem() }} to {{ $quotes->lastItem() }} of {{ $quotes->total() }} quotes
                    </div>
                    <div>
                    {{ $quotes->appends(['project' => $project->id ?? '', 'enquiry' => $enquiry->id ?? ''])->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-file-earmark-text text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="fw-semibold mb-2">No quotes found</h4>
                    <p class="text-muted mb-4">Get started by creating your first quote for this project</p>
                    <a href="{{ isset($enquiry) ? route('enquiries.quotes.create', $enquiry) : route('quotes.create', $project) }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                        <i class="bi bi-plus-circle"></i>
                        Create Your First Quote
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Quote</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this quote? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Quote</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const table = document.getElementById('quotesTable');
        const rows = table.querySelectorAll('tbody tr');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Delete confirmation
        document.querySelectorAll('.delete-quote').forEach(button => {
            button.addEventListener('click', function() {
                const quoteId = this.dataset.quoteId;
                const form = document.getElementById('deleteForm');
                form.action = `{{ isset($enquiry) ? route('enquiries.quotes.destroy', ['enquiry' => $enquiry->id, 'quote' => ':id']) : route('quotes.destroy', ['project' => $project->id, 'quote' => ':id']) }}`.replace(':id', quoteId);
                
                const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
                modal.show();
            });
        });
    });
</script>
@endpush

<style>
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.btn-group .btn {
    border-radius: 0.375rem !important;
}

.btn-group .btn:first-child {
    border-top-left-radius: 0.375rem !important;
    border-bottom-left-radius: 0.375rem !important;
}

.btn-group .btn:last-child {
    border-top-right-radius: 0.375rem !important;
    border-bottom-right-radius: 0.375rem !important;
}
</style>
@endsection
