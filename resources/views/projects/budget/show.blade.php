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
        <a href="{{ route('budget.index', $project) }}" class="btn btn-outline-primary btn-sm">← Back to Budgets</a>
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
                        </tbody>
                    </table>
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
            <div class="col-md-4 mb-2">
                <strong>Invoice:</strong><br>
                KES {{ number_format($budget->invoice ?? 0, 2) }}
            </div>
            <div class="col-md-4 mb-2">
                <strong>Profit:</strong><br>
                <span class="text-success">KES {{ number_format($budget->profit, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Approval --}}
    <div class="card">
        <div class="card-header bg-light fw-bold">Approvals</div>
        <div class="card-body row">
            <div class="col-md-6 mb-2">
                <strong>Approved By:</strong><br>
                {{ $budget->approved_by ?? '-' }}
            </div>
            <div class="col-md-6 mb-2">
                <strong>Departments:</strong><br>
                {{ $budget->approved_departments ?? '-' }}
            </div>
        </div>
    </div>
</div>
@endsection
