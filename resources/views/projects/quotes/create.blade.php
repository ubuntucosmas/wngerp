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

                <div class="mb-4">
                    <label for="reference" class="form-label small fw-semibold">Reference</label>
                    <input type="text" class="form-control form-control-sm" id="reference" name="reference" 
                           value="{{ old('reference') }}" autocomplete="off">
                </div>

                <div class="card rounded-3 shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center py-2 px-3">
                        <h5 class="mb-0 fs-6 fw-semibold">Items</h5>
                        <button type="button" class="btn btn-sm btn-primary d-flex align-items-center gap-1" id="addItem" aria-label="Add Item">
                            <i class="fas fa-plus"></i> Add Item
                        </button>
                    </div>
                    <div class="card-body p-3" style="max-height: 50vh; overflow-y: auto;">
                        <div id="items-container">
                            <div class="item-row mb-2 p-2 rounded bg-light border d-flex align-items-center gap-2" data-index="0">
                                <input type="text" class="form-control form-control-sm flex-grow-1" name="items[0][description]" placeholder="Description" required autocomplete="off" autofocus>
                                <input type="number" min="1" class="form-control form-control-sm days" style="width: 4.5rem;" name="items[0][days]" value="1" aria-label="Days">
                                <input type="number" min="0.01" step="0.01" class="form-control form-control-sm quantity" style="width: 6rem;" name="items[0][quantity]" value="1" required aria-label="Quantity">
                                <div class="input-group input-group-sm" style="width: 8rem;">
                                    <span class="input-group-text">KES</span>
                                    <input type="number" min="0" step="0.01" class="form-control unit-price" name="items[0][unit_price]" required aria-label="Unit Price">
                                </div>
                                <button type="button" class="btn btn-outline-danger btn-sm remove-item" disabled title="Cannot remove last item" aria-label="Remove Item">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
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

    document.getElementById('addItem').addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.className = 'item-row mb-2 p-2 rounded bg-light border d-flex align-items-center gap-2';
        newRow.dataset.index = itemCount;

        newRow.innerHTML = `
            <input type="text" class="form-control form-control-sm flex-grow-1" name="items[${itemCount}][description]" placeholder="Description" required autocomplete="off">
            <input type="number" min="1" class="form-control form-control-sm days" style="width: 4.5rem;" name="items[${itemCount}][days]" value="1" aria-label="Days">
            <input type="number" min="0.01" step="0.01" class="form-control form-control-sm quantity" style="width: 6rem;" name="items[${itemCount}][quantity]" value="1" required aria-label="Quantity">
            <div class="input-group input-group-sm" style="width: 8rem;">
                <span class="input-group-text">KES</span>
                <input type="number" min="0" step="0.01" class="form-control unit-price" name="items[${itemCount}][unit_price]" required aria-label="Unit Price">
            </div>
            <button type="button" class="btn btn-outline-danger btn-sm remove-item" title="Remove Item" aria-label="Remove Item">
                <i class="fas fa-trash"></i>
            </button>
        `;
        itemsContainer.appendChild(newRow);
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