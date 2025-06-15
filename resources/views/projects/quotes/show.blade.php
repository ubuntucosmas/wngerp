@extends('layouts.master')

@section('title', "Quote #{$quote->id}")

@section('content')
<div class="container py-3">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
        <div>
            <h4 class="fw-semibold mb-0">Quote #{{ $quote->id }}</h4>
            <nav aria-label="breadcrumb" class="mb-0">
                <ol class="breadcrumb bg-transparent p-0 mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}" class="text-decoration-none text-primary">Project Files</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('quotes.index', $project) }}" class="text-decoration-none text-primary">Quotes</a></li>
                    <li class="breadcrumb-item active text-secondary" aria-current="page">#{{ $quote->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-1 flex-wrap">
            <a href="{{ route('quotes.edit', ['project' => $project->id, 'quote' => $quote->id]) }}" class="btn btn-sm btn-primary d-flex align-items-center gap-1 shadow-sm">
                <i class="bi bi-pencil-fill fs-6"></i> Edit
            </a>
            <button onclick="window.print()" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1 shadow-sm">
                <i class="bi bi-printer-fill fs-6"></i> Print
            </button>
        </div>
    </div>

    <div class="card shadow-sm mb-4 border-0 rounded-3">
        <div class="card-body px-3 py-4">
            <div class="row mb-4">
                <div class="col-md-6 border-end border-md-end-0 border-bottom border-md-bottom-0 pb-3 pb-md-0">
                    <h6 class="text-muted fw-semibold mb-1 small">From</h6>
                    <h5 class="fw-bold text-success mb-1">WOODNORKGREEN</h5>
                    <address class="mb-0 fw-light text-muted small" style="line-height: 1.2;">
                        Karen Village Art Centre, Ngong Rd Nairobi<br>
                        <a href="mailto:admin@woodnorkgreen.co.ke" class="text-decoration-none text-muted">admin@woodnorkgreen.co.ke</a><br>
                        Phone: <a href="tel:+254780397798" class="text-decoration-none text-muted">+254 780 397 798</a>
                    </address>
                </div>
                <div class="col-md-6 ps-md-4 text-md-end">
                    <h6 class="text-muted fw-semibold mb-1 small">To</h6>
                    <h5 class="fw-bold mb-1 small">{{ $quote->customer_name }}</h5>
                    @if($quote->customer_location)
                        <p class="mb-1 text-muted fst-italic small">{{ $quote->customer_location }}</p>
                    @endif
                    @if($quote->attention)
                        <p class="mb-1 small"><strong>Attn:</strong> <span class="text-muted">{{ $quote->attention }}</span></p>
                    @endif
                    @if($quote->reference)
                        <p class="mb-1 small"><strong>Ref:</strong> <span class="text-muted">{{ $quote->reference }}</span></p>
                    @endif
                    @if($quote->project_start_date)
                        <p class="mb-0 small"><strong>Project Start:</strong> <span class="text-muted">{{ $quote->project_start_date->format('M d, Y') }}</span></p>
                    @endif
                </div>
            </div>

            <div class="table-responsive rounded-2 overflow-hidden shadow-sm mb-4" style="font-size: 0.8rem;">
                <table class="table align-middle mb-0 table-sm" style="min-width: 650px;">
                    <thead class="table-light text-uppercase text-secondary small">
                        <tr>
                            <th scope="col" class="text-center" style="width: 30px;">#</th>
                            <th scope="col">Description</th>
                            <th scope="col" class="text-center" style="width: 65px;">Days</th>
                            <th scope="col" class="text-end" style="width: 70px;">Qty</th>
                            <th scope="col" class="text-end" style="width: 100px;">Unit Price (KES)</th>
                            <th scope="col" class="text-end" style="width: 100px;">Total (KES)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $subtotal = 0; @endphp
                        @foreach($quote->lineItems as $i => $item)
                            @php
                                $itemTotal = $item->quantity * $item->unit_price;
                                $subtotal += $itemTotal;
                            @endphp
                            <tr class="border-bottom">
                                <td class="text-center text-muted small">{{ $i + 1 }}</td>
                                <td class="small">{{ $item->description }}</td>
                                <td class="text-center small">{{ $item->days }}</td>
                                <td class="text-end small">{{ number_format($item->quantity, 2) }}</td>
                                <td class="text-end fw-monospace small">{{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-end fw-bold fw-monospace small">{{ number_format($itemTotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-light small">
                        <tr>
                            <th colspan="5" class="text-end py-1">Subtotal</th>
                            <th class="text-end py-1 fw-semibold fw-monospace">KES {{ number_format($subtotal, 2) }}</th>
                        </tr>
                        <tr>
                            <th colspan="5" class="text-end py-1">VAT (16%)</th>
                            <th class="text-end py-1 fw-semibold fw-monospace">KES {{ number_format($vatAmount, 2) }}</th>
                        </tr>
                        <tr class="fw-semibold fs-6">
                            <th colspan="5" class="text-end py-2 text-success">Total</th>
                            <th class="text-end py-2 fw-bold text-success fw-monospace">KES {{ number_format($total, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- <div class="bg-light rounded-2 p-3 shadow-sm small">
                <h6 class="mb-2 fw-semibold">Payment Instructions</h6>
                <p class="mb-1">Thank you for your business! Please make payment to:</p>
                <address class="mb-0 fw-light" style="line-height: 1.2;">
                    <strong>Bank:</strong> Bank Name<br>
                    <strong>Account Name:</strong> Company Name<br>
                    <strong>Account Number:</strong> Account Number<br>
                    <strong>Branch:</strong> Branch Name
                </address>
            </div> -->

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <a href="{{ route('quotes.index', $project) }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1 shadow-sm">
                    <i class="fas fa-arrow-left fs-6"></i> Back
                </a>
                <div class="d-flex gap-1 flex-wrap">
                    <a href="{{ route('quotes.edit', ['project' => $project->id, 'quote' => $quote->id]) }}" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1 shadow-sm">
                        <i class="fas fa-edit fs-6"></i> Edit
                    </a>
                    <button onclick="window.print()" class="btn btn-outline-success btn-sm d-flex align-items-center gap-1 shadow-sm">
                    <i class="fas fa-print fs-6"></i> Print
                </button>
                <form action="{{ route('quotes.destroy', ['project' => $project->id, 'quote' => $quote->id]) }}" method="POST" 
                    class="d-inline" onsubmit="return confirm('Are you sure you want to delete this quote?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1 shadow-sm">
                        <i class="fas fa-trash fs-6"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Compact print styles */
    @media print {
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            font-size: 10pt;
            color: #000;
        }
        .no-print, nav, form, button, a.btn {
            display: none !important;
        }
        .container {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .card {
            box-shadow: none !important;
            border: none !important;
        }
        table {
            font-size: 0.85rem !important;
            page-break-inside:auto;
        }
        tr {
            page-break-inside:avoid;
            page-break-after:auto;
        }
    }
</style>
@endsection