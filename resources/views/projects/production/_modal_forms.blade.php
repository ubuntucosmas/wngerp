<!-- New Brief Modal -->
<div class="modal fade" id="newBriefModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Job Brief</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('projects.production.job-brief.store', $project) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="job_number" class="form-label fw-bold">Job Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="job_number" name="job_number" 
                                       value="{{ $project->project_id ?? '' }}" required>
                                <small class="form-text text-muted">Project reference number</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="project_title" class="form-label fw-bold">Project Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="project_title" name="project_title" 
                                       value="{{ $project->name }}" required>
                                <small class="form-text text-muted">Project name or description</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="client_name" class="form-label fw-bold">Client Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="client_name" name="client_name" 
                                       value="{{ $project->client->name ?? '' }}" required>
                                <small class="form-text text-muted">Client organization name</small>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-4">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="briefing_date" class="form-label fw-bold">Briefing Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="briefing_date" name="briefing_date" 
                                       value="{{ now()->format('Y-m-d') }}" required>
                                <small class="form-text text-muted">Date when project was briefed</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="briefed_by" class="form-label fw-bold">Briefed By <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="briefed_by" name="briefed_by" 
                                       value="{{ auth()->user()->name }}" required>
                                <small class="form-text text-muted">Name of the person who briefed the project</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="delivery_date" class="form-label fw-bold">Delivery Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="delivery_date" name="delivery_date" required>
                                <small class="form-text text-muted">Project completion date</small>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="production_team" class="form-label fw-bold">Production Team Members <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="production_team" name="production_team" rows="3" required></textarea>
                                <small class="form-text text-muted">List all team members separated by commas</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="materials_required" class="form-label fw-bold">Materials Required</label>
                                <textarea class="form-control" id="materials_required" name="materials_required" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="key_instructions" class="form-label fw-bold">Key Instructions/Notes from Design</label>
                                <textarea class="form-control" id="key_instructions" name="key_instructions" rows="4"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="special_considerations" class="form-label fw-bold">Special Considerations (Site, Safety, etc.)</label>
                                <textarea class="form-control" id="special_considerations" name="special_considerations" rows="4"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Files Received? <span class="text-danger">*</span></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="files_received" id="files_yes" value="1" required>
                                    <label class="form-check-label" for="files_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="files_received" id="files_no" value="0" checked>
                                    <label class="form-check-label" for="files_no">No</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-4">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="additional_notes" class="form-label fw-bold">Additional Notes</label>
                                <div class="w-100">
                                    <textarea class="form-control" id="additional_notes" name="additional_notes" rows="6" style="width: 100%; min-height: 150px; resize: none;"></textarea>
                                </div>
                                <small class="form-text text-muted">Add any extra information or notes here</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save Job Brief</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Brief Modal -->
<div class="modal fade" id="editBriefModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Job Brief</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('projects.production.job-brief.store', $project) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="job_number" class="form-label fw-bold">Job Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="job_number" name="job_number" 
                                       value="{{ old('job_number', $production->job_number) }}" required>
                                <small class="form-text text-muted">Project reference number</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="project_title" class="form-label fw-bold">Project Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="project_title" name="project_title" 
                                       value="{{ old('project_title', $production->project_title) }}" required>
                                <small class="form-text text-muted">Project name or description</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="client_name" class="form-label fw-bold">Client Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="client_name" name="client_name" 
                                       value="{{ old('client_name', $production->client_name) }}" required>
                                <small class="form-text text-muted">Client organization name</small>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-4">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="briefing_date" class="form-label fw-bold">Briefing Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="briefing_date" name="briefing_date" 
                                       value="{{ old('briefing_date', $production->briefing_date->format('Y-m-d')) }}" required>
                                <small class="form-text text-muted">Date when project was briefed</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="briefed_by" class="form-label fw-bold">Briefed By <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="briefed_by" name="briefed_by" 
                                       value="{{ old('briefed_by', $production->briefed_by) }}" required>
                                <small class="form-text text-muted">Name of the person who briefed the project</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="delivery_date" class="form-label fw-bold">Delivery Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="delivery_date" name="delivery_date" 
                                       value="{{ old('delivery_date', $production->delivery_date->format('Y-m-d')) }}" required>
                                <small class="form-text text-muted">Project completion date</small>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="production_team" class="form-label fw-bold">Production Team Members <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="production_team" name="production_team" rows="3" required>{{ old('production_team', $production->production_team) }}</textarea>
                                <small class="form-text text-muted">List all team members separated by commas</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="materials_required" class="form-label fw-bold">Materials Required</label>
                                <textarea class="form-control" id="materials_required" name="materials_required" rows="3">{{ old('materials_required', $production->materials_required) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="key_instructions" class="form-label fw-bold">Key Instructions/Notes from Design</label>
                                <textarea class="form-control" id="key_instructions" name="key_instructions" rows="4">{{ old('key_instructions', $production->key_instructions) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="special_considerations" class="form-label fw-bold">Special Considerations (Site, Safety, etc.)</label>
                                <textarea class="form-control" id="special_considerations" name="special_considerations" rows="4">{{ old('special_considerations', $production->special_considerations) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Files Received? <span class="text-danger">*</span></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="files_received" id="files_yes" value="1" 
                                           {{ old('files_received', $production->files_received) ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="files_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="files_received" id="files_no" value="0" 
                                           {{ !old('files_received', $production->files_received) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="files_no">No</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-4">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="additional_notes" class="form-label fw-bold">Additional Notes</label>
                                <div class="w-100">
                                    <textarea class="form-control" id="additional_notes" name="additional_notes" rows="6" style="width: 100%; min-height: 150px; resize: none;">{{ old('additional_notes', $production->additional_notes) }}</textarea>
                                </div>
                                <small class="form-text text-muted">Add any extra information or notes here</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Update Job Brief</button>
                </div>
            </form>
        </div>
    </div>
</div>
