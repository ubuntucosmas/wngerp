@extends('layouts.master')

@section('title', 'View Project Budget')

@section('content')
<div class="container mt-4">

    {{-- Breadcrumbs --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-2 rounded">
            <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
            <li class="breadcrumb-item"><a href="{{ route('budget.index', $project) }}">Budgets</a></li>
            <li class="breadcrumb-item active" aria-current="page">View Budget</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Budget Details</h4>
        <div>
            <a href="{{ route('budget.index', $project) }}" class="btn btn-outline-primary btn-sm">← Back to Budgets</a>
            <a href="{{ route('budget.export', [$project->id, $budget->id]) }}" class="btn btn-outline-success btn-sm ms-2">
                <i class="bi bi-download"></i> Export to Excel
            </a>
            <x-delete-button :action="route('budget.destroy', [$project->id, $budget->id])" class="ms-2">
                Delete
            </x-delete-button>
            @if(auth()->user()->hasRole('super-admin|finance') && $budget->status !== 'approved')
                <form action="{{ route('budget.approve', [$project->id, $budget->id]) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm ms-2">Approve Budget</button>
                </form>
            @endif
        </div>
    </div>
    <div class="mb-3">
        <span class="badge bg-{{ $budget->status === 'approved' ? 'success' : 'secondary' }}">
            Status: {{ ucfirst($budget->status ?? 'draft') }}
        </span>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="mb-3 text-primary">{{ $project->name }}</h5>

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

    {{-- Budget Items Grouped by Category --}}
    @php $grouped = $budget->items->groupBy('category'); @endphp

    @foreach($grouped as $category => $items)
        <div class="card mb-4">
            <div class="card-header bg-light fw-bold">{{ $category }}</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    @if($category === 'Materials - Production')
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
                    @else
                        @php $catTotal = $items->sum('budgeted_cost'); @endphp
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
                                @foreach($items as $item)
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
                                    <td colspan="4" class="text-end">Subtotal for {{ $category }}</td>
                                    <td colspan="2">KES {{ number_format($catTotal, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    @endforeach

    {{-- Summary --}}
    <div class="card mb-3">
        <div class="card-header bg-light fw-bold">Summary</div>
        <div class="card-body row">
            <div class="col-md-4 mb-2">
                <strong>Total Budget:</strong><br>
                <span class="text-success">KES {{ number_format($budget->budget_total, 2) }}</span>
            </div>
        </div>
    </div> 

    {{-- Approval --}}
    <div class="card">
        <div class="card-header bg-light fw-bold">Approvals</div>
        <div class="card-body row">
            <!-- <div class="col-md-6 mb-2">
                <strong>Approved By:</strong><br>
                {{ $budget->approved_by ?? '-' }}
            </div> -->
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

    @php
        $editLogs = \App\Models\BudgetEditLog::where('project_budget_id', $budget->id)->latest()->get();
    @endphp
    @if($budget->status === 'approved' && $editLogs->count())
        <div class="card mt-4">
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
@endsection
