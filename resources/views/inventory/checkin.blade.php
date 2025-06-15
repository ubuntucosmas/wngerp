@extends('layouts.master')

@section('title', 'CheckIns')
@section('navbar-title', 'CheckIns')

@section('content')

<!-- Inline Styles -->
<style>
    /* Body Styling */
    body {
        background: rgb(248, 248, 248);
        font-family: "Gill Sans", sans-serif;
    }

    /* Header Styling */
    h2 {
        font-weight: bold;
        color: #212529;
        text-align: center;
        margin-bottom: 1.5rem;
    }

    /* Modal Styling */
    .modal-content {
        background: rgba(255, 255, 255, 0.97);
        border-radius: 1rem;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    /* Table Styling */
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(240, 240, 240, 0.5);
    }

    .table-light {
        background-color: rgb(87, 92, 94);
        color: #212529;
        font-weight: bold;
        text-align: center;
        border-radius: 0.5rem 0.5rem 0 0;
    }

    .table-responsive {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border-radius: 0.5rem;
    }

    /* Button Styling */
    .btn-outline-primary {
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover {
        background-color: #0d6efd;
        color: #fff;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.5);
    }

    .btn-outline-success:hover,
    .btn-outline-secondary:hover {
        transform: scale(1.05);
    }

    /* Search Bar Styling */
    .search-bar {
        width: 100%;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        border: 1px solid rgba(0, 0, 0, 0.1);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    /* Pagination Styling */
    .pagination {
        justify-content: center;
        margin-top: 1rem;
    }
</style>

<div class="container mt-4">

    <!-- Button to Open Check In Modal -->
    <button type="button" class="btn btn-outline-primary mb-3" data-bs-toggle="modal" data-bs-target="#checkInModal">
    <i class="bi bi-plus-lg me-1"></i> Check In Stock
    </button>
    <!-- Check In Modal -->
    <div class="modal fade" id="checkInModal" tabindex="-1" aria-labelledby="checkInModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('inventory.checkin') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="checkInModalLabel">Check In Stock</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- SKU Dropdown -->
                        <div class="mb-3">
                            <label for="id" class="form-label">Select SKU</label>
                            <select id="id" class="form-select @error('id') is-invalid @enderror" name="id" required>
                                <option value="">-- Select SKU and Item Name --</option>
                                @foreach ($skus as $item)
                                    <option value="{{ $item->id }}" {{ old('id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->sku }} - {{ $item->item_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Quantity Input -->
                        <div class="mb-3">
                            <label for="quantity_in" class="form-label">Quantity to Check In</label>
                            <input type="number" id="quantity_in" class="form-control @error('quantity_in') is-invalid @enderror" name="quantity_in" required min="1" value="{{ old('quantity_in') }}">
                            @error('quantity_in')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Check-In Date -->
                        <div class="mb-3">
                            <label for="check_in_date" class="form-label">Check-In Date</label>
                            <input type="date" id="check_in_date" class="form-control @error('check_in_date') is-invalid @enderror" name="check_in_date" required value="{{ old('check_in_date', now()->toDateString()) }}">
                            @error('check_in_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-success" id="checkInSubmitBtn">
                            <i class="bi bi-check-circle me-1"></i> Check In
                        </button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @if ($errors->any())
    <script>
        const checkInModal = new bootstrap.Modal(document.getElementById('checkInModal'));
        checkInModal.show();
    </script>
    @endif

    <script>
    document.querySelector('form').addEventListener('submit', function () {
        const btn = document.getElementById('checkInSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Checking In...`;
    });
    </script>


    <!-- Table Displaying CheckIn Records -->
    <div class="search-box" style="max-width: 300px; margin-bottom: 16px;">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control border-start-0" placeholder="Search items..." id="itemSearch">
        </div>
    </div>
    <div class="table-responsive mt-3">
    <table class="table table-hover table-striped text-black bg-transparent border-0 rounded-3 shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>SKU</th> 
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Unit of Measure</th>
                    <th>Stock on Hand</th>
                    <th>Checked In</th>
                    <th>Checked Out</th>
                    <th>Returns</th>
                    <th>Supplier</th>
                    <th>Unit Price</th>
                    <th>Total Value</th>
                    <th>Order Date</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody id="checkinItems">
                @forelse ($stocks as $stock)
                    <tr>
                        <td>{{ $stock->sku }}</td>
                        <td>{{ $stock->item_name }}</td>
                        <td>{{ $stock->category->category_name ?? 'No Category' }}</td> <!-- Updated for category -->
                        <td>{{ $stock->unit_of_measure }}</td>
                        <td>{{ $stock->stock_on_hand }}</td>
                        <td>{{ $stock->quantity_checked_in }}</td>
                        <td>{{ $stock->quantity_checked_out }}</td>
                        <td>{{ $stock->returns }}</td>
                        <td>{{ $stock->supplier }}</td>
                        <td>{{ number_format($stock->unit_price, 2) }}</td>
                        <td>{{ number_format($stock->total_value, 2) }}</td>
                        <td>{{ $stock->order_date }}</td>
                        <td>{{ $stock->updated_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="text-muted">No records available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $stocks->links('pagination::bootstrap-5') }}
        </div>

    </div>

</div>

<script>
    function filterTable(query) {
        query = query.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(query) ? '' : 'none';
        });
    }



        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('itemSearch');
            const rows = document.querySelectorAll('#checkinItems tr');

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