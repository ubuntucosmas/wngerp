@extends('layouts.master')

@section('title', 'Create Quote')

@section('content')
<div class="container-fluid py-3">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 small">
            @if(isset($enquiry))
                <li class="breadcrumb-item"><a href="{{ route('enquiries.index') }}">Enquiries</a></li>
                <li class="breadcrumb-item"><a href="{{ route('enquiries.files', $enquiry) }}">{{ $enquiry->project_name }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('enquiries.files.quotation', $enquiry) }}">Budget & Quotation</a></li>
                <li class="breadcrumb-item"><a href="{{ route('enquiries.quotes.index', $enquiry) }}">Quotes</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Quote</li>
            @else
                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}">{{ $project->name }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('projects.quotation.index', $project) }}">Budget & Quotation</a></li>
                <li class="breadcrumb-item"><a href="{{ route('quotes.index', $project) }}">Quotes</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Quote</li>
            @endif
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0 text-dark fw-semibold">Create Quote</h4>
        <a href="{{ isset($enquiry) ? route('enquiries.quotes.index', $enquiry) : route('quotes.index', $project) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>

    <div class="card shadow-sm rounded-3">
        <div class="card-body px-3 py-3">
            <form action="{{ isset($enquiry) ? route('enquiries.quotes.store', $enquiry) : route('quotes.store', $project) }}" method="POST" id="quoteForm" autocomplete="off" novalidate>
                @csrf
                @if(isset($project))
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                @elseif(isset($enquiry))
                <input type="hidden" name="enquiry_id" value="{{ $enquiry->id }}">
                @endif

                <!-- Customer Information Section -->
                <div class="mb-4">
                    <h5 class="card-title mb-3 text-muted fw-semibold">Customer Information</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="customer_name" class="form-label fw-medium">Customer Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name"
                                   value="{{ old('customer_name') }}{{ isset($enquiry) ? $enquiry->client_name : $project->client_name }}" required autocomplete="off" autofocus>
                            @error('customer_name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="customer_location" class="form-label fw-medium">Customer Location</label>
                            <input type="text" class="form-control" id="customer_location" name="customer_location"
                                   value="{{ old('customer_location') }}{{ isset($enquiry) ? $enquiry->venue : $project->venue }}" autocomplete="off">
                        </div>
                    </div>
                </div>

                <!-- Project Details Section -->
                <div class="mb-4">
                    <h5 class="card-title mb-3 text-muted fw-semibold">Project Details</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="quote_date" class="form-label fw-medium">Quote Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="quote_date" name="quote_date"
                                   value="{{ old('quote_date', now()->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="project_start_date" class="form-label fw-medium">Project Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="project_start_date" name="project_start_date"
                                   value="{{ old('project_start_date') }}" required>
                        </div>
                    </div>
                </div>

                <!-- <div class="mb-4">
                    <label for="reference" class="form-label small fw-semibold">Reference</label>
                    <input type="text" class="form-control form-control-sm" id="reference" name="reference" 
                           value="{{ old('reference') }}" autocomplete="off">
                </div> -->

                <!--  -->

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center py-3 px-4">
                        <h5 class="mb-0 fw-semibold text-dark">Quote Items</h5>
                        <button type="button" class="btn btn-primary d-flex align-items-center gap-2" id="addItem" aria-label="Add Item">
                            <i class="fas fa-plus"></i> Add Item
                        </button>
                    </div>
                    <div class="card-body p-3" style="max-height: 50vh; overflow-y: auto;">
                        <div id="items-container">
                            @if(isset($productionItems) && $productionItems->count())
                                @foreach($productionItems as $itemIndex => $item)
                                    <div class="item-row mb-2 p-2 rounded bg-light border">
                                        <div class="fw-bold mb-1">{{ $item['item_name'] }}</div>
                                        <!-- <div class="mb-2">
                                            <label class="form-label small">Profit Margin (%) for this Item</label>
                                            <input type="number" min="0" max="100" step="0.01" class="form-control form-control-sm item-profit-margin" name="items[{{ $itemIndex }}][item_profit_margin]" value="{{ old('items.'.$itemIndex.'.item_profit_margin', 0) }}">
                                        </div> -->
                                        @if(count($item['particulars']))
                                            <table class="table table-hover mb-3">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="fw-semibold">Particular</th>
                                                        <th class="fw-semibold">Unit</th>
                                                        <th class="fw-semibold">Quantity</th>
                                                        <th class="fw-semibold">Unit Price</th>
                                                        <th class="fw-semibold">Total Cost</th>
                                                        <th class="fw-semibold">Profit Margin (%)</th>
                                                        <th class="fw-semibold">Quote Price</th>
                                                        <th class="fw-semibold">Comment</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($item['particulars'] as $particularIndex => $particular)
                                                        <tr>
                                                            <td>
                                                                <input type="text" class="form-control" name="items[{{ $itemIndex }}][particulars][{{ $particularIndex }}][particular]" value="{{ $particular['particular'] }}" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="items[{{ $itemIndex }}][particulars][{{ $particularIndex }}][unit]" value="{{ $particular['unit'] }}" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="number" step="0.01" class="form-control quantity-input" name="items[{{ $itemIndex }}][particulars][{{ $particularIndex }}][quantity]" value="{{ $particular['quantity'] }}">
                                                            </td>
                                                            <td>
                                                                <input type="number" step="0.01" class="form-control unit-price-input" name="items[{{ $itemIndex }}][particulars][{{ $particularIndex }}][unit_price]" value="{{ $particular['unit_price'] ?? 0 }}">
                                                            </td>
                                                            <td>
                                                                <input type="number" step="0.01" class="form-control total-cost" name="items[{{ $itemIndex }}][particulars][{{ $particularIndex }}][total_cost]" value="{{ ($particular['quantity'] ?? 0) * ($particular['unit_price'] ?? 0) }}" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="number" min="0" max="100" step="0.01" class="form-control profit-margin-input" name="items[{{ $itemIndex }}][particulars][{{ $particularIndex }}][profit_margin]" value="{{ old('items.'.$itemIndex.'.particulars.'.$particularIndex.'.profit_margin', 0) }}">
                                                            </td>
                                                            <td>
                                                                <input type="number" step="0.01" class="form-control quote-price" name="items[{{ $itemIndex }}][particulars][{{ $particularIndex }}][quote_price]" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="items[{{ $itemIndex }}][particulars][{{ $particularIndex }}][comment]" value="{{ $particular['comment'] }}">
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
                                <div class="item-row mb-3 p-3 rounded bg-white border" data-index="0">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label fw-medium">Description</label>
                                            <input type="text" class="form-control" name="items[0][description]" placeholder="Description" required autocomplete="off" autofocus>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label fw-medium">Days</label>
                                            <input type="number" min="1" class="form-control days" name="items[0][days]" value="1" aria-label="Days">
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label fw-medium">Qty</label>
                                            <input type="number" min="0.01" step="0.01" class="form-control quantity" name="items[0][quantity]" value="1" required aria-label="Quantity">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-medium">Unit Price</label>
                                            <div class="input-group">
                                                <span class="input-group-text">KES</span>
                                                <input type="number" min="0" step="0.01" class="form-control unit-price" name="items[0][unit_price]" required aria-label="Unit Price">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-medium">Total Cost</label>
                                            <div class="input-group">
                                                <span class="input-group-text">KES</span>
                                                <input type="number" step="0.01" class="form-control total-cost" name="items[0][total_cost]" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label fw-medium">Profit %</label>
                                            <input type="number" min="0" max="100" step="0.01" class="form-control profit-margin" name="items[0][profit_margin]" value="" placeholder="Enter margin %">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-medium">Quote Price</label>
                                            <div class="input-group">
                                                <span class="input-group-text">KES</span>
                                                <input type="number" step="0.01" class="form-control quote-price" name="items[0][quote_price]" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-12 d-flex justify-content-end mt-3">
                                            <button type="button" class="btn btn-outline-danger remove-item" disabled title="Cannot remove last item" aria-label="Remove Item">
                                                <i class="fas fa-trash me-2"></i> Remove
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
                                                    <input type="text" class="form-control form-control-sm" name="hire_items[{{ $hireIndex }}][description]" value="{{ $hireItem['particular'] }}" readonly>
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label small">Days</label>
                                                    <input type="number" min="1" class="form-control form-control-sm days" name="hire_items[{{ $hireIndex }}][days]" value="1">
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label small">Qty</label>
                                                    <input type="number" min="0.01" step="0.01" class="form-control form-control-sm quantity" name="hire_items[{{ $hireIndex }}][quantity]" value="{{ $hireItem['quantity'] ?? 1 }}" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small">Unit Price</label>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">KES</span>
                                                        <input type="number" min="0" step="0.01" class="form-control unit-price" name="hire_items[{{ $hireIndex }}][unit_price]" value="{{ $hireItem['unit_price'] ?? 0 }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small">Total Cost</label>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">KES</span>
                                                        <input type="number" step="0.01" class="form-control total-cost" name="hire_items[{{ $hireIndex }}][total_cost]" value="{{ ($hireItem['quantity'] ?? 1) * ($hireItem['unit_price'] ?? 0) }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label small">Profit %</label>
                                                    <input type="number" min="0" max="100" step="0.01" class="form-control form-control-sm profit-margin" name="hire_items[{{ $hireIndex }}][profit_margin]" value="" placeholder="Enter margin %">
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
                                                    <input type="text" class="form-control form-control-sm" name="hire_items[{{ $hireIndex }}][comment]" value="{{ $hireItem['comment'] ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ isset($enquiry) ? route('enquiries.quotes.index', $enquiry) : route('quotes.index', $project) }}" class="btn btn-outline-secondary d-flex align-items-center gap-2 px-4 py-2">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary d-flex align-items-center gap-2 px-4 py-2">
                        <i class="fas fa-save"></i> Save Quote
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

        // Calculate total as quantity × unit_price × (1 + profit_margin/100)
        // Unit price remains unchanged, profit margin is applied to get the final selling price
        const totalCost = quantity * unitPrice;
        const quotePrice = quantity * unitPrice * (1 + profitMargin / 100);

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
                    <input type="number" min="0" max="100" step="0.01" class="form-control form-control-sm profit-margin" name="items[${itemCount}][profit_margin]" value="" placeholder="Enter margin %">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Quote Price</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">KES</span>
                        <input type="number" step="0.01" class="form-control quote-price" name="items[${itemCount}][quote_price]" readonly>
                    </div>
                </div>
                <div class="col-md-12 d-flex justify-content-end mt-2">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-item" disabled title="Cannot remove last item" aria-label="Remove Item">
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
/* Compact Design Styles */
.container-fluid {
    padding-left: 1rem;
    padding-right: 1rem;
}

.card-body {
    padding: 1rem !important;
}

.item-row {
    transition: all 0.15s ease-in-out;
    border: 1px solid #e9ecef;
    margin-bottom: 0.5rem !important;
    padding: 0.75rem !important;
}

.item-row:hover {
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.item-row:focus-within {
    border-color: #007bff;
    box-shadow: 0 0 0 0.15rem rgba(0,123,255,0.25);
}

.form-label {
    margin-bottom: 0.25rem;
    font-weight: 500;
    font-size: 0.875rem;
}

.form-control-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.card {
    border-radius: 0.375rem;
    margin-bottom: 1rem;
}

.card-header {
    padding: 0.75rem 1rem !important;
}

.btn {
    border-radius: 0.25rem;
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

/* Reduce spacing for compact layout */
.mb-3 { margin-bottom: 0.75rem !important; }
.mb-4 { margin-bottom: 1rem !important; }
.mt-3 { margin-top: 0.75rem !important; }
.mt-4 { margin-top: 1rem !important; }
.p-3 { padding: 0.75rem !important; }
.p-4 { padding: 1rem !important; }

.remove-item {
    opacity: 0.7;
    transition: all 0.2s ease;
}
.remove-item:hover:not(:disabled) {
    opacity: 1;
    transform: scale(1.05);
}
.remove-item:disabled {
    cursor: not-allowed;
    opacity: 0.4;
}

.table th {
    border-top: none;
    font-size: 0.9rem;
}
.table td {
    vertical-align: middle;
}
</style>
@endsection