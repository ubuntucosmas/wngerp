@extends('layouts.master')

@section('title', 'Quotation - ' . (isset($enquiry) ? $enquiry->project_name : $project->name))
@section('navbar-title', 'Quotation')

@section('content')
<div class="px-3 mx-10 w-100">
    <div class="px-3 mx-10 w-100">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    @if(isset($enquiry))
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.index') }}">Enquiries</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.files', $enquiry) }}">{{ $enquiry->project_name }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Budget & Quotation</li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}">{{ $project->name }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Budget & Quotation</li>
                    @endif
                </ol>
            </nav>
            <h2 class="mb-0">Budget & Quotation</h2>
        </div>
        <a href="{{ (isset($enquiry) && is_object($enquiry) && isset($enquiry->id)) ? route('enquiries.files', $enquiry) : route('projects.files.index', $project) }}" class="btn btn-primary">
            <i class="bi bi-arrow-left me-2"></i>Back to Files & Phases
        </a>
    </div>

    @php
        $entity = isset($enquiry) ? $enquiry : $project;
        $budgets = $entity->budgets ?? collect();
        $quotes = $entity->quotes ?? collect();
        
        // Get latest budget and quote for status
        $latestBudget = $budgets->sortByDesc('created_at')->first();
        $latestQuote = $quotes->sortByDesc('created_at')->first();
        
        // Determine statuses
        $budgetStatus = $latestBudget ? ($latestBudget->status ?? 'Draft') : 'Not Created';
        $quoteStatus = $latestQuote ? ($latestQuote->status ?? 'Draft') : 'Not Created';
        
        // Status badge colors
        $budgetBadgeClass = match($budgetStatus) {
            'Approved' => 'bg-success',
            'Pending' => 'bg-warning',
            'Draft' => 'bg-secondary',
            'Rejected' => 'bg-danger',
            default => 'bg-light text-dark'
        };
        
        $quoteBadgeClass = match($quoteStatus) {
            'Approved' => 'bg-success',
            'Sent' => 'bg-info',
            'Draft' => 'bg-secondary',
            'Rejected' => 'bg-danger',
            default => 'bg-light text-dark'
        };
    @endphp

    @if(auth()->user()->hasAnyRole(['super-admin', 'po', 'admin', 'finance']))
        {{-- Full access cards for authorized roles --}}
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-4">
                <a href="{{ isset($enquiry) ? route('enquiries.budget.index', $enquiry) : route('budget.index', $project) }}" class="text-decoration-none">
                    <div class="file-card h-100">
                        <div class="d-flex align-items-start">
                            <div class="file-card-icon me-3">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="file-card-title">Budget</h3>
                                <p class="file-card-description">
                                    Create and manage budget document
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="badge {{ $budgetBadgeClass }}">{{ $budgetStatus }}</span>
                                    @if($latestBudget)
                                        <small class="text-muted">{{ $latestBudget->created_at->format('M d, Y') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-6 col-md-6 mb-4">
                <a href="{{ isset($enquiry) ? route('enquiries.quotes.index', $enquiry) : route('quotes.index', $project) }}" class="text-decoration-none">
                    <div class="file-card h-100">
                        <div class="d-flex align-items-start">
                            <div class="file-card-icon me-3">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="file-card-title">Quotation</h3>
                                <p class="file-card-description">
                                    Create and manage quotation document
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="badge {{ $quoteBadgeClass }}">{{ $quoteStatus }}</span>
                                    @if($latestQuote)
                                        <small class="text-muted">{{ $latestQuote->created_at->format('M d, Y') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    @else
        {{-- Status-only view for other roles --}}
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="status-card h-100">
                    <div class="d-flex align-items-start">
                        <div class="status-card-icon me-3">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="status-card-title">Budget</h3>
                            <p class="status-card-description">
                                Budget status and information
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="badge {{ $budgetBadgeClass }}">{{ $budgetStatus }}</span>
                                @if($latestBudget)
                                    <small class="text-muted">{{ $latestBudget->created_at->format('M d, Y') }}</small>
                                @endif
                            </div>
                            @if($latestBudget && $latestBudget->budget_total)
                                <div class="mt-2">
                                    <small class="text-muted">Total: ${{ number_format($latestBudget->budget_total, 2) }}</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 mb-4">
                <div class="status-card h-100">
                    <div class="d-flex align-items-start">
                        <div class="status-card-icon me-3">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="status-card-title">Quotation</h3>
                            <p class="status-card-description">
                                Quotation status and information
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="badge {{ $quoteBadgeClass }}">{{ $quoteStatus }}</span>
                                @if($latestQuote)
                                    <small class="text-muted">{{ $latestQuote->created_at->format('M d, Y') }}</small>
                                @endif
                            </div>
                            @if($latestQuote && $latestQuote->total_amount)
                                <div class="mt-2">
                                    <small class="text-muted">Amount: ${{ number_format($latestQuote->total_amount, 2) }}</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-info mt-3">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Limited Access:</strong> You can view budget and quotation status information. Contact finance or admin for detailed access.
        </div>
    @endif
</div>

<style>
    .file-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .file-card:hover {
        border-color: #0d6efd;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }
    
    .file-card-icon {
        font-size: 1.75rem;
        color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.1);
        width: 3.5rem;
        height: 3.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .file-card-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #212529;
    }
    
    .file-card-description {
        color: #6c757d;
        margin-bottom: 0;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    /* Status card styles for non-authorized users */
    .status-card {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 12px;
        padding: 1.5rem;
        height: 100%;
        cursor: default;
    }
    
    .status-card-icon {
        font-size: 1.75rem;
        color: #6c757d;
        background-color: rgba(108, 117, 125, 0.1);
        width: 3.5rem;
        height: 3.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .status-card-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #495057;
    }
    
    .status-card-description {
        color: #6c757d;
        margin-bottom: 0;
        font-size: 0.9rem;
        line-height: 1.5;
    }
    
    .breadcrumb {
        background: transparent;
        padding: 0.5rem 0;
        margin-bottom: 0;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        content: ">";
    }
    
    .breadcrumb-item a {
        color: #6c757d;
        text-decoration: none;
    }
    
    .breadcrumb-item.active {
        color: #0d6efd;
    }
</style>
@endsection
