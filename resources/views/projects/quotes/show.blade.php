@extends('layouts.master')

@section('title', "Quote #{$quote->id}")

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Quote #{{ $quote->id }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('projects.files.index', $project) }}" class="text-decoration-none text-primary">
                            <i class="bi bi-folder me-1"></i>Project Files
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('quotes.index', $project) }}" class="text-decoration-none text-primary">
                            <i class="bi bi-file-earmark-text me-1"></i>Quotes
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-muted" aria-current="page">
                        Quote #{{ $quote->id }}
                    </li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('quotes.edit', ['project' => $project->id, 'quote' => $quote->id]) }}" 
               class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-pencil-fill"></i>
                <span>Edit Quote</span>
            </a>
            <a href="{{ route('quotes.print', [$project->id, $quote->id]) }}" 
               class="btn btn-outline-primary d-flex align-items-center gap-2 shadow-sm" target="_blank">
                <i class="bi bi-printer"></i>
                <span>Print</span>
            </a>
            <a href="{{ route('quotes.download', [$project->id, $quote->id]) }}" 
               class="btn btn-outline-success d-flex align-items-center gap-2 shadow-sm"> 
                <i class="bi bi-download"></i>
                <span>Download PDF</span>
            </a>
            <a href="{{ route('quotes.excel', [$project->id, $quote->id]) }}" 
               class="btn btn-outline-info d-flex align-items-center gap-2 shadow-sm"> 
                <i class="bi bi-file-earmark-excel"></i>
                <span>Export Excel</span>
            </a>
        </div>
    </div>

    <!-- Quote Document -->
    <div class="card border-0 mb-4">
        <div class="card-body p-3">
            <!-- Quote Header -->
            <!-- <div class="bg-gradient-primary text-white p-4 rounded-top">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-white bg-opacity-20 rounded-circle p-3">
                                <i class="bi bi-file-earmark-text fs-2"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-1">QUOTE</h3>
                                <p class="mb-0 opacity-75">Professional Quotation</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="bg-white bg-opacity-20 rounded p-3 d-inline-block">
                            <h4 class="fw-bold mb-1">#{{ $quote->id }}</h4>
                            <p class="mb-0 small opacity-75">{{ $quote->quote_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div> -->

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
                                    Project ID: {{ $quote->project->project_id }}<br>
                                    <a href="mailto:admin@woodnorkgreen.co.ke" class="text-decoration-none text-muted">Project Name: {{ $quote->project->name }}</a><br>
                                </address>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-6">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                        <i class="bi bi-person text-primary"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-1">Customer: {{ $quote->customer_name }}</h5>
                                        @if($quote->customer_location)
                                            <p class="text-muted mb-0 small">{{ $quote->customer_location }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-muted small">
                                    @if($quote->attention)
                                        <p class="mb-1"><strong>Attn:</strong> {{ $quote->attention }}</p>
                                    @endif
                                    @if($quote->reference)
                                        <p class="mb-1"><strong>Ref:</strong> {{ $quote->reference }}</p>
                                    @endif
                                    @if($quote->project_start_date)
                                        <p class="mb-0"><strong>Project Start:</strong> {{ $quote->project_start_date->format('M d, Y') }}</p>
                                    @endif 
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>

                                <!-- Quote Items Table -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-2">
                        <h6 class="fw-semibold mb-0 text-uppercase small">Itemized Breakdown with Profit Margins</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 py-2 px-2 text-center" style="width: 40px; font-size: 0.8rem;">#</th>
                                    <th class="border-0 py-2 px-2" style="font-size: 0.8rem;">Description</th>
                                    <th class="border-0 py-2 px-2 text-end" style="width: 80px; font-size: 0.8rem;">Qty</th>
                                    <th class="border-0 py-2 px-2 text-end" style="width: 100px; font-size: 0.8rem;">Unit Price</th>
                                    <th class="border-0 py-2 px-2 text-end" style="width: 100px; font-size: 0.8rem;">Total Cost</th>
                                    <th class="border-0 py-2 px-2 text-center" style="width: 100px; font-size: 0.8rem;">Profit</th>
                                    <th class="border-0 py-2 px-2 text-end" style="width: 100px; font-size: 0.8rem;">Quote Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $subtotal = 0; 
                                    $totalCost = 0;
                                    $totalProfit = 0;
                                @endphp
                                @foreach($quote->lineItems as $i => $item)
                                    @php
                                        $itemTotalCost = $item->quantity * $item->unit_price;
                                        $itemQuotePrice = $item->quote_price ?? $itemTotalCost * (1 + ($item->profit_margin / 100));
                                        $itemProfit = $itemQuotePrice - $itemTotalCost;
                                        $subtotal += $itemQuotePrice;
                                        $totalCost += $itemTotalCost;
                                        $totalProfit += $itemProfit;
                                    @endphp
                                    <tr class="border-bottom">
                                        <td class="py-2 px-2 text-center">
                                            <span class="badge bg-light text-dark" style="font-size: 0.75rem;">{{ $i + 1 }}</span>
                                        </td>
                                        <td class="py-2 px-2">
                                            <div>
                                                <div class="fw-semibold" style="font-size: 0.85rem; line-height: 1.2;">{{ $item->description }}</div>
                                                @if($item->comment)
                                                    <small class="text-muted" style="font-size: 0.75rem;">{{ $item->comment }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-2 px-2 text-end fw-semibold" style="font-size: 0.85rem;">{{ number_format($item->quantity, 2) }}</td>
                                        <td class="py-2 px-2 text-end fw-monospace" style="font-size: 0.85rem;">{{ number_format($item->unit_price, 2) }}</td>
                                        <td class="py-2 px-2 text-end fw-monospace text-muted" style="font-size: 0.85rem;">{{ number_format($itemTotalCost, 2) }}</td>
                                        <td class="py-2 px-2 text-center">
                                            <div class="d-flex flex-column align-items-center">
                                                <small class="text-success fw-semibold" style="font-size: 0.75rem;">+{{ number_format($itemProfit, 2) }}</small>
                                                <small class="text-muted" style="font-size: 0.7rem;">{{ number_format($item->profit_margin, 2) }}%</small>
                                            </div>
                                        </td>
                                        <td class="py-2 px-2 text-end fw-bold fw-monospace text-success" style="font-size: 0.85rem;">{{ number_format($itemQuotePrice, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr class="border-top">
                                    <th colspan="4" class="text-end py-2 px-2" style="font-size: 0.85rem;">Totals:</th>
                                    <th class="text-end py-2 px-2 fw-semibold text-muted" style="font-size: 0.85rem;">{{ number_format($totalCost, 2) }}</th>
                                    <th class="text-center py-2 px-2">
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="badge bg-success text-white" style="font-size: 0.75rem;">{{ number_format(($totalProfit / $totalCost) * 100, 2) }}%</span>
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
        <a href="{{ route('quotes.index', $project) }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
            <i class="bi bi-arrow-left"></i>
            <span>Back to Quotes</span>
        </a>
        <div class="d-flex gap-2">
            <a href="{{ route('quotes.edit', ['project' => $project->id, 'quote' => $quote->id]) }}" 
               class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-pencil"></i>
                <span>Edit Quote</span>
            </a>
            <a href="{{ route('quotes.print', [$project->id, $quote->id]) }}" 
               class="btn btn-outline-primary d-flex align-items-center gap-2" target="_blank">
                <i class="bi bi-printer"></i>
                <span>Print</span>
            </a>
            <a href="{{ route('quotes.excel', [$project->id, $quote->id]) }}" 
               class="btn btn-outline-info d-flex align-items-center gap-2">
                <i class="bi bi-file-earmark-excel"></i>
                <span>Export Excel</span>
            </a>
            @if(auth()->user()->hasRole('super-admin'))
                <form action="{{ route('quotes.destroy', ['project' => $project->id, 'quote' => $quote->id]) }}" 
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

.table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

/* Print Styles */
@media print {
    body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        font-size: 10pt;
        color: #000;
        background: white;
    }
    
    .no-print, nav, form, button, a.btn {
        display: none !important;
    }
    
    .container-fluid {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
        page-break-inside: avoid;
    }
    
    .bg-gradient-primary {
        background: #0d6efd !important;
        color: white !important;
    }
    
    table {
        font-size: 0.85rem !important;
        page-break-inside: auto;
    }
    
    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }
    
    .badge {
        border: 1px solid #000 !important;
        background: white !important;
        color: black !important;
    }
}
</style>
@endsection