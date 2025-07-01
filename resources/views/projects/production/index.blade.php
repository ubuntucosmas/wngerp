@extends('layouts.master')

@section('title', 'Job Brief - ' . $project->name)
@section('navbar-title', 'Job Brief')

@section('content')
<div class="container">
    <!-- Sticky Header -->
    <div class="sticky-header bg-white mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">Production Log</h2>
                <p class="mb-0 text-muted">Project: {{ $project->name }}</p>
            </div>
            <div>
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
                    <i class="bi bi-plus-lg"></i> Add Production Entry
                </button>
            </div>
        </div>
  @else
    
    <div class="d-flex justify-content-end mb-3">
        @if($production)
            <form action="{{ route('projects.production.destroy', [$project, $production]) }}" method="POST" class="me-2" onsubmit="return confirm('Are you sure you want to delete this production record? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="bi bi-trash"></i> Delete Production Record
                </button>
            </form>
        @endif
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#productionFormModal">
            <i class="bi bi-plus-lg"></i> {{ $production ? 'Edit' : 'Add New' }} Job Brief
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
                            <span class="fw-medium">{{ $production ? $production->job_number : 'N/A' }}</span>
                        </li>
                        <li class="list-group-item px-0 border-0 d-flex justify-content-between">
                            <span class="text-muted">Client:</span>
                            <span class="fw-medium">{{ $production ? $production->client_name : 'N/A' }}</span>
                        </li>
                        <li class="list-group-item px-0 border-0">
                            <div class="text-muted mb-1">Project Title:</div>
                            <div class="fw-medium">{{ $production ? $production->project_title : 'N/A' }}</div>
                        </li>
                        <li class="list-group-item px-0 border-0 d-flex justify-content-between">
                            <span class="text-muted">Briefed By:</span>
                            <span class="fw-medium">{{ $production ? $production->briefed_by : 'N/A' }}</span>
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
                                <div class="fw-medium">{{ $production ? ($production->briefing_date ? $production->briefing_date->format('M d, Y') : 'N/A') : 'N/A' }}</div>
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
                                <div class="fw-medium">{{ $production ? ($production->delivery_date ? $production->delivery_date->format('M d, Y') : 'N/A') : 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Status:</span>
                            <span class="badge bg-{{ $production && $production->status ? ($production->status === 'pending' ? 'warning' : ($production->status === 'approved' ? 'success' : 'info')) : 'secondary' }} px-3 py-2">
                                {{ $production && $production->status ? ucfirst($production->status) : 'N/A' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Files Received:</span>
                            <span class="badge bg-{{ $production ? ($production->files_received ? 'success' : 'secondary') : 'secondary' }} px-3 py-2">
                                {{ $production ? ($production->files_received ? 'Yes' : 'No') : 'N/A' }}
                            </span>
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
                    <div class="mb-4">
                        <label class="form-label small text-muted mb-2">Production Team</label>
                        <div class="team-list">
                            @if($production && !empty($production->production_team))
                                @foreach(explode(',', $production->production_team) as $member)
                                    @if(trim($member) !== '')
                                        <div class="team-member d-flex align-items-center mb-2">
                                            <div class="member-avatar bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                            <span class="small">{{ trim($member) }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="text-muted small">No team members assigned</div>
                            @endif
                        </div>
                    </div>

                    <div class="border-top pt-3">
                        <label class="form-label small text-muted mb-2">Status Notes</label>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0 small">{{ $production && $production->status_notes ? $production->status_notes : 'No status notes available' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes & Materials Section -->
    <div class="row g-4 mt-2">
        <!-- Key Instructions Card -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-chat-square-text text-primary me-2"></i>
                        Key Instructions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0">{{ $production && $production->key_instructions ? $production->key_instructions : 'No key instructions provided' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Special Considerations Card -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                        Special Considerations
                    </h6>
                </div>
                <div class="card-body">
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0">{{ $production && $production->special_considerations ? $production->special_considerations : 'No special considerations' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Materials Required Card -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-box-seam text-primary me-2"></i>
                        Materials Required
                    </h6>
                </div>
                <div class="card-body">
                    <div class="bg-light p-3 rounded">
                        @if($production && !empty($production->materials_required))
                            <ul class="list-unstyled mb-0">
                                @foreach(explode('\n', $production->materials_required) as $material)
                                    @if(trim($material))
                                        <li class="mb-1">
                                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                                            {{ trim($material) }}
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            <p class="mb-0">No materials specified</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Notes Card -->
    @if($production && $production->additional_notes)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-sticky text-primary me-2"></i>
                        Additional Notes
                    </h6>
                </div>
                <div class="card-body">
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0">{{ $production->additional_notes }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

@endif

<!-- Production Form Modal -->
<div class="modal fade" id="productionFormModal" tabindex="-1" aria-labelledby="productionFormModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content" style="border: none; border-radius: 0.5rem; box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15); overflow: hidden;">
      <div class="modal-header" style="background-color: #f8f9fa; border-bottom: 1px solid #e9ecef; padding: 1.25rem 1.5rem;">
        <h5 class="modal-title" id="productionFormModalLabel" style="font-size: 1.25rem; font-weight: 600; color: #2c3e50; margin: 0; line-height: 1.5;">Add New Job Brief</h5>
      </div>
      <div class="modal-body" style="padding: 1.5rem; max-height: 70vh; overflow-y: auto;">
        <form action="{{ route('projects.production.job-brief.store', $project) }}" method="POST" class="needs-validation" novalidate style="--bs-border-color: #ced4da; --bs-border-radius: 0.375rem; --bs-focus-ring-color: rgba(13, 110, 253, 0.25);">
          @csrf
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
  <div class="col-md-5">
    <div class="mb-3">
      <label for="special_considerations" class="form-label fw-bold">Special Considerations (Site, Safety, etc.)</label>
      <textarea class="form-control form-textarea" id="special_considerations" name="special_considerations"></textarea>
    </div>
  </div>
  <div class="col-md-3">
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
  <div class="col-md-4">
    <div class="mb-3">
      <label for="additional_notes" class="form-label fw-bold">Additional Notes</label>
      <div class="w-100">
        <textarea class="form-control form-textarea" id="additional_notes" name="additional_notes" style="width: 100%; resize: none;"></textarea>
      </div>
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
                      <th style="width: 40%;">Task</th>
                      <th style="width: 30%;">Assigned To</th>
                      <th style="width: 20%;">Deadline</th>
                      <th style="width: 10%;">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><input type="text" class="form-control form-control-sm" name="tasks[0][task]" placeholder="Task description"></td>
                      <td><input type="text" class="form-control form-control-sm" name="tasks[0][assigned_to]" placeholder="Team member"></td>
                      <td><input type="date" class="form-control form-control-sm" name="tasks[0][deadline]"></td>
                      <td>
                        <select class="form-select form-select-sm" name="tasks[0][status]">
                          <option value="pending">Pending</option>
                          <option value="in_progress">In Progress</option>
                          <option value="completed">Completed</option>
                        </select>
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
      
        <!-- Additional Notes has been moved next to Files Received -->
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
            <button type="reset" class="btn btn-outline-secondary btn-sm me-2">Reset</button>
            <button type="submit" class="btn btn-primary btn-sm">Save Job Brief</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
        </div>
    </div>
</div>
@push('styles')
<style>
    /* Card Styling */
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.05) !important;
    }
    
    .card-header {
        background-color: #f8f9fa;
    }
    
    /* Timeline Styling */
    .timeline-wrapper {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 20px;
    }
    
    .timeline-badge {
        position: absolute;
        left: -30px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
    }
    
    .timeline-content {
        padding-bottom: 10px;
    }
    
    .timeline-divider {
        position: relative;
        height: 30px;
        margin-left: -30px;
        display: flex;
        align-items: center;
    }
    
    .divider-line {
        height: 100%;
        width: 2px;
        background: #e9ecef;
        margin-left: 11px;
    }
    
    /* Team Member List */
    .team-member {
        padding: 6px 0;
        border-bottom: 1px solid #f1f1f1;
    }
    
    .team-member:last-child {
        border-bottom: none;
    }
    
    /* Material List */
    .material-item {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
        padding: 6px 10px;
        background: white;
        border-radius: 4px;
        border-left: 3px solid #0d6efd;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .card {
            margin-bottom: 20px;
        }
    }
    
    /* Modal Styling - Compact */
    #productionFormModal .modal-content {
        border: none;
        border-radius: 0.25rem;
        box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    #productionFormModal .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        padding: 0.5rem 1rem;
    }
    
    #productionFormModal .modal-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
        line-height: 1.25;
    }
    
    #productionFormModal .modal-body {
        padding: 0.75rem;
        max-height: 70vh;
        overflow-y: auto;
    }
    
    #productionFormModal .modal-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #e9ecef;
        padding: 0.5rem;
        justify-content: flex-end;
    }
    
    /* Form Elements */
    #productionFormModal .form-label {
        font-size: 0.8rem;
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.2rem;
    }
    
    #productionFormModal .form-control,
    #productionFormModal .form-select,
    #productionFormModal textarea {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 0.3rem 0.5rem;
        font-size: 0.85rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        height: auto;
        min-height: 32px;
    }
    
    #productionFormModal .form-textarea {
        min-height: 80px;
        height: 100px;
        resize: vertical;
    }
    
    #productionFormModal .form-control:focus,
    #productionFormModal .form-select:focus,
    #productionFormModal textarea:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.15rem rgba(0, 123, 255, 0.15);
        outline: 0;
    }
    
    /* Buttons */
    #productionFormModal .btn {
        padding: 0.3rem 0.8rem;
        font-size: 0.8rem;
        font-weight: 500;
        border-radius: 0.25rem;
        transition: all 0.2s ease-in-out;
    }
    
    #productionFormModal .btn-outline-secondary {
        border-color: #6c757d;
        color: #6c757d;
    }
    
    #productionFormModal .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
    }
    
    #productionFormModal .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    #productionFormModal .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
    }
    
    /* Reduce spacing between form groups */
    #productionFormModal .mb-3 {
        margin-bottom: 0.5rem !important;
    }
    
    #productionFormModal .row {
        --bs-gutter-x: 0.75rem;
        --bs-gutter-y: 0.5rem;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        #productionFormModal .modal-body {
            padding: 0.5rem;
        }
        
        #productionFormModal .col-md-4,
        #productionFormModal .col-md-5,
        #productionFormModal .col-md-3 {
            margin-bottom: 0.5rem;
        }
    }
</style>
@endpush

    @push('scripts')
    <script>
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
        });
    </script>
    @endpush

    <!-- Modal Initialization Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM fully loaded');
            
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
                    modal.show();
                });
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
