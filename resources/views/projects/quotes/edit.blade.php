@extends('layouts.master')

@section('title', "Edit Quote #{$quote->id}")

@section('content')
<div class="container py-3">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
        <div>
            <h4 class="fw-semibold mb-0">Edit Quote #{{ $quote->id }}</h4>
            <nav aria-label="breadcrumb" class="mb-1">
                <ol class="breadcrumb bg-transparent p-0 small mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('quotes.index', $project) }}" class="text-decoration-none text-primary">Quotes</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('quotes.show', ['project' => $project->id, 'quote' => $quote->id]) }}" class="text-decoration-none text-primary">#{{ $quote->id }}</a>
                    </li>
                    <li class="breadcrumb-item active text-secondary" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card shadow-sm rounded-3">
        <div class="card-body px-3 py-4">
            <form action="{{ route('quotes.update', ['project' => $project->id, 'quote' => $quote->id]) }}" method="POST" id="quoteForm" autocomplete="off">
                @csrf
                @method('PUT')
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                
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
                                <div class="item-row mb-2 p-2 rounded bg-light border d-flex align-items-center gap-2" data-index="{{ $index }}">
                                    <input type="text" class="form-control form-control-sm flex-grow-1" name="items[{{ $index }}][description]"
                                           placeholder="Description" value="{{ $item->description }}" required>

                                    <input type="number" min="1" class="form-control form-control-sm days" style="width: 4.5rem;" 
                                           name="items[{{ $index }}][days]" value="{{ $item->days }}">

                                    <input type="number" min="0.01" step="0.01" class="form-control form-control-sm quantity" style="width: 6rem;" 
                                           name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" required>

                                    <div class="input-group input-group-sm" style="width: 8rem;">
                                        <span class="input-group-text">KES</span>
                                        <input type="number" min="0" step="0.01" class="form-control unit-price" name="items[{{ $index }}][unit_price]"
                                               value="{{ $item->unit_price }}" required>
                                    </div>

                                    <button type="button" class="btn btn-outline-danger btn-sm remove-item" title="Delete Item">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                                @php $itemCount = $index + 1; @endphp
                            @endforeach

                            @if($quote->lineItems->isEmpty())
                                <div class="item-row mb-2 p-2 rounded bg-light border d-flex align-items-center gap-2" data-index="0">
                                    <input type="text" class="form-control form-control-sm flex-grow-1" name="items[0][description]" placeholder="Description" required>

                                    <input type="number" min="1" class="form-control form-control-sm days" style="width: 4.5rem;" name="items[0][days]" value="1">

                                    <input type="number" min="0.01" step="0.01" class="form-control form-control-sm quantity" style="width: 6rem;" 
                                           name="items[0][quantity]" value="1" required>

                                    <div class="input-group input-group-sm" style="width: 8rem;">
                                        <span class="input-group-text">KES</span>
                                        <input type="number" min="0" step="0.01" class="form-control unit-price" name="items[0][unit_price]" required>
                                    </div>

                                    <button type="button" class="btn btn-outline-danger btn-sm remove-item" disabled title="Cannot remove last item">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('quotes.show', ['project' => $project->id, 'quote' => $quote->id]) }}"
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
    
    // Add new item row
    document.getElementById('addItem').addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.className = 'item-row mb-2 p-2 rounded bg-light border d-flex align-items-center gap-2';
        newRow.dataset.index = itemCount;

        newRow.innerHTML = `
            <input type="text" class="form-control form-control-sm flex-grow-1" name="items[${itemCount}][description]" placeholder="Description" required>
            <input type="number" min="1" class="form-control form-control-sm days" style="width: 4.5rem;" name="items[${itemCount}][days]" value="1">
            <input type="number" min="0.01" step="0.01" class="form-control form-control-sm quantity" style="width: 6rem;" name="items[${itemCount}][quantity]" value="1" required>
            <div class="input-group input-group-sm" style="width: 8rem;">
                <span class="input-group-text">KES</span>
                <input type="number" min="0" step="0.01" class="form-control unit-price" name="items[${itemCount}][unit_price]" required>
            </div>
            <button type="button" class="btn btn-outline-danger btn-sm remove-item" title="Delete Item">
                <i class="bi bi-x-lg"></i>
            </button>
        `;

        itemsContainer.appendChild(newRow);
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
        removeButtons.forEach(btn => btn.disabled = items.length <= 1);
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
    /* Smaller vertical spacing for compact layout */
    form .form-label {
        margin-bottom: 0.2rem;
    }
    form .form-control-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.85rem;
    }
    .card-header h5 {
        font-size: 1rem;
        margin-bottom: 0;
    }
</style>
@endsection