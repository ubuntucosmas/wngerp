@extends('layouts.master')

@section('title', 'Edit Project Budget')

@section('content')
<div class="container mt-4">
    <form action="{{ route('budget.update', [$project, $budget]) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Project Info --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <label>Project Name</label>
                <input type="text" class="form-control" value="{{ $project->name }}" readonly>
            </div>
            <div class="col-md-4">
                <label>Client</label>
                <input type="text" class="form-control" value="{{ $project->client_name }}" readonly>
            </div>
            <div class="col-md-4">
                <label>Venue</label>
                <input type="text" class="form-control" value="{{ $project->venue }}" readonly>
            </div>
        </div>

        {{-- Budget Period --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <label>Start Date</label>
                <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $budget->project->start_date) }}" required>
            </div>
            <div class="col-md-6">
                <label>End Date</label>
                <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $budget->project->end_date) }}" required>
            </div>
        </div>

        @php
            $editableSections = ['Materials - Production', 'Materials for Hire'];
            $isFinance = auth()->user()->hasAnyRole(['finance', 'super-admin']);
        @endphp

        {{-- Budget Items --}}
        <div class="mb-4" id="budgetSections">
            @foreach($items as $category => $group)
                <div class="card mb-4 budget-section">
                    <div class="card-header bg-light d-flex justify-content-between">
                        <strong>{{ $category }}</strong>
                        @if(in_array($category, $editableSections))
                            <button type="button" class="btn btn-sm btn-outline-primary add-row-btn" data-section="{{ $category }}">+ Add Row</button>
                        @endif
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>Particular</th>
                                    <th>Unit</th>
                                    <th>Qty</th>
                                    <th>Unit Price</th>
                                    <th>Budgeted Cost</th>
                                    <th>Comment</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="item-rows" data-section="{{ $category }}">
                                @foreach($group as $item)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="items[{{ $category }}][{{ $item->id }}][id]" value="{{ $item->id }}">
                                            <input type="text" name="items[{{ $category }}][{{ $item->id }}][particular]" class="form-control" value="{{ old('items.' . $category . '.' . $item->id . '.particular', $item->particular) }}" required>
                                        </td>
                                        <td><input type="text" name="items[{{ $category }}][{{ $item->id }}][unit]" class="form-control" value="{{ old('items.' . $category . '.' . $item->id . '.unit', $item->unit) }}"></td>
                                        <td><input type="number" step="0.01" name="items[{{ $category }}][{{ $item->id }}][quantity]" class="form-control quantity" value="{{ old('items.' . $category . '.' . $item->id . '.quantity', $item->quantity) }}" required></td>
                                        <td><input type="number" step="0.01" name="items[{{ $category }}][{{ $item->id }}][unit_price]" class="form-control unit-price" value="{{ old('items.' . $category . '.' . $item->id . '.unit_price', $item->unit_price) }}" required></td>
                                        <td><input type="number" step="0.01" name="items[{{ $category }}][{{ $item->id }}][budgeted_cost]" class="form-control cost" value="{{ old('items.' . $category . '.' . $item->id . '.budgeted_cost', $item->budgeted_cost) }}" readonly></td>
                                        <td><input type="text" name="items[{{ $category }}][{{ $item->id }}][comment]" class="form-control" value="{{ old('items.' . $category . '.' . $item->id . '.comment', $item->comment) }}"></td>
                                        <td><button type="button" class="btn btn-sm btn-outline-danger remove-row">×</button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Summary --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <label>Total Budget</label>
                <input type="number" step="0.01" name="budget_total" id="totalBudget" class="form-control" value="{{ $budget->budget_total }}" readonly>
            </div>
            <div class="col-md-4">
                <label>Invoice</label>
                <input type="number" step="0.01" name="invoice" id="invoiceAmount" class="form-control" value="{{ $budget->invoice }}">
            </div>
            <div class="col-md-4">
                <label>Profit</label>
                <input type="number" step="0.01" name="profit" id="profitAmount" class="form-control" value="{{ $budget->profit }}" readonly>
            </div>
        </div>

        {{-- Approval --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <label>Approved By</label>
                <input type="text" name="approved_by" class="form-control" value="{{ Auth::user()->name }}">
            </div>
            <div class="col-md-6">
                <label>Approved Departments</label>
                <input type="text" name="approved_departments" class="form-control" value="{{ Auth::user()->department }}">
            </div>
        </div>

        {{-- Submit --}}
        <div class="text-end">
            <button type="submit" class="btn btn-primary">Update Budget</button>
        </div>
    </form>
</div>

{{-- Scripts --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    function updateRowCost(row) {
        const qty = parseFloat(row.querySelector('.quantity')?.value || 0);
        const price = parseFloat(row.querySelector('.unit-price')?.value || 0);
        const cost = qty * price;
        const costField = row.querySelector('.cost');
        if (costField) {
            costField.value = cost.toFixed(2);
        }
        return cost;
    }

    function updateTotals() {
        let total = 0;
        document.querySelectorAll('.budget-section tbody tr').forEach(row => {
            total += updateRowCost(row);
        });
        document.getElementById('totalBudget').value = total.toFixed(2);

        const invoice = parseFloat(document.getElementById('invoiceAmount')?.value || 0);
        document.getElementById('profitAmount').value = (invoice - total).toFixed(2);
    }

    // Initial load
    updateTotals();

    document.querySelectorAll('.quantity, .unit-price').forEach(input => {
        input.addEventListener('input', updateTotals);
    });

    document.getElementById('invoiceAmount').addEventListener('input', updateTotals);

    document.querySelectorAll('.remove-row').forEach(button => {
        button.addEventListener('click', (e) => {
            const row = e.target.closest('tr');
            row.remove();
            updateTotals();
        });
    });

    // Add Row
    document.querySelectorAll('.add-row-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const section = btn.dataset.section;
            const tbody = document.querySelector(`tbody[data-section="${section}"]`);
            const timestamp = Date.now(); // Unique ID for new row
            
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>
                    <input type="hidden" name="items[${section}][new_${timestamp}][id]" value="">
                    <input type="text" name="items[${section}][new_${timestamp}][particular]" class="form-control" required>
                </td>
                <td><input type="text" name="items[${section}][new_${timestamp}][unit]" class="form-control"></td>
                <td><input type="number" step="0.01" name="items[${section}][new_${timestamp}][quantity]" class="form-control quantity" required></td>
                <td><input type="number" step="0.01" name="items[${section}][new_${timestamp}][unit_price]" class="form-control unit-price" required></td>
                <td><input type="number" step="0.01" name="items[${section}][new_${timestamp}][budgeted_cost]" class="form-control cost" readonly></td>
                <td><input type="text" name="items[${section}][new_${timestamp}][comment]" class="form-control"></td>
                <td><button type="button" class="btn btn-sm btn-outline-danger remove-row">×</button></td>
            `;
            tbody.appendChild(newRow);

            // Add event listeners
            const quantityInput = newRow.querySelector('.quantity');
            const priceInput = newRow.querySelector('.unit-price');
            
            quantityInput.addEventListener('input', updateTotals);
            priceInput.addEventListener('input', updateTotals);
            
            newRow.querySelector('.remove-row').addEventListener('click', () => {
                newRow.remove();
                updateTotals();
            });
            
            // Focus on the particular field for better UX
            newRow.querySelector('input[name$="[particular]"]').focus();
        });
    });
    
    // Handle form submission
    document.querySelector('form').addEventListener('submit', function(e) {
        // Recalculate totals before submit to ensure they're up to date
        updateTotals();
        
        // Basic form validation
        let isValid = true;
        this.querySelectorAll('[required]').forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
});
</script>
@endpush
@endsection
