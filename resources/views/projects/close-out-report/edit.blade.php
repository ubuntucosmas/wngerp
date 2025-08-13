<!-- Edit Report Modal -->
<div class="modal fade" id="editReportModal" tabindex="-1" aria-labelledby="editReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editReportModalLabel">
                    <i class="fas fa-edit me-2"></i>
                    Edit Close-Out Report
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('projects.close-out-report.update', [$project, $report ?? $project]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="modal-body">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-lg-6">
                            <!-- Project Summary -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Project Summary
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="project_title" class="form-label">Project Title</label>
                                        <input type="text" class="form-control" id="project_title" name="project_title" 
                                               value="{{ old('project_title', $report->project_title ?? $project->name) }}">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="client_name" class="form-label">Client Name</label>
                                        <input type="text" class="form-control" id="client_name" name="client_name" 
                                               value="{{ old('client_name', $report->client_name ?? $project->client) }}">
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="project_code" class="form-label">Project Code</label>
                                                <input type="text" class="form-control" id="project_code" name="project_code" 
                                                       value="{{ old('project_code', $report->project_code ?? $project->project_id) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="project_officer" class="form-label">Project Officer</label>
                                                <input type="text" class="form-control" id="project_officer" name="project_officer" 
                                                       value="{{ old('project_officer', $report->project_officer ?? '') }}">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="scope_summary" class="form-label">Scope Summary</label>
                                        <textarea class="form-control" id="scope_summary" name="scope_summary" rows="3">{{ old('scope_summary', $report->scope_summary ?? $project->description) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Timeline Information -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-calendar me-2"></i>
                                        Timeline Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="set_up_date" class="form-label">Setup Date</label>
                                                <input type="date" class="form-control" id="set_up_date" name="set_up_date" 
                                                       value="{{ old('set_up_date', $report->set_up_date ? $report->set_up_date->format('Y-m-d') : '') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="set_down_date" class="form-label">Set Down Date</label>
                                                <input type="date" class="form-control" id="set_down_date" name="set_down_date" 
                                                       value="{{ old('set_down_date', $report->set_down_date ? $report->set_down_date->format('Y-m-d') : '') }}">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="production_start_date" class="form-label">Production Start Date</label>
                                                <input type="date" class="form-control" id="production_start_date" name="production_start_date" 
                                                       value="{{ old('production_start_date', $report->production_start_date ? $report->production_start_date->format('Y-m-d') : '') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="handover_date" class="form-label">Handover Date</label>
                                                <input type="date" class="form-control" id="handover_date" name="handover_date" 
                                                       value="{{ old('handover_date', $report->handover_date ? $report->handover_date->format('Y-m-d') : '') }}">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="site_location" class="form-label">Site Location</label>
                                        <input type="text" class="form-control" id="site_location" name="site_location" 
                                               value="{{ old('site_location', $report->site_location ?? ($project->siteSurveys->first()->location ?? '')) }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Setup Information -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-tools me-2"></i>
                                        Setup Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="setup_dates" class="form-label">Setup Dates</label>
                                        <input type="text" class="form-control" id="setup_dates" name="setup_dates" 
                                               value="{{ old('setup_dates', $report->setup_dates ?? '') }}" 
                                               placeholder="e.g., March 15-17, 2024">
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="estimated_setup_time" class="form-label">Estimated Setup Time</label>
                                                <input type="text" class="form-control" id="estimated_setup_time" name="estimated_setup_time" 
                                                       value="{{ old('estimated_setup_time', $report->estimated_setup_time ?? '') }}" 
                                                       placeholder="e.g., 8 hours">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="actual_setup_time" class="form-label">Actual Setup Time</label>
                                                <input type="text" class="form-control" id="actual_setup_time" name="actual_setup_time" 
                                                       value="{{ old('actual_setup_time', $report->actual_setup_time ?? '') }}" 
                                                       placeholder="e.g., 10 hours">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="team_composition" class="form-label">Team Composition</label>
                                        <textarea class="form-control" id="team_composition" name="team_composition" rows="2" 
                                                  placeholder="List team members and their roles">{{ old('team_composition', $report->team_composition ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-lg-6">
                            <!-- Procurement Information -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-clipboard-list me-2"></i>
                                        Procurement Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="materials_requested_notes" class="form-label">Materials Requested</label>
                                        <textarea class="form-control" id="materials_requested_notes" name="materials_requested_notes" rows="2">{{ old('materials_requested_notes', $report->materials_requested_notes ?? '') }}</textarea>
                                        <div class="form-text">
                                            @if($project->materialLists->count() > 0)
                                                Based on {{ $project->materialLists->count() }} material list(s) in the system
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="items_sourced_externally" class="form-label">Items Sourced Externally</label>
                                        <textarea class="form-control" id="items_sourced_externally" name="items_sourced_externally" rows="2">{{ old('items_sourced_externally', $report->items_sourced_externally ?? '') }}</textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="store_issued_items" class="form-label">Store Issued Items</label>
                                        <textarea class="form-control" id="store_issued_items" name="store_issued_items" rows="2">{{ old('store_issued_items', $report->store_issued_items ?? '') }}</textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="inventory_returns_balance" class="form-label">Inventory Returns & Balance</label>
                                        <textarea class="form-control" id="inventory_returns_balance" name="inventory_returns_balance" rows="2">{{ old('inventory_returns_balance', $report->inventory_returns_balance ?? '') }}</textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="procurement_challenges" class="form-label">Procurement Challenges</label>
                                        <textarea class="form-control" id="procurement_challenges" name="procurement_challenges" rows="2">{{ old('procurement_challenges', $report->procurement_challenges ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Production & QC -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-cogs me-2"></i>
                                        Production & Quality Control
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="packaging_labeling_status" class="form-label">Packaging & Labeling Status</label>
                                        <select class="form-select" id="packaging_labeling_status" name="packaging_labeling_status">
                                            <option value="">Select Status</option>
                                            <option value="completed" {{ old('packaging_labeling_status', $report->packaging_labeling_status ?? '') === 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="in_progress" {{ old('packaging_labeling_status', $report->packaging_labeling_status ?? '') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="pending" {{ old('packaging_labeling_status', $report->packaging_labeling_status ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="qc_findings_resolutions" class="form-label">QC Findings & Resolutions</label>
                                        <textarea class="form-control" id="qc_findings_resolutions" name="qc_findings_resolutions" rows="3">{{ old('qc_findings_resolutions', $report->qc_findings_resolutions ?? '') }}</textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="production_challenges" class="form-label">Production Challenges</label>
                                        <textarea class="form-control" id="production_challenges" name="production_challenges" rows="2">{{ old('production_challenges', $report->production_challenges ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Client & Handover -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-handshake me-2"></i>
                                        Client & Handover
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="client_interactions" class="form-label">Client Interactions</label>
                                        <textarea class="form-control" id="client_interactions" name="client_interactions" rows="2">{{ old('client_interactions', $report->client_interactions ?? '') }}</textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="client_signoff_status" class="form-label">Client Signoff Status</label>
                                        <select class="form-select" id="client_signoff_status" name="client_signoff_status">
                                            <option value="">Select Status</option>
                                            <option value="signed_off" {{ old('client_signoff_status', $report->client_signoff_status ?? '') === 'signed_off' ? 'selected' : '' }}>Signed Off</option>
                                            <option value="pending" {{ old('client_signoff_status', $report->client_signoff_status ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="rejected" {{ old('client_signoff_status', $report->client_signoff_status ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="client_feedback_qr" class="form-label">Client Feedback</label>
                                        <textarea class="form-control" id="client_feedback_qr" name="client_feedback_qr" rows="2">{{ old('client_feedback_qr', $report->client_feedback_qr ?? '') }}</textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="post_handover_adjustments" class="form-label">Post-Handover Adjustments</label>
                                        <textarea class="form-control" id="post_handover_adjustments" name="post_handover_adjustments" rows="2">{{ old('post_handover_adjustments', $report->post_handover_adjustments ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Set-Down & Debrief -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-clipboard-check me-2"></i>
                                        Set-Down & Debrief
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="condition_of_items_returned" class="form-label">Condition of Items Returned</label>
                                        <textarea class="form-control" id="condition_of_items_returned" name="condition_of_items_returned" rows="2">{{ old('condition_of_items_returned', $report->condition_of_items_returned ?? '') }}</textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="site_clearance_status" class="form-label">Site Clearance Status</label>
                                        <select class="form-select" id="site_clearance_status" name="site_clearance_status">
                                            <option value="">Select Status</option>
                                            <option value="cleared" {{ old('site_clearance_status', $report->site_clearance_status ?? '') === 'cleared' ? 'selected' : '' }}>Cleared</option>
                                            <option value="partial" {{ old('site_clearance_status', $report->site_clearance_status ?? '') === 'partial' ? 'selected' : '' }}>Partial</option>
                                            <option value="pending" {{ old('site_clearance_status', $report->site_clearance_status ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="debrief_notes" class="form-label">Debrief Notes</label>
                                        <textarea class="form-control" id="debrief_notes" name="debrief_notes" rows="3">{{ old('debrief_notes', $report->debrief_notes ?? '') }}</textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="onsite_challenges" class="form-label">On-site Challenges</label>
                                        <textarea class="form-control" id="onsite_challenges" name="onsite_challenges" rows="2">{{ old('onsite_challenges', $report->onsite_challenges ?? '') }}</textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="safety_issues" class="form-label">Safety Issues</label>
                                        <textarea class="form-control" id="safety_issues" name="safety_issues" rows="2">{{ old('safety_issues', $report->safety_issues ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attachments Checklist -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-check-square me-2"></i>
                                Attachments Checklist
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @php
                                    $attachmentItems = [
                                        'att_deliverables_ppt' => 'Deliverables PPT',
                                        'att_cutlist' => 'Cut List',
                                        'att_site_survey' => 'Site Survey',
                                        'att_project_budget' => 'Project Budget',
                                        'att_mrf_or_material_list' => 'Material List/MRF',
                                        'att_qc_checklist' => 'QC Checklist',
                                        'att_setup_setdown_checklists' => 'Setup/Set-down Checklists',
                                        'att_client_feedback_form' => 'Client Feedback Form',
                                    ];
                                @endphp
                                @foreach($attachmentItems as $field => $label)
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="{{ $field }}" name="{{ $field }}" value="1" 
                                               {{ old($field, $report->$field ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="{{ $field }}">
                                            {{ $label }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Modal Specific Styles */
.modal-xl {
    max-width: 1200px;
}

.modal-body {
    max-height: 70vh;
    overflow-y: auto;
}

.card {
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
}

.card-header {
    background-color: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 0.75rem 1rem;
}

.card-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1e293b;
}

.form-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
}

.form-control,
.form-select {
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus,
.form-select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
}

.form-text {
    font-size: 0.75rem;
    color: #6b7280;
    margin-top: 0.25rem;
}

.form-check-input:checked {
    background-color: #2563eb;
    border-color: #2563eb;
}

.form-check-label {
    font-size: 0.875rem;
    color: #374151;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .modal-xl {
        max-width: 95%;
        margin: 1rem auto;
    }
    
    .modal-body {
        max-height: 60vh;
    }
}
</style>