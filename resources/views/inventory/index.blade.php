@extends('layouts.master')

@section('title', 'Inventory Management')
@section('navbar-title', 'Inventory Information')

@section('content')
<style>
    h2 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #145DA0;
        margin-bottom: 1.5rem;
    }

    .table {
        font-size: 0.875rem;
        border-radius: 12px;
        overflow: hidden;
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        animation: slideUp 0.5s ease-out;
    }

    .table th {
        white-space: nowrap;
        position: relative;
        background-color: #0C2D48 !important;
        color: white;
        font-weight: 500;
        padding: 0.75rem 1rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .table th:hover {
        background-color: #072540 !important;
    }

    .table td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(46, 139, 192, 0.05);
    }

    .table-striped > tbody > tr:nth-of-type(odd) > * {
        --bs-table-accent-bg: rgba(0, 0, 0, 0.02);
        color: var(--bs-table-striped-color);
    }

    .btn, .btn-sm, .btn-xs {
        border-radius: 6px;
        transition: all 0.18s cubic-bezier(.4,0,.2,1);
        box-shadow: 0 2px 6px rgba(44,62,80,0.07);
    }

    .btn-xs {
        font-size: 0.72rem;
        padding: 0.21rem 0.6rem;
    }

    .btn-outline-secondary {
        color: #6c757d;
        border-color: #dee2e6;
    }

    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }

    .btn-outline-info {
        color: #0dcaf0;
        border-color: #0dcaf0;
    }

    .btn-outline-info:hover {
        background-color: #0dcaf0;
        color: white;
    }

    .modal-content {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        background-color: #f9fcff;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .modal-title {
        color: #145DA0;
        font-weight: 600;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .form-control, .form-select {
        border-radius: 8px;
        font-size: 0.85rem;
        padding: 0.375rem 0.75rem;
        transition: border-color 0.3s ease;
        height: 35px;
        min-height: 35px;
        width: 100%;
        max-width: 100%;
    }

    .form-control:focus, .form-select:focus {
        border-color: #2E8BC0;
        box-shadow: 0 0 0 0.2rem rgba(46, 139, 192, 0.25);
    }

    .form-label {
        font-size: 0.7rem;
        color: #6c757d; 
        font-weight: 500;
        margin-bottom: 0.3rem;
    }

    .invalid-feedback {
        font-size: 0.65rem;
    }

    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }

    .container-fluid {
        padding: 16px;
        background-color: #f9fcff;
        border-radius: 12px;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .pagination {
        margin-bottom: 0;
    }

    .pagination .page-link {
        color: #0C2D48;
        border-color: #dee2e6;
    }

    .badge {
        font-size: 0.7rem;
        padding: 0.35rem 0.65rem;
    }

    .stock-status {
        font-weight: 600;
    }

    .stock-low {
        color: #dc3545;
    }

    .stock-medium {
        color: #fd7e14;
    }

    .stock-high {
        color: #198754;
    }
</style>

<div class="container-fluid p-2">
    <div class="px-3 mx-10 mt-2 w-100">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center">
                <div class="me-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('inventory.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Inventory</li>
                        </ol>
                    </nav>
                    <h2 class="mb-0 fw-bold me-4" style="letter-spacing:0.01em;">
                        @if(isset($viewType) && $viewType === 'trashed')
                            Deleted Inventory Items
                        @else
                            Inventory Management
                        @endif
                    </h2>
                </div>
                
                <!-- Inventory Toggle Buttons -->
                <div class="d-flex align-items-center gap-2 ms-3" role="group">
                    <a href="{{ route('inventory.index') }}" 
                       class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                        <i class="bi bi-boxes"></i>
                        <span>All Items</span>
                    </a>
                    @hasanyrole('admin|pm|super-admin')
                    <span class="text-muted">|</span>
                    <a href="{{ route('inventory.trash') }}" 
                       class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1">
                        <i class="bi bi-trash"></i>
                        <span>Deleted</span>
                    </a>
                    @endhasanyrole
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <!-- Quick Actions -->
                <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#returnModal">
                    <i class="bi bi-arrow-return-left me-1"></i>Process Return
                </button>
                <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#defectiveItemModal">
                    <i class="bi bi-exclamation-triangle me-1"></i>Report Defective
                </button>
                
                <!-- Export/Import -->
                <div class="btn-group">
                    <a href="{{ route('inventory.export') }}" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-download me-1"></i>Export
                    </a>
                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="bi bi-upload me-1"></i>Import
                    </button>
                </div>
            </div>
        </div>
        
        <hr class="mb-4">
        
        <!-- Search and Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <form method="GET" action="{{ route('inventory.index') }}" class="d-flex gap-2 align-items-end">
                    <div class="flex-fill">
                        <label class="form-label">Search by SKU</label>
                        <input type="text" name="sku" class="form-control" placeholder="Enter SKU..." value="{{ request('sku') }}">
                    </div>
                    <div class="flex-fill">
                        <label class="form-label">Search by Item Name</label>
                        <input type="text" name="item_name" class="form-control" placeholder="Enter item name..." value="{{ request('item_name') }}">
                    </div>
                    <div class="flex-fill">
                        <label class="form-label">Search by Category</label>
                        <input type="text" name="category_name" class="form-control" placeholder="Enter category..." value="{{ request('category_name') }}">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                        </button>
                        @if(request()->hasAny(['sku', 'item_name', 'category_name']))
                            <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Inventory Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle shadow-sm">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Unit</th>
                        <th>Stock Status</th>
                        <th>In/Out/Returns</th>
                        <th>Supplier</th>
                        <th>Unit Price</th>
                        <th>Total Value</th>
                        <th>Order Date</th>
                        <th class="text-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>
                                <span class="badge bg-secondary">{{ $item->sku }}</span>
                            </td>
                            <td>
                                <strong>{{ $item->item_name }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $item->category->category_name ?? 'No Category' }}</span>
                            </td>
                            <td>{{ $item->unit_of_measure }}</td>
                            <td>
                                @php
                                    $stock = $item->stock_on_hand;
                                    $statusClass = $stock <= 10 ? 'stock-low' : ($stock <= 50 ? 'stock-medium' : 'stock-high');
                                    $statusText = $stock <= 10 ? 'Low Stock' : ($stock <= 50 ? 'Medium Stock' : 'Good Stock');
                                @endphp
                                <div class="stock-status {{ $statusClass }}">
                                    <strong>{{ $stock }}</strong>
                                    <small class="d-block">{{ $statusText }}</small>
                                </div>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <div>In: <span class="text-success">{{ $item->quantity_checked_in }}</span></div>
                                    <div>Out: <span class="text-warning">{{ $item->quantity_checked_out }}</span></div>
                                    <div>Returns: <span class="text-info">{{ $item->returns }}</span></div>
                                </small>
                            </td>
                            <td>{{ $item->supplier }}</td>
                            <td>
                                <strong>Ksh{{ number_format($item->unit_price, 2) }}</strong>
                            </td>
                            <td>
                                <strong class="text-success">Ksh{{ number_format($item->total_value, 2) }}</strong>
                            </td>
                            <td>
                                {{ $item->order_date ? \Carbon\Carbon::parse($item->order_date)->format('M d, Y') : '-' }}
                            </td>
                            <td class="actions">
                                @if(isset($viewType) && $viewType === 'trashed')
                                    <!-- Actions for trashed items -->
                                    <div class="btn-group">
                                        <form action="{{ route('inventory.restore', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-xs btn-outline-success" title="Restore" onclick="return confirm('Are you sure you want to restore this item?')">
                                                <i class="bi bi-arrow-clockwise"></i>
                                            </button>
                                        </form>
                                        @hasrole('super-admin')
                                        <form action="{{ route('inventory.forceDelete', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-outline-danger" title="Permanently Delete" onclick="return confirm('Are you sure you want to permanently delete this item? This action cannot be undone!')">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                        @endhasrole
                                    </div>
                                @else
                                    <!-- Actions for active items -->
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-xs btn-outline-info" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $item->id }}" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form method="POST" action="{{ route('inventory.softDelete', $item->id) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this item?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    <p>No inventory items found.</p>
                                    <a href="{{ route('inventory.newstock') }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-plus-circle me-1"></i>Add New Stock
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $items->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
<!-- Retur
n Modal -->
<div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="returnModalLabel">
                    <i class="bi bi-arrow-return-left me-2"></i>Process Return
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('inventory.returns.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="sku" class="form-label">SKU</label>
                            <select class="form-select" id="sku" name="sku" required>
                                <option value="">-- Select SKU --</option>
                                @foreach ($skus_returns as $sku => $itemName)
                                    <option value="{{ $sku }}">{{ $sku }} - {{ $itemName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="item_name" class="form-label">Item Name</label>
                            <input type="text" class="form-control" id="item_name" name="item_name" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required>
                        </div>
                        <div class="col-md-6">
                            <label for="return_date" class="form-label">Return Date</label>
                            <input type="date" class="form-control" id="return_date" name="return_date" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-12">
                            <label for="reason" class="form-label">Reason</label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Enter reason for return..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Process Return</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Defective Item Modal -->
<div class="modal fade" id="defectiveItemModal" tabindex="-1" aria-labelledby="defectiveItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="defectiveItemModalLabel">
                    <i class="bi bi-exclamation-triangle me-2"></i>Report Defective Item
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('inventory.defective_items.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="defective_sku" class="form-label">SKU</label>
                            <select class="form-select" id="defective_sku" name="sku" required>
                                <option value="">-- Select SKU --</option>
                                @foreach ($skus as $sku => $item_name)
                                    <option value="{{ $sku }}">{{ $sku }} - {{ $item_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="defective_quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="defective_quantity" name="quantity" required>
                        </div>
                        <div class="col-md-6">
                            <label for="date_reported" class="form-label">Date Reported</label>
                            <input type="date" class="form-control" id="date_reported" name="date_reported" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="defect_type" class="form-label">Defect Type</label>
                            <input type="text" class="form-control" id="defect_type" name="defect_type" placeholder="e.g., Damaged, Broken, etc." required>
                        </div>
                        <div class="col-md-6">
                            <label for="reported_by" class="form-label">Reported By</label>
                            <input type="text" class="form-control" id="reported_by" name="reported_by" value="{{ auth()->user()->name }}" required>
                        </div>
                        <div class="col-12">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Additional details about the defect..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Report Defective</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">
                    <i class="bi bi-upload me-2"></i>Import Inventory Data
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('inventory.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="importFile" class="form-label">Select Excel File</label>
                        <input type="file" class="form-control" id="importFile" name="file" accept=".xlsx,.xls" required>
                        <div class="form-text">Supported formats: .xlsx, .xls</div>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Note:</strong> Make sure your Excel file follows the correct format with columns: SKU, Item Name, Category, Unit, Quantity, Supplier, Unit Price.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Import Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Item Modals -->
@foreach ($items as $item)
<div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1" aria-labelledby="editItemModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editItemModalLabel{{ $item->id }}">
                    <i class="bi bi-pencil-square me-2"></i>Edit Inventory Item
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('inventory.update', $item->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="sku{{ $item->id }}" class="form-label">SKU</label>
                            <input type="text" class="form-control" id="sku{{ $item->id }}" name="sku" value="{{ $item->sku }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="item_name{{ $item->id }}" class="form-label">Item Name</label>
                            <input type="text" class="form-control" id="item_name{{ $item->id }}" name="item_name" value="{{ $item->item_name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="category{{ $item->id }}" class="form-label">Category</label>
                            <input type="text" class="form-control" id="category{{ $item->id }}" value="{{ $item->category->category_name ?? 'No Category' }}" readonly>
                            <input type="hidden" name="category_id" value="{{ $item->category_id }}">
                        </div>
                        <div class="col-md-6">
                            <label for="unit_of_measure{{ $item->id }}" class="form-label">Unit of Measure</label>
                            <input type="text" class="form-control" id="unit_of_measure{{ $item->id }}" name="unit_of_measure" value="{{ $item->unit_of_measure }}">
                        </div>
                        <div class="col-md-6">
                            <label for="supplier{{ $item->id }}" class="form-label">Supplier</label>
                            <input type="text" class="form-control" id="supplier{{ $item->id }}" name="supplier" value="{{ $item->supplier }}">
                        </div>
                        <div class="col-md-6">
                            <label for="unit_price{{ $item->id }}" class="form-label">Unit Price</label>
                            <input type="number" step="0.01" class="form-control" id="unit_price{{ $item->id }}" name="unit_price" value="{{ $item->unit_price }}">
                        </div>
                        <div class="col-md-6">
                            <label for="order_date{{ $item->id }}" class="form-label">Order Date</label>
                            <input type="date" class="form-control" id="order_date{{ $item->id }}" name="order_date" value="{{ $item->order_date }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Current Stock</label>
                            <input type="text" class="form-control" value="{{ $item->stock_on_hand }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Item</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle SKU selection for returns
    document.getElementById('sku').addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const itemNameInput = document.getElementById('item_name');

        if (selectedOption && selectedOption.value) {
            const itemName = selectedOption.text.split(' - ')[1];
            itemNameInput.value = itemName || '';
        } else {
            itemNameInput.value = '';
        }
    });
});
</script>

@endsection