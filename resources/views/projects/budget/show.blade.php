@extends('layouts.master')

@section('title', 'Budget Details')

@section('content')
<div class="container-fluid p-0">
    <!-- Header Section -->
    <div class="bg-white border-bottom shadow-sm">
        <div class="container-fluid px-4 py-3">
            <div class="d-flex justify-content-between align-items-center">
        <div>
                    <nav aria-label="breadcrumb" class="mb-2">
                                                <ol class="breadcrumb mb-0 small">
                            @if(isset($enquiry) && $enquiry)
                                <li class="breadcrumb-item"><a href="{{ route('enquiries.index') }}" class="text-decoration-none">Enquiries</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('enquiries.files', $enquiry) }}" class="text-decoration-none">{{ $enquiry->project_name }}</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('enquiries.files.quotation', $enquiry) }}" class="text-decoration-none">Budget & Quotation</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('enquiries.budget.index', $enquiry) }}" class="text-decoration-none">Budgets</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Details</li>
                            @elseif(isset($project) && $project)
                                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}" class="text-decoration-none">Projects</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}" class="text-decoration-none">{{ $project->name }}</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('projects.quotation.index', $project) }}" class="text-decoration-none">Budget & Quotation</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('budget.index', $project) }}" class="text-decoration-none">Budgets</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Details</li>
                    @else
                                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}" class="text-decoration-none">Projects</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Budget Details</li>
                    @endif
                </ol>
            </nav>
                    <h4 class="mb-0 fw-bold text-dark">Budget #{{ $budget->id }}</h4>
                    <p class="text-muted small mb-0">{{ \Carbon\Carbon::parse($budget->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($budget->end_date)->format('M d, Y') }}</p>
        </div>
                                <div class="d-flex gap-2">
                    @if(isset($enquiry) && $enquiry)
                        <a href="{{ route('enquiries.budget.index', $enquiry) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Back
                        </a>
                        <a href="{{ route('enquiries.budget.edit', [$enquiry, $budget]) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </a>
                    @elseif(isset($project) && $project)
                        <a href="{{ route('budget.index', $project) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Back
                        </a>
                        <a href="{{ route('budget.edit', [$project, $budget]) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil me-1"></i>Edit
            </a>
                    @else
                        <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Back
                        </a>
                    @endif
                    <div class="dropdown">
                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-download me-1"></i>Export
                        </button>
                                                <ul class="dropdown-menu">
                            @if(isset($enquiry) && $enquiry)
                                <li>
                                    <a class="dropdown-item" href="{{ route('enquiries.budget.download', [$enquiry, $budget]) }}">
                                        <i class="bi bi-file-earmark-pdf me-2"></i>Download PDF
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('enquiries.budget.print', [$enquiry, $budget]) }}" target="_blank">
                                        <i class="bi bi-printer me-2"></i>Print
            </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('enquiries.budget.export', [$enquiry, $budget]) }}">
                                        <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
                                    </a>
                                </li>
                            @elseif(isset($project) && $project)
                                <li>
                                    <a class="dropdown-item" href="{{ route('budget.download', [$project, $budget]) }}">
                <i class="bi bi-file-earmark-pdf me-2"></i>Download PDF
            </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('budget.print', [$project, $budget]) }}" target="_blank">
                <i class="bi bi-printer me-2"></i>Print
            </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('budget.export', [$project, $budget]) }}">
                <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
            </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="container-fluid px-4 py-4">
        <!-- Project Information Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <div class="row g-3">
                    <div class="col-md-6">
                        <h6 class="fw-semibold text-dark mb-2">Project Information</h6>
                        <div class="small text-muted">
                            <div class="d-flex justify-content-between mb-1">
                                <span><i class="bi bi-building me-1"></i>Project:</span>
                                <span class="fw-medium">{{ (isset($enquiry) && $enquiry) ? $enquiry->project_name : (isset($project) && $project ? $project->name : 'N/A') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span><i class="bi bi-person me-1"></i>Client:</span>
                                <span class="fw-medium">{{ (isset($enquiry) && $enquiry) ? $enquiry->client_name : (isset($project) && $project ? $project->client_name : 'N/A') }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span><i class="bi bi-geo-alt me-1"></i>Venue:</span>
                                <span class="fw-medium">{{ (isset($enquiry) && $enquiry) ? $enquiry->venue : (isset($project) && $project ? $project->venue : 'N/A') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-semibold text-dark mb-2">Budget Details</h6>
                        <div class="small text-muted">
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
                                <span class="fw-medium">{{ $budget->created_at->format('M d, Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Budget Total Summary -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h6 class="fw-semibold text-dark mb-1">Budget Summary</h6>
                        <p class="text-muted small mb-0">Total budget allocation for this project period</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="h3 fw-bold text-primary mb-0">
                            KES {{ number_format((float) $budget->budget_total, 2) }}
                    </div>
                        <div class="small text-muted">Total Budget</div>
                </div>
            </div>
        </div>
    </div>

        <!-- Budget Items by Category -->
    @php $grouped = $budget->items->groupBy(fn($item) => strtolower(trim($item->category))); @endphp
    @foreach($grouped as $category => $items)
        @php
            $isProduction = str_replace(' ', '', strtolower($category)) === 'materials-production';
            $byItem = $items->groupBy('item_name');
        @endphp
        @if($isProduction)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary bg-opacity-10 border-0 py-3">
                        <h6 class="mb-0 fw-semibold text-primary">
                            <i class="bi bi-box-seam me-2"></i>Production Materials
                        </h6>
                </div>
                <div class="card-body p-0">
                    @foreach($byItem as $itemName => $particulars)
                            @php
                                $filteredParticulars = $particulars->filter(function($item) {
                                    return !empty($item->particular) && $item->quantity > 0;
                                });
                                $item = $filteredParticulars->first();
                                $itemTotal = $filteredParticulars->sum('budgeted_cost');
                            @endphp
                            @if($filteredParticulars->isNotEmpty())
                                <div class="border-bottom">
                                    <div class="p-3 bg-light">
                                        <h6 class="mb-0 fw-semibold text-dark">{{ $itemName }}</h6>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-sm mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="border-0">Particular</th>
                                                    <th class="border-0">Unit</th>
                                                    <th class="border-0 text-end">Quantity</th>
                                                    <th class="border-0 text-end">Unit Price</th>
                                                    <th class="border-0 text-end">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($filteredParticulars as $particular)
                                                    <tr>
                                                        <td class="fw-medium">{{ $particular->particular }}</td>
                                                        <td class="text-muted">{{ $particular->unit ?? '-' }}</td>
                                                        <td class="text-end">{{ number_format($particular->quantity, 2) }}</td>
                                                        <td class="text-end">{{ $particular->unit_price ? 'KES ' . number_format($particular->unit_price, 2) : '-' }}</td>
                                                        <td class="text-end fw-semibold">
                                                            KES {{ number_format($particular->budgeted_cost, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="table-light">
                                                <tr>
                                                    <td colspan="4" class="text-end fw-bold">Subtotal:</td>
                                                    <td class="text-end fw-bold text-primary">KES {{ number_format($itemTotal, 2) }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            @endif
                    @endforeach
                </div>
            </div>
        @else
                @php
                    $categoryColors = [
                        'Materials for Hire' => ['bg' => 'bg-success', 'text' => 'text-success'],
                        'Workshop labour' => ['bg' => 'bg-primary', 'text' => 'text-primary'],
                        'Site' => ['bg' => 'bg-success', 'text' => 'text-success'],
                        'Set down' => ['bg' => 'bg-warning', 'text' => 'text-warning'],
                        'Logistics' => ['bg' => 'bg-info', 'text' => 'text-info'],
                        'Outsourced' => ['bg' => 'bg-danger', 'text' => 'text-danger'],
                    ];
                    $color = $categoryColors[$category] ?? ['bg' => 'bg-secondary', 'text' => 'text-secondary'];
                @endphp
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header {{ $color['bg'] }} bg-opacity-10 border-0 py-3">
                        <h6 class="mb-0 fw-semibold {{ $color['text'] }}">
                            <i class="bi bi-tools me-2"></i>{{ ucwords(str_replace('-', ' ', $category)) }}
                        </h6>
                </div>
                <div class="card-body p-0">
                        @foreach($byItem as $itemName => $particulars)
                            @php
                                $filteredParticulars = $particulars->filter(function($item) {
                                    return !empty($item->particular) && $item->quantity > 0;
                                });
                                $itemTotal = $filteredParticulars->sum('budgeted_cost');
                            @endphp
                            @if($filteredParticulars->isNotEmpty())
                                <div class="border-bottom">
                                    <div class="p-3 bg-light">
                                        <h6 class="mb-0 fw-semibold text-dark">{{ $itemName }}</h6>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-sm mb-0">
                                            <thead class="table-light">
                                        <tr>
                                                    <th class="border-0">Particular</th>
                                                    <th class="border-0">Unit</th>
                                                    <th class="border-0 text-end">Quantity</th>
                                                    <th class="border-0 text-end">Unit Price</th>
                                                    <th class="border-0 text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                                @foreach($filteredParticulars as $particular)
                                            <tr>
                                                        <td class="fw-medium">{{ $particular->particular }}</td>
                                                        <td class="text-muted">{{ $particular->unit ?? '-' }}</td>
                                                        <td class="text-end">{{ number_format($particular->quantity, 2) }}</td>
                                                        <td class="text-end">{{ $particular->unit_price ? 'KES ' . number_format($particular->unit_price, 2) : '-' }}</td>
                                                        <td class="text-end fw-semibold">
                                                            KES {{ number_format($particular->budgeted_cost, 2) }}
                                                        </td>
                                            </tr>
                                        @endforeach
                                            </tbody>
                                            <tfoot class="table-light">
                                                <tr>
                                                    <td colspan="4" class="text-end fw-bold">Subtotal:</td>
                                                    <td class="text-end fw-bold {{ $color['text'] }}">KES {{ number_format($itemTotal, 2) }}</td>
                                        </tr>
                                            </tfoot>
                                </table>
                            </div>
                                </div>
                            @endif
                        @endforeach
                </div>
            </div>
        @endif
    @endforeach

                <!-- Action Buttons -->
        <div class="d-flex justify-content-center gap-3 mt-4 pt-4 border-top">
            @if(!$budget->quote)
                @if(isset($enquiry) && $enquiry)
                    <a href="{{ route('enquiries.quotes.create', ['enquiry' => $enquiry, 'project_budget_id' => $budget->id]) }}" class="btn btn-success">
                        <i class="bi bi-file-earmark-text me-2"></i>Create Quote from this Budget
                    </a>
                @elseif(isset($project) && $project)
                    <a href="{{ route('quotes.create', ['project' => $project, 'project_budget_id' => $budget->id]) }}" class="btn btn-success">
                        <i class="bi bi-file-earmark-text me-2"></i>Create Quote from this Budget
                    </a>
                @endif
            @else
                @if(isset($enquiry) && $enquiry)
                    <a href="{{ route('enquiries.quotes.show', ['enquiry' => $enquiry, 'quote' => $budget->quote->id]) }}" class="btn btn-info">
                        <i class="bi bi-file-earmark-text me-2"></i>View Associated Quote
                    </a>
                @elseif(isset($project) && $project)
                    <a href="{{ route('quotes.show', ['project' => $project, 'quote' => $budget->quote->id]) }}" class="btn btn-info">
                        <i class="bi bi-file-earmark-text me-2"></i>View Associated Quote
                    </a>
                @endif
    @endif
        </div>
    </div>
</div>

<style>
.card {
    transition: all 0.2s ease-in-out;
}

.table th {
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    vertical-align: middle;
    font-size: 0.875rem;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}
</style>
@endsection
