@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Edit Close-Out Report: {{ $project->name }}
                    </h5>
                </div>
                
                <form action="{{ route('projects.close-out-report.update', [$project, $report ?? $project]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-lg-6">
                                <!-- Project Information -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Project Information
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
                                                   value="{{ old('client_name', $report->client_name ?? $project->client_name) }}">
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
                                                           value="{{ old('project_officer', $report->project_officer ?? optional($project->projectOfficer)->name) }}">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="scope_summary" class="form-label">Scope Summary</label>
                                            <textarea class="form-control" id="scope_summary" name="scope_summary" rows="3">{{ old('scope_summary', $report->scope_summary ?? '') }}</textarea>
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
                                                   value="{{ old('site_location', $report->site_location ?? $project->venue) }}">
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
                    
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('projects.close-out-report.show', [$project, $report ?? $project]) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>
                                Cancel
                            </a>
                            <div>
                                <button type="submit" name="action" value="save" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    Save Changes
                                </button>
                                @if(($report->status ?? 'draft') === 'draft')
                                <button type="submit" name="action" value="submit" class="btn btn-success">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Save & Submit
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
