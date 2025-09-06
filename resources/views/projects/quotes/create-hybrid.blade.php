@extends('layouts.master')

@section('title', 'Create Hybrid Quote')

@section('content')
<div class="container-fluid py-3">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 small">
            @if(isset($enquiry))
                <li class="breadcrumb-item"><a href="{{ route('enquiries.index') }}">Enquiries</a></li>
                <li class="breadcrumb-item"><a href="{{ route('enquiries.files', $enquiry) }}">{{ $enquiry->project_name }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('enquiries.files.quotation', $enquiry) }}">Budget & Quotation</a></li>
                <li class="breadcrumb-item"><a href="{{ route('enquiries.quotes.index', $enquiry) }}">Quotes</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Hybrid Quote</li>
            @else
                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}">{{ $project->name }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('projects.quotation.index', $project) }}">Budget & Quotation</a></li>
                <li class="breadcrumb-item"><a href="{{ route('quotes.index', $project) }}">Quotes</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Hybrid Quote</li>
            @endif
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0 text-dark fw-semibold">Create Hybrid Quote</h4>
        <a href="{{ isset($enquiry) ? route('enquiries.quotes.index', $enquiry) : route('quotes.index', $project) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-0 py-2">
                    <h5 class="mb-0 fw-semibold text-dark small">
                        <i class="fas fa-file-invoice-dollar me-1 text-primary"></i>
                        Quote for {{ isset($enquiry) ? $enquiry->project_name : $project->name }}
                    </h5>
                </div>
                
                <div class="card-body p-3">
                    <!-- Cost Summary Dashboard -->
                    <div class="mb-3">
                        <h6 class="card-title mb-2 text-muted fw-semibold small">Cost Summary</h6>
                        <div class="row g-2">
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm bg-light">
                                    <div class="card-body text-center p-2">
                                        <div class="text-primary mb-1">
                                            <i class="fas fa-dollar-sign"></i>
                                        </div>
                                        <small class="text-muted d-block">Internal Cost</small>
                                        <div class="fw-bold">KES{{ number_format($hybridData['total_internal_cost'], 2) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm bg-light">
                                    <div class="card-body text-center p-2">
                                        <div class="text-success mb-1">
                                            <i class="fas fa-tags"></i>
                                        </div>
                                        <small class="text-muted d-block">Suggested Price</small>
                                        <div class="fw-bold">KES{{ number_format($hybridData['suggested_total_price'], 2) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm bg-light">
                                    <div class="card-body text-center p-2">
                                        <div class="text-warning mb-1">
                                            <i class="fas fa-chart-line"></i>
                                        </div>
                                        <small class="text-muted d-block">Profit Margin</small>
                                        <div class="fw-bold">{{ $hybridData['total_internal_cost'] > 0 ? number_format((($hybridData['suggested_total_price'] - $hybridData['total_internal_cost']) / $hybridData['total_internal_cost']) * 100, 1) : 0 }}%</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm bg-light">
                                    <div class="card-body text-center p-2">
                                        <div class="text-info mb-1">
                                            <i class="fas fa-list"></i>
                                        </div>
                                        <small class="text-muted d-block">Total Items</small>
                                        <div class="fw-bold">{{ $hybridData['cost_summary']['item_count'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ isset($enquiry) ? route('enquiries.quotes.store', $enquiry) : route('quotes.store', $project) }}" method="POST" id="hybridQuoteForm">
                        @csrf
                        <input type="hidden" name="project_budget_id" value="{{ $hybridData['budget']->id }}">

                        <!-- Customer Information Section -->
                        <div class="mb-3">
                            <h6 class="card-title mb-2 text-muted fw-semibold small">Customer Information</h6>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label for="customer_name" class="form-label fw-medium small">Customer Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" name="customer_name" required
                                           value="{{ old('customer_name', isset($enquiry) ? $enquiry->client_name ?? '' : $project->client_name ?? '') }}">
                                    @error('customer_name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Project Details Section -->
                        <div class="mb-3">
                            <h6 class="card-title mb-2 text-muted fw-semibold small">Project Details</h6>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label for="quote_date" class="form-label fw-medium small">Quote Date</label>
                                    <input type="date" class="form-control form-control-sm" name="quote_date"
                                           value="{{ old('quote_date', date('Y-m-d')) }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="project_start_date" class="form-label fw-medium small">Project Start Date</label>
                                    <input type="date" class="form-control form-control-sm" name="project_start_date"
                                           value="{{ old('project_start_date') }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="reference" class="form-label fw-medium small">Reference</label>
                                    <input type="text" class="form-control form-control-sm" name="reference"
                                           value="{{ old('reference') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Customization Options -->
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-header bg-light border-0 py-2">
                                <h6 class="mb-0 fw-semibold text-dark small"><i class="fas fa-cogs me-1 text-primary"></i>Quote Customization Options</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label for="consolidation_level" class="form-label fw-medium small">Detail Level</label>
                                        <select class="form-select form-select-sm" name="consolidation_level" id="consolidationLevel">
                                            @foreach($hybridData['customization_options']['consolidation_levels'] as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="pricing_strategy" class="form-label fw-medium small">Pricing Strategy</label>
                                        <select class="form-select form-select-sm" name="pricing_strategy" id="pricingStrategy">
                                            @foreach($hybridData['customization_options']['pricing_strategies'] as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="description_style" class="form-label fw-medium small">Description Style</label>
                                        <select class="form-select form-select-sm" name="description_style" id="descriptionStyle">
                                            @foreach($hybridData['customization_options']['description_styles'] as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hybrid Quote Items -->
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center py-2 px-3">
                                <h6 class="mb-0 fw-semibold text-dark small"><i class="fas fa-list me-1 text-primary"></i>Quote Items (Customizable)</h6>
                                <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-1" onclick="addCustomItem()">
                                    <i class="fas fa-plus"></i>Add Item
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="quoteItemsContainer">
                                    @foreach($hybridData['suggested_items'] as $index => $item)
                                        <div class="quote-item-row border rounded p-2 mb-2" data-index="{{ $index }}">
                                            <div class="row align-items-center g-2">
                                                <div class="col-md-4">
                                                    <label class="form-label small">Description</label>
                                                    <textarea class="form-control form-control-sm" name="items[{{ $index }}][description]" rows="1"
                                                              placeholder="Enter client-friendly description">{{ $item['suggested_description'] }}</textarea>
                                                    <small class="text-muted d-block">Cost: ${{ number_format($item['total_internal_cost'], 2) }}</small>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small">Quantity</label>
                                                    <input type="number" class="form-control form-control-sm item-quantity" name="items[{{ $index }}][quantity]"
                                                           value="1" min="0.01" step="0.01">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small">Unit Price</label>
                                                    <input type="number" class="form-control form-control-sm item-unit-price" name="items[{{ $index }}][unit_price]"
                                                           value="{{ $item['suggested_quote_price'] }}" min="0" step="0.01">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small">Total</label>
                                                    <input type="number" class="form-control form-control-sm item-total" name="items[{{ $index }}][total_cost]"
                                                           value="{{ $item['suggested_quote_price'] }}" readonly>
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label small">Margin %</label>
                                                    <input type="number" class="form-control form-control-sm profit-margin" name="items[{{ $index }}][profit_margin]"
                                                           value="" min="0" max="100" step="0.1" placeholder="%">
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label small">&nbsp;</label>
                                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeQuoteItem({{ $index }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <!-- Hidden fields for source tracking -->
                                            <input type="hidden" name="items[{{ $index }}][source_items]" value="{{ implode(',', $item['source_items']) }}">
                                            <input type="hidden" name="items[{{ $index }}][category_type]" value="{{ $item['category_type'] }}">
                                            
                                            <!-- Source Items Reference (Collapsible) -->
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="collapse" 
                                                        data-bs-target="#sourceItems{{ $index }}">
                                                    <i class="fas fa-eye me-1"></i>View Source Items ({{ count($item['source_items']) }} items)
                                                </button>
                                                <div class="collapse mt-2" id="sourceItems{{ $index }}">
                                                    <div class="bg-light p-2 rounded">
                                                        <small class="text-muted">
                                                            <strong>Source Budget Items:</strong><br>
                                                            @foreach($hybridData['raw_categories'] as $category => $categoryData)
                                                                @foreach($categoryData['items'] as $sourceItem)
                                                                    @if(in_array($sourceItem['id'], $item['source_items']))
                                                                        • {{ $sourceItem['particular'] }} ({{ $sourceItem['quantity'] }} {{ $sourceItem['unit'] }} @ ${{ $sourceItem['unit_price'] }})<br>
                                                                    @endif
                                                                @endforeach
                                                            @endforeach
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <!-- Quote Totals -->
                                <div class="row mt-4">
                                    <div class="col-md-8"></div>
                                    <div class="col-md-4">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <strong>Subtotal:</strong>
                                                    <span id="quoteSubtotal">KES{{ number_format($hybridData['suggested_total_price'], 2) }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <strong>VAT (16%):</strong>
                                                    <span id="quoteVat">KES{{ number_format($hybridData['suggested_total_price'] * 0.16, 2) }}</span>
                                                </div>
                                                <hr>
                                                <div class="d-flex justify-content-between">
                                                    <strong>Total:</strong>
                                                    <strong id="quoteTotal">KES{{ number_format($hybridData['suggested_total_price'] * 1.16, 2) }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ isset($enquiry) ? route('enquiries.quotes.index', $enquiry) : route('quotes.index', $project) }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                                <i class="fas fa-save"></i> Create Quote
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let itemIndex = {{ count($hybridData['suggested_items']) }};

// Calculate totals when quantities or prices change
document.addEventListener('DOMContentLoaded', function() {
    attachCalculationListeners();
    calculateQuoteTotals();
});

function attachCalculationListeners() {
    document.querySelectorAll('.item-quantity, .item-unit-price, .profit-margin').forEach(input => {
        input.addEventListener('input', function() {
            calculateItemTotal(this.closest('.quote-item-row'));
            calculateQuoteTotals();
        });
    });
}

function calculateItemTotal(row) {
    const quantity = parseFloat(row.querySelector('.item-quantity').value) || 0;
    const unitPriceInput = row.querySelector('.item-unit-price');
    const profitMarginInput = row.querySelector('.profit-margin');
    const totalInput = row.querySelector('.item-total');

    const unitPrice = parseFloat(unitPriceInput.value) || 0;
    const profitMargin = parseFloat(profitMarginInput.value) || 0;

    // Calculate total as quantity × unit_price × (1 + profit_margin/100)
    // Unit price remains unchanged, profit margin is applied to get the final selling price
    const total = quantity * unitPrice * (1 + profitMargin / 100);
    totalInput.value = total.toFixed(2);
}

function calculateQuoteTotals() {
    let subtotal = 0;
    document.querySelectorAll('.item-total').forEach(input => {
        subtotal += parseFloat(input.value) || 0;
    });
    
    const vat = subtotal * 0.16;
    const total = subtotal + vat;
    
    document.getElementById('quoteSubtotal').textContent = 'KES-' + subtotal.toFixed(2);
    document.getElementById('quoteVat').textContent = 'KES-' + vat.toFixed(2);
    document.getElementById('quoteTotal').textContent = 'KES-' + total.toFixed(2);
}

function addCustomItem() {
    const container = document.getElementById('quoteItemsContainer');
    const newItem = `
        <div class="quote-item-row border rounded p-2 mb-2" data-index="${itemIndex}">
            <div class="row align-items-center g-2">
                <div class="col-md-4">
                    <label class="form-label small">Description</label>
                    <textarea class="form-control form-control-sm" name="items[${itemIndex}][description]" rows="1"
                              placeholder="Enter custom item description"></textarea>
                    <small class="text-muted d-block">Cost: $0.00</small>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Quantity</label>
                    <input type="number" class="form-control form-control-sm item-quantity" name="items[${itemIndex}][quantity]"
                           value="1" min="0.01" step="0.01">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Unit Price</label>
                    <input type="number" class="form-control form-control-sm item-unit-price" name="items[${itemIndex}][unit_price]"
                           value="0" min="0" step="0.01">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Total</label>
                    <input type="number" class="form-control form-control-sm item-total" name="items[${itemIndex}][total_cost]"
                           value="0" readonly>
                </div>
                <div class="col-md-1">
                    <label class="form-label small">Margin %</label>
                    <input type="number" class="form-control form-control-sm profit-margin" name="items[${itemIndex}][profit_margin]"
                           value="" min="0" max="100" step="0.1" placeholder="%">
                </div>
                <div class="col-md-1">
                    <label class="form-label small">&nbsp;</label>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeQuoteItem(${itemIndex})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <input type="hidden" name="items[${itemIndex}][category_type]" value="Custom">
        </div>
    `;

    container.insertAdjacentHTML('beforeend', newItem);
    attachCalculationListeners();
    itemIndex++;
}

function removeQuoteItem(index) {
    const row = document.querySelector(`[data-index="${index}"]`);
    if (row) {
        row.remove();
        calculateQuoteTotals();
    }
}

// Update descriptions based on style selection
document.getElementById('descriptionStyle').addEventListener('change', function() {
    // This could trigger AJAX to regenerate descriptions based on selected style
    console.log('Description style changed to:', this.value);
});

// Update pricing based on strategy selection
document.getElementById('pricingStrategy').addEventListener('change', function() {
    // This could trigger AJAX to recalculate prices based on selected strategy
    console.log('Pricing strategy changed to:', this.value);
});
</script>

<style>
/* Compact Design Styles */
.container-fluid {
    padding-left: 1rem;
    padding-right: 1rem;
}

.card-body {
    padding: 1rem !important;
}

.quote-item-row {
    transition: all 0.15s ease-in-out;
    border: 1px solid #e9ecef;
    margin-bottom: 0.5rem !important;
    padding: 0.75rem !important;
}

.quote-item-row:hover {
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.quote-item-row:focus-within {
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
</style>
@endsection
