@extends('layouts.master')

@section('title', 'Phase Tasks')

@section('content')

<style>
    :root {
        --primary-color: #0BADD3;
        --secondary-color: #6E6F71;
        --accent-color: #C8DA30;
        --light-bg: #f8fafc;
        --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    body {
        font-family: 'Inter', 'Poppins', sans-serif;
        background-color: var(--light-bg);
        color: #333;
        line-height: 1.6;
    }

    .task-card {
        font-size: 0.9rem;
        border-radius: 12px;
        background: white;
        box-shadow: var(--card-shadow);
        padding: 1.25rem;
        margin-bottom: 1rem;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        border-left: 4px solid transparent;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }

    .task-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        border-left-color: var(--primary-color);
    }

    .task-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--secondary-color);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .details-pane {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        padding: 1.75rem;
        position: relative;
        border-left: 4px solid transparent;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }

    .details-pane.active-pointer {
        border-left-color: #007bff; /* Blue left border when active */
    }

    .details-pane.active-pointer::before {
        content: '';
        position: absolute;
        top: var(--pointer-top, 50%); /* Dynamically set by JavaScript */
        left: -20px; /* New width of the pointer */
        width: 0;
        height: 0;
        border-top: 20px solid transparent;
        border-bottom: 20px solid transparent;
        border-right: 20px solid #007bff; /* Blue pointer, matching the active border */
        filter: drop-shadow(-2px 0px 3px rgba(0,0,0,0.15)); /* Enhanced shadow */
        z-index: 1;
    }

    .sticky-col {
        height: calc(100vh - 140px);
        overflow-y: auto;
        position: sticky;
        top: 120px;
    }

    .sticky-header {
        position: sticky;
        top: 0;
    }

    .sticky-task-header {
        position: sticky;
        top: 0;
    }

    .scrollable-content {
        height: calc(100vh - 150px); /* Adjust based on header height */
        overflow-y: auto;
        padding: 1rem;
    }

    .task-header {
        position: sticky;
        top: 0;
        background: white;
        padding: 1rem;
        border-bottom: 1px solid #eee;
        z-index: 1;
    }

    .deliverables-list {
        max-height: 180px;
        overflow-x: auto;
    }

    .form-section + .form-section {
        margin-top: 1.5rem;
    }

    .task-card-active {
        background-color: #e0f7fa; /* Light blue background */
        border-left: 5px solid #007bff; /* Thicker blue left border */
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2); /* Existing shadow */
    }

    .status-text-extra-small {
        font-size: 0.7rem; /* Custom smaller font size */
    }
   /* General Deliverables Styling */
.deliverables-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

/* Deliverable Item Container */
.deliverable-item {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    background: #f9f9f9;
    padding: 12px 15px;
    border-radius: 8px;
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease-in-out;
    position: relative;
}

.deliverable-item:hover {
    background: #eef2f7;
    transform: scale(1.02);
}

/* Numbering for Deliverables */
.deliverable-number {
    font-weight: bold;
    font-size: 1rem;
    width: 30px;
    text-align: right;
    margin-right: 12px;
    color: #007bff;
}

/* Checkbox Styling */
.deliverable-item input[type="checkbox"] {
    accent-color: #28a745;
    transform: scale(1.3);
    margin-right: 12px;
    cursor: pointer;
}

/* Tooltip Message */
.deliverable-item input[type="checkbox"]::after {
    content: "Mark as complete";
    position: absolute;
    top: -25px;
    left: 0;
    background: #333;
    color: #fff;
    padding: 4px 6px;
    border-radius: 4px;
    font-size: 0.5rem;
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
    pointer-events: none;
}

.deliverable-item input[type="checkbox"]:hover::after {
    opacity: 1;
}

/* Input Text Styling */
.deliverable-item input[type="text"] {
    flex-grow: 1;
    border: none;
    background: transparent;
    font-size: 1rem;
    color: #333;
    padding: 5px;
}

.deliverable-item input[type="text"]:focus {
    outline: none;
    border-bottom: 2px solid #007bff;
}

/* Delete Button */
.remove-btn {
    background: none;
    border: none;
    color: #dc3545;
    cursor: pointer;
    font-size: 1.2rem;
    transition: all 0.3s ease-in-out;
    position: absolute;
    right: 15px;
}

.remove-btn:hover {
    color: #ff4d4d;
}

/* Buttons Styling */
.btn-outline-primary, .btn-outline-secondary {
    border-radius: 5px;
    padding: 6px 12px;
    transition: 0.3s;
}

.btn-outline-primary:hover, .btn-outline-secondary:hover {
    background: #007bff;
    color: #fff;
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(5px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.deliverable-item {
    animation: fadeIn 0.3s ease-in-out;
}

</style>

<div class="container-fluid mt-4">
<div class="d-flex justify-content-between align-items-center mb-4 sticky-header bg-white p-3 rounded-lg shadow-sm" style="z-index: 2;">
        <div>
            <h2 class="h4 mb-0 text-gray-800 fw-bold d-flex align-items-center">
                <i class="bi bi-list-task text-primary me-2"></i>
                {{ $phase->title }}
                <span class="badge bg-light text-dark ms-2 fw-normal">{{ $phase->tasks->count() }} tasks</span>
            </h2>
            <nav aria-label="breadcrumb" class="mt-2">
                <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="{{ route('projects.index') }}" class="text-decoration-none">{{ $phase->project->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Phase Tasks</li>
                </ol>
            </nav>
            @if($phase->project->enquiryLog)
                <div class="enquiry-status mb-3">
                    @php
                        $status = strtolower($phase->project->enquiryLog->status);
                        $statusClass = [
                            'active' => 'bg-success',
                            'completed' => 'bg-primary',
                            'pending' => 'bg-warning text-dark',
                            'cancelled' => 'bg-danger',
                            'on hold' => 'bg-info',
                        ][$status] ?? 'bg-secondary';
                        
                        $statusIcon = [
                            'active' => 'bi-check-circle-fill',
                            'completed' => 'bi-check-all',
                            'pending' => 'bi-hourglass-split',
                            'cancelled' => 'bi-x-circle-fill',
                            'on hold' => 'bi-pause-circle-fill',
                        ][$status] ?? 'bi-info-circle-fill';
                    @endphp
                    <span class="badge {{ $statusClass }} d-inline-flex align-items-center py-2 px-3 shadow-sm" style="font-size: 0.9rem; letter-spacing: 0.5px;">
                        <i class="bi {{ $statusIcon }} me-2"></i>
                        <span class="fw-medium">Enquiry Status:</span>
                        <span class="ms-1 text-uppercase fw-bold">{{ $phase->project->enquiryLog->status }}</span>
                        @if($status === 'active')
                            <span class="ms-2 badge-pulse"></span>
                        @endif
                    </span>
                </div>
                <style>
                    .badge-pulse {
                        display: inline-block;
                        width: 8px;
                        height: 8px;
                        background-color: #fff;
                        border-radius: 50%;
                        animation: pulse 1.5s infinite;
                        margin-left: 6px;
                    }
                    @keyframes pulse {
                        0% { transform: scale(0.95); opacity: 1; }
                        70% { transform: scale(1.5); opacity: 0.7; }
                        100% { transform: scale(0.95); opacity: 1; }
                    }
                </style>
            @endif
        </div>
        <!-- @role('pm')
        @if(auth()->user()->level >= 4) -->
        <button class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#createTaskModal">
            <i class="bi bi-plus-lg me-2"></i> New Task
        </button>
        <!-- @endif
        @endrole -->
    </div>
    <h2 class="text-info sticky-header" style="margin-top: -10px; padding-top: 10px; background-color: white; z-index: 1;">Tasks</h2>

    @include('partials.projects.create_task_modal', ['phase' => $phase])


    <div class="row">
        <!-- Left Column - Task List -->
        <div class="col-md-3 sticky-col">
            @forelse($phase->tasks as $task)
                <div class="task-card" id="task-card-{{ $task->id }}" onclick="showTaskDetails(this, {{ $task->id }})">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="task-title">{{ $task->name }}</div>
                            <small class="text-muted">{{ $task->created_at->format('M d, Y') }}</small>
                        </div>
                        @if($task->status === 'Complete')
                            <span class="status-text-extra-small text-white w-30 rounded-2 p-1 bg-success">
                                <i class="bi bi-check-circle-fill me-1"></i>{{ $task->status }}
                            </span>
                        @elseif($task->status === 'In Progress')
                            <span class="status-text-extra-small text-dark w-30 rounded-2 p-1 bg-warning">
                                <i class="bi bi-hourglass-split me-1"></i>{{ $task->status }}
                            </span>
                        @else
                            <span class="status-text-extra-small text-white w-30 rounded-2 p-1 bg-secondary">
                                <i class="bi bi-clock-history me-1"></i>{{ $task->status }}
                            </span>
                        @endif

                    </div>
                </div>
            @empty
                <p class="text-muted">No tasks yet.</p>
            @endforelse
        </div>

        <!-- Right Column - Compact Task Detail -->
        <div class="col-md-9">
            @forelse($phase->tasks as $task)
            <div class="details-pane task-detail-pane p-2" id="taskDetail{{ $task->id }}" style="display: none; font-size: 0.875rem;">
                {{-- New Header --}}
            <div class="d-flex justify-content-between align-items-center mb-3 sticky-task-header" style="background-color: white; z-index: 1;">
                <h5 class="mb-0 text-info"><strong>{{ $task->name }}</strong></h5>
                <small class="text-muted mx-auto">Assigned to: <strong class="text-dark">{{ $task->assigned_to ?? 'N/A' }}</strong></small>
                <div>
                    <!-- @role('pm')
                    @if(auth()->user()->level >= 4) {{-- Only PMs can edit tasks --}} -->
                    <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editTaskModal{{ $task->id }}"><i class="bi bi-pencil"></i></button>
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this task?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                    <!-- @endif
                    @endrole -->
                </div>
            </div>
            <hr class="my-3">


            <!-- {{-- Timelines Section --}}
            <div class="mb-3 border rounded p-3 bg-light"> {{-- Added a container with border/padding/background --}}
                <h5 class="mb-3"><i class="bi bi-clock-history me-2"></i>Timelines</h5> {{-- Added icon to title --}}
                <div class="row g-2"> {{-- Use a grid row --}}
                    {{-- Left Column --}}
                    <div class="col-md-6">
                        <div class="mb-1"> {{-- Reduced margin-bottom slightly --}}
                            <small>
                                <strong><i class="bi bi-calendar-plus me-1 text-secondary"></i>Created:</strong>
                                <span class="text-primary ms-1">{{ $task->created_at->format('M d, Y') }}</span>
                            </small>
                        </div>
                        <div class="mb-1">
                            <small>
                                <strong><i class="bi bi-play-circle me-1 text-secondary"></i>Start Date:</strong>
                                <span class="text-primary ms-1">{{ $task->start_date}}</span>
                            </small>
                        </div>
                        <!-- <div class="mb-1">
                            <small>
                                <strong><i class="bi bi-hourglass-split me-1 text-secondary"></i>Hours Worked:</strong>
                                <span class="text-primary ms-1">{{ $task->hours_worked ?? 'N/A' }}</span>
                            </small>
                        </div> -->
                    <!-- </div>
                    {{-- Right Column --}}
                    <div class="col-md-6">
                        <div class="mb-1">
                            <small>
                                <strong><i class="bi bi-pencil-square me-1 text-secondary"></i>Updated:</strong>
                                <span class="text-primary ms-1">{{ $task->updated_at->format('M d, Y H:i A') }}</span>
                            </small>
                        </div>
                        <div class="mb-1">
                            <small>
                                <strong><i class="bi bi-calendar-check me-1 text-secondary"></i>Due Date:</strong>
                                <span class="text-primary ms-1">{{ $task->due_date }}</span>
                            </small>
                        </div>
                    </div>
                </div>
            </div> {{-- Closes Timelines div --}} -->

            <hr class="my-3"> {{-- Existing separator --}}

            {{-- Description Section --}}
            <div class="mb-3">
                <h5 class="text-black">DESCRIPTION</h5>
                <p class="text-muted fst-italic">
                    {!! nl2br(e($task->description ?? 'No description provided.')) !!}
                </p>
            </div>
            <hr class="my-3"> {{-- Add a new separator before Deliverables --}}
            {{-- End Description Section --}}

            <hr class="my-3"> {{-- Add a new separator before Deliverables --}}

                    <h5 class="text-black">ACTIONS</h5>
                    <!-- Deliverables -->
                    <div class="mb-2">
                        <form action="{{ route('phases.updateDeliverables', $task->id) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="deliverables-list"> {{-- ADDED: Wrapper list --}}
                                @forelse ($task->deliverables as $deliverable)
                                    <div class="deliverable-item"> {{-- CHANGED: Apply new item class --}}
                                        <span class="deliverable-number">{{ $loop->iteration }}.</span> {{-- ADDED: Number --}}
                                        <input type="checkbox" name="deliverables[{{ $loop->index }}][done]" value="1"
                                               {{ $deliverable->done ? 'checked' : '' }}
                                               aria-label="Mark item {{ $loop->iteration }} as done"> {{-- Checkbox --}}
                                        <input type="text" name="deliverables[{{ $loop->index }}][item]" value="{{ $deliverable->item }}"
                                               class="form-control form-control-sm" required
                                               aria-label="Deliverable item {{ $loop->iteration }}"> {{-- Text input --}}
                                        {{-- NOTE: The remove button functionality needs JS. This button currently does nothing. --}}
                                        <button type="button" class="remove-btn" aria-label="Remove item {{ $loop->iteration }}">&times;</button> {{-- CHANGED: New remove button --}}
                                    </div>
                                @empty
                                    <p class="text-muted small">No actions added yet.</p>
                                @endforelse
                            </div>
            
                            <button class="btn btn-sm btn-outline-primary mt-2">Update changes</button> {{-- Update button --}}
                        </form>

                        {{-- Add New Item Form (Input Group) --}}
                        <form method="POST" action="{{ route('tasks.deliverables.store', $task->id) }}" class="mt-3">
                            @csrf
                            <div class="input-group input-group-sm">
                                <input type="text" name="item" class="form-control" placeholder="Add a new deliverable..." required aria-label="New deliverable item">
                                <button class="btn btn-outline-success" type="submit">
                                    <i class="bi bi-plus-lg"></i> Add
                                </button>
                            </div>
                        </form>
                    </div>
                <hr class="my-3"> 
                <!-- Comments Section -->
                <div class="mt-4">
                    <h5 class="text-dark mb-3">
                        <i class="bi bi-chat-dots me-2"></i>Comments
                    </h5>

                    @forelse ($task->comments as $comment)
                        <div class="border-bottom pb-2 mb-2">
                            <div class="small text-muted">
                                <strong class="text-primary">{{ $comment->user->name ?? 'Unknown User' }}</strong>
                                • {{ $comment->created_at->diffForHumans() }}
                            </div>
                            <p class="mb-0">{{ $comment->comment }}</p>
                        </div>
                    @empty
                        <p class="text-muted">No comments yet.</p>
                    @endforelse
                </div>

                <!-- Add Comment Form -->
                <form action="{{ route('phases.tasks.update', $task->id) }}" method="POST" class="mt-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    {{-- Task Name --}}
                    <input type="hidden" name="name" value="{{ $task->name }}" class="form-control" required>
                    <!-- Hidden Status Input - Default to "In Progress" -->
                    <input type="hidden" name="status" value="In Progress">

                    <div class="mb-3">
                        <label class="form-label">Comments</label>
                        <textarea name="comments[]" class="form-control" rows="2" placeholder="Add a comment"></textarea>
                    </div>

                    <button type="submit" class="btn btn-outline-info">
                        <i class="bi bi-send me-1"></i> Submit Comment
                    </button>
                </form>
                
                <!-- End Comments Section -->

                <!-- Attachments Section
                <div class="mt-3">
                    <h5 class="text-dark mb-2">
                        <i class="bi bi-paperclip me-2"></i>Attachments
                    </h5>
                    <div class="border p-3 rounded bg-light">
                        {{-- List of existing files --}}
                        <div class="attachments-list mb-3">
                            @forelse ($task->attachments as $file)
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                                    <div>
                                        {{-- Determine icon based on file extension --}}
                                        @php
                                            $ext = strtolower(pathinfo($file->file_name, PATHINFO_EXTENSION));
                                            $iconClass = match($ext) {
                                                'jpg', 'jpeg', 'png', 'gif', 'bmp' => 'bi-file-earmark-image',
                                                'pdf' => 'bi-file-earmark-pdf',
                                                'doc', 'docx' => 'bi-file-earmark-word',
                                                'xls', 'xlsx' => 'bi-file-earmark-excel',
                                                'ppt', 'pptx' => 'bi-file-earmark-ppt',
                                                default => 'bi-file-earmark-text', // fallback icon
                                            };
                                        @endphp
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="bi {{ $iconClass }} text-primary me-2 fs-5"></i>
                                        <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="text-decoration-none">
                                            {{ $file->file_name }}
                                        </a>
                                    </div>
                                    <form action="{{ route('attachments.delete', $file->id) }}" method="POST" onsubmit="return confirm('Delete this file?');">
                                        @csrf
                                        @method('DELETE')
                                        @if(auth()->user()->hasRole('super-admin'))
                                        <button class="btn btn-sm btn-outline-danger" type="submit" onclick="return confirm('Delete this file?');">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        @endif
                                    </form>
                                </div>
                            @empty
                                <p class="text-muted small mb-0">No files attached yet.</p>
                            @endforelse
                        </div>

                        {{-- Add New Attachment Form (UI only for now) --}}
                        <div>
                            <label for="taskAttachmentInput{{ $task->id }}" class="form-label form-label-sm">Add new attachments:</label>
                            <div class="input-group input-group-sm">
                                <input type="file" class="form-control" id="taskAttachmentInput{{ $task->id }}" multiple>
                                <button class="btn btn-outline-secondary" type="button" disabled>
                                    <i class="bi bi-upload me-1"></i> Upload
                                </button>
                            </div>
                        </div>
                    </div>
                </div> -->

                <!-- End Attachments Section -->

                </div>

                <!-- Edit Modal -->
                <div class="modal fade" id="editTaskModal{{ $task->id }}" tabindex="-1" aria-labelledby="editTaskModalLabel{{ $task->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <form action="{{ route('phases.tasks.update', $task->id) }}" method="POST" class="modal-content" enctype="multipart/form-data">
                            @csrf @method('PUT')

                            <div class="modal-header">
                                <h5 class="modal-title" id="editTaskModalLabel{{ $task->id }}">Edit Task</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                {{-- Task Name --}}
                                <div class="mb-3">
                                    <label class="form-label">Task Name</label>
                                    <input type="text" name="name" value="{{ $task->name }}" class="form-control" required>
                                </div>

                                {{-- Status --}}
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select" required>
                                        <option value="Pending" {{ $task->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="In Progress" {{ $task->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="Complete" {{ $task->status === 'Complete' ? 'selected' : '' }}>Complete</option>
                                    </select>
                                </div>

                                {{-- Assigned To --}}
                                <div class="mb-3">
                                    <label class="form-label">Assigned To</label>
                                    <input type="text" name="assigned_to" value="{{ $task->assigned_to }}" class="form-control">
                                </div>

                                {{-- Dates --}}
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Start Date</label>
                                        <input type="date" name="start_date" value="{{ $task->start_date }}" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Due Date</label>
                                        <input type="date" name="due_date" value="{{ $task->due_date }}" class="form-control">
                                    </div>
                                </div>

                                {{-- Description --}}
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="3">{{ $task->description }}</textarea>
                                </div>

                                <!-- {{-- Deliverables --}}
                                <div class="mb-3">
                                    <label class="form-label">Deliverables</label>
                                    <input type="text" name="deliverables[]" class="form-control mb-2" placeholder="Enter deliverables">
                                    <small class="text-muted">Add more after saving or dynamically via JavaScript.</small>
                                </div> -->

                                {{-- Comments --}}
                                <div class="mb-3">
                                    <label class="form-label">Comments</label>
                                    <textarea name="comments[]" class="form-control" rows="2" placeholder="Add a comment"></textarea>
                                    <small class="text-muted">Previous comments won't show here — new ones get appended.</small>
                                </div>

                                {{-- Attachments --}}
                                <div class="mb-3">
                                    <label class="form-label">Attachments (PDF, Excel, Images, Docs)</label>
                                    <input type="file" name="attachments[]" class="form-control" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png">
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-outline-primary">Update Task</button>
                            </div>
                        </form>
                    </div>
                </div>

            @empty
                <h5 class="text-muted">No tasks found.</h5>
            @endforelse
            <a href="{{ route('projects.index') }}" class="btn btn-outline-info" >Return</a>
            <i href="{{ route('projects.index') }}" class="bi bi-arrow-return-left">Back to projects</i>
        </div>
    </div>
</div>

<script>
    function showTaskDetails(clickedElement, taskId) {
        // Remove active class from all task cards
        document.querySelectorAll('.task-card').forEach(card => {
            card.classList.remove('task-card-active');
        });

        // Add active class to the clicked card
        if (clickedElement) {
            clickedElement.classList.add('task-card-active');
        }

        // Show/hide detail panes and pointer
        document.querySelectorAll('.task-detail-pane').forEach(pane => {
            pane.style.display = 'none';
            pane.classList.remove('active-pointer'); // Remove pointer from all panes
        });

        const activePane = document.getElementById('taskDetail' + taskId);
        if (activePane) {
            activePane.style.display = 'block';
            activePane.classList.add('active-pointer'); // Add pointer to the active pane

            // Calculate and set pointer position
            if (clickedElement) {
                const taskCardRect = clickedElement.getBoundingClientRect();
                const detailsColumn = activePane.closest('.col-md-9');

                if (detailsColumn) {
                    const detailsColumnRect = detailsColumn.getBoundingClientRect();
                    // Calculate top for the pointer relative to detailsColumn, aiming for vertical center of task card.
                    // Pointer height is 40px (20px border-top + 20px border-bottom).
                    const pointerCssTop = (taskCardRect.top - detailsColumnRect.top) + (taskCardRect.height / 2) - 20; // 20 is half new pointer height
                    activePane.style.setProperty('--pointer-top', pointerCssTop + 'px');
                }
            }
        } else {
            console.warn('Detail pane for task ID ' + taskId + ' not found.');
        }
    }

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-btn')) {
            e.preventDefault();
            e.target.closest('.deliverable-item').remove();
        }
    });

    function toggleIframe() {
    const container = document.getElementById('iframe-container');
    if (container.style.display === 'none') {
        container.style.display = 'block';
    } else {
        container.style.display = 'none';
    }
}

    document.addEventListener('DOMContentLoaded', function() {
        const firstTaskCard = document.querySelector('.task-card');
        if (firstTaskCard) {
            // Extract task ID from the card's ID (e.g., "task-card-1" -> "1")
            const taskId = firstTaskCard.id.split('-').pop();
            if (taskId) {
                showTaskDetails(firstTaskCard, taskId);
            }
        }
    });
</script>

@endsection
