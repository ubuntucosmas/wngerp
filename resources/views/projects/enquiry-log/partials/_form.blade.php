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
                   value="{{ old('venue', $enquiryLog->venue ?? (isset($enquiry) ? $enquiry->venue : ($project->venue ?? ''))) }}" required {{ isset($project) ? 'readonly' : '' }}>
        </div>
    </div>
    
    <div class="col-12 col-md-6 col-lg-3">
        <div class="form-group">
            @php
                $dateReceived = old('date_received');
                if (!$dateReceived && isset($enquiryLog->date_received)) {
                    $dateReceived = $enquiryLog->date_received;
                } elseif (!$dateReceived && isset($enquiry) && $enquiry->date_received) {
                    $dateReceived = $enquiry->date_received;
                } elseif (!$dateReceived && isset($project->enquirySource->date_received)) {
                    $dateReceived = $project->enquirySource->date_received;
                } else {
                    $dateReceived = now();
                }
                
                if (is_string($dateReceived)) {
                    $dateReceived = \Carbon\Carbon::parse($dateReceived);
                }
                $formattedDate = $dateReceived->format('Y-m-d\TH:i');
            @endphp
            <label for="date_received" class="form-label small text-muted mb-1">Date & Time Received</label>
            <input type="datetime-local" name="date_received" class="form-control form-control-sm" 
                   value="{{ $formattedDate }}" required>
        </div>
    </div>
    
    <div class="col-12 col-md-6 col-lg-5">
        <div class="form-group">
            <label for="client_name" class="form-label small text-muted mb-1">Client Name</label>
            <input type="text" name="client_name" class="form-control form-control-sm" 
                   value="{{ old('client_name', $enquiryLog->client_name ?? (isset($enquiry) ? $enquiry->client_name : ($project->client_name ?? ''))) }}" required {{ isset($project) ? 'readonly' : '' }}>
        </div>
    </div>
    
    <!-- Second Row -->
    <div class="col-12 col-md-6 col-lg-4">
        <div class="form-group">
            <label for="contact_person" class="form-label small text-muted mb-1">Contact Person</label>
            <input type="text" name="contact_person" class="form-control form-control-sm" 
                   value="{{ old('contact_person', $enquiryLog->contact_person ?? (isset($enquiry) ? $enquiry->contact_person : ($project->contact_person ?? ''))) }}">
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
            <label class="form-label small text-muted mb-1">Project Officer</label>
            @php
                // Get the project officer's name or fallback to enquiry source or empty string
                if (isset($project)) {
                $projectOfficerName = $project->projectOfficer->name ?? 
                                    ($project->enquirySource->assigned_to ?? 'Not assigned');
                } elseif (isset($enquiry)) {
                    $projectOfficerName = $enquiry->assigned_po ?? 'Not assigned';
                } else {
                    $projectOfficerName = 'Not assigned';
                }
            @endphp
            <input type="text" class="form-control form-control-sm bg-light" 
                   value="{{ $projectOfficerName }}" readonly>
            <input type="hidden" name="assigned_to" value="{{ $projectOfficerName }}">
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
                
                // If there's no old input and we have an enquiry log, use its data
                if (empty($scopeSummary) && isset($enquiryLog)) {
                    $rawSummary = $enquiryLog->project_scope_summary;
                    if (!empty($rawSummary)) {
                        $decoded = is_array($rawSummary) ? $rawSummary : json_decode($rawSummary, true);
                        $scopeSummary = is_array($decoded) ? implode(', ', $decoded) : $rawSummary;
                    }
                }
                
                // If still no data, try to get from enquiry
                if (empty($scopeSummary) && isset($enquiry) && $enquiry) {
                    $rawSummary = $enquiry->project_deliverables;
                    if (!empty($rawSummary)) {
                        $decoded = is_array($rawSummary) ? $rawSummary : json_decode($rawSummary, true);
                        $scopeSummary = is_array($decoded) ? implode(', ', $decoded) : $rawSummary;
                    }
                }
                
                // If we still don't have anything, use an empty string
                $scopeSummary = $scopeSummary ?? '';
            @endphp
            
            <label for="project_scope_summary" class="form-label small text-muted mb-1">
                Project Scope Summary 
                <small class="text-muted">(Enter items separated by commas)</small>
            </label>
            <textarea name="project_scope_summary" id="project_scope_summary" class="form-control form-control-sm" rows="3" 
                     placeholder="e.g., Design, Development, Testing, Deployment">{{ $scopeSummary }}</textarea>
            <div class="form-text small">Each item will be saved as a separate scope item.</div>
            
            @if(isset($enquiry) && $enquiry && empty($scopeSummary))
                <div class="alert alert-warning small mt-2">
                    No project scope found in the associated enquiry.
                </div>
            @endif
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
