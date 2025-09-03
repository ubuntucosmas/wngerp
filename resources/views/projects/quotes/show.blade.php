@extends('layouts.master')

@section('title', $quoteName)

@section('content')
<div class="container-fluid py-4">
    <div class="px-3 mx-10 w-100">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    @if(isset($enquiry) && $enquiry)
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.index') }}">Enquiries</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.files', $enquiry) }}">{{ $enquiry->project_name }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.files.quotation', $enquiry) }}">Budget & Quotation</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.quotes.index', $enquiry) }}">Quotes</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View {{ $quoteName }}</li>
                    @elseif(isset($project) && $project)
                        <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}">{{ $project->name }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('projects.quotation.index', $project) }}">Budget & Quotation</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('quotes.index', $project) }}">Quotes</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View {{ $quoteName }}</li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View {{ $quoteName }}</li>
                    @endif
                </ol>
            </nav>
            <h2 class="mb-0">{{ $quoteName }}</h2>
        </div>
        <div class="page-actions">
            @if(isset($enquiry) && $enquiry)
                <a href="{{ route('enquiries.quotes.index', $enquiry) }}" class="btn btn-primary me-2">
                    <i class="bi bi-arrow-left me-2"></i>Back to Quotes
                </a>
                <a href="{{ route('enquiries.quotes.edit', ['enquiry' => $enquiry->id, 'quote' => $quote->id]) }}" class="btn btn-info me-2">
                    <i class="bi bi-pencil me-2"></i>Edit
                </a>
                <a href="{{ route('enquiries.quotes.print', [$enquiry->id, $quote->id]) }}" class="btn btn-secondary me-2" target="_blank">
                    <i class="bi bi-printer me-2"></i>Print
                </a>
                <a href="{{ route('enquiries.quotes.excel', [$enquiry->id, $quote->id]) }}" class="btn btn-secondary me-2">
                    <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
                </a>
                <a href="{{ route('enquiries.quotes.download', [$enquiry->id, $quote->id]) }}" class="btn btn-secondary me-2">
                    <i class="bi bi-file-earmark-pdf me-2"></i>Download PDF
                </a>
                @hasanyrole('super-admin|admin|finance|pm')
                    @if($quote->status !== 'approved')
                        <form action="{{ route('quotes.approve', [$enquiry->id, $quote->id]) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to approve this quote? This will notify all users.')">
                                <i class="bi bi-check-circle me-2"></i>Approve Quote
                            </button>
                        </form>
                    @else
                        <span class="badge bg-success fs-6 px-3 py-2">
                            <i class="bi bi-check-circle me-1"></i>Approved
                            @if($quote->approved_at)
                                <small class="ms-2">{{ $quote->approved_at->format('M d, Y') }}</small>
                            @endif
                        </span>
                    @endif
                @endhasanyrole
            @elseif(isset($project) && $project)
                <a href="{{ route('quotes.index', $project) }}" class="btn btn-primary me-2">
                    <i class="bi bi-arrow-left me-2"></i>Back to Quotes
                </a>
                <a href="{{ route('quotes.edit', ['project' => $project->id, 'quote' => $quote->id]) }}" class="btn btn-info me-2">
                    <i class="bi bi-pencil me-2"></i>Edit
                </a>
                <a href="{{ route('quotes.print', [$project->id, $quote->id]) }}" class="btn btn-secondary me-2" target="_blank">
                    <i class="bi bi-printer me-2"></i>Print
                </a>
                <a href="{{ route('quotes.excel', [$project->id, $quote->id]) }}" class="btn btn-secondary me-2">
                    <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
                </a>
                <a href="{{ route('quotes.download', [$project->id, $quote->id]) }}" class="btn btn-secondary me-2">
                    <i class="bi bi-file-earmark-pdf me-2"></i>Download PDF
                </a>
                @hasanyrole('super-admin|admin|finance|pm')
                    @if($quote->status !== 'approved')
                        <form action="{{ route('quotes.approve', [$project->id, $quote->id]) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to approve this quote? This will notify all users.')">
                                <i class="bi bi-check-circle me-2"></i>Approve Quote
                            </button>
                        </form>
                    @else
                        <span class="badge bg-success fs-6 px-3 py-2">
                            <i class="bi bi-check-circle me-1"></i>Approved
                            @if($quote->approved_at)
                                <small class="ms-2">{{ $quote->approved_at->format('M d, Y') }}</small>
                            @endif
                        </span>
                    @endif
                @endhasanyrole
            @else
                <a href="{{ route('projects.index') }}" class="btn btn-primary me-2">
                    <i class="bi bi-arrow-left me-2"></i>Back to Projects
                </a>
            @endif
        </div>
    </div>

    <!-- Quote Document -->
    <div class="card border-0 mb-4">
        <div class="card-body p-3">
            <!-- Company & Customer Info -->
            <div class="p-4">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-2">
                                        <i class="bi bi-building text-success"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold text-success mb-1"><span class="text-muted">CUSTOMER:</span> {{ $quote->customer_name }}</h5>
                                        <p class="text-muted mb-0 small">                                        
                                            @if($quote->customer_location)
                                            <p class="bi bi-geo-alt me-2 text-muted mb-0 small">Location:{{ $quote->customer_location }}</p>
                                            @endif</p>
                                    </div>
                                </div>
                                <address class="mb-0 text-muted small" style="line-height: 1.6;">
                                    @if(isset($enquiry) && $enquiry)
                                    Enquiry Name: {{ $enquiry->project_name }}<br>
                                    @elseif(isset($project) && $project)
                                    Project ID: {{ $project->project_id }}<br>
                                    Project Name: {{ $project->name }}<br>
                                    @endif
                                </address>
                            </div>
                        </div>
                    </div>
                </div>

                                <!-- Quote Items Table -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="fw-semibold mb-0 text-uppercase small">Quote Items</h6>
                            @php
                                // Group items by item name (for production items) or description (for other items)
                                $groupedItems = $quote->lineItems->groupBy(function($item) {
                                    if (str_contains($item->comment, 'Item Name:')) {
                                        return str_replace('Item Name: ', '', explode(' | ', $item->comment)[0]);
                                    }
                                    return $item->description;
                                });
                                
                                $expandableItems = $groupedItems->filter(function($items) { return $items->count() > 1; })->count();
                                $totalComponents = $quote->lineItems->count();
                            @endphp
                            @if($expandableItems > 0)
                                <div class="d-flex align-items-center gap-3">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        {{ $expandableItems }} items with detailed components ({{ $totalComponents }} total components)
                                    </small>
                                    <small class="text-primary">
                                        <i class="bi bi-cursor-fill me-1"></i>
                                        Click rows to expand
                                    </small>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-outline-primary btn-sm" 
                                                onclick="
                                                    var rows = document.querySelectorAll('.details-row');
                                                    var buttons = document.querySelectorAll('.btn-outline-primary, .btn-primary');
                                                    rows.forEach(function(row) { row.style.display = 'table-row'; });
                                                    buttons.forEach(function(btn) {
                                                        if (btn.querySelector('i.bi-chevron-down, i.bi-chevron-up')) {
                                                            btn.classList.remove('btn-outline-primary');
                                                            btn.classList.add('btn-primary');
                                                            var icon = btn.querySelector('i');
                                                            icon.classList.remove('bi-chevron-down');
                                                            icon.classList.add('bi-chevron-up');
                                                        }
                                                    });
                                                ">
                                            <i class="bi bi-arrows-expand me-1"></i>Expand All Details
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" 
                                                onclick="
                                                    var rows = document.querySelectorAll('.details-row');
                                                    var buttons = document.querySelectorAll('.btn-outline-primary, .btn-primary');
                                                    rows.forEach(function(row) { row.style.display = 'none'; });
                                                    buttons.forEach(function(btn) {
                                                        if (btn.querySelector('i.bi-chevron-down, i.bi-chevron-up')) {
                                                            btn.classList.remove('btn-primary');
                                                            btn.classList.add('btn-outline-primary');
                                                            var icon = btn.querySelector('i');
                                                            icon.classList.remove('bi-chevron-up');
                                                            icon.classList.add('bi-chevron-down');
                                                        }
                                                    });
                                                ">
                                            <i class="bi bi-arrows-collapse me-1"></i>Collapse All
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="table-responsive">
                        @php 
                            $subtotal = 0; 
                            $totalCost = 0;
                            $totalProfit = 0;
                        @endphp
                        
                                    <table class="table table-sm table-hover mb-0" id="quote-items-table">
                                        <thead class="table-secondary">
                                            <tr>
                                                <th class="border-0 py-2 px-2 text-center" style="width: 50px; font-size: 0.8rem;">
                                                    <i class="bi bi-list-ul"></i>
                                                </th>
                                    <th class="border-0 py-2 px-2" style="font-size: 0.8rem;">Item Name</th>
                                                <th class="border-0 py-2 px-2 text-end" style="width: 100px; font-size: 0.8rem;">Total Cost</th>
                                                <th class="border-0 py-2 px-2 text-center" style="width: 100px; font-size: 0.8rem;">Profit</th>
                                                <th class="border-0 py-2 px-2 text-end" style="width: 100px; font-size: 0.8rem;">Quote Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                @foreach($groupedItems as $itemName => $items)
                                                @php
                                        $itemTotalCost = $items->sum('total_cost');
                                        $itemTotalQuotePrice = $items->sum('quote_price');
                                        $itemTotalProfit = $itemTotalQuotePrice - $itemTotalCost;
                                        $subtotal += $itemTotalQuotePrice;
                                        $totalCost += $itemTotalCost;
                                        $totalProfit += $itemTotalProfit;
                                        $firstItem = $items->first();
                                        $itemId = 'item-' . $loop->iteration;
                                                @endphp
                                                <!-- Main Item Row -->
                                                <tr class="border-bottom">
                                                    <td class="py-2 px-2 text-center">
                                                        <div class="d-flex flex-column align-items-center">
                                            <span class="badge bg-light text-dark" style="font-size: 0.75rem;">{{ $loop->iteration }}</span>
                                                            @if($items->count() > 1)
                                                                <button class="btn btn-sm btn-outline-primary mt-1" 
                                                                        onclick="
                                                                            var row = document.getElementById('{{ $itemId }}');
                                                                            var btn = this;
                                                                            var icon = btn.querySelector('i');
                                                                            if (row.style.display === 'none' || row.style.display === '') {
                                                                                row.style.display = 'table-row';
                                                                                btn.classList.remove('btn-outline-primary');
                                                                                btn.classList.add('btn-primary');
                                                                                icon.classList.remove('bi-chevron-down');
                                                                                icon.classList.add('bi-chevron-up');
                                                                            } else {
                                                                                row.style.display = 'none';
                                                                                btn.classList.remove('btn-primary');
                                                                                btn.classList.add('btn-outline-primary');
                                                                                icon.classList.remove('bi-chevron-up');
                                                                                icon.classList.add('bi-chevron-down');
                                                                            }
                                                                        ">
                                                                    <i class="bi bi-chevron-down" style="font-size: 0.7rem;"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="py-2 px-2">
                                                        <div>
                                                <div class="fw-semibold d-flex align-items-center" style="font-size: 0.85rem; line-height: 1.2;">
                                                    {{ $itemName }}
                                                    @if($items->count() > 1)
                                                        <span class="badge bg-secondary ms-2" style="font-size: 0.7rem;">
                                                            {{ $items->count() }} components
                                                        </span>
                                                    @endif
                                                </div>
                                                @if($firstItem->template)
                                                    <span class="badge bg-info fs-6 py-1 px-2" style="font-size: 0.7rem;">
                                                        <i class="bi bi-file-earmark-text me-1"></i>
                                                        Template: {{ $firstItem->template->name }}
                                                    </span>
                                                @endif
                                                        </div>
                                                    </td>
                                        <td class="py-2 px-2 text-end fw-monospace text-muted" style="font-size: 0.85rem;">{{ number_format($itemTotalCost, 2) }}</td>
                                                    <td class="py-2 px-2 text-center">
                                                        <div class="d-flex flex-column align-items-center">
                                                <small class="text-success fw-semibold" style="font-size: 0.75rem;">+{{ number_format($itemTotalProfit, 2) }}</small>
                                                <small class="text-muted" style="font-size: 0.7rem;">{{ $itemTotalCost > 0 ? number_format(($itemTotalProfit / $itemTotalCost) * 100, 2) : '0.00' }}%</small>
                                                        </div>
                                                    </td>
                                        <td class="py-2 px-2 text-end fw-bold fw-monospace text-success" style="font-size: 0.85rem;">{{ number_format($itemTotalQuotePrice, 2) }}</td>
                                                </tr>
                                                
                                                <!-- Details Section -->
                                                @if($items->count() > 1)
                                                <tr class="details-row bg-light" id="{{ $itemId }}" style="display: none;">
                                                    <td colspan="5" class="p-3">
                                                        <div class="border-start border-primary border-3 ps-3">
                                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                                <h6 class="text-primary mb-0 fw-bold" style="font-size: 0.9rem;">
                                                                    <i class="bi bi-list-check me-2"></i>
                                                                    {{ $itemName }} - Components ({{ $items->count() }})
                                                                </h6>
                                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                                        onclick="toggleDetails('{{ $itemId }}')" 
                                                                        title="Hide details">
                                                                    <i class="bi bi-x"></i>
                                                                </button>
                                                            </div>
                                                                    
                                                                    <div class="table-responsive">
                                                                        <table class="table table-sm table-striped mb-0 shadow-sm rounded">
                                                                            <thead class="table-primary">
                                                                                <tr>
                                                                                    <th style="font-size: 0.75rem; width: 40%;" class="fw-semibold">
                                                                                        <i class="bi bi-card-text me-1"></i>Description
                                                                                    </th>
                                                                                    <th style="font-size: 0.75rem; width: 10%;" class="text-center fw-semibold">
                                                                                        <i class="bi bi-123 me-1"></i>Qty
                                                                                    </th>
                                                                                    <th style="font-size: 0.75rem; width: 15%;" class="text-end fw-semibold">
                                                                                        <i class="bi bi-currency-dollar me-1"></i>Unit Price
                                                                                    </th>
                                                                                    <th style="font-size: 0.75rem; width: 15%;" class="text-end fw-semibold">
                                                                                        <i class="bi bi-calculator me-1"></i>Cost
                                                                                    </th>
                                                                                    <th style="font-size: 0.75rem; width: 10%;" class="text-center fw-semibold">
                                                                                        <i class="bi bi-percent me-1"></i>Margin
                                                                                    </th>
                                                                                    <th style="font-size: 0.75rem; width: 15%;" class="text-end fw-semibold">
                                                                                        <i class="bi bi-tag me-1"></i>Quote Price
                                                                                    </th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach($items as $item)
                                                                                <tr class="align-middle">
                                                                                    <td style="font-size: 0.75rem;">
                                                                                        <div class="fw-medium text-dark">{{ $item->description }}</div>
                                                                                        @if($item->comment && !str_contains($item->comment, 'Item Name:'))
                                                                                            <small class="text-muted fst-italic">
                                                                                                <i class="bi bi-chat-left-text me-1"></i>{{ $item->comment }}
                                                                                            </small>
                                                                                        @endif
                                                                                    </td>
                                                                                    <td class="text-center" style="font-size: 0.75rem;">
                                                                                        <span class="badge bg-light text-dark">{{ number_format($item->quantity, 0) }}</span>
                                                                                    </td>
                                                                                    <td class="text-end fw-monospace" style="font-size: 0.75rem;">
                                                                                        <span class="text-info">{{ number_format($item->unit_price, 2) }}</span>
                                                                                    </td>
                                                                                    <td class="text-end fw-monospace text-muted" style="font-size: 0.75rem;">
                                                                                        <span class="text-warning">{{ number_format($item->total_cost, 2) }}</span>
                                                                                    </td>
                                                                                    <td class="text-center" style="font-size: 0.75rem;">
                                                                                        <span class="badge bg-success-subtle text-success">
                                                                                            {{ number_format($item->profit_margin ?? 0, 1) }}%
                                                                                        </span>
                                                                                    </td>
                                                                                    <td class="text-end fw-monospace fw-bold" style="font-size: 0.75rem;">
                                                                                        <span class="text-success">{{ number_format($item->quote_price, 2) }}</span>
                                                                                    </td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                            <tfoot class="table-secondary">
                                                                                <tr class="fw-bold">
                                                                                    <td colspan="3" class="text-end" style="font-size: 0.75rem;">
                                                                                        <i class="bi bi-calculator me-1"></i>Component Totals:
                                                                                    </td>
                                                                                    <td class="text-end fw-monospace" style="font-size: 0.75rem;">
                                                                                        <span class="text-warning">{{ number_format($items->sum('total_cost'), 2) }}</span>
                                                                                    </td>
                                                                                    <td class="text-center" style="font-size: 0.75rem;">
                                                                                        @php
                                                                                            $totalCost = $items->sum('total_cost');
                                                                                            $totalQuote = $items->sum('quote_price');
                                                                                            $avgMargin = $totalCost > 0 ? (($totalQuote - $totalCost) / $totalCost) * 100 : 0;
                                                                                        @endphp
                                                                                        <span class="badge bg-success text-white">{{ number_format($avgMargin, 1) }}%</span>
                                                                                    </td>
                                                                                    <td class="text-end fw-monospace" style="font-size: 0.75rem;">
                                                                                        <span class="text-success">{{ number_format($items->sum('quote_price'), 2) }}</span>
                                                                                    </td>
                                                                                </tr>
                                                                            </tfoot>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-info">
                                <tr class="border-top">
                                    <th colspan="2" class="text-end py-2 px-2" style="font-size: 0.85rem;">Grand Total:</th>
                                    <th class="text-end py-2 px-2 fw-semibold text-muted" style="font-size: 0.85rem;">{{ number_format($totalCost, 2) }}</th>
                                    <th class="text-center py-2 px-2">
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="badge bg-success text-white" style="font-size: 0.75rem;">{{ $totalCost > 0 ? number_format(($totalProfit / $totalCost) * 100, 2) : '0.00' }}%</span>
                                            <small class="text-success fw-semibold" style="font-size: 0.75rem;">+{{ number_format($totalProfit, 2) }}</small>
                                            <small class="text-muted" style="font-size: 0.7rem;">Total Profit</small>
                                        </div>
                                    </th>
                                    <th class="text-end py-2 px-2 fw-bold text-success" style="font-size: 0.9rem;">{{ number_format($subtotal, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Summary Section -->
                <div class="row">
                    <div class="col-md-8">
                        <!-- Terms and Conditions -->
                        <div class="card border-0 bg-light mb-4">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">Terms & Conditions</h6>
                                <ul class="text-muted small mb-0" style="line-height: 1.6;">
                                    <li>This quote is valid for 30 days from the date of issue</li>
                                    <li>Payment terms: 50% advance payment, 50% upon completion</li>
                                    <li>Delivery timeline will be confirmed upon order confirmation</li>
                                    <li>All prices are subject to VAT at 16%</li>
                                    <li>Any modifications to the scope may affect pricing and timeline</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <!-- Price Summary -->
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0 py-3">
                                <h6 class="fw-semibold mb-0">Price Summary</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted">Subtotal:</span>
                                    <span class="fw-semibold">KES {{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted">VAT (16%):</span>
                                    <span class="fw-semibold">KES {{ number_format($vatAmount, 2) }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold fs-5">Total:</span>
                                    <span class="fw-bold fs-5 text-success">KES {{ number_format($total, 2) }}</span>
                                </div>
                                <div class="mt-3 p-3 bg-success bg-opacity-10 rounded">
                                    <small class="text-success fw-semibold">
                                        <i class="bi bi-info-circle me-1"></i>
                                        All prices include VAT
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
        <a href="{{ isset($enquiry) ? route('enquiries.quotes.index', $enquiry) : route('quotes.index', $project) }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
            <i class="bi bi-arrow-left"></i>
            <span>Back to Quotes</span>
        </a>
        <div class="d-flex gap-2">
            <a href="{{ isset($enquiry) ? route('enquiries.quotes.edit', ['enquiry' => $enquiry->id, 'quote' => $quote->id]) : route('quotes.edit', ['project' => $project->id, 'quote' => $quote->id]) }}" 
               class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-pencil"></i>
                <span>Edit Quote</span>
            </a>
            <a href="{{ isset($enquiry) ? route('enquiries.quotes.print', [$enquiry->id, $quote->id]) : route('quotes.print', [$project->id, $quote->id]) }}" 
               class="btn btn-outline-primary d-flex align-items-center gap-2" target="_blank">
                <i class="bi bi-printer"></i>
                <span>Print</span>
            </a>
            <a href="{{ isset($enquiry) ? route('enquiries.quotes.excel', [$enquiry->id, $quote->id]) : route('quotes.excel', [$project->id, $quote->id]) }}" 
               class="btn btn-outline-info d-flex align-items-center gap-2">
                <i class="bi bi-file-earmark-excel"></i>
                <span>Export Excel</span>
            </a>
            @if(auth()->user()->hasRole('super-admin'))
                <form action="{{ isset($enquiry) ? route('enquiries.quotes.destroy', ['enquiry' => $enquiry->id, 'quote' => $quote->id]) : route('quotes.destroy', ['project' => $project->id, 'quote' => $quote->id]) }}" 
                      method="POST" class="d-inline" 
                      onsubmit="return confirm('Are you sure you want to delete this quote?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger d-flex align-items-center gap-2">
                        <i class="bi bi-trash"></i>
                        <span>Delete</span>
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

/* Enhanced expandable sections styles */
.item-row {
    transition: all 0.3s ease;
}

.expandable-row {
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.expandable-row:hover {
    background-color: rgba(13, 110, 253, 0.08) !important;
}

.expandable-row.expanded {
    background-color: rgba(25, 135, 84, 0.1) !important;
    border-left: 3px solid #198754;
}

.expand-icon {
    transition: transform 0.3s ease;
}

.expand-icon.bi-chevron-up {
    transform: rotate(0deg);
}

.expand-icon.bi-chevron-down {
    transform: rotate(0deg);
}

/* Particulars section styling */
.particulars-row {
    animation: slideDown 0.3s ease-in-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.particulars-section {
    border-left: 3px solid #0d6efd;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
}

.component-badge {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.4);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(13, 110, 253, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(13, 110, 253, 0);
    }
}

/* Visual feedback for clickable elements */
.expandable-row::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: transparent;
    transition: background-color 0.3s ease;
}

.expandable-row:hover::before {
    background-color: #0d6efd;
}

.expandable-row.expanded::before {
    background-color: #198754;
}

.table tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.05);
}
</style>





@endsection