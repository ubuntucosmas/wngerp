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
                <td><input type="number" step="0.01" name="items[Materials for Hire][0][quantity]" class="form-control quantity"></td>
                <td><input type="number" step="0.01" name="items[Materials for Hire][0][unit_price]" class="form-control unit-price"></td>
                <td><input type="number" step="0.01" name="items[Materials for Hire][0][budgeted_cost]" class="form-control budgeted-cost" readonly></td>
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
                <label for="total_budget">Total Budget</label>
                <input type="number" step="0.01" name="total_budget" id="total_budget" class="form-control" readonly>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Material list items from the server
    const materialItems = @json($materialItems);

    // Function to find the closest parent table for a category
    function findTableForCategory(category) {
        const headers = document.querySelectorAll('h5');
        for (const header of headers) {
            if (header.textContent.trim() === category) {
                return header.nextElementSibling;
            }
        }
        return null;
    }

    // Function to add a new row to a table
    function addRowToTable(table, item, index) {
        const tbody = table.querySelector('tbody');
        const newRow = tbody.insertRow();
        
        // Create cells for the row
        const cell1 = newRow.insertCell(0);
        const cell2 = newRow.insertCell(1);
        const cell3 = newRow.insertCell(2);
        const cell4 = newRow.insertCell(3);
        const cell5 = newRow.insertCell(4);
        const cell6 = newRow.insertCell(5);
        const cell7 = tbody.rows[0] && tbody.rows[0].cells.length > 6 ? newRow.insertCell(6) : null;
        
        // Add input fields to cells
        cell1.innerHTML = `<input type="text" name="items[${item.category}][${index}][particular]" class="form-control" value="${item.particular || ''}">`;
        cell2.innerHTML = `<input type="text" name="items[${item.category}][${index}][unit]" class="form-control" value="${item.unit || ''}">`;
        cell3.innerHTML = `<input type="number" step="0.01" name="items[${item.category}][${index}][quantity]" class="form-control quantity" value="${item.quantity || ''}">`;
        
        if (cell7) {
            // For tables with 7 columns (like Materials for Hire)
            cell4.innerHTML = `<input type="number" step="0.01" name="items[${item.category}][${index}][unit_price]" class="form-control unit-price">`;
            cell5.innerHTML = `<input type="number" step="0.01" name="items[${item.category}][${index}][budgeted_cost]" class="form-control budgeted-cost" readonly>`;
            cell6.innerHTML = `<input type="text" name="items[${item.category}][${index}][comment]" class="form-control" value="${item.comment || ''}">`;
            cell7.innerHTML = '<button type="button" class="btn btn-danger btn-sm remove-row">Remove</button>';
        } else {
            // For tables with 6 columns (like labor categories)
            cell4.innerHTML = `<input type="number" step="0.01" name="items[${item.category}][${index}][unit_price]" class="form-control unit-price">`;
            cell5.innerHTML = `<input type="number" step="0.01" name="items[${item.category}][${index}][budgeted_cost]" class="form-control budgeted-cost" readonly>`;
            cell6.innerHTML = `<input type="text" name="items[${item.category}][${index}][comment]" class="form-control" value="${item.comment || ''}">`;
        }
    }

    // Function to populate form fields
    function populateFormFields() {
        if (!materialItems || materialItems.length === 0) return;
        
        // Group items by category
        const itemsByCategory = materialItems.reduce((acc, item) => {
            if (!acc[item.category]) {
                acc[item.category] = [];
            }
            acc[item.category].push(item);
            return acc;
        }, {});
        
        // Process each category
        Object.entries(itemsByCategory).forEach(([category, items]) => {
            const table = findTableForCategory(category);
            if (!table) return;
            
            // Clear existing rows except the first one
            const tbody = table.querySelector('tbody');
            if (tbody.rows.length > 1) {
                tbody.innerHTML = '';
            }
            
            // Add rows for each item
            items.forEach((item, index) => {
                addRowToTable(table, item, index);
            });
        });
    }

    // Function to calculate budgeted cost for a row
    function calculateBudgetedCost(row) {
        const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
        const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
        const budgetedCost = quantity * unitPrice;
        const budgetedCostInput = row.querySelector('.budgeted-cost');
        
        if (budgetedCostInput) {
            budgetedCostInput.value = budgetedCost.toFixed(2);
        }
        
        // Update total budget
        updateTotalBudget();
        
        return budgetedCost;
    }
    
    // Function to update the total budget
    function updateTotalBudget() {
        let total = 0;
        
        // Sum up all budgeted costs from all tables
        document.querySelectorAll('.budgeted-cost').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        
        // Update the total budget field
        const totalBudgetInput = document.getElementById('total_budget');
        if (totalBudgetInput) {
            totalBudgetInput.value = total.toFixed(2);
        }
    }
    
    // Function to initialize calculation for all rows
    function initializeCalculations() {
        // No need to add event listeners here as they're handled by jQuery below
        // This function is kept for future use if needed
    }

    // Wait for the DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        let productionIndex = 1;
        let hireIndex = 1;
        
        // Initialize calculations
        initializeCalculations();
        
        // Populate form fields with material list items after a short delay
        // to ensure all tables are rendered
        setTimeout(function() {
            populateFormFields();
            // Re-initialize calculations after populating fields
            initializeCalculations();
        }, 300);

        // Add row to Materials - Production
        $(document).on('click', '#addProductionRow', function() {
            const tbody = document.getElementById('materialsProductionBody');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td><input type="text" name="items[Materials - Production][${productionIndex}][particular]" class="form-control"></td>
                <td><input type="text" name="items[Materials - Production][${productionIndex}][unit]" class="form-control"></td>
                <td><input type="number" step="0.01" name="items[Materials - Production][${productionIndex}][quantity]" class="form-control quantity"></td>
                <td><input type="number" step="0.01" name="items[Materials - Production][${productionIndex}][unit_price]" class="form-control unit-price"></td>
                <td><input type="number" step="0.01" name="items[Materials - Production][${productionIndex}][budgeted_cost]" class="form-control budgeted-cost" readonly></td>
                <td><input type="text" name="items[Materials - Production][${productionIndex}][comment]" class="form-control"></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
            `;
            tbody.appendChild(newRow);
            productionIndex++;
        });

        // Add row to Materials for Hire
        $(document).on('click', '#addHireRow', function() {
            const tbody = document.getElementById('materialsHireBody');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td><input type="text" name="items[Materials for Hire][${hireIndex}][particular]" class="form-control"></td>
                <td><input type="text" name="items[Materials for Hire][${hireIndex}][unit]" class="form-control"></td>
                <td><input type="number" step="0.01" name="items[Materials for Hire][${hireIndex}][quantity]" class="form-control quantity"></td>
                <td><input type="number" step="0.01" name="items[Materials for Hire][${hireIndex}][unit_price]" class="form-control unit-price"></td>
                <td><input type="number" step="0.01" name="items[Materials for Hire][${hireIndex}][budgeted_cost]" class="form-control budgeted-cost" readonly></td>
                <td><input type="text" name="items[Materials for Hire][${hireIndex}][comment]" class="form-control"></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
            `;
            tbody.appendChild(newRow);
            hireIndex++;
        });

        // Remove row for both tables and update total
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
            updateTotalBudget();
        });

        // Calculate budgeted cost when quantity or unit price changes
        $(document).on('input', '.quantity, .unit-price', function() {
            const row = $(this).closest('tr');
            const quantity = parseFloat(row.find('.quantity').val()) || 0;
            const unitPrice = parseFloat(row.find('.unit-price').val()) || 0;
            const budgetedCost = (quantity * unitPrice).toFixed(2);
            row.find('.budgeted-cost').val(budgetedCost);
            updateTotalBudget();
        });
        
        // Initialize total budget on page load
        updateTotalBudget();
    });
</script>
@endpush
