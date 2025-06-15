@extends('layouts.master')

@section('title', 'Checkout Records')
@section('navbar-title', 'Checkout Records')

@section('content')

<!-- Button to Open Modal -->
<button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#checkoutModal">
    <i class="bi bi-box-arrow-up"></i> Checkout Items
</button>

<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <form action="{{ route('inventory.checkout.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkoutModalLabel">Checkout Items</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Received By -->
                    <div class="mt-3">
                        <label for="received_by" class="form-label">Received By</label>
                        <input type="text" class="form-control" style="text-transform: capitalize;" name="received_by" required>
                    </div>

                    <!-- Check Out Date -->
                    <div class="mt-3">
                        <label for="check_out_date" class="form-label">Check Out Date</label>
                        <input type="date" class="form-control" name="check_out_date" required>
                    </div>

                    <!-- Destination -->
                    <div class="mt-3">
                        <label for="destination" class="form-label">Project</label>
                        <input type="text" class="form-control" style="text-transform: capitalize;" name="destination" required>
                    </div>
                    <!-- Items to Checkout -->
                    <div id="checkoutItems">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="items[0][id]" class="form-label">Item</label>
                                <select class="form-select" name="items[0][id]" required>
                                    <option value="">-- Select Item --</option>
                                    @foreach ($inventoryItems as $inventory)
                                        <option value="{{ $inventory->id }}">{{ $inventory->sku }}-{{ $inventory->item_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="items[0][quantity]" class="form-label">Quantity</label>
                                <input type="number" class="form-control" name="items[0][quantity]" required min="1">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-secondary" onclick="addCheckoutItem()">Add Another Item</button>                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-outline-success">
                        <i class="bi bi-check-circle"></i> Checkout
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancel
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    let itemIndex = 1;

    function addCheckoutItem() {
        const checkoutItems = document.getElementById('checkoutItems');
        const newItem = `
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="items[${itemIndex}][id]" class="form-label">Item</label>
                    <select class="form-select" name="items[${itemIndex}][id]" required>
                        <option value="">-- Select Item --</option>
                        @foreach ($inventoryItems as $inventory)
                            <option value="{{ $inventory->id }}">{{ $inventory->item_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="items[${itemIndex}][quantity]" class="form-label">Quantity</label>
                    <input type="number" class="form-control" name="items[${itemIndex}][quantity]" required min="1">
                </div>
            </div>
        `;
        checkoutItems.insertAdjacentHTML('beforeend', newItem);
        itemIndex++;
    }
</script>

<div class="container mt-4"> 
    <div class="table-responsive">
        <table class="table table-hover table-striped text-black bg-transparent border-0 rounded-2 shadow-sm">
        <h2 class="text-center mb-4">Checked out Stock</h2>
        <a href="{{ route('inventory.checkout.export') }}" class="btn btn-outline-success mb-3">
            <i class="bi bi-file-earmark-excel"></i> Export to Excel
        </a>

            <div class="search-box" style="max-width: 300px; margin-bottom: 16px;">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-start-0" placeholder="Search items..." id="itemSearch">
                </div>
            </div>
            <thead class="table-dark">
                <tr>
                    <th>Checkout Batch ID</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Unit of Measure</th>
                    <th>Checked Out By</th>
                    <th>Received By</th>
                    <th>Project</th>
                    <th>Date Checked Out</th>
                </tr>
            </thead>
            <tbody id="checkoutItems">
                @forelse ($checkouts as $checkout)
                    <tr>
                        <td>{{ $checkout->check_out_id }}</td>
                        <td>{{ $checkout->inventory->item_name }}</td>
                        <td>{{ $checkout->quantity }}</td>
                        <td>{{ $checkout->inventory->unit_of_measure }}</td>
                        <td>{{ $checkout->checked_out_by }}</td>
                        <td>{{ $checkout->received_by }}</td>
                        <td>{{ $checkout->destination }}</td>
                        <td>{{ $checkout->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-muted">No checkout records available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $checkouts->links('pagination::bootstrap-5') }}
        </div>
        
    </div>
</div>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('itemSearch');
            const rows = document.querySelectorAll('#checkoutItems tr');

            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        });
    </script>

@endsection