@extends('layouts.master')

@section('title', 'View Project Budget')

@section('content')
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4 rounded shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-primary" href="{{ route('projects.index', $project->id) }}">
            <i class="bi bi-house-door"></i> Project Dashboard
        </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('budget.edit', [$project->id, $budget->id]) }}">
                        <i class="bi bi-pencil-square"></i> Edit Budget
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-success" href="{{ route('budget.export', [$project->id, $budget->id]) }}">
                <i class="bi bi-download"></i> Export to Excel
            </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('budget.index', $project->id) }}">
                        <i class="bi bi-arrow-left"></i> Back to Budgets
                    </a>
                </li>
            </ul>
            @if($budget->status !== 'approved')
                <form method="POST" action="{{ route('budget.approve', [$project->id, $budget->id]) }}" class="d-flex ms-auto">
                    @csrf
                    <button type="submit" class="btn btn-success fw-bold">
                        <i class="bi bi-check-circle"></i> Approve Budget
                    </button>
                </form>
            @endif
        </div>
    </div>
</nav>
<div class="container mt-4">
    {{-- Project/Budget Summary --}}
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-3">
        <div class="card-body">
                    <h4 class="mb-2 text-primary"><i class="bi bi-folder2-open me-2"></i>{{ $project->name }}</h4>
            <div class="row mb-2">
                <div class="col-md-6"><strong>Client:</strong> {{ $project->client_name }}</div>
                <div class="col-md-6"><strong>Venue:</strong> {{ $project->venue }}</div>
            </div>
            <div class="row">
                <div class="col-md-6"><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($budget->project->start_date)->format('d M Y') }}</div>
                <div class="col-md-6"><strong>End Date:</strong> {{ \Carbon\Carbon::parse($budget->project->end_date)->format('d M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <span class="badge bg-{{ $budget->status === 'approved' ? 'success' : 'secondary' }} fs-6">
                            Status: {{ ucfirst($budget->status ?? 'draft') }}
                        </span>
                    </div>
                    <div class="mb-2">
                        <strong>Total Budget:</strong><br>
                        <span class="text-success fs-5">KES {{ number_format((float) $budget->budget_total, 2) }}</span>
                    </div>
                    <a href="{{ route('budget.export', [$project->id, $budget->id]) }}" class="btn btn-outline-success btn-sm mt-2">
                        <i class="bi bi-download"></i> Export to Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Budget Items Grouped by Category --}}
    @php $grouped = $budget->items->groupBy(fn($item) => strtolower(trim($item->category))); @endphp
    @foreach($grouped as $category => $items)
        @php
            $isProduction = str_replace(' ', '', strtolower($category)) === 'materials-production';
            $byItem = $items->groupBy('item_name');
        @endphp
        @if($isProduction)
            <div class="card mb-4 animate-fade-in">
                <div class="card-header d-flex align-items-center bg-light">
                    <i class="bi bi-box-seam me-2 text-primary"></i>
                    <h5 class="mb-0">Production Materials</h5>
                </div>
                <div class="card-body p-0">
                    @foreach($byItem as $itemName => $particulars)
                        @php $item = $particulars->first(); $itemTotal = $particulars->sum('budgeted_cost'); @endphp
                        <div class="production-item border-bottom">
                            <div class="p-4 bg-light">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h5 class="mb-1 fw-bold text-primary">
                                            {{ $itemName }}
                                        </h5>
                                        @if($item->template)
                                            <div class="mb-2">
                                                <span class="badge bg-info fs-6 py-2 px-3">
                                                    <i class="bi bi-file-earmark-text me-1"></i>
                                                    Template: {{ $item->template->name }}
                                                </span>
                                        @endif
                                        @if($item->description)
                                            <p class="text-muted small mb-0 mt-2">{{ $item->description }}</p>
                                        @endif
                                    </div>
                                    @if($item->template)
                                        <div class="text-end">
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                Template created {{ $item->template->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @if($item->template && $item->template->particulars->count())
                                <div class="mb-3">
                                    <h6 class="fw-bold">Template Particulars</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead class="table-secondary">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Template ID</th>
                                                    <th>Particular</th>
                                                    <th>Unit</th>
                                                    <th>Default Qty</th>
                                                    <th>Comment</th>
                                                    <th>Created At</th>
                                                    <th>Updated At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($item->template->particulars as $tp)
                                                    <tr>
                                                        <td>{{ $tp->id }}</td>
                                                        <td>{{ $tp->item_template_id }}</td>
                                                        <td>{{ $tp->particular }}</td>
                                                        <td>{{ $tp->unit }}</td>
                                                        <td>{{ $tp->default_quantity }}</td>
                                                        <td>{{ $tp->comment }}</td>
                                                        <td>{{ $tp->created_at }}</td>
                                                        <td>{{ $tp->updated_at }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            @if(count($particulars))
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="text-nowrap ps-4">#</th>
                                                <th class="text-nowrap">Particular</th>
                                                <th class="text-nowrap text-center">Unit</th>
                                                <th class="text-nowrap text-end pe-4">Qty</th>
                                                <th class="text-nowrap text-end pe-4">Unit Price</th>
                                                <th class="text-nowrap text-end pe-4">Cost</th>
                                                <th class="text-nowrap">Comment</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($particulars as $index => $item)
                                                <tr class="border-top">
                                                    <td class="ps-4 fw-bold text-muted">{{ $index + 1 }}</td>
                                                    <td class="fw-semibold">{{ $item->particular }}</td>
                                                    <td class="text-center">
                                                        <span class="bg-primary-soft text-primary px-3 py-2">
                                                            {{ $item->unit ?? 'N/A' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end fw-bold pe-4">{{ number_format($item->quantity, 2) }}</td>
                                                    <td class="text-end pe-4">KES {{ number_format($item->unit_price, 2) }}</td>
                                                    <td class="text-end pe-4">KES {{ number_format($item->budgeted_cost, 2) }}</td>
                                                    <td>
                                                        @if($item->comment)
                                                            <span class="d-inline-block text-truncate" style="max-width: 200px;" data-bs-toggle="tooltip" title="{{ $item->comment }}">
                                                                {{ $item->comment }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr class="table-info fw-bold">
                                                <td colspan="5" class="text-end">Subtotal for {{ $itemName }}</td>
                                                <td colspan="2">KES {{ number_format($itemTotal, 2) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center p-5">
                                    <i class="bi bi-inbox fs-1 text-muted opacity-50"></i>
                                    <h6 class="mt-3 mb-1">No particulars found</h6>
                                    <p class="text-muted small mb-0">Add particulars to this item</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                    @php $catTotal = $items->sum('budgeted_cost'); @endphp
                    <div class="text-end fw-bold mb-3">
                        <span class="badge bg-info">Category Subtotal: KES {{ number_format($catTotal, 2) }}</span>
                    </div>
                </div>
            </div>
        @else
        <div class="card mb-4">
                <div class="card-header bg-light fw-bold">{{ ucwords($category) }}</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                        @php $byItem = $items->groupBy('item_name'); @endphp
                        @foreach($byItem as $itemName => $particulars)
                            @php $itemTotal = $particulars->sum('budgeted_cost'); @endphp
                            <div class="mb-3">
                                <div class="fw-bold text-primary mb-1">{{ $itemName }}</div>
                                <table class="table table-bordered mb-0">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th>Particular</th>
                                            <th>Unit</th>
                                            <th>Qty</th>
                                            <th>Unit Price</th>
                                            <th>Cost</th>
                                            <th>Comment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($particulars as $item)
                                            <tr>
                                                <td>{{ $item->particular }}</td>
                                                <td>{{ $item->unit }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>KES {{ number_format($item->unit_price, 2) }}</td>
                                                <td>KES {{ number_format($item->budgeted_cost, 2) }}</td>
                                                <td>{{ $item->comment }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="table-info fw-bold">
                                            <td colspan="4" class="text-end">Subtotal for {{ $itemName }}</td>
                                            <td colspan="2">KES {{ number_format($itemTotal, 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                        @php $catTotal = $items->sum('budgeted_cost'); @endphp
                        <div class="text-end fw-bold mb-3">
                            <span class="badge bg-info">Category Subtotal: KES {{ number_format($catTotal, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    {{-- Approvals & Edit Logs --}}
    <div class="row mt-4">
        <div class="col-lg-6">
    <div class="card mb-3">
        <div class="card-header bg-light fw-bold">Approvals</div>
        <div class="card-body row">
            <div class="col-md-6 mb-2">
                <strong>Departments:</strong><br>
                {{ $budget->approved_departments ?? '-' }}
            </div>
            <div class="col-md-6 mb-2">
                <strong>Approved:</strong><br>
                {{ $budget->approved_at ? $budget->approved_at->format('d M Y H:i') : '-' }} BY: {{ $budget->approved_by ?? '-' }}
            </div>
        </div>
    </div>
        </div>
        <div class="col-lg-6">
    @php
        $editLogs = \App\Models\BudgetEditLog::where('project_budget_id', $budget->id)->latest()->get();
    @endphp
    @if($budget->status === 'approved' && $editLogs->count())
                <div class="card mb-3">
            <div class="card-header bg-light fw-bold">Edit Logs (After Approval)</div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach($editLogs as $log)
                        <li class="list-group-item">
                            <strong>{{ $log->user->name ?? 'Unknown User' }}</strong> edited on {{ $log->created_at->format('d M Y H:i') }}<br>
                            <small>Changes: {{ json_encode($log->changes) }}</small>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12 text-end">
        <span class="badge bg-success fs-4 p-3">
            Grand Total Budget: KES {{ number_format($budget->budget_total, 2) }}
        </span>
    </div>
</div>
@endsection
