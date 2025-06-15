@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Close Out Report</h5>
                    <div>
                        <span class="badge bg-{{ $report->status === 'approved' ? 'success' : ($report->status === 'rejected' ? 'danger' : 'info') }} me-2">
                            {{ ucfirst($report->status) }}
                        </span>
                        
                        @if($report->status === 'draft' || auth()->user()->hasRole(['admin', 'super-admin']))
                            <a href="{{ route('projects.close-out-report.edit', [$project, $report]) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        @endif
                        
                        @if(auth()->user()->hasRole(['admin', 'super-admin', 'project-manager']))
                            @if($report->status === 'submitted')
                                <form action="{{ route('projects.close-out-report.approve', [$project, $report]) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                </form>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Project: {{ $project->name }}</h6>
                            <h6>Created By: {{ $report->creator->name }}</h6>
                            <h6>Created At: {{ $report->created_at->format('M d, Y h:i A') }}</h6>
                        </div>
                        <div class="col-md-6 text-md-end">
                            @if($report->status === 'approved' && $report->approved_by)
                                <h6>Approved By: {{ $report->approver->name ?? 'N/A' }}</h6>
                                <h6>Approved At: {{ $report->approved_at ? $report->approved_at->format('M d, Y h:i A') : 'N/A' }}</h6>
                            @elseif($report->status === 'rejected' && $report->rejected_by)
                                <h6>Rejected By: {{ $report->rejector->name ?? 'N/A' }}</h6>
                                <h6>Rejected At: {{ $report->rejected_at ? $report->rejected_at->format('M d, Y h:i A') : 'N/A' }}</h6>
                                @if($report->rejection_reason)
                                    <h6 class="mt-2">
                                        <strong>Rejection Reason:</strong><br>
                                        {{ $report->rejection_reason }}
                                    </h6>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Project/Client Details</h6>
                        </div>
                        <div class="card-body">
                            {!! nl2br(e($report->project_client_details)) !!}
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Budget vs. Actual Summary</h6>
                        </div>
                        <div class="card-body">
                            {!! nl2br(e($report->budget_vs_actual_summary)) !!}
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Issues Encountered</h6>
                        </div>
                        <div class="card-body">
                            {!! nl2br(e($report->issues_encountered)) !!}
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Client Feedback Summary</h6>
                        </div>
                        <div class="card-body">
                            {!! nl2br(e($report->client_feedback_summary)) !!}
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">PO Recommendations</h6>
                        </div>
                        <div class="card-body">
                            {!! nl2br(e($report->po_recommendations)) !!}
                        </div>
                    </div>

                    @if($report->attachments->count() > 0)
                        <div class="card mb-4">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Attachments</h6>
                            </div>
                            <div class="card-body">
                                <div class="list-group">
                                    @foreach($report->attachments as $attachment)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>{{ $attachment->filename }}</span>
                                            <a href="{{ route('projects.close-out-report.attachments.download', [$project, $report, $attachment]) }}" 
                                               class="btn btn-sm btn-info" title="Download">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('projects.close-out-report.index', $project) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                        @if($report->status === 'draft')
                            <form action="{{ route('projects.close-out-report.submit', [$project, $report]) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Submit for Approval
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
@if(auth()->user()->hasRole(['admin', 'super-admin', 'project-manager']) && $report->status === 'submitted')
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Reject Close Out Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('projects.close-out-report.reject', [$project, $report]) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Reason for Rejection</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endpush
@endsection
