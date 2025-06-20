@csrf

@if(isset($enquiryLog) && $enquiryLog->id)
    @method('PUT')
@endif

<div class="row g-3 mb-4">
    <!-- First Row -->
    <div class="col-12 col-md-6 col-lg-4">
        <div class="form-group">
            <label for="venue" class="form-label small text-muted mb-1">Venue</label>
            <input type="text" name="venue" class="form-control form-control-sm" 
                   value="{{ old('venue', $enquiryLog->venue ?? ($project->venue ?? '')) }}" required {{ isset($project) ? 'readonly' : '' }}>
        </div>
    </div>
    
    <div class="col-12 col-md-6 col-lg-3">
        <div class="form-group">
            <label for="date_received" class="form-label small text-muted mb-1">Date Received</label>
            <input type="date" name="date_received" class="form-control form-control-sm" 
                   value="{{ old('date_received', isset($enquiryLog->date_received) ? $enquiryLog->date_received->format('Y-m-d') : '') }}" required>
        </div>
    </div>
    
    <div class="col-12 col-md-6 col-lg-5">
        <div class="form-group">
            <label for="client_name" class="form-label small text-muted mb-1">Client Name</label>
            <input type="text" name="client_name" class="form-control form-control-sm" 
                   value="{{ old('client_name', $enquiryLog->client_name ?? ($project->client_name ?? '')) }}" required {{ isset($project) ? 'readonly' : '' }}>
        </div>
    </div>
    
    <!-- Second Row -->
    <div class="col-12 col-md-6 col-lg-4">
        <div class="form-group">
            <label for="contact_person" class="form-label small text-muted mb-1">Contact Person</label>
            <input type="text" name="contact_person" class="form-control form-control-sm" 
                   value="{{ old('contact_person', $enquiryLog->contact_person ?? '') }}">
        </div>
    </div>
    
    <div class="col-12 col-md-6 col-lg-4">
        <div class="form-group">
            <label for="status" class="form-label small text-muted mb-1">Status</label>
            <select name="status" class="form-select form-select-sm" required>
                <option value="" disabled {{ !isset($enquiryLog) ? 'selected' : '' }}>Select status</option>
                <option value="Open" {{ old('status', $enquiryLog->status ?? '') === 'Open' ? 'selected' : '' }}>Open</option>
                <option value="Quoted" {{ old('status', $enquiryLog->status ?? '') === 'Quoted' ? 'selected' : '' }}>Quoted</option>
                <option value="Approved" {{ old('status', $enquiryLog->status ?? '') === 'Approved' ? 'selected' : '' }}>Approved</option>
                <option value="Declined" {{ old('status', $enquiryLog->status ?? '') === 'Declined' ? 'selected' : '' }}>Declined</option>
            </select>
        </div>
    </div>
    
    <div class="col-12 col-md-6 col-lg-4">
        <div class="form-group">
            <label for="assigned_to" class="form-label small text-muted mb-1">Assigned To</label>
            <input type="text" name="assigned_to" class="form-control form-control-sm" 
                   value="{{ old('assigned_to', $enquiryLog->assigned_to ?? '') }}">
        </div>
    </div>
    
    <!-- Third Row - Full width fields -->
    <div class="col-12">
        <div class="form-group">
            <label for="follow_up_notes" class="form-label small text-muted mb-1">Follow Up Notes</label>
            <textarea name="follow_up_notes" class="form-control form-control-sm" rows="3">{{ old('follow_up_notes', $enquiryLog->follow_up_notes ?? '') }}</textarea>
        </div>
    </div>
    
    <div class="col-12">
        <div class="form-group">
            @php
                $scopeSummary = old('project_scope_summary');
                if (isset($enquiryLog)) {
                    $rawSummary = $enquiryLog->project_scope_summary;
                    $decoded = is_array($rawSummary) ? $rawSummary : json_decode($rawSummary, true);
                    if (!$scopeSummary) {
                        $scopeSummary = is_array($decoded) ? implode(', ', $decoded) : '';
                    }
                }
            @endphp
            <label for="project_scope_summary" class="form-label small text-muted mb-1">
                Project Scope Summary 
                <small class="text-muted">(Enter items separated by commas)</small>
            </label>
            <textarea name="project_scope_summary" class="form-control form-control-sm" rows="3" 
                     placeholder="e.g., Design, Development, Testing, Deployment">{{ $scopeSummary }}</textarea>
            <div class="form-text small">Each item will be saved as a separate scope item.</div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center border-top pt-3 mt-4">
    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Cancel
    </a>
    <button type="submit" class="btn btn-primary px-4">
        <i class="bi bi-save me-1"></i> {{ $buttonText }}
    </button>
</div>
