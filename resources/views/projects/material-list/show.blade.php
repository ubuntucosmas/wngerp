@extends('layouts.master')

@section('title', 'Material List Details')

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
                                <li class="breadcrumb-item"><a href="{{ route('enquiries.material-list.index', $enquiry) }}" class="text-decoration-none">Material Lists</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Details</li>
                    @else
                                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}" class="text-decoration-none">Projects</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}" class="text-decoration-none">{{ $project->name }}</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('projects.material-list.index', $project) }}" class="text-decoration-none">Material Lists</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Details</li>
                    @endif
                </ol>
            </nav>
                    <h4 class="mb-0 fw-bold text-dark">Material List #{{ $materialList->id }}</h4>
                    <p class="text-muted small mb-0">{{ $materialList->date_range }}</p>
        </div>
                <div class="d-flex gap-2">
                    <a href="{{ (isset($enquiry) && is_object($enquiry) && isset($enquiry->id)) ? route('enquiries.material-list.index', $enquiry) : route('projects.material-list.index', $project) }}" 
                       class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Back
                    </a>
                    <a href="{{ isset($enquiry) ? route('enquiries.material-list.edit', [$enquiry, $materialList]) : route('projects.material-list.edit', [$project, $materialList]) }}" 
                       class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-download me-1"></i>Export
                        </button>
                        <ul class="dropdown-menu">
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
                                <span class="fw-medium">{{ isset($enquiry) ? $enquiry->project_name : $project->name }}</span>
                        </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span><i class="bi bi-person me-1"></i>Client:</span>
                                <span class="fw-medium">{{ isset($enquiry) ? $enquiry->client_name : $project->client_name }}</span>
                        </div>
                            <div class="d-flex justify-content-between">
                                <span><i class="bi bi-geo-alt me-1"></i>Venue:</span>
                                <span class="fw-medium">{{ isset($enquiry) ? $enquiry->venue : $project->venue }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                        <h6 class="fw-semibold text-dark mb-2">Material List Details</h6>
                        <div class="small text-muted">
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
                                <span class="fw-medium">{{ $materialList->created_at->format('M d, Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- Material List Sections -->
        <div class="row g-4">
            <!-- Production Items -->
            @php
                $nonEmptyProductionItems = $materialList->productionItems->filter(function($item) {
                    return $item->particulars && $item->particulars->filter(function($p) {
                        return !empty($p->particular) && $p->quantity > 0;
                    })->isNotEmpty();
                });
            @endphp
            @if($nonEmptyProductionItems->count() > 0)
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary bg-opacity-10 border-0 py-3">
                            <h6 class="mb-0 fw-semibold text-primary">
                                <i class="bi bi-box-seam me-2"></i>Materials - Production
                            </h6>
        </div>
            <div class="card-body p-0">
                            @foreach ($nonEmptyProductionItems as $item)
                                @php
                                    $filteredParticulars = $item->particulars->filter(function($p) {
                                        return !empty($p->particular) && $p->quantity > 0;
                                    });
                                @endphp
                                @if($filteredParticulars->isNotEmpty())
                                    <div class="border-bottom">
                                        <div class="p-3 bg-light">
                                            <h6 class="mb-0 fw-semibold text-dark">{{ $item->item_name }}</h6>
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
                                                        <th class="border-0">Comment</th>
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
                                                                {{ $particular->quantity && $particular->unit_price ? 'KES ' . number_format($particular->quantity * $particular->unit_price, 2) : '-' }}
                                                            </td>
                                                            <td class="text-muted small">{{ $particular->comment ?? '-' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                            </div>
            @endif

            <!-- Materials for Hire -->
            @php
                $nonEmptyMaterialsHire = $materialList->materialsHire->filter(function($item) {
                    return (!empty($item->particular) || !empty($item->item_name)) && $item->quantity > 0;
                });
            @endphp
            @if($nonEmptyMaterialsHire->count() > 0)
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-success bg-opacity-10 border-0 py-3">
                            <h6 class="mb-0 fw-semibold text-success">
                                <i class="bi bi-tools me-2"></i>Materials for Hire
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0">Particular</th>
                                            <th class="border-0">Unit</th>
                                            <th class="border-0 text-end">Quantity</th>
                                            <th class="border-0 text-end">Unit Price</th>
                                            <th class="border-0 text-end">Total</th>
                                            <th class="border-0">Comment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($nonEmptyMaterialsHire as $item)
                                            <tr>
                                                <td class="fw-medium">{{ $item->particular }}</td>
                                                <td class="text-muted">{{ $item->unit ?? '-' }}</td>
                                                <td class="text-end">{{ number_format($item->quantity, 2) }}</td>
                                                <td class="text-end">{{ $item->unit_price ? 'KES ' . number_format($item->unit_price, 2) : '-' }}</td>
                                                <td class="text-end fw-semibold">
                                                    {{ $item->quantity && $item->unit_price ? 'KES ' . number_format($item->quantity * $item->unit_price, 2) : '-' }}
                                                </td>
                                                <td class="text-muted small">{{ $item->comment ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        </div>
                    </div>
                @endif

            <!-- Labour Items -->
            @php
                $labourCategories = [
                    'Workshop labour' => [
                        'name' => 'Workshop Labour',
                        'color' => 'primary',
                        'bg' => 'bg-primary-soft',
                        'description' => 'Workshop labor and fabrication work'
                    ],
                    'Site' => [
                        'name' => 'Site',
                        'color' => 'success',
                        'bg' => 'bg-success-soft',
                        'description' => 'On-site installation and construction'
                    ],
                    'Set down' => [
                        'name' => 'Set Down',
                        'color' => 'warning',
                        'bg' => 'bg-warning-soft',
                        'description' => 'Material setup and arrangement'
                    ],
                    'Logistics' => [
                        'name' => 'Logistics',
                        'color' => 'info',
                        'bg' => 'bg-info-soft',
                        'description' => 'Transportation and material handling'
                    ],
                    'Outsourced' => [
                        'name' => 'Outsourced',
                        'color' => 'danger',
                        'bg' => 'bg-danger-soft',
                        'description' => 'External services and subcontractors'
                    ]
                ];
            @endphp
            @foreach ($labourCategories as $key => $categoryData)
                @php
                    $items = $materialList->labourItems->where('category', $key)->filter(function($item) {
                        return (!empty($item->particular) || !empty($item->item_name)) && $item->quantity > 0;
                    });
                    $itemCount = $items->count();
                @endphp
                @if($itemCount > 0)
                <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-{{ $categoryData['color'] }} bg-opacity-10 border-0 py-3">
                                <h6 class="mb-0 fw-semibold text-{{ $categoryData['color'] }}">
                                    <i class="bi bi-people me-2"></i>{{ $categoryData['name'] }}
                                </h6>
                        </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="border-0">Particular</th>
                                                <th class="border-0">Unit</th>
                                                <th class="border-0 text-end">Quantity</th>
                                                <th class="border-0 text-end">Unit Price</th>
                                                <th class="border-0 text-end">Total</th>
                                                <th class="border-0">Comment</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($items as $item)
                                                <tr>
                                                    <td class="fw-medium">{{ $item->particular }}</td>
                                                    <td class="text-muted">{{ $item->unit ?? '-' }}</td>
                                                    <td class="text-end">{{ number_format($item->quantity, 2) }}</td>
                                                    <td class="text-end">{{ $item->unit_price ? 'KES ' . number_format($item->unit_price, 2) : '-' }}</td>
                                                    <td class="text-end fw-semibold">
                                                        {{ $item->quantity && $item->unit_price ? 'KES ' . number_format($item->quantity * $item->unit_price, 2) : '-' }}
                                                        </td>
                                                    <td class="text-muted small">{{ $item->comment ?? '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-center gap-3 mt-4 pt-4 border-top">
            <a href="{{ isset($enquiry) ? route('enquiries.budget.create', ['enquiry' => $enquiry, 'material_list_id' => $materialList->id]) : route('budget.create', ['project' => $project, 'material_list_id' => $materialList->id]) }}" 
               class="btn btn-success">
                <i class="bi bi-calculator me-2"></i>Create Budget from this List
            </a>
            @if(auth()->user()->hasAnyRole(['pm', 'po', 'super-admin']))
            <form action="{{ isset($enquiry) ? route('enquiries.material-list.destroy', [$enquiry, $materialList]) : route('projects.material-list.destroy', [$project, $materialList]) }}" 
                  method="POST" 
                  class="d-inline delete-form"
                  onsubmit="return confirm('Are you sure you want to delete this material list? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash me-2"></i>Delete Material List
                </button>
            </form>
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

.delete-form {
    margin: 0;
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
