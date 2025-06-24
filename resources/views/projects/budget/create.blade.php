@extends('layouts.master')
@section('title', 'Create Project Budget')

@section('content')
<div class="container-fluid p-0">
    <div class="mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Budget</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 m-0">Create Project Budget</h1>
            <div>
                <a href="{{ route('projects.files.index', $project) }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Back to Project Files
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Save Budget
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('budget.store', $project) }}" method="POST">
                @csrf
                <div class="container">
                    {{-- Project & Client --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="project_name">Project Name</label>
                <input type="text" class="form-control" name="project_name" value="{{ $project->name }}" readonly>
            </div>
            <div class="col-md-6">
                <label for="client">Client</label>
                <input type="text" class="form-control" name="client" value="{{ $project->client_name }}" readonly>
            </div>
        </div>

        {{-- Start & End Dates --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <label for="start_date">Start Date</label>
                <input type="date" class="form-control" name="start_date" value="{{ old('start_date', $project->start_date) }}">
            </div>
            <div class="col-md-6">
                <label for="end_date">End Date</label>
                <input type="date" class="form-control" name="end_date" value="{{ old('end_date', $project->end_date) }}">
            </div>
        </div>

        {{-- Materials - Production --}}
<div class="mb-4">
    <h5>Materials - Production</h5>
    <table class="table table-bordered" id="materialsProductionTable">
        <thead>
            <tr>
                <th>Particular</th>
                <th>Unit Of Measure</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Budgeted Cost</th>
                <th>Comment</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="materialsProductionBody">
            <tr>
                <td><input type="text" name="items[Materials - Production][0][particular]" class="form-control"></td>
                <td><input type="text" name="items[Materials - Production][0][unit]" class="form-control"></td>
                <td><input type="number" step="0.01" name="items[Materials - Production][0][quantity]" class="form-control"></td>
                <td><input type="number" step="0.01" name="items[Materials - Production][0][unit_price]" class="form-control"></td>
                <td><input type="number" step="0.01" name="items[Materials - Production][0][budgeted_cost]" class="form-control"></td>
                <td><input type="text" name="items[Materials - Production][0][comment]" class="form-control"></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
            </tr>
        </tbody>
    </table>
    <button type="button" class="btn btn-success btn-sm" id="addProductionRow">+ Add Row</button>
</div>

{{-- Materials for Hire --}}
<div class="mb-4">
    <h5>Materials for Hire</h5>
    <table class="table table-bordered" id="materialsHireTable">
        <thead>
            <tr>
                <th>Particular</th>
                <th>Unit Of Measure</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Budgeted Cost</th>
                <th>Comment</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="materialsHireBody">
            <tr>
                <td><input type="text" name="items[Materials for Hire][0][particular]" class="form-control"></td>
                <td><input type="text" name="items[Materials for Hire][0][unit]" class="form-control"></td>
                <td><input type="number" step="0.01" name="items[Materials for Hire][0][quantity]" class="form-control"></td>
                <td><input type="number" step="0.01" name="items[Materials for Hire][0][unit_price]" class="form-control"></td>
                <td><input type="number" step="0.01" name="items[Materials for Hire][0][budgeted_cost]" class="form-control"></td>
                <td><input type="text" name="items[Materials for Hire][0][comment]" class="form-control"></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
            </tr>
        </tbody>
    </table>
    <button type="button" class="btn btn-success btn-sm" id="addHireRow">+ Add Row</button>
</div>

        {{-- Static Category-Based Budget Items --}}
        @foreach($categories as $category => $particulars)
            <div class="mb-4">
                <h5>{{ $category }}</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Particular</th>
                            <th>Unit Of Measure</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Budgeted Cost</th>
                            <th>Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($particulars as $index => $particular)
                            <tr>
                                <td>
                                    <input type="text" name="items[{{ $category }}][{{ $index }}][particular]" class="form-control" value="{{ $particular }}">
                                </td>
                                <td><input type="text" name="items[{{ $category }}][{{ $index }}][unit]" class="form-control"></td>
                                <td><input type="number" step="0.01" name="items[{{ $category }}][{{ $index }}][quantity]" class="form-control"></td>
                                <td><input type="number" step="0.01" name="items[{{ $category }}][{{ $index }}][unit_price]" class="form-control"></td>
                                <td><input type="number" step="0.01" name="items[{{ $category }}][{{ $index }}][budgeted_cost]" class="form-control"></td>
                                <td><input type="text" name="items[{{ $category }}][{{ $index }}][comment]" class="form-control"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach

        {{-- Budget Summary --}}
        <div class="row my-4">
            <div class="col-md-4">
                <label for="budget_total">Total Budget</label>
                <input type="number" step="0.01" name="budget_total" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="invoice">Invoice</label>
                <input type="text" name="invoice" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="profit">Profit</label>
                <input type="number" step="0.01" name="profit" class="form-control">
            </div>
        </div>

        {{-- Approval Section --}}
        <div class="mb-4">
            <label for="approved_by">Approved By</label>
            <input type="text" name="approved_by" class="form-control mb-2">

            <label for="approved_departments">Departments (comma-separated)</label>
            <input type="text" name="approved_departments" class="form-control" placeholder="Production, Finance">
        </div>

        {{-- Form actions --}}
        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
            <a href="{{ route('projects.files.index', $project) }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle"></i> Cancel
            </a>
            <div>
                <button type="reset" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Save Budget
                </button>
            </div>
        </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{{-- JS for dynamic row addition/removal --}}
<script>
    let productionIndex = 1;
    let hireIndex = 1;

    // Add row to Materials - Production
    document.getElementById('addProductionRow').addEventListener('click', function () {
        const tbody = document.getElementById('materialsProductionBody');
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td><input type="text" name="items[Materials - Production][${productionIndex}][particular]" class="form-control"></td>
            <td><input type="text" name="items[Materials - Production][${productionIndex}][unit]" class="form-control"></td>
            <td><input type="number" step="0.01" name="items[Materials - Production][${productionIndex}][quantity]" class="form-control"></td>
            <td><input type="number" step="0.01" name="items[Materials - Production][${productionIndex}][unit_price]" class="form-control"></td>
            <td><input type="number" step="0.01" name="items[Materials - Production][${productionIndex}][budgeted_cost]" class="form-control"></td>
            <td><input type="text" name="items[Materials - Production][${productionIndex}][comment]" class="form-control"></td>
            <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
        `;
        tbody.appendChild(newRow);
        productionIndex++;
    });

    // Add row to Materials for Hire
    document.getElementById('addHireRow').addEventListener('click', function () {
        const tbody = document.getElementById('materialsHireBody');
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td><input type="text" name="items[Materials for Hire][${hireIndex}][particular]" class="form-control"></td>
            <td><input type="text" name="items[Materials for Hire][${hireIndex}][unit]" class="form-control"></td>
            <td><input type="number" step="0.01" name="items[Materials for Hire][${hireIndex}][quantity]" class="form-control"></td>
            <td><input type="number" step="0.01" name="items[Materials for Hire][${hireIndex}][unit_price]" class="form-control"></td>
            <td><input type="number" step="0.01" name="items[Materials for Hire][${hireIndex}][budgeted_cost]" class="form-control"></td>
            <td><input type="text" name="items[Materials for Hire][${hireIndex}][comment]" class="form-control"></td>
            <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
        `;
        tbody.appendChild(newRow);
        hireIndex++;
    });

    // Remove row for both tables
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
        }
    });


    document.addEventListener('input', function (e) {
    const row = e.target.closest('tr');
    if (!row) return;

    const quantityInput = row.querySelector('input[name*="[quantity]"]');
    const unitPriceInput = row.querySelector('input[name*="[unit_price]"]');
    const budgetedCostInput = row.querySelector('input[name*="[budgeted_cost]"]');

    if (quantityInput && unitPriceInput && budgetedCostInput) {
        const quantity = parseFloat(quantityInput.value) || 0;
        const unitPrice = parseFloat(unitPriceInput.value) || 0;
        budgetedCostInput.value = (quantity * unitPrice).toFixed(2);
    }
});

</script>


