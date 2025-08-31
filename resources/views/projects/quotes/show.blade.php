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
                        <h6 class="fw-semibold mb-0 text-uppercase small">Quote Items</h6>
                    </div>
                    <div class="table-responsive">
                        @php 
                            $subtotal = 0; 
                            $totalCost = 0;
                            $totalProfit = 0;
                            
                            // Group items by item name (for production items) or description (for other items)
                            $groupedItems = $quote->lineItems->groupBy(function($item) {
                                if (str_contains($item->comment, 'Item Name:')) {
                                    return str_replace('Item Name: ', '', explode(' | ', $item->comment)[0]);
                                }
                                return $item->description;
                            });
                        @endphp
                        
                                    <table class="table table-sm table-hover mb-0">
                                        <thead class="table-secondary">
                                            <tr>
                                                <th class="border-0 py-2 px-2 text-center" style="width: 40px; font-size: 0.8rem;">#</th>
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
                                                @endphp
                                                <tr class="border-bottom">
                                                    <td class="py-2 px-2 text-center">
                                            <span class="badge bg-light text-dark" style="font-size: 0.75rem;">{{ $loop->iteration }}</span>
                                                    </td>
                                                    <td class="py-2 px-2">
                                                        <div>
                                                <div class="fw-semibold" style="font-size: 0.85rem; line-height: 1.2;">{{ $itemName }}</div>
                                                @if($firstItem->template)
                                                    <span class="badge bg-info fs-6 py-1 px-2" style="font-size: 0.7rem;">
                                                        <i class="bi bi-file-earmark-text me-1"></i>
                                                        Template: {{ $firstItem->template->name }}
                                                    </span>
                                                @endif
                                                @if($items->count() > 1)
                                                    <small class="text-muted" style="font-size: 0.75rem;">{{ $items->count() }} items included</small>
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