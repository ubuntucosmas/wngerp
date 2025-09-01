@extends('layouts.master')

@section('title', 'Create Hybrid Quote')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-file-invoice-dollar me-2"></i>
                        Quote for- {{ isset($enquiry) ? $enquiry->project_name : $project->name }}
                    </h4>
                </div>
                
                <div class="card-body">
                    <!-- Cost Summary Dashboard -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5>Internal Cost</h5>
                                    <h3>${{ number_format($hybridData['total_internal_cost'], 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5>Suggested Price</h5>
                                    <h3>${{ number_format($hybridData['suggested_total_price'], 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5>Profit Margin</h5>
                                    <h3>{{ $hybridData['total_internal_cost'] > 0 ? number_format((($hybridData['suggested_total_price'] - $hybridData['total_internal_cost']) / $hybridData['total_internal_cost']) * 100, 1) : 0 }}%</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-secondary text-white">
                                <div class="card-body text-center">
                                    <h5>Total Items</h5>
                                    <h3>{{ $hybridData['cost_summary']['item_count'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ isset($enquiry) ? route('enquiries.quotes.store', $enquiry) : route('quotes.store', $project) }}" method="POST" id="hybridQuoteForm">
                        @csrf
                        <input type="hidden" name="project_budget_id" value="{{ $hybridData['budget']->id }}">
                        
                        <!-- Basic Quote Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="customer_name" class="form-label">Customer Name *</label>
                                <input type="text" class="form-control" name="customer_name" required 
                                       value="{{ old('customer_name', isset($enquiry) ? $enquiry->client_name ?? '' : $project->client_name ?? '') }}">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="quote_date" class="form-label">Quote Date</label>
                                <input type="date" class="form-control" name="quote_date" 
                                       value="{{ old('quote_date', date('Y-m-d')) }}">
                            </div>
                            <div class="col-md-4">
                                <label for="project_start_date" class="form-label">Project Start Date</label>
                                <input type="date" class="form-control" name="project_start_date" 
                                       value="{{ old('project_start_date') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="reference" class="form-label">Reference</label>
                                <input type="text" class="form-control" name="reference" 
                                       value="{{ old('reference') }}">
                            </div>
                        </div>

                        <!-- Customization Options -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5><i class="fas fa-cogs me-2"></i>Quote Customization Options</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="consolidation_level" class="form-label">Detail Level</label>
                                        <select class="form-select" name="consolidation_level" id="consolidationLevel">
                                            @foreach($hybridData['customization_options']['consolidation_levels'] as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="pricing_strategy" class="form-label">Pricing Strategy</label>
                                        <select class="form-select" name="pricing_strategy" id="pricingStrategy">
                                            @foreach($hybridData['customization_options']['pricing_strategies'] as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="description_style" class="form-label">Description Style</label>
                                        <select class="form-select" name="description_style" id="descriptionStyle">
                                            @foreach($hybridData['customization_options']['description_styles'] as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hybrid Quote Items -->
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5><i class="fas fa-list me-2"></i>Quote Items (Customizable)</h5>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addCustomItem()">
                                    <i class="fas fa-plus me-1"></i>Add Custom Item
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="quoteItemsContainer">
                                    @foreach($hybridData['suggested_items'] as $index => $item)
                                        <div class="quote-item-row border rounded p-3 mb-3" data-index="{{ $index }}">
                                            <div class="row align-items-center">
                                                <div class="col-md-4">
                                                    <label class="form-label">Description</label>
                                                    <textarea class="form-control" name="items[{{ $index }}][description]" rows="2" 
                                                              placeholder="Enter client-friendly description">{{ $item['suggested_description'] }}</textarea>
                                                    <small class="text-muted">Internal cost: ${{ number_format($item['total_internal_cost'], 2) }}</small>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Quantity</label>
                                                    <input type="number" class="form-control item-quantity" name="items[{{ $index }}][quantity]" 
                                                           value="1" min="0.01" step="0.01">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Unit Price</label>
                                                    <input type="number" class="form-control item-unit-price" name="items[{{ $index }}][unit_price]" 
                                                           value="{{ $item['suggested_quote_price'] }}" min="0" step="0.01">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Total</label>
                                                    <input type="number" class="form-control item-total" name="items[{{ $index }}][total_cost]" 
                                                           value="{{ $item['suggested_quote_price'] }}" readonly>
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label">Margin %</label>
                                                    <input type="number" class="form-control profit-margin" name="items[{{ $index }}][profit_margin]" 
                                                           value="{{ $item['profit_margin_percentage'] }}" min="0" max="100" step="0.1">
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label">&nbsp;</label>
                                                    <button type="button" class="btn btn-outline-danger btn-sm d-block" onclick="removeQuoteItem({{ $index }})">
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
                        <div class="row mt-4">
                            <div class="col-12 text-end">
                                <a href="{{ isset($enquiry) ? route('enquiries.quotes.index', $enquiry) : route('quotes.index', $project) }}" 
                                   class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Create Quote
                                </button>
                            </div>
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
    const unitPrice = parseFloat(row.querySelector('.item-unit-price').value) || 0;
    const total = quantity * unitPrice;
    
    row.querySelector('.item-total').value = total.toFixed(2);
}

function calculateQuoteTotals() {
    let subtotal = 0;
    document.querySelectorAll('.item-total').forEach(input => {
        subtotal += parseFloat(input.value) || 0;
    });
    
    const vat = subtotal * 0.16;
    const total = subtotal + vat;
    
    document.getElementById('quoteSubtotal').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('quoteVat').textContent = '$' + vat.toFixed(2);
    document.getElementById('quoteTotal').textContent = '$' + total.toFixed(2);
}

function addCustomItem() {
    const container = document.getElementById('quoteItemsContainer');
    const newItem = `
        <div class="quote-item-row border rounded p-3 mb-3" data-index="${itemIndex}">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="items[${itemIndex}][description]" rows="2" 
                              placeholder="Enter custom item description"></textarea>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Quantity</label>
                    <input type="number" class="form-control item-quantity" name="items[${itemIndex}][quantity]" 
                           value="1" min="0.01" step="0.01">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Unit Price</label>
                    <input type="number" class="form-control item-unit-price" name="items[${itemIndex}][unit_price]" 
                           value="0" min="0" step="0.01">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Total</label>
                    <input type="number" class="form-control item-total" name="items[${itemIndex}][total_cost]" 
                           value="0" readonly>
                </div>
                <div class="col-md-1">
                    <label class="form-label">Margin %</label>
                    <input type="number" class="form-control profit-margin" name="items[${itemIndex}][profit_margin]" 
                           value="25" min="0" max="100" step="0.1">
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-outline-danger btn-sm d-block" onclick="removeQuoteItem(${itemIndex})">
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
@endsection