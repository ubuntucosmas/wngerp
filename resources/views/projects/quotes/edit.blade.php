@extends('layouts.master')

@section('title', "Edit Quote #{$quote->id}")

@section('content')
<div class="container py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
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
            <h2 class="mb-0">Edit Quote #{{ $quote->id }}</h2>
        </div>
        <a href="{{ isset($enquiry) ? route('enquiries.quotes.show', ['enquiry' => $enquiry->id, 'quote' => $quote->id]) : route('quotes.show', ['project' => $project->id, 'quote' => $quote->id]) }}" class="btn btn-primary">
            <i class="bi bi-arrow-left me-2"></i>Back to Quote
        </a>
    </div>

    <div class="card shadow-sm rounded-3">
        <div class="card-body px-3 py-4">
            <form action="{{ isset($enquiry) ? route('enquiries.quotes.update', ['enquiry' => $enquiry->id, 'quote' => $quote->id]) : route('quotes.update', ['project' => $project->id, 'quote' => $quote->id]) }}" method="POST" id="quoteForm" autocomplete="off">
                @csrf
                @method('PUT')
                @if(isset($project))
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                @elseif(isset($enquiry))
                <input type="hidden" name="enquiry_id" value="{{ $enquiry->id }}">
                @endif
                
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="customer_name" class="form-label small fw-semibold">Customer Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="customer_name" name="customer_name"
                               value="{{ old('customer_name', $quote->customer_name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="customer_location" class="form-label small fw-semibold">Customer Location</label>
                        <input type="text" class="form-control form-control-sm" id="customer_location" name="customer_location"
                               value="{{ old('customer_location', $quote->customer_location) }}">
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="attention" class="form-label small fw-semibold">Attention</label>
                        <input type="text" class="form-control form-control-sm" id="attention" name="attention"
                               value="{{ old('attention', $quote->attention) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="quote_date" class="form-label small fw-semibold">Quote Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control form-control-sm" id="quote_date" name="quote_date"
                               value="{{ old('quote_date', $quote->quote_date->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="project_start_date" class="form-label small fw-semibold">Project Start Date</label>
                        <input type="date" class="form-control form-control-sm" id="project_start_date" name="project_start_date"
                               value="{{ old('project_start_date', $quote->project_start_date?->format('Y-m-d') ?? '') }}">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="reference" class="form-label small fw-semibold">Reference</label>
                    <input type="text" class="form-control form-control-sm" id="reference" name="reference"
                           value="{{ old('reference', $quote->reference) }}">
                </div>



                <div class="card rounded-3 shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center py-2 px-3">
                        <h5 class="mb-0 fs-6 fw-semibold">Items</h5>
                        <button type="button" class="btn btn-sm btn-primary d-flex align-items-center gap-1" id="addItem" aria-label="Add Item">
                            <i class="fas fa-plus"></i> Add Item
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
                                            <input type="number" min="0" max="100" step="0.01" class="form-control form-control-sm profit-margin" name="items[{{ $index }}][profit_margin]" value="{{ $item->profit_margin ?? 0 }}">
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
                                            <input type="number" min="0" max="100" step="0.01" class="form-control form-control-sm profit-margin" name="hire_items[{{ $hireIndex }}][profit_margin]" value="{{ $hireItem->profit_margin ?? 0 }}">
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

                <div class="d-flex justify-content-between">
                    <a href="{{ isset($enquiry) ? route('enquiries.quotes.show', ['enquiry' => $enquiry->id, 'quote' => $quote->id]) : route('quotes.show', ['project' => $project->id, 'quote' => $quote->id]) }}"
                       class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
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
        
        const totalCost = quantity * unitPrice;
        const quotePrice = totalCost * (1 + profitMargin / 100);
        
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