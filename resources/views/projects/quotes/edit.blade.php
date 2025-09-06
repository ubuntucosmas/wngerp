@extends('layouts.master')

@section('title', "Edit Quote #{$quote->id}")

@section('content')
<div class="container-fluid py-3">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 small">
            @if(isset($enquiry))
                <li class="breadcrumb-item"><a href="{{ route('enquiries.index') }}">Enquiries</a></li>
                <li class="breadcrumb-item"><a href="{{ route('enquiries.files', $enquiry) }}">{{ $enquiry->project_name }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('enquiries.files.quotation', $enquiry) }}">Budget & Quotation</a></li>
                <li class="breadcrumb-item"><a href="{{ route('enquiries.quotes.index', $enquiry) }}">Quotes</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Quote #{{ $quote->id }}</li>
            @else
                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}">{{ $project->name }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('projects.quotation.index', $project) }}">Budget & Quotation</a></li>
                <li class="breadcrumb-item"><a href="{{ route('quotes.index', $project) }}">Quotes</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Quote #{{ $quote->id }}</li>
            @endif
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0 text-dark fw-semibold">Edit Quote #{{ $quote->id }}</h4>
        <a href="{{ isset($enquiry) ? route('enquiries.quotes.show', ['enquiry' => $enquiry->id, 'quote' => $quote->id]) : route('quotes.show', ['project' => $project->id, 'quote' => $quote->id]) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>

    <div class="card shadow-sm rounded-3">
        <div class="card-body px-3 py-3">
            <form action="{{ isset($enquiry) ? route('enquiries.quotes.update', ['enquiry' => $enquiry->id, 'quote' => $quote->id]) : route('quotes.update', ['project' => $project->id, 'quote' => $quote->id]) }}" method="POST" id="quoteForm" autocomplete="off">
                @csrf
                @method('PUT')
                @if(isset($project))
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                @elseif(isset($enquiry))
                <input type="hidden" name="enquiry_id" value="{{ $enquiry->id }}">
                @endif

                <!-- Customer Information Section -->
                <div class="mb-3">
                    <h6 class="card-title mb-2 text-muted fw-semibold small">Customer Information</h6>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label for="customer_name" class="form-label fw-medium small">Customer Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="customer_name" name="customer_name"
                                   value="{{ old('customer_name', $quote->customer_name) }}" required>
                            @error('customer_name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="customer_location" class="form-label fw-medium small">Customer Location</label>
                            <input type="text" class="form-control form-control-sm" id="customer_location" name="customer_location"
                                   value="{{ old('customer_location', $quote->customer_location) }}">
                        </div>
                    </div>
                </div>

                <!-- Project Details Section -->
                <div class="mb-3">
                    <h6 class="card-title mb-2 text-muted fw-semibold small">Project Details</h6>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label for="quote_date" class="form-label fw-medium small">Quote Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control form-control-sm" id="quote_date" name="quote_date"
                                   value="{{ old('quote_date', $quote->quote_date->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="project_start_date" class="form-label fw-medium small">Project Start Date</label>
                            <input type="date" class="form-control form-control-sm" id="project_start_date" name="project_start_date"
                                   value="{{ old('project_start_date', $quote->project_start_date?->format('Y-m-d') ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="reference" class="form-label fw-medium small">Reference</label>
                            <input type="text" class="form-control form-control-sm" id="reference" name="reference"
                                   value="{{ old('reference', $quote->reference) }}">
                        </div>
                    </div>
                </div>



                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center py-2 px-3">
                        <h6 class="mb-0 fw-semibold text-dark small"><i class="fas fa-list me-1 text-primary"></i>Quote Items</h6>
                        <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-1" id="addItem" aria-label="Add Item">
                            <i class="fas fa-plus"></i>Add Item
                        </button>
                    </div>
                    <div class="card-body p-3">
                        <div id="items-container">
                            @php $itemCount = 0; @endphp
                            @foreach($quote->lineItems as $index => $item)
                                <div class="item-row mb-2 p-2 rounded bg-light border" data-index="{{ $index }}">
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <label class="form-label small">Description</label>
                                            <input type="text" class="form-control form-control-sm" name="items[{{ $index }}][description]"
                                                   placeholder="Description" value="{{ $item->description }}" required>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label small">Days</label>
                                            <input type="number" min="1" class="form-control form-control-sm days" 
                                                   name="items[{{ $index }}][days]" value="{{ $item->days }}">
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label small">Qty</label>
                                            <input type="number" min="0.01" step="0.01" class="form-control form-control-sm quantity" 
                                                   name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small">Unit Price</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">KES</span>
                                                <input type="number" min="0" step="0.01" class="form-control unit-price" name="items[{{ $index }}][unit_price]"
                                                       value="{{ $item->unit_price }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small">Total Cost</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">KES</span>
                                                <input type="number" step="0.01" class="form-control total-cost" name="items[{{ $index }}][total_cost]" value="{{ $item->total_cost ?? ($item->quantity * $item->unit_price) }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label small">Profit %</label>
                                            <input type="number" min="0" max="100" step="0.01" class="form-control form-control-sm profit-margin" name="items[{{ $index }}][profit_margin]" value="{{ $item->profit_margin ?? '' }}" placeholder="Enter margin %">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small">Quote Price</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">KES</span>
                                                <input type="number" step="0.01" class="form-control quote-price" name="items[{{ $index }}][quote_price]" value="{{ $item->quote_price ?? $item->total }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-12 d-flex justify-content-end mt-2">
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-item" title="Delete Item">
                                                <i class="fas fa-trash"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @php $itemCount = $index + 1; @endphp
                            @endforeach

                            @if($quote->lineItems->isEmpty())
                                <div class="item-row mb-2 p-2 rounded bg-light border" data-index="0">
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <label class="form-label small">Description</label>
                                            <input type="text" class="form-control form-control-sm" name="items[0][description]" placeholder="Description" required>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label small">Days</label>
                                            <input type="number" min="1" class="form-control form-control-sm days" name="items[0][days]" value="1">
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label small">Qty</label>
                                            <input type="number" min="0.01" step="0.01" class="form-control form-control-sm quantity" name="items[0][quantity]" value="1" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small">Unit Price</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">KES</span>
                                                <input type="number" min="0" step="0.01" class="form-control unit-price" name="items[0][unit_price]" required>
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
                                            <input type="number" min="0" max="100" step="0.01" class="form-control form-control-sm profit-margin" name="items[0][profit_margin]" value="" placeholder="Enter margin %">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small">Quote Price</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">KES</span>
                                                <input type="number" step="0.01" class="form-control quote-price" name="items[0][quote_price]" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-12 d-flex justify-content-end mt-2">
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-item" disabled title="Cannot remove last item">
                                                <i class="fas fa-trash"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        {{-- Materials for Hire Section --}}
                        @php
                            $hireItems = $quote->lineItems->filter(function($item) {
                                // You may want to use a category field if available, or use a naming convention
                                return str_contains(strtolower($item->description), 'hire');
                            });
                        @endphp
                        @if($hireItems->count())
                        <div class="mt-4">
                            <h6 class="fw-bold text-primary mb-3">Materials for Hire</h6>
                            @foreach($hireItems as $hireIndex => $hireItem)
                                <div class="item-row mb-2 p-2 rounded bg-light border">
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <label class="form-label small">Description</label>
                                            <input type="text" class="form-control form-control-sm" name="hire_items[{{ $hireIndex }}][description]" value="{{ $hireItem->description }}" readonly>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label small">Days</label>
                                            <input type="number" min="1" class="form-control form-control-sm days" name="hire_items[{{ $hireIndex }}][days]" value="{{ $hireItem->days }}">
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label small">Qty</label>
                                            <input type="number" min="0.01" step="0.01" class="form-control form-control-sm quantity" name="hire_items[{{ $hireIndex }}][quantity]" value="{{ $hireItem->quantity }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small">Unit Price</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">KES</span>
                                                <input type="number" min="0" step="0.01" class="form-control unit-price" name="hire_items[{{ $hireIndex }}][unit_price]" value="{{ $hireItem->unit_price }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small">Total Cost</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">KES</span>
                                                <input type="number" step="0.01" class="form-control total-cost" name="hire_items[{{ $hireIndex }}][total_cost]" value="{{ $hireItem->total_cost ?? ($hireItem->quantity * $hireItem->unit_price) }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label small">Profit %</label>
                                            <input type="number" min="0" max="100" step="0.01" class="form-control form-control-sm profit-margin" name="hire_items[{{ $hireIndex }}][profit_margin]" value="{{ $hireItem->profit_margin ?? '' }}" placeholder="Enter margin %">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small">Quote Price</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">KES</span>
                                                <input type="number" step="0.01" class="form-control quote-price" name="hire_items[{{ $hireIndex }}][quote_price]" value="{{ $hireItem->quote_price ?? $hireItem->total }}" readonly>
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

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ isset($enquiry) ? route('enquiries.quotes.show', ['enquiry' => $enquiry->id, 'quote' => $quote->id]) : route('quotes.show', ['project' => $project->id, 'quote' => $quote->id]) }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                        <i class="fas fa-save"></i> Update Quote
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemCount = {{ max($quote->lineItems->count(), 1) }};
    const itemsContainer = document.getElementById('items-container');

    // Function to calculate total cost and quote price
    function calculateCosts(row) {
        const quantity = parseFloat(row.querySelector('.quantity')?.value) || 0;
        const unitPrice = parseFloat(row.querySelector('.unit-price')?.value) || 0;
        const profitMargin = parseFloat(row.querySelector('.profit-margin')?.value) || 0;

        // Calculate total as quantity × unit_price × (1 + profit_margin/100)
        // Unit price remains unchanged, profit margin is applied to get the final selling price
        const totalCost = quantity * unitPrice;
        const quotePrice = quantity * unitPrice * (1 + profitMargin / 100);

        const totalCostInput = row.querySelector('.total-cost');
        const quotePriceInput = row.querySelector('.quote-price');

        if (totalCostInput) totalCostInput.value = totalCost.toFixed(2);
        if (quotePriceInput) quotePriceInput.value = quotePrice.toFixed(2);
    }

    // Add event listeners for existing inputs
    function addCalculationListeners(container) {
        const inputs = container.querySelectorAll('.quantity, .unit-price, .profit-margin');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                const row = this.closest('.item-row');
                if (row) {
                    calculateCosts(row);
                }
            });
        });
    }

    // Initialize calculations for existing items
    addCalculationListeners(itemsContainer);
    
    // Add new item row
    document.getElementById('addItem').addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.className = 'item-row mb-2 p-2 rounded bg-light border';
        newRow.dataset.index = itemCount;

        newRow.innerHTML = `
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="form-label small">Description</label>
                    <input type="text" class="form-control form-control-sm" name="items[${itemCount}][description]" placeholder="Description" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label small">Days</label>
                    <input type="number" min="1" class="form-control form-control-sm days" name="items[${itemCount}][days]" value="1">
                </div>
                <div class="col-md-1">
                    <label class="form-label small">Qty</label>
                    <input type="number" min="0.01" step="0.01" class="form-control form-control-sm quantity" name="items[${itemCount}][quantity]" value="1" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Unit Price</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">KES</span>
                        <input type="number" min="0" step="0.01" class="form-control unit-price" name="items[${itemCount}][unit_price]" required>
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
                    <button type="button" class="btn btn-outline-danger btn-sm remove-item" title="Delete Item">
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
    
    // Remove item row
    itemsContainer.addEventListener('click', function(e) {
        if(e.target.closest('.remove-item')) {
            const items = itemsContainer.querySelectorAll('.item-row');
            if(items.length > 1) {
                e.target.closest('.item-row').remove();
                updateRemoveButtons();
            }
        }
    });

    // Disable remove button if last item
    function updateRemoveButtons() {
        const removeButtons = itemsContainer.querySelectorAll('.remove-item');
        const items = itemsContainer.querySelectorAll('.item-row');
        removeButtons.forEach(btn => {
            btn.disabled = items.length <= 1;
            if(btn.disabled){
                btn.setAttribute('title', 'Cannot remove last item');
            } else {
                btn.setAttribute('title', 'Remove Item');
            }
        });
    }



    updateRemoveButtons();
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