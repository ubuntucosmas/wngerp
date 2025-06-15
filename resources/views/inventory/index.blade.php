@extends('layouts.master')

@section('title', 'Inventory')
@section('navbar-title', 'Inventory')

@section('content')


        <div class="container mt-4">
            <div class="card bg-transparent shadow-sm rounded-4 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="fw-normal text-black">INVENTORY</h2>
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#returnModal">
                        Process Return
                    </button>
                    <button class="btn btn-outline-warning text-black" data-bs-toggle="modal" data-bs-target="#defectiveItemModal">
                        Report Defective Item
                    </button>

                    <!-- Trash Bin Trigger Button -->

                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#trashModal">
                        🗑 View Trash Bin
                    </button>

                    <div>
                        <!-- Export Button -->
                        <a href="{{ route('inventory.export') }}" class="btn btn-outline-success btn-sm  px-4">
                            <i class="bi bi-download"></i> Export to Excel
                        </a>

                        <!-- Import Button -->
                        <button type="button" class="btn btn-outline-primary btn-sm  px-4" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="bi bi-upload"></i> Import from Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>


       <!-- Trash Bin Modal-->
        <div class="modal fade" id="trashModal" tabindex="-1" aria-labelledby="trashModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable mt-3"> <!-- mt-3 moves it down slightly -->
            <div class="modal-content border-danger shadow-lg" style="font-size: 0.85rem;">
            
            <!-- Header -->
            <div class="modal-header bg-white text-dark py-2">
                <h6 class="modal-title" id="trashModalLabel">
                🗑️ <strong class="text-danger">Trash Bin - Deleted Inventory</strong>
                </h6>
                <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="modal-body p-2 bg-light">
                @if($deletedItems->count())
                <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped align-middle mb-0">
                    <thead class="table-dark small">
                    <tr class="text-center">
                        <th>SKU</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Unit</th>
                        <th>SOH</th>
                        <th>In</th>
                        <th>Out</th>
                        <th>Returns</th>
                        <th>Supplier</th>
                        <th>Unit Price</th>
                        <th>Total Value</th>
                        <th>Order Date</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($deletedItems as $item)
                    <tr class="text-center">
                        <td>{{ $item->sku }}</td>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->category->category_name ?? 'N/A' }}</td>
                        <td>{{ $item->unit_of_measure }}</td>
                        <td>{{ $item->stock_on_hand }}</td>
                        <td>{{ $item->quantity_checked_in }}</td>
                        <td>{{ $item->quantity_checked_out }}</td>
                        <td>{{ $item->returns }}</td>
                        <td>{{ $item->supplier }}</td>
                        <td>Ksh{{ number_format($item->unit_price, 2) }}</td>
                        <td>Ksh{{ number_format($item->total_value, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->order_date)->format('d M Y') }}</td>
                        <td class="text-nowrap">
                        <form action="{{ route('inventory.restore', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-success btn-sm px-2 py-0" title="Restore">🔄Restore</button>
                        </form>
                        <form action="{{ route('inventory.forceDelete', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            @role('admin|super-admin')
                            @if(auth()->user()->level >= 4)
                            <button type="submit" class="btn btn-danger btn-sm px-2 py-0" title="Delete Forever"
                            onclick="return confirm('Permanently delete this item?')">🗑Permanently Delete</button>
                            @endif
                            @endrole
                        </form>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-2">
                {{ $deletedItems->links() }}
                </div>
                @else
                <div class="text-center text-muted py-4">🧺 Trash bin is empty.</div>
                @endif
            </div>

            <!-- Footer -->
            <div class="modal-footer py-1 bg-white">
                <button type="button" class="btn btn-outline-dark btn-sm" data-bs-dismiss="modal">Close</button>
            </div>

            </div>
        </div>
        </div>

        <!-- Import Modal -->
        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Import Inventory Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('inventory.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <label for="importFile" class="form-label">Select Excel File</label>
                            <input type="file" class="form-control" id="importFile" name="file" accept=".xlsx, .xls" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-outline-primary">Import</button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

                <!-- Return Modal -->
        <div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true" >
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="returnModalLabel">Process a Return</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('inventory.returns.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <!-- SKU Dropdown -->
                            <div class="mb-3">
                                <label for="sku" class="form-label">SKU</label>
                                <select class="form-select form-select-sm" id="sku" name="sku" required>
                                    <option value="">-- Select SKU --</option>
                                    @foreach ($skus_returns as $sku => $itemName)
                                        <option value="{{ $sku }}">{{ $sku }} - {{ $itemName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Item Name (Read-Only, Auto-Filled) -->
                            <div class="mb-3">
                                <label for="item_name" class="form-label">Item Name</label>
                                <input type="text" class="form-control" id="item_name" name="item_name" style="text-transform: capitalize;" readonly>
                            </div>
                            <!-- Quantity -->
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" required>
                            </div>
                            <!-- Reason -->
                            <div class="mb-3">
                                <label for="reason" class="form-label">Reason</label>
                                <textarea class="form-control" id="reason" name="reason" rows="3" style="text-transform: capitalize;"></textarea>
                            </div>
                            <!-- Return Date -->
                            <div class="mb-3">
                                <label for="return_date" class="form-label">Return Date</label>
                                <input type="date" class="form-control" id="return_date" name="return_date" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-outline-primary">Process Return</button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

                                <!-- defective items modal -->

        <div class="modal fade" id="defectiveItemModal" tabindex="-1" aria-labelledby="defectiveItemModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="defectiveItemModalLabel">Log Defective Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('inventory.defective_items.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="sku" class="form-label">SKU</label>
                                <select class="form-select" id="sku" name="sku" required>
                                    <option value="">-- Select SKU --</option>
                                    @foreach ($skus as $sku => $item_name)
                                        <option value="{{ $sku }}">{{ $sku }} - {{ $item_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" required>
                            </div>
                            <div class="mb-3">
                                <label for="defect_type" class="form-label">Defect Type</label>
                                <input type="text" class="form-control" id="defect_type" name="defect_type" style="text-transform: capitalize;" required>
                            </div>
                            <div class="mb-3">
                                <label for="reported_by" class="form-label">Reported By</label>
                                <input type="text" class="form-control" id="reported_by" name="reported_by" style="text-transform: capitalize;" required>
                            </div>
                            <div class="mb-3">
                                <label for="date_reported" class="form-label">Date Reported</label>
                                <input type="date" class="form-control" id="date_reported" name="date_reported" required>
                            </div>
                            <div class="mb-3">
                                <label for="remarks" class="form-label">Remarks</label>
                                <textarea class="form-control" id="remarks" name="remarks" rows="3" style="text-transform: capitalize;"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-outline-primary">Submit</button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <hr>
                                <!-- filter section-->

        <div class="table-responsive mt-5">
            <form method="GET" action="{{ route('inventory.index') }}" class="mb-4">
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="sku" placeholder="Search by SKU" value="{{ request('sku') }}">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="item_name" placeholder="Search by Item Name" value="{{ request('item_name') }}">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="category_name" placeholder="Search by Category" value="{{ request('category_name') }}">
                    </div>
                    <div class="col-md-4 mt-2">
                        <button type="submit" class="btn btn-outline-primary">Filter</button>
                        <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">Clear</a>
                    </div>
                </div>
            </form>
            <table class="table table-hover table-striped text-black bg-transparent border-0 rounded-3 shadow-sm">
                <thead class="table-dark rounded-top">
                    <tr>
                        <th>SKU</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Unit</th>
                        <th>SOH</th>
                        <th>In</th>
                        <th>Out</th>
                        <th>Returns</th>
                        <th>Supplier</th>
                        <th>Unit Price</th>
                        <th>Total Value</th>
                        <th>Order Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                    <tr class="align-middle">
                        <td>{{ $item->sku }}</td>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->category->category_name ?? 'No Category' }}</td>
                        <td>{{ $item->unit_of_measure }}</td>
                        <td>{{ $item->stock_on_hand }}</td>
                        <td>{{ $item->quantity_checked_in }}</td>
                        <td>{{ $item->quantity_checked_out }}</td>
                        <td>{{ $item->returns }}</td>
                        <td>{{ $item->supplier }}</td>
                        <td>Ksh{{ number_format($item->unit_price, 2) }}</td>
                        <td>Ksh{{ number_format($item->total_value, 2) }}</td>
                        <td>{{ $item->order_date }}</td>
                        <td>
                            <button class="btn btn-outline-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $item->id }}">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <form method="POST" action="{{ route('inventory.softDelete', $item->id) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this item? This action cannot be undone.');">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $items->appends(request()->except('page'))->links('pagination::bootstrap-5') }}

        </div>
    </div>
</div>

<!--Edit Modals -->
@foreach ($items as $item)
<div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1" aria-labelledby="editItemModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="editItemModalLabel{{ $item->id }}">Edit Inventory Item</h5>
                <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('inventory.update', $item->id) }}">
                    @csrf
                    @method('PUT')

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
                            <input type="text" class="form-control" id="category{{ $item->id }}" value="{{ $item->category->category_name }}" readonly>
                            <input type="hidden" name="category_id" value="{{ $item->category_id }}">
                        </div>


                        <div class="col-md-6">
                            <label for="unit_of_measure{{ $item->id }}" class="form-label">Unit of Measure</label>
                            <input type="text" class="form-control" id="unit_of_measure{{ $item->id }}" name="unit_of_measure" value="{{ $item->unit_of_measure }}">
                        </div>

                        <div class="col-md-4">
                            <label for="stock_on_hand{{ $item->id }}" class="form-label">Stock on Hand</label>
                            <input type="number" class="form-control" id="stock_on_hand{{ $item->id }}" name="stock_on_hand" value="{{ $item->stock_on_hand }}">
                        </div>

                        <div class="col-md-4">
                            <label for="quantity_checked_in{{ $item->id }}" class="form-label">Checked In</label>
                            <input type="number" class="form-control" id="quantity_checked_in{{ $item->id }}" name="quantity_checked_in" value="{{ $item->quantity_checked_in }}">
                        </div>

                        <!-- <div class="col-md-4">
                            <label for="quantity_checked_out{{ $item->id }}" class="form-label">Checked Out</label>
                            <input type="number" class="form-control" id="quantity_checked_out{{ $item->id }}" name="quantity_checked_out" value="{{ $item->quantity_checked_out }}">
                        </div> -->

                        <!-- <div class="col-md-4">
                            <label for="returns{{ $item->id }}" class="form-label">Returns</label>
                            <input type="number" class="form-control" id="returns{{ $item->id }}" name="returns" value="{{ $item->returns }}">
                        </div> -->

                        <div class="col-md-4">
                            <label for="supplier{{ $item->id }}" class="form-label">Supplier</label>
                            <input type="text" class="form-control" id="supplier{{ $item->id }}" name="supplier" value="{{ $item->supplier }}">
                        </div>

                        <div class="col-md-4">
                            <label for="unit_price{{ $item->id }}" class="form-label">Unit Price</label>
                            <input type="number" step="0.01" class="form-control" id="unit_price{{ $item->id }}" name="unit_price" value="{{ $item->unit_price }}">
                        </div>

                        <!-- <div class="col-md-6">
                            <label for="total_value{{ $item->id }}" class="form-label">Total Value</label>
                            <input type="number" step="0.01" class="form-control" id="total_value{{ $item->id }}" name="total_value" value="{{ $item->total_value }}">
                        </div> -->

                        <div class="col-md-6">
                            <label for="order_date{{ $item->id }}" class="form-label">Order Date</label>
                            <input type="date" class="form-control" id="order_date{{ $item->id }}" name="order_date" value="{{ $item->order_date }}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Update Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<script>
    document.getElementById('sku').addEventListener('change', function () {
    const selectedOption = this.options[this.selectedIndex];
    const itemNameInput = document.getElementById('item_name');

    // Get the item name from the selected option's text
    if (selectedOption) {
        const itemName = selectedOption.text.split(' - ')[1];
        itemNameInput.value = itemName || '';
    }

});
</script>

@endsection
