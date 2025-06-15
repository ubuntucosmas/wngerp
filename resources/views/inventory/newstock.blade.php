@extends('layouts.master')

@section('title', 'New Stock')
@section('navbar-title', 'New Stock')

@section('content')

<!-- Inline Styles -->
<style>
   
    body, h1, h2, h3, h4, h5, h6, p, label, input, select, textarea, button, .btn, .modal-title, .form-label, .table {
        font-family: "Gill Sans", sans-serif !important; /* Apply everywhere */
    }

    body {
        background-color: rgb(245, 247, 250); /* Soft background for readability */
        color: #212529;
    }

    h2 {
        font-weight: bold;
        font-size: 2rem;
        color: #0c2d48;
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .table {
        border-spacing: 0 0.5rem; /* Add space between rows */
    }

    .table-hover tbody tr:hover {
        background-color: rgba(200, 225, 255, 0.4); /* Light blue hover effect */
        transition: background-color 0.2s ease-in-out;
    }

    .table-dark {
        background-color: #343a40;
        color: #fff;
    }

    .btn-outline-primary {
        border-color: #2e8bc0;
        color: #2e8bc0;
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover {
        background-color: #2e8bc0;
        color: #fff;
    }

    .btn-primary {
        background-color: #2e8bc0;
        border-color: #2e8bc0;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #145da0;
        border-color: #145da0;
    }

    .modal-content {
        background-color: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .form-control {
        background-color: rgba(255, 255, 255, 0.85);
        border: 1px solid #ced4da;
        border-radius: 0.5rem;
    }

    .form-control:focus {
        border-color: #2e8bc0;
        box-shadow: 0 0 0 0.2rem rgba(46, 139, 192, 0.25);
    }

    .alert-success {
        font-weight: bold;
        color: #fff;
        background-color: #28a745;
        border: 1px solid #28a745;
        border-radius: 0.5rem;
    }
</style>


<div class="container mt-4">

    <!-- Add New Stock and Create Category Buttons -->
    <div class="d-flex justify-content-between mb-3">
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addNewStockModal">
            Add New Stock
        </button>
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
            Create Category
        </button>
    </div>

    <!-- New Stock Table -->
    <div class="search-box" style="max-width: 300px; margin-bottom: 16px;">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control border-start-0" placeholder="Search items..." id="itemSearch">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Sku</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Unit of Measure</th>
                    <th>Quantity</th>
                    <th>Supplier</th>
                    <th>Added On</th>
                </tr>
            </thead>
            <tbody id="stockItems">
                @forelse ($stocks as $stock)
                <tr>
                    <td>{{ $stock->sku }}</td>
                    <td>{{ $stock->item_name }}</td>
                    <td>{{ $stock->category->category_name ?? 'No Category' }}</td> <!-- Updated for category -->
                    <td>{{ $stock->unit_of_measure }}</td>
                    <td>{{ $stock->stock_on_hand }}</td>
                    <td>{{ $stock->supplier }}</td>
                    <td>{{ $stock->created_at->format('Y-m-d') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No stock records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $stocks->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Add New Stock Modal -->
<div class="modal fade" id="addNewStockModal" tabindex="-1" aria-labelledby="addNewStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewStockModalLabel">Add New Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('inventory.store.newstock') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="item_name" class="form-label">Item Name</label>
                        <input type="text" class="form-control" id="item_name" style="text-transform: capitalize;" name="item_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">-- Select Category --</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="unit_of_measure" class="form-label">Unit of Measure</label>
                        <select class="form-select" id="unit_of_measure" name="unit_of_measure" required>
                            <option value="" disabled selected>--Select unit--</option>
                            <option value="boxes">Boxes</option>
                            <option value="boxes">Packets</option>
                            <option value="metres">Metres</option>
                            <option value="rolls">Rolls</option>
                            <option value="pieces">Pieces</option>
                            <option value="kg">Kilograms</option>
                            <option value="grams">Grams</option>
                            <option value="sheet">Sheet</option>
                            <option value="litres">Litres</option>
                            <option value="gallons">Gallons</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required>
                    </div>
                    <div class="mb-3">
                        <label for="supplier" class="form-label">Supplier</label>
                        <input type="text" class="form-control" id="supplier" style="text-transform: capitalize;" name="supplier" required>
                    </div>
                    <div class="mb-3">
                        <label for="unit_price" class="form-label">Unit Price</label>
                        <input type="number" class="form-control" id="unit_price" name="unit_price" step="0.01" required>
                    </div>
                    <button type="submit" class="btn btn-outline-primary">Add Stock</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Create Category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCategoryModalLabel">Create New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('inventory.categories.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="category_name" style="text-transform: capitalize;" name="category_name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Category</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('itemSearch');
        const rows = document.querySelectorAll('#stockItems tr');

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