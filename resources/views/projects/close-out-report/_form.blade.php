@csrf

<div class="row mb-3">
    <div class="col-md-12">
        <label for="project_client_details" class="form-label">Project/Client Details</label>
        <textarea class="form-control @error('project_client_details') is-invalid @enderror" 
                  id="project_client_details" name="project_client_details" rows="4" required>{{ old('project_client_details', $report->project_client_details ?? '') }}</textarea>
        @error('project_client_details')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <label for="budget_vs_actual_summary" class="form-label">Budget vs. Actual Summary</label>
        <textarea class="form-control @error('budget_vs_actual_summary') is-invalid @enderror" 
                  id="budget_vs_actual_summary" name="budget_vs_actual_summary" rows="4" required>{{ old('budget_vs_actual_summary', $report->budget_vs_actual_summary ?? '') }}</textarea>
        @error('budget_vs_actual_summary')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <label for="issues_encountered" class="form-label">Issues Encountered</label>
        <textarea class="form-control @error('issues_encountered') is-invalid @enderror" 
                  id="issues_encountered" name="issues_encountered" rows="4" required>{{ old('issues_encountered', $report->issues_encountered ?? '') }}</textarea>
        @error('issues_encountered')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <label for="client_feedback_summary" class="form-label">Client Feedback Summary</label>
        <textarea class="form-control @error('client_feedback_summary') is-invalid @enderror" 
                  id="client_feedback_summary" name="client_feedback_summary" rows="4" required>{{ old('client_feedback_summary', $report->client_feedback_summary ?? '') }}</textarea>
        @error('client_feedback_summary')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <label for="po_recommendations" class="form-label">PO Recommendations</label>
        <textarea class="form-control @error('po_recommendations') is-invalid @enderror" 
                  id="po_recommendations" name="po_recommendations" rows="4" required>{{ old('po_recommendations', $report->po_recommendations ?? '') }}</textarea>
        @error('po_recommendations')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <label for="attachments" class="form-label">Attachments</label>
        <input type="file" class="form-control @error('attachments') is-invalid @enderror" 
               id="attachments" name="attachments[]" multiple>
        @error('attachments')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="form-text text-muted">You can upload multiple files. Maximum size: 10MB per file.</small>
    </div>
</div>

@if(isset($report) && $report->attachments->count() > 0)
<div class="row mb-3">
    <div class="col-md-12">
        <label class="form-label">Current Attachments</label>
        <div class="list-group">
            @foreach($report->attachments as $attachment)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ $attachment->filename }}</span>
                    <div>
                        <a href="{{ route('projects.close-out-report.attachments.download', [$project, $report, $attachment]) }}" 
                           class="btn btn-sm btn-info me-1" title="Download">
                            <i class="fas fa-download"></i>
                        </a>
                        @if($report->status === 'draft' || auth()->user()->hasRole(['admin', 'super-admin']))
                            <button type="button" class="btn btn-sm btn-danger" 
                                    onclick="deleteAttachment({{ $attachment->id }})" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<div class="row mb-3">
    <div class="col-md-12 d-flex justify-content-between">
        <a href="{{ route('projects.close-out-report.index', $project) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
        <div>
            @if(isset($report) && $report->status === 'draft')
                <button type="submit" name="action" value="save_draft" class="btn btn-info me-2">
                    <i class="fas fa-save"></i> Save as Draft
                </button>
            @endif
            <button type="submit" name="action" value="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> {{ isset($report) ? 'Update' : 'Submit' }} Report
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function deleteAttachment(attachmentId) {
    if (confirm('Are you sure you want to delete this attachment?')) {
        fetch(`{{ route('projects.close-out-report.attachments.destroy', [$project, $report ?? null, '']) }}/${attachmentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting attachment');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting attachment');
        });
    }
}
</script>
@endpush
