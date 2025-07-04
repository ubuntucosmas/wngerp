@extends('layouts.master')

@section('title', 'Job Brief - ' . $project->name)
@section('navbar-title', 'Job Brief')

@section('content')
<div class="container pb-5">
    <!-- Sticky Header -->

       <!-- Breadcrumbs -->
       <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-light p-2 rounded">
            <li class="breadcrumb-item"><a href="{{ route('projects.index') }}"><i class="fas fa-home"></i> Projects</a></li>
            <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}">Project Files</a></li>
            <li class="breadcrumb-item active" aria-current="page">Production Log</li>
        </ol>
    </nav>
    <div class="sticky-header bg-white mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">Production Log</h2>
                <p class="mb-0 text-muted">Project: {{ $project->name }}</p>
            </div>
            <div class="d-flex gap-2">
                @if($production)
                    <a href="{{ route('projects.production.download', $project) }}" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-file-earmark-pdf"></i> Download PDF
                    </a>
                    <a href="{{ route('projects.production.print', $project) }}" class="btn btn-outline-primary btn-sm" target="_blank">
                        <i class="bi bi-printer"></i> Print
                    </a>
                @endif
                <a href="{{ route('projects.files.index', $project) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Back to Project Files
                </a>
            </div>
        </div>
    </div>

    @if(!$production)
        <div class="compact-card bg-white p-5 text-center">
            <div class="py-4">
                <i class="bi bi-journal-x fs-1 text-muted mb-3 opacity-50"></i>
                <h5 class="mb-2">No Production Entry Found</h5>
                <p class="text-muted mb-4">Click the button below to add your first production entry.</p>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#productionFormModal">
                    <i class="bi bi-plus-lg"></i> Add Job Brief
                </button>
            </div>
        </div>
    @else
        <div class="d-flex justify-content-end mb-3">
            <form action="{{ route('projects.production.destroy', [$project, $production]) }}" method="POST" class="me-2" onsubmit="return confirm('Are you sure you want to delete this production record? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="bi bi-trash"></i> Delete Job Brief
                </button>
            </form>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#productionFormModal" 
                @if(isset($production)) 
                    data-edit-mode="true"
                    data-production-id="{{ $production->id }}"
                @endif>
                <i class="bi bi-pencil"></i> Edit Job Brief
            </button>
        </div>

        <div class="row g-4">
            <!-- Main Info Card -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-info-circle text-primary me-2"></i>
                            Basic Information
                        </h6>
                    </div>
                    <div class="card-body pt-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0 border-0 d-flex justify-content-between">
                                <span class="text-muted">Job Number:</span>
                                <span class="fw-medium">{{ $production->job_number ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item px-0 border-0 d-flex justify-content-between">
                                <span class="text-muted">Client:</span>
                                <span class="fw-medium">{{ $production->client_name ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item px-0 border-0 d-flex justify-content-between">
                                <div class="text-muted mb-1">Project Title:</div>
                                <div class="fw-medium">{{ $production->project_title ?? 'N/A' }}</div>
                            </li>
                            <li class="list-group-item px-0 border-0 d-flex justify-content-between">
                                <span class="text-muted">Briefed By:</span>
                                <span class="fw-medium">{{ $production->briefed_by ?? 'N/A' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-calendar3 text-primary me-2"></i>
                            Timeline & Status
                        </h6>
                    </div>
                    <div class="card-body pt-0">
                        <div class="timeline-wrapper">
                            <div class="timeline-item">
                                <div class="timeline-badge bg-primary">
                                    <i class="bi bi-calendar-check"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="text-muted small">Briefing Date</div>
                                    <div class="fw-medium">{{ $production->briefing_date ? $production->briefing_date->format('M d, Y') : 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="timeline-divider">
                                <div class="divider-line"></div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-badge bg-success">
                                    <i class="bi bi-truck"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="text-muted small">Delivery Date</div>
                                    <div class="fw-medium">
                                        @if($production->delivery_date)
                                            {{ $production->delivery_date->format('M d, Y') }}
                                            @if($production->delivery_time)
                                                <span class="text-muted ms-2">{{ $production->delivery_time }}</span>
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team & Materials Card -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-people text-primary me-2"></i>
                            Team & Materials
                        </h6>
                    </div>
                    <div class="card-body pt-0">
                        <div class="mb-3">
                            <div class="text-muted small mb-1">Team Members</div>
                            @if($production->production_team)
                                <div class="bg-light p-2 rounded">
                                    {!! nl2br(e($production->production_team)) !!}
                                </div>
                            @else
                                <p class="text-muted mb-0">No team members assigned</p>
                            @endif
                        </div>
                        <div>
                            <div class="text-muted small mb-1">Required Materials</div>
                            @if($production->materials_required)
                                <div class="bg-light p-2 rounded">
                                    {!! nl2br(e($production->materials_required)) !!}
                                </div>
                            @else
                                <p class="text-muted mb-0">No materials specified</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Key Instructions -->
        @if($production->key_instructions)
        <div class="row g-4 mt-2">
            <div class="col-12">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-lightbulb text-warning me-2"></i>
                            Key Instructions
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="bg-light p-3 rounded h-100">
                            <p class="mb-0">{{ $production->key_instructions }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Notes, Special Considerations & Files Section -->
        <div class="row g-4 mt-2">
            <!-- Additional Notes -->
            @if($production->additional_notes)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-sticky text-primary me-2"></i>
                            Additional Notes
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="bg-light p-3 rounded h-100">
                            <p class="mb-0">{{ $production->additional_notes }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Special Considerations -->
            @if($production->special_considerations)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                            Special Considerations
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="bg-light p-3 rounded h-100">
                            <p class="mb-0">{{ $production->special_considerations }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Files Received Card -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-folder-check text-primary me-2"></i>
                            Files Received
                        </h6>
                    </div>
                    <div class="card-body">
                        @if(is_array($production->files_received) && count($production->files_received) > 0)
                            <ul class="list-unstyled mb-0">
                                @foreach($production->files_received as $file)
                                    @if(is_array($file))
                                        <li class="mb-2 d-flex align-items-center">
                                            <i class="bi bi-file-earmark-text text-muted me-2"></i>
                                            <span class="text-truncate">{{ $file['name'] ?? 'Unnamed file' }}</span>
                                            @if(isset($file['size']) && $file['size'])
                                                <small class="text-muted ms-auto">{{ $file['size'] }}</small>
                                            @endif
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-0">No files received yet</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Tasks Assigned Section -->
        <div class="row g-4 mt-2">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-list-check text-primary me-2"></i>
                            Tasks Assigned
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        @if($production->tasks && $production->tasks->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Assigned To</th>
                                            <th>Due Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($production->tasks as $task)
                                            <tr>
                                                <td style="min-width: 200px;" class="align-middle">
                                                    <div class="fw-medium">{{ $task->title ?? 'Untitled Task' }}</div>
                                                </td>
                                                <td style="min-width: 300px;" class="align-middle">
                                                    @if(!empty($task->description))
                                                        {{ $task->description }}
                                                    @else
                                                        <span class="text-muted">No description</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle">
                                                    @if(!empty($task->assigned_to))
                                                        {{ $task->assigned_to }}
                                                    @else
                                                        <span class="text-muted">Unassigned</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle">
                                                    @if($task->due_date)
                                                        <span class="text-nowrap">{{ $task->due_date->format('M d, Y') }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-3 text-center">
                                <p class="text-muted mb-0">No tasks assigned yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
@endif
    <!-- Production Form Modal -->
    <div class="modal fade" id="productionFormModal" tabindex="-1" aria-labelledby="productionFormModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="modal-title mb-0" id="productionFormModalLabel">
                                <i class="bi bi-briefcase me-2"></i>{{ isset($production) ? 'Edit' : 'Add New' }} Job Brief
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="p-4">
                            <form id="jobBriefForm" action="{{ isset($production) ? route('projects.production.job-brief.update', [$project, $production]) : route('projects.production.job-brief.store', $project) }}" method="POST" class="needs-validation" novalidate onsubmit="return validateForm(this);">
                                @csrf
                                @if(isset($production))
                                    @method('PUT')
                                @endif
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="job_number" class="form-label fw-bold" style="font-size: 0.9rem; font-weight: 500; color: #495057; margin-bottom: 0.4rem;">Job Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="job_number" name="job_number" value="{{ $project->project_id ?? '' }}" required style="border: 1px solid var(--bs-border-color); border-radius: var(--bs-border-radius); padding: 0.5rem 0.75rem; font-size: 0.9rem; transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out; height: auto; min-height: 38px; width: 100%;">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="project_title" class="form-label fw-bold">Project Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="project_title" name="project_title" value="{{ $project->name }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="client_name" class="form-label fw-bold">Client Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="client_name" name="client_name" value="{{ $project->client->name ?? '' }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 mt-4">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="briefing_date" class="form-label fw-bold">Briefing Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="briefing_date" name="briefing_date" value="{{ now()->format('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="briefed_by" class="form-label fw-bold">Briefed By <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="briefed_by" name="briefed_by" value="{{ auth()->user()->name }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="delivery_date" class="form-label fw-bold">Delivery Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="delivery_date" name="delivery_date" required>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 mt-4">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="production_team" class="form-label fw-bold">Production Team Members <span class="text-danger">*</span></label>
                                    <textarea class="form-control form-textarea" id="production_team" name="production_team" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="materials_required" class="form-label fw-bold">Materials Required</label>
                                    <textarea class="form-control form-textarea" id="materials_required" name="materials_required"></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="key_instructions" class="form-label fw-bold">Key Instructions/Notes from Design</label>
                                    <textarea class="form-control form-textarea" id="key_instructions" name="key_instructions"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 mt-4">
                            <!-- Left Column: Special Considerations -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="special_considerations" class="form-label fw-bold">Special Considerations (Site, Safety, etc.)</label>
                                    <textarea class="form-control form-textarea" id="special_considerations" name="special_considerations" style="min-height: 150px;"></textarea>
                                </div>
                            </div>
                            
                            <!-- Middle Column: Files Received -->
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-bold d-block">Files Received <span class="text-danger">*</span></label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="files_received" id="files_received_yes" value="1" required>
                                        <label class="form-check-label" for="files_received_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="files_received" id="files_received_no" value="0" required>
                                        <label class="form-check-label" for="files_received_no">No</label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right Column: Additional Notes -->
                            <div class="col-md-5">
                                <div class="mb-3">
                                    <label for="additional_notes" class="form-label fw-bold">Additional Notes</label>
                                    <textarea class="form-control form-textarea" id="additional_notes" name="additional_notes" style="min-height: 150px;"></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- Tasks Table -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="mb-3">Assigned Tasks & Responsibilities</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="tasksTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 25%;">Title</th>
                                                <th style="width: 40%;">Description</th>
                                                <th style="width: 20%;">Assigned To</th>
                                                <th style="width: 15%;">Due Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm" name="tasks[0][title]" placeholder="Task title" required>
                                                </td>
                                                <td>
                                                    <textarea class="form-control form-control-sm" name="tasks[0][description]" placeholder="Task description" rows="2"></textarea>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm" name="tasks[0][assigned_to]" placeholder="Team member">
                                                </td>
                                                <td>
                                                    <input type="date" class="form-control form-control-sm" name="tasks[0][due_date]" required>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="addTask">
                                        <i class="bi bi-plus-circle"></i> Add Task
                                    </button>
                                </div>
                            </div>
                        </div>
                       
                    </form>
                </div>
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('jobBriefForm').reset()">Reset</button>
                        <button type="submit" form="jobBriefForm" class="btn btn-primary btn-sm">
                            <i class="bi bi-save me-1"></i> Save Job Brief
                        </button>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

    @push('scripts')
    <script>
        // Form validation function
        function validateForm(form) {
            'use strict';
            
            // Check if the form is valid
            if (!form.checkValidity()) {
                // Add was-validated class to show validation messages
                form.classList.add('was-validated');
                
                // Find first invalid field and focus on it
                const firstInvalid = form.querySelector(':invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                    
                    // Scroll to the first invalid field if it's in a modal
                    const modal = form.closest('.modal');
                    if (modal) {
                        const modalContent = modal.querySelector('.modal-content');
                        modalContent.scrollTop = firstInvalid.offsetTop - 20;
                    }
                }
                
                return false;
            }
            
            return true;
        }

        // Enable Bootstrap form validation
        document.addEventListener('DOMContentLoaded', function() {
            let taskCount = 1;
            const addTaskBtn = document.getElementById('addTask');
            
            if (addTaskBtn) {
                addTaskBtn.addEventListener('click', function() {
                    const tbody = document.querySelector('#tasksTable tbody');
                    if (tbody) {
                        const newRow = document.createElement('tr');
                        newRow.innerHTML = `
                            <td><input type="text" class="form-control form-control-sm" name="tasks[${taskCount}][task]" placeholder="Task description"></td>
                            <td><input type="text" class="form-control form-control-sm" name="tasks[${taskCount}][assigned_to]" placeholder="Team member"></td>
                            <td><input type="date" class="form-control form-control-sm" name="tasks[${taskCount}][deadline]"></td>
                            <td>
                                <select class="form-select form-select-sm" name="tasks[${taskCount}][status]">
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </td>
                        `;
                        tbody.appendChild(newRow);
                        taskCount++;
                    }
                });
            }
            // Add input event listeners to clear validation state when user starts typing
            const forms = document.querySelectorAll('.needs-validation');
            forms.forEach(form => {
                const inputs = form.querySelectorAll('input, textarea, select');
                inputs.forEach(input => {
                    input.addEventListener('input', function() {
                        if (input.checkValidity()) {
                            input.classList.remove('is-invalid');
                            input.classList.add('is-valid');
                        } else {
                            input.classList.remove('is-valid');
                            input.classList.add('is-invalid');
                        }
                    });
                });
            });
        });
    </script>
    @endpush

    <!-- Modal Initialization Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM fully loaded');
            
            // Task counter for dynamic task addition
            let taskCounter = 1;
            
            // Handle edit mode when modal is shown
            const productionFormModal = document.getElementById('productionFormModal');
            if (productionFormModal) {
                productionFormModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const isEditMode = button.getAttribute('data-edit-mode') === 'true';
                    
                    if (isEditMode) {
                        // Get the production data from PHP
                        const productionData = @json($production ?? []);
                        const tasksData = @json($production->tasks ?? []);
                        const form = document.getElementById('jobBriefForm');
                        
                        if (Object.keys(productionData).length > 0) {
                            // Update form fields with production data
                            for (const [key, value] of Object.entries(productionData)) {
                                const input = form.querySelector(`[name="${key}"]`);
                                if (input) {
                                    if (input.type === 'checkbox' || input.type === 'radio') {
                                        input.checked = Boolean(value);
                                    } else if (input.type === 'file') {
                                        // Skip file inputs
                                        continue;
                                    } else if (input.type === 'date' && value) {
                                        // Format date for date inputs
                                        const date = new Date(value);
                                        const formattedDate = date.toISOString().split('T')[0];
                                        input.value = formattedDate;
                                    } else {
                                        input.value = value || '';
                                    }
                                }
                                
                                // Handle textareas
                                const textarea = form.querySelector(`textarea[name="${key}"]`);
                                if (textarea) {
                                    textarea.value = value || '';
                                }
                                
                                // Handle select elements
                                const select = form.querySelector(`select[name="${key}"]`);
                                if (select) {
                                    const option = select.querySelector(`option[value="${value}"]`);
                                    if (option) {
                                        option.selected = true;
                                    }
                                }
                            }
                            
                            // Handle tasks
                            const tbody = document.querySelector('#tasksTable tbody');
                            if (tbody && tasksData.length > 0) {
                                // Clear existing rows except the first one
                                while (tbody.firstChild) {
                                    tbody.removeChild(tbody.firstChild);
                                }
                                
                                // Add rows for each task
                                tasksData.forEach((task, index) => {
                                    const newRow = document.createElement('tr');
                                    const taskId = index + 1;
                                    const dueDate = task.due_date ? new Date(task.due_date).toISOString().split('T')[0] : '';
                                    
                                    newRow.innerHTML = `
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="tasks[${taskId}][title]" value="${task.title || ''}" placeholder="Task title" required>
                                        </td>
                                        <td>
                                            <textarea class="form-control form-control-sm" name="tasks[${taskId}][description]" placeholder="Task description" rows="2">${task.description || ''}</textarea>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="tasks[${taskId}][assigned_to]" value="${task.assigned_to || ''}" placeholder="Team member">
                                        </td>
                                        <td>
                                            <input type="date" class="form-control form-control-sm" name="tasks[${taskId}][due_date]" value="${dueDate}" required>
                                        </td>
                                    `;
                                    tbody.appendChild(newRow);
                                    taskCounter = taskId + 1; // Update counter
                                });
                            }
                        }
                    } else {
                        // Reset form for new entry
                        const form = document.getElementById('jobBriefForm');
                        if (form) {
                            form.reset();
                            // Clear tasks table except first row
                            const tbody = document.querySelector('#tasksTable tbody');
                            if (tbody) {
                                while (tbody.rows.length > 1) {
                                    tbody.deleteRow(1);
                                }
                                // Reset first row
                                if (tbody.rows[0]) {
                                    const firstRow = tbody.rows[0];
                                    firstRow.querySelector('input[type="text"]').value = '';
                                    firstRow.querySelector('textarea').value = '';
                                    firstRow.querySelector('input[type="date"]').value = '';
                                }
                            }
                            taskCounter = 1;
                        }
                    }
                });
            }
            
            // Function to add a new task row
            function addNewTaskRow() {
                const tbody = document.querySelector('#tasksTable tbody');
                
                // Find the highest existing task index to prevent duplicates
                const existingInputs = tbody.querySelectorAll('input[name^="tasks["]');
                const existingIndices = Array.from(existingInputs).map(input => {
                    const match = input.name.match(/tasks\[(\d+)\]/);
                    return match ? parseInt(match[1]) : 0;
                });
                
                const maxIndex = Math.max(0, ...existingIndices);
                const newIndex = maxIndex + 1;
                
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td>
                        <input type="text" class="form-control form-control-sm" name="tasks[${newIndex}][title]" placeholder="Task title" required>
                    </td>
                    <td>
                        <textarea class="form-control form-control-sm" name="tasks[${newIndex}][description]" placeholder="Task description" rows="2"></textarea>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="tasks[${newIndex}][assigned_to]" placeholder="Team member">
                    </td>
                    <td>
                        <input type="date" class="form-control form-control-sm" name="tasks[${newIndex}][due_date]" required>
                    </td>
                `;
                tbody.appendChild(newRow);
                
                // Update the task counter to be one more than the new index
                taskCounter = newIndex + 1;
                
                return false;
            }
            
            // Function to initialize the Add Task button
            function initAddTaskButton() {
                const addTaskButton = document.getElementById('addTask');
                if (!addTaskButton) return;
                
                // Remove any existing click event listeners
                const newButton = addTaskButton.cloneNode(true);
                addTaskButton.parentNode.replaceChild(newButton, addTaskButton);
                
                // Add click event listener using event delegation
                newButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    addNewTaskRow();
                    return false;
                });
                
                return newButton;
            }
            
            // Initialize the Add Task button
            let addTaskButton = initAddTaskButton();
            
            // Check if Bootstrap is loaded
            if (typeof bootstrap === 'undefined') {
                console.error('Bootstrap is not loaded');
                return;
            }
            
            // Get modal element
            const modalElement = document.getElementById('productionFormModal');
            if (!modalElement) {
                console.error('Modal element not found');
                return;
            }
            
            console.log('Modal element found, initializing...');
            
            // Initialize modal with default options
            const modal = new bootstrap.Modal(modalElement, {
                backdrop: true,
                keyboard: true
            });
            
            // Add click handlers to all modal triggers
            const modalTriggers = document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target="#productionFormModal"]');
            modalTriggers.forEach(trigger => {
                trigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Modal trigger clicked');
                    
                    // Reinitialize the Add Task button when modal is about to be shown
                    addTaskButton = initAddTaskButton();
                    
                    // Show the modal
                    modal.show();
                });
            });
            
            // Also reinitialize the button when the modal is shown
            modalElement.addEventListener('shown.bs.modal', function() {
                addTaskButton = initAddTaskButton();
            });
            
            // Debug: Log modal events
            modalElement.addEventListener('show.bs.modal', function() {
                console.log('Modal show event triggered');
            });
            
            console.log('Modal initialization complete');
        });
    </script>
</div>
@endsection
