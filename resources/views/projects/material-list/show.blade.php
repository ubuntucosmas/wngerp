@extends('layouts.master')

@push('styles')
    <link href="{{ asset('css/material-list.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4cc9f0;
            --light-bg: #f8f9fa;
            --card-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.05);
            --card-hover-shadow: 0 0.75rem 2rem rgba(0, 0, 0, 0.1);
        }
        
        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f5f7fb;
            color: #333;
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 4px 20px rgba(67, 97, 238, 0.15);
        }
        
        .page-title {
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 0.5rem;
        }
        
        .breadcrumb {
            background: transparent;
            padding: 0.5rem 0;
        }
        
        .breadcrumb-item a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.2s ease;
        }
        
        .breadcrumb-item a:hover {
            color: white;
            text-decoration: underline;
        }
        
        .breadcrumb-item.active {
            color: rgba(255,255,255,0.9);
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
            overflow: hidden;
            background: white;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-hover-shadow);
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #6c757d;
            background-color: #f8fafc;
            border-bottom: 2px solid #e9ecef;
        }
        
        .table td {
            vertical-align: middle;
            padding: 1rem 1.25rem;
            border-color: #f1f3f9;
        }
        
        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
            border-radius: 6px;
        }
        
        .btn {
            font-weight: 500;
            border-radius: 8px;
            padding: 0.5rem 1.25rem;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn i {
            font-size: 1.1em;
        }
        
        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.25);
        }
        
        .btn-outline-secondary {
            border-color: #dee2e6;
            color: #495057;
        }
        
        .btn-outline-secondary:hover {
            background: #f8f9fa;
            border-color: #ced4da;
            color: #212529;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        /* Print styles */
        @media print {
            .no-print {
                display: none !important;
            }
            
            .card {
                box-shadow: none;
                border: 1px solid #e9ecef;
            }
            
            .page-header {
                padding: 1rem 0;
                background: #f8f9fa !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                color: #000 !important;
            }
            
            .table th {
                background-color: #f8f9fa !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
@endpush

@section('title', 'Material List - ' . $project->name)

@section('content')
<div class="page-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div class="mb-3 mb-md-0">
                <h1 class="page-title mb-2">
                    <i class="bi bi-clipboard2-data me-2"></i>
                    Material List Details
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                        
                        <li class="breadcrumb-item active" aria-current="page">Material List</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('projects.material-list.download', [$project, $materialList]) }}" class="btn btn-light me-2 no-print" data-bs-toggle="tooltip" title="Download as PDF">
                    <i class="bi bi-file-earmark-pdf"></i> <span class="d-none d-md-inline">PDF</span>
                </a>
                <a href="{{ route('projects.material-list.print', [$project, $materialList]) }}" class="btn btn-light me-2 no-print" data-bs-toggle="tooltip" title="Print PDF" target="_blank">
                    <i class="bi bi-printer"></i> <span class="d-none d-md-inline">Print</span>
                </a>
                <a href="{{ route('projects.material-list.edit', [$project, $materialList]) }}" class="btn btn-light me-2 no-print" data-bs-toggle="tooltip" title="Edit material list">
                    <i class="bi bi-pencil"></i> <span class="d-none d-md-inline">Edit</span>
                </a>
                <a href="{{ route('projects.material-list.index', $project) }}" class="btn btn-light no-print" data-bs-toggle="tooltip" title="Back to material lists">
                    <i class="bi bi-arrow-left"></i> <span class="d-none d-md-inline">Back</span>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">

    <!-- Project Information Card -->
    <div class="card mb-4 animate-fade-in" style="animation-delay: 0.1s">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-info-circle me-2"></i>
                Project Information
            </h5>
            <span class="badge bg-primary">
                {{ $materialList->status ?? 'Active' }}
            </span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex align-items-start mb-3">
                        <div class="me-3 text-primary">
                            <i class="bi bi-building"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 text-muted small">Project Name</h6>
                            <p class="mb-2 fw-semibold">{{ $project->name }}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-3">
                        <div class="me-3 text-primary">
                            <i class="bi bi-person"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 text-muted small">Client</h6>
                            <p class="mb-2 fw-semibold">{{ $project->client_name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start">
                        <div class="me-3 text-primary">
                            <i class="bi bi-calendar-range"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 text-muted small">Project Duration</h6>
                            <p class="mb-0 fw-semibold">
                                {{ \Carbon\Carbon::parse($materialList->start_date)->format('M d, Y') }} - 
                                {{ \Carbon\Carbon::parse($materialList->end_date)->format('M d, Y') }}
                                <small class="text-muted ms-2">
                                    ({{ \Carbon\Carbon::parse($materialList->start_date)->diffInDays(\Carbon\Carbon::parse($materialList->end_date)) }} days)
                                </small>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-start mb-3">
                        <div class="me-3 text-primary">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 text-muted small">Approved By</h6>
                            <p class="mb-2 fw-semibold">{{ $materialList->approved_by ?? 'Pending Approval' }}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-3">
                        <div class="me-3 text-primary">
                            <i class="bi bi-diagram-3"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 text-muted small">Departments</h6>
                            <div class="d-flex flex-wrap gap-1">
                                @if($materialList->approved_departments)
                                    @foreach(explode(',', $materialList->approved_departments) as $dept)
                                        <span class="bg-light text-dark">{{ $dept }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">No departments assigned</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start">
                        <div class="me-3 text-primary">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 text-muted small">Last Updated</h6>
                            <p class="mb-0 fw-semibold">
                                {{ $materialList->updated_at->diffForHumans() }}
                                <small class="text-muted ms-2">
                                    ({{ $materialList->updated_at->format('M d, Y H:i') }})
                                </small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Production Materials Section -->
    <div class="card mb-4 animate-fade-in" style="animation-delay: 0.2s">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-box-seam me-2 text-primary"></i>
                Production Materials
            </h5>
            <div class="d-flex align-items-center">
                <span class="badge bg-primary-soft text-primary me-2">
                    {{ $materialList->productionItems->count() }} {{ Str::plural('item', $materialList->productionItems->count()) }}
                </span>
                <button class="btn btn-sm btn-link text-muted no-print" type="button" data-bs-toggle="collapse" data-bs-target="#productionMaterialsCollapse" aria-expanded="true" aria-controls="productionMaterialsCollapse">
                    <i class="bi bi-chevron-down"></i>
                </button>
            </div>
        </div>
        <div class="collapse show" id="productionMaterialsCollapse">
            <div class="card-body p-0">
                @forelse ($materialList->productionItems as $item)
                    <div class="production-item border-bottom">
                        <div class="p-4 bg-light">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="bi bi-box-seam fs-4 text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $item->item_name }}</h6>
                                    @if($item->description)
                                        <p class="text-muted small mb-0 mt-1">{{ $item->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        @if($item->particulars->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-nowrap ps-4">#</th>
                                            <th class="text-nowrap">Particulars</th>
                                            <th class="text-nowrap text-center">Unit of Measure</th>
                                            <th class="text-nowrap text-end pe-4">Quantity</th>
                                            <th class="text-nowrap">Comments</th>
                                            <th class="text-nowrap">Design Ref</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($item->particulars as $index => $particular)
                                            <tr class="border-top">
                                                <td class="ps-4 fw-bold text-muted">{{ $index + 1 }}</td>
                                                <td class="fw-semibold">{{ $particular->particular }}</td>
                                                <td class="text-center">
                                                    <span class="bg-primary-soft text-primary px-3 py-2">
                                                        {{ $particular->unit ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td class="text-end fw-bold pe-4">
                                                    {{ number_format($particular->quantity, 2) }}
                                                </td>
                                                <td>
                                                    @if($particular->comment)
                                                        <span class="d-inline-block text-truncate" style="max-width: 200px;" data-bs-toggle="tooltip" title="{{ $particular->comment }}">
                                                            {{ $particular->comment }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($particular->design_reference)
                                                        <a href="#" class="text-decoration-none" data-bs-toggle="tooltip" title="View Design">
                                                            {{ $particular->design_reference }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center p-5">
                                <div class="text-center">
                                    <i class="bi bi-inbox fs-1 text-muted opacity-50"></i>
                                    <h6 class="mt-3 mb-1">No particulars found</h6>
                                    <p class="text-muted small mb-0">Add particulars to this production item</p>
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center p-5">
                        <div class="text-center">
                            <i class="bi bi-inbox fs-1 text-muted opacity-50"></i>
                            <h6 class="mt-3 mb-1">No production materials</h6>
                            <p class="text-muted small mb-0">Add production materials to get started</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Materials for Hire Section -->
    <div class="card mb-4 animate-fade-in" style="animation-delay: 0.3s">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-tools me-2 text-warning"></i>
                Materials for Hire
            </h5>
            <div class="d-flex align-items-center">
                <span class="badge bg-warning-soft text-warning me-2">
                    {{ $materialList->materialsHire->count() }} {{ Str::plural('item', $materialList->materialsHire->count()) }}
                </span>
                <button class="btn btn-sm btn-link text-muted no-print" type="button" data-bs-toggle="collapse" data-bs-target="#materialsHireCollapse" aria-expanded="true" aria-controls="materialsHireCollapse">
                    <i class="bi bi-chevron-down"></i>
                </button>
            </div>
        </div>
        <div class="collapse show" id="materialsHireCollapse">
            <div class="card-body p-0">
                @if($materialList->materialsHire && $materialList->materialsHire->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-nowrap ps-4">#</th>
                                    <th class="text-nowrap">Item Details</th>
                                    <th class="text-nowrap text-center">Unit of Measure</th>
                                    <th class="text-nowrap text-end pe-4">Quantity</th>
                                    <th class="text-nowrap">Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($materialList->materialsHire as $index => $hire)
                                    <tr class="border-top">
                                        <td class="ps-4 fw-bold text-muted">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="icon-shape icon-sm rounded-3 bg-warning-soft text-warning me-3">
                                                    <i class="bi bi-tools"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-semibold">{{ $hire->item_name }}</h6>
                                                    @if($hire->particular && $hire->particular !== $hire->item_name)
                                                        <small class="text-muted">{{ $hire->particular }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="bg-primary text-warning px-3 py-2">
                                                {{ $hire->unit ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="text-end fw-bold pe-4">
                                            {{ number_format($hire->quantity, 2) }}
                                        </td>
                                        <td>
                                            @if($hire->comment)
                                                <span class="d-inline-block text-truncate" style="max-width: 200px;" data-bs-toggle="tooltip" title="{{ $hire->comment }}">
                                                    {{ $hire->comment }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center p-5">
                        <div class="text-center">
                            <i class="bi bi-inbox fs-1 text-muted opacity-50"></i>
                            <h6 class="mt-3 mb-1">No materials for hire</h6>
                            <p class="text-muted small mb-0">Add materials for hire to get started</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Labour Categories Section -->
    <div class="animate-fade-in" style="animation-delay: 0.4s">
        <h4 class="mb-4 d-flex align-items-center">
            <i class="bi bi-people-fill me-3 text-primary"></i>
            Labour Categories
        </h4>
        
        <div class="row g-4">
            @php
                $labourCategories = [
                    'Workshop labour' => [
                        'icon' => 'bi-tools',
                        'color' => 'primary',
                        'bg' => 'bg-primary-soft',
                        'description' => 'Workshop labor and fabrication work'
                    ],
                    'Site' => [
                        'icon' => 'bi-building',
                        'color' => 'success',
                        'bg' => 'bg-success-soft',
                        'description' => 'On-site installation and construction'
                    ],
                    'Set down' => [
                        'icon' => 'bi-box-arrow-down',
                        'color' => 'warning',
                        'bg' => 'bg-warning-soft',
                        'description' => 'Material setup and arrangement'
                    ],
                    'Logistics' => [
                        'icon' => 'bi-truck',
                        'color' => 'info',
                        'bg' => 'bg-info-soft',
                        'description' => 'Transportation and material handling'
                    ]
                ];
            @endphp

            @foreach ($labourCategories as $key => $categoryData)
                @php
                    $items = $materialList->labourItems->where('category', $key);
                    $itemCount = $items->count();
                    $categoryName = ucwords(str_replace('_', ' ', $key));
                    $collapseId = 'collapse' . str_replace(' ', '', $categoryName);
                @endphp
                
                <div class="col-12">
                    <div class="card h-100">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center p-0">
                            <button class="btn btn-link text-decoration-none text-start w-100 p-4" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="true" aria-controls="{{ $collapseId }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-shape icon-lg rounded-3 {{ $categoryData['bg'] }} text-{{ $categoryData['color'] }} me-3">
                                        <i class="bi {{ $categoryData['icon'] }} fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h5 class="mb-0 fw-bold">{{ $categoryName }}</h5>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-{{ $categoryData['color'] }}-subtle text-{{ $categoryData['color'] }} me-3">
                                                    {{ $itemCount }} {{ Str::plural('item', $itemCount) }}
                                                </span>
                                                <i class="bi bi-chevron-down text-muted"></i>
                                            </div>
                                        </div>
                                        <p class="text-muted mb-0 small mt-1">{{ $categoryData['description'] }}</p>
                                    </div>
                                </div>
                            </button>
                        </div>
                        
                        <div id="{{ $collapseId }}" class="collapse show" data-bs-parent=".labour-categories">
                            @if($itemCount > 0)
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="text-uppercase small fw-bold text-muted border-0 ps-4">#</th>
                                                    <th class="text-uppercase small fw-bold text-muted border-0">Item Details</th>
                                                    <th class="text-uppercase small fw-bold text-muted border-0 text-center">Unit of Measure</th>
                                                    <th class="text-uppercase small fw-bold text-muted border-0 text-end pe-4">Quantity</th>
                                                    <th class="text-uppercase small fw-bold text-muted border-0">Notes</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($items as $index => $item)
                                                    <tr class="border-top">
                                                        <td class="ps-4 fw-bold text-muted">{{ $index + 1 }}</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="icon-shape icon-sm rounded-3 {{ $categoryData['bg'] }} text-{{ $categoryData['color'] }} me-3">
                                                                    <i class="bi {{ $categoryData['icon'] }}"></i>
                                                                </div>
                                                                <div>
                                                                    <h6 class="mb-0 fw-semibold">{{ $item->item_name ?? $item->particular }}</h6>
                                                                    @if($item->particular && $item->particular !== ($item->item_name ?? ''))
                                                                        <small class="text-muted">{{ $item->particular }}</small>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="bg-{{ $categoryData['color'] }}-subtle text-{{ $categoryData['color'] }} px-3 py-2">
                                                                {{ $item->unit ?? 'N/A' }}
                                                            </span>
                                                        </td>
                                                        <td class="text-end fw-bold pe-4">
                                                            {{ number_format($item->quantity, 2) }}
                                                            @if(isset($item->rate) && $item->rate > 0)
                                                                <div class="text-muted small">
                                                                    @ {{ number_format($item->rate, 2) }} each
                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($item->comment)
                                                                <div class="d-flex align-items-center">
                                                                    <span class="d-inline-block text-truncate me-2" style="max-width: 200px;" data-bs-toggle="tooltip" title="{{ $item->comment }}">
                                                                        {{ $item->comment }}
                                                                    </span>
                                                                    @if(strlen($item->comment) > 30)
                                                                        <button class="btn btn-sm btn-link p-0 text-muted" data-bs-toggle="tooltip" title="View full note">
                                                                            <i class="bi bi-arrows-angle-expand"></i>
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                @if($items->sum('rate') > 0)
                                    <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                                        <span class="text-muted small">Total estimated cost</span>
                                        <strong class="text-{{ $categoryData['color'] }}">
                                            ${{ number_format($items->sum(function($item) {
                                                return ($item->quantity ?? 0) * ($item->rate ?? 0);
                                            }), 2) }}
                                        </strong>
                                    </div>
                                @endif
                            @else
                                <div class="card-body text-center p-5">
                                    <div class="text-center">
                                        <i class="bi bi-inbox fs-1 text-muted opacity-25"></i>
                                        <h6 class="mt-3 mb-1">No {{ strtolower($categoryName) }} items</h6>
                                        <p class="text-muted small mb-0">Add items to this category to get started</p>
                                        <a href="{{ route('projects.material-list.edit', [$project, $materialList]) }}" class="btn btn-sm btn-outline-{{ $categoryData['color'] }} mt-3">
                                            <i class="bi bi-plus-lg me-1"></i> Add Items
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
   
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                trigger: 'hover',
                boundary: 'window',
                customClass: 'custom-tooltip',
                delay: { show: 300, hide: 100 }
            });
        });

        // Add animation to sections when they come into view
        const animateOnScroll = function() {
            const elements = document.querySelectorAll('.card, .production-item, .animate-on-scroll');
            elements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;
                
                if (elementTop < windowHeight - 100) {
                    element.classList.add('animate-fade-in');
                }
            });
        };

        // Run once on page load
        animateOnScroll();
        
        // Run on scroll with throttling
        let isScrolling;
        window.addEventListener('scroll', function() {
            window.clearTimeout(isScrolling);
            isScrolling = setTimeout(function() {
                animateOnScroll();
            }, 66); // ~15fps
        }, false);

        // Add print functionality
        const printButton = document.querySelector('button[onclick="window.print()"]');
        if (printButton) {
            printButton.addEventListener('click', function() {
                // Expand all collapsed sections before printing
                const collapses = document.querySelectorAll('.collapse');
                collapses.forEach(collapse => {
                    new bootstrap.Collapse(collapse, { toggle: true });
                });
                
                // Small delay to ensure all content is visible before printing
                setTimeout(() => {
                    window.print();
                }, 300);
            });
        }
        
        // Add animation to table rows on hover
        document.querySelectorAll('table tbody tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(5px)';
                this.style.transition = 'transform 0.2s ease';
            });
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });
        
        // Handle expandable notes
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(button => {
            button.addEventListener('click', function(e) {
                if (this.getAttribute('title')) {
                    const tooltip = bootstrap.Tooltip.getInstance(this);
                    if (tooltip) {
                        tooltip.hide();
                    }
                    const fullText = this.getAttribute('title');
                    this.removeAttribute('title');
                    this.setAttribute('data-bs-original-title', fullText);
                    this.parentElement.innerHTML = `
                        <div class="position-relative">
                            <div class="p-2 rounded bg-light border">
                                <button type="button" class="btn-close position-absolute top-0 end-0 m-1" aria-label="Close"></button>
                                <p class="mb-0">${fullText}</p>
                            </div>
                        </div>
                    `;
                    
                    // Add event to close button
                    const closeBtn = this.parentElement.querySelector('.btn-close');
                    if (closeBtn) {
                        closeBtn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            const parent = this.closest('td');
                            parent.innerHTML = `
                                <div class="d-flex align-items-center">
                                    <span class="d-inline-block text-truncate me-2" style="max-width: 200px;" data-bs-toggle="tooltip" title="${fullText}">
                                        ${fullText.length > 30 ? fullText.substring(0, 30) + '...' : fullText}
                                    </span>
                                    <button class="btn btn-sm btn-link p-0 text-muted" data-bs-toggle="tooltip" title="View full note">
                                        <i class="bi bi-arrows-angle-expand"></i>
                                    </button>
                                </div>
                            `;
                            // Reinitialize tooltip
                            const newButton = parent.querySelector('[data-bs-toggle="tooltip"]');
                            if (newButton) {
                                new bootstrap.Tooltip(newButton);
                            }
                        });
                    }
                }
            });
        });

        // Add tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush

@endsection
