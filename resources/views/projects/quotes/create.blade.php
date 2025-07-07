@extends('layouts.master')

@section('title', 'Create Quote')

@section('content')
<div class="container py-3">
    <div class="card shadow-sm rounded-3">
        <div class="card-header d-flex justify-content-between align-items-center py-2 px-3">
            <h4 class="mb-0 fw-semibold">Create New Quote</h4>
            <a href="{{ route('quotes.index', $project) }}" class="btn btn-sm btn-secondary d-flex align-items-center gap-1 px-3">
                <i class="fas fa-arrow-left fs-6"></i> Back
            </a>
        </div>
        <div class="card-body px-3 py-4">
            <form action="{{ route('quotes.store', $project) }}" method="POST" id="quoteForm" autocomplete="off" novalidate>
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="customer_name" class="form-label small fw-semibold">Customer Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" 
                               value="{{ old('customer_name') }}" required autocomplete="off" autofocus>
                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="customer_location" class="form-label small fw-semibold">Customer Location</label>
                        <input type="text" class="form-control form-control-sm" id="customer_location" name="customer_location" 
                               value="{{ old('customer_location') }}" autocomplete="off">
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="attention" class="form-label small fw-semibold">Attention</label>
                        <input type="text" class="form-control form-control-sm" id="attention" name="attention" 
                               value="{{ old('attention') }}" autocomplete="off">
                    </div>
                    <div class="col-md-4">
                        <label for="quote_date" class="form-label small fw-semibold">Quote Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control form-control-sm" id="quote_date" name="quote_date" 
                               value="{{ old('quote_date', now()->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="project_start_date" class="form-label small fw-semibold">Project Start Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control form-control-sm" id="project_start_date" name="project_start_date" 
                               value="{{ old('project_start_date', now()->format('Y-m-d')) }}" required>
                    </div>
                </div>

                <!-- <div class="mb-4">
                    <label for="reference" class="form-label small fw-semibold">Reference</label>
                    <input type="text" class="form-control form-control-sm" id="reference" name="reference" 
                           value="{{ old('reference') }}" autocomplete="off">
                </div> -->

                <!--  -->

                <div class="card rounded-3 shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center py-2 px-3">
                        <h5 class="mb-0 fs-6 fw-semibold">Items</h5>
                        <button type="button" class="btn btn-sm btn-primary d-flex align-items-center gap-1" id="addItem" aria-label="Add Item">
                            <i class="fas fa-plus"></i> Add Item
                        </button>
                    </div>
                    <div class="card-body p-3" style="max-height: 50vh; overflow-y: auto;">
                        <div id="items-container">
                            @if(isset($productionItems) && $productionItems->count())
                                @foreach($productionItems as $itemIndex => $item)
                                    <div class="item-row mb-2 p-2 rounded bg-light border">
                                        <div class="fw-bold mb-1">{{ $item->item_name }}</div>
                                        <div class="mb-2">
                                            <label class="form-label small">Profit Margin (%) for this Item</label>
                                            <input type="number" min="0" max="100" step="0.01" class="form-control form-control-sm item-profit-margin" name="items[{{ $itemIndex }}][item_profit_margin]" value="{{ old('items.'.$itemIndex.'.item_profit_margin', 0) }}">
                                        </div>
                                        @if($item->particulars->count())
                                            <table class="table table-bordered table-sm mb-2">
                                                <thead>
                                                    <tr>
                                                        <th>Particular</th>
                                                        <th>Unit</th>
                                                        <th>Quantity</th>
                                                        <th>Unit Price</th>
                                                        <th>Total Cost</th>
                                                        <th>Profit Margin (%)</th>
                                                        <th>Quote Price</th>
                                                        <th>Comment</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($item->particulars as $particularIndex => $particular)
                                                        <tr>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm" name="items[{{ $itemIndex }}][particulars][{{ $particularIndex }}][particular]" value="{{ $particular->particular }}" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm" name="items[{{ $itemIndex }}][particulars][{{ $particularIndex }}][unit]" value="{{ $particular->unit }}" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="number" step="0.01" class="form-control form-control-sm quantity-input" name="items[{{ $itemIndex }}][particulars][{{ $particularIndex }}][quantity]" value="{{ $particular->quantity }}">
                                                            </td>
                                                            <td>
                                                                <input type="number" step="0.01" class="form-control form-control-sm unit-price-input" name="items[{{ $itemIndex }}][particulars][{{ $particularIndex }}][unit_price]" value="{{ $particular->unit_price ?? 0 }}">
                                                            </td>
                                                            <td>
                                                                <input type="number" step="0.01" class="form-control form-control-sm total-cost" name="items[{{ $itemIndex }}][particulars][{{ $particularIndex }}][total_cost]" value="{{ ($particular->quantity ?? 0) * ($particular->unit_price ?? 0) }}" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="number" min="0" max="100" step="0.01" class="form-control form-control-sm profit-margin-input" name="items[{{ $itemIndex }}][particulars][{{ $particularIndex }}][profit_margin]" value="{{ old('items.'.$itemIndex.'.particulars.'.$particularIndex.'.profit_margin', 0) }}">
                                                            </td>
                                                            <td>
                                                                <input type="number" step="0.01" class="form-control form-control-sm quote-price" name="items[{{ $itemIndex }}][particulars][{{ $particularIndex }}][quote_price]" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm" name="items[{{ $itemIndex }}][particulars][{{ $particularIndex }}][comment]" value="{{ $particular->comment }}">
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <!-- Fallback to manual entry if no production items -->
                                <div class="item-row mb-2 p-2 rounded bg-light border" data-index="0">
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <label class="form-label small">Description</label>
                                            <input type="text" class="form-control form-control-sm" name="items[0][description]" placeholder="Description" required autocomplete="off" autofocus>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label small">Days</label>
                                            <input type="number" min="1" class="form-control form-control-sm days" name="items[0][days]" value="1" aria-label="Days">
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label small">Qty</label>
                                            <input type="number" min="0.01" step="0.01" class="form-control form-control-sm quantity" name="items[0][quantity]" value="1" required aria-label="Quantity">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small">Unit Price</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">KES</span>
                                                <input type="number" min="0" step="0.01" class="form-control unit-price" name="items[0][unit_price]" required aria-label="Unit Price">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small">Total Cost</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">KES</span>
                                                <input type="number" step="0.01" class="form-control total-cost" name="items[0][total_cost]" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label small">Profit %</label>
                                            <input type="number" min="0" max="100" step="0.01" class="form-control form-control-sm profit-margin" name="items[0][profit_margin]" value="0">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small">Quote Price</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">KES</span>
                                                <input type="number" step="0.01" class="form-control quote-price" name="items[0][quote_price]" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-12 d-flex justify-content-end mt-2">
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-item" disabled title="Cannot remove last item" aria-label="Remove Item">
                                                <i class="fas fa-trash"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if(isset($materialsForHire) && $materialsForHire->count())
                                <div class="mt-4">
                                    <h6 class="fw-bold text-primary mb-3">Materials for Hire</h6>
                                    @foreach($materialsForHire as $hireIndex => $hireItem)
                                        <div class="item-row mb-2 p-2 rounded bg-light border">
                                            <div class="row g-2">
                                                <div class="col-md-3">
                                                    <label class="form-label small">Description</label>
                                                    <input type="text" class="form-control form-control-sm" name="hire_items[{{ $hireIndex }}][description]" value="{{ $hireItem->particular }}" readonly>
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label small">Days</label>
                                                    <input type="number" min="1" class="form-control form-control-sm days" name="hire_items[{{ $hireIndex }}][days]" value="1">
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label small">Qty</label>
                                                    <input type="number" min="0.01" step="0.01" class="form-control form-control-sm quantity" name="hire_items[{{ $hireIndex }}][quantity]" value="{{ $hireItem->quantity ?? 1 }}" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small">Unit Price</label>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">KES</span>
                                                        <input type="number" min="0" step="0.01" class="form-control unit-price" name="hire_items[{{ $hireIndex }}][unit_price]" value="{{ $hireItem->unit_price ?? 0 }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small">Total Cost</label>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">KES</span>
                                                        <input type="number" step="0.01" class="form-control total-cost" name="hire_items[{{ $hireIndex }}][total_cost]" value="{{ ($hireItem->quantity ?? 1) * ($hireItem->unit_price ?? 0) }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label small">Profit %</label>
                                                    <input type="number" min="0" max="100" step="0.01" class="form-control form-control-sm profit-margin" name="hire_items[{{ $hireIndex }}][profit_margin]" value="0">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small">Quote Price</label>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">KES</span>
                                                        <input type="number" step="0.01" class="form-control quote-price" name="hire_items[{{ $hireIndex }}][quote_price]" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label small">Comment</label>
                                                    <input type="text" class="form-control form-control-sm" name="hire_items[{{ $hireIndex }}][comment]" value="{{ $hireItem->comment ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('quotes.index', $project) }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1 px-3">
                        <i class="fas fa-times fs-6"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm d-flex align-items-center gap-1 px-3">
                        <i class="fas fa-save fs-6"></i> Save Quote
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemCount = 1;
    const itemsContainer = document.getElementById('items-container');

    // Function to calculate total cost and quote price
    function calculateCosts(row) {
        const quantity = parseFloat(row.querySelector('.quantity, .quantity-input')?.value) || 0;
        const unitPrice = parseFloat(row.querySelector('.unit-price, .unit-price-input')?.value) || 0;
        const profitMargin = parseFloat(row.querySelector('.profit-margin, .profit-margin-input')?.value) || 0;
        
        const totalCost = quantity * unitPrice;
        const quotePrice = totalCost * (1 + profitMargin / 100);
        
        const totalCostInput = row.querySelector('.total-cost');
        const quotePriceInput = row.querySelector('.quote-price');
        
        if (totalCostInput) totalCostInput.value = totalCost.toFixed(2);
        if (quotePriceInput) quotePriceInput.value = quotePrice.toFixed(2);
    }

    // Function to calculate costs for all rows in a table
    function calculateTableCosts(table) {
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            calculateCosts(row);
        });
    }

    // Add event listeners for existing inputs
    function addCalculationListeners(container) {
        const inputs = container.querySelectorAll('.quantity, .quantity-input, .unit-price, .unit-price-input, .profit-margin, .profit-margin-input');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                const row = this.closest('tr, .item-row');
                if (row) {
                    calculateCosts(row);
                }
            });
        });
    }

    // Initialize calculations for existing items
    addCalculationListeners(itemsContainer);

    document.getElementById('addItem').addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.className = 'item-row mb-2 p-2 rounded bg-light border';
        newRow.dataset.index = itemCount;

        newRow.innerHTML = `
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="form-label small">Description</label>
                    <input type="text" class="form-control form-control-sm" name="items[${itemCount}][description]" placeholder="Description" required autocomplete="off">
                </div>
                <div class="col-md-1">
                    <label class="form-label small">Days</label>
                    <input type="number" min="1" class="form-control form-control-sm days" name="items[${itemCount}][days]" value="1" aria-label="Days">
                </div>
                <div class="col-md-1">
                    <label class="form-label small">Qty</label>
                    <input type="number" min="0.01" step="0.01" class="form-control form-control-sm quantity" name="items[${itemCount}][quantity]" value="1" required aria-label="Quantity">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Unit Price</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">KES</span>
                        <input type="number" min="0" step="0.01" class="form-control unit-price" name="items[${itemCount}][unit_price]" required aria-label="Unit Price">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Total Cost</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">KES</span>
                        <input type="number" step="0.01" class="form-control total-cost" name="items[${itemCount}][total_cost]" readonly>
                    </div>
                </div>
                <div class="col-md-1">
                    <label class="form-label small">Profit %</label>
                    <input type="number" min="0" max="100" step="0.01" class="form-control form-control-sm profit-margin" name="items[${itemCount}][profit_margin]" value="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Quote Price</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">KES</span>
                        <input type="number" step="0.01" class="form-control quote-price" name="items[${itemCount}][quote_price]" readonly>
                    </div>
                </div>
                <div class="col-md-12 d-flex justify-content-end mt-2">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-item" title="Remove Item" aria-label="Remove Item">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
            </div>
        `;
        itemsContainer.appendChild(newRow);
        addCalculationListeners(newRow);
        itemCount++;
        updateRemoveButtons();
    });

    itemsContainer.addEventListener('click', function(e) {
        if(e.target.closest('.remove-item')) {
            const itemRows = itemsContainer.querySelectorAll('.item-row');
            if(itemRows.length > 1) {
                e.target.closest('.item-row').remove();
                updateRemoveButtons();
            }
        }
    });

    function updateRemoveButtons() {
        const removeButtons = itemsContainer.querySelectorAll('.remove-item');
        const itemRowsCount = itemsContainer.querySelectorAll('.item-row').length;
        removeButtons.forEach(btn => {
            btn.disabled = itemRowsCount <= 1;
            if(btn.disabled){
                btn.setAttribute('title', 'Cannot remove last item');
                btn.setAttribute('aria-disabled', 'true');
            } else {
                btn.removeAttribute('aria-disabled');
                btn.setAttribute('title', 'Remove Item');
            }
        });
    }


});
</script>
@endpush

<style>
.item-row {
    transition: background-color 0.15s ease-in-out;
}
.item-row:focus-within {
    background-color: #e9f5ff;
}
.remove-item {
    opacity: 0.75;
    transition: opacity 0.15s ease;
}
.remove-item:hover:not(:disabled) {
    opacity: 1;
}
.remove-item:disabled {
    cursor: not-allowed;
    opacity: 0.3;
}
.form-label {
    margin-bottom: 0.25rem;
}
.form-control-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.85rem;
}
.card-header h5, .card-header h4 {
    font-size: 1rem;
    margin-bottom: 0;
}
</style>
@endsection