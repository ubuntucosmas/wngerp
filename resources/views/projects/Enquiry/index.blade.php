@extends('layouts.master')

@section('navbar-title', 'Enquiries Log')
@section('content')
<style>
    h1 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #145DA0;
    }

    .table {
        font-size: 0.875rem;
        border-radius: 12px;
        overflow: hidden;
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        animation: slideUp 0.5s ease-out;
    }

    .table th {
        white-space: nowrap;
        position: relative;
    }

    .table td.actions {
        white-space: nowrap;
        width: 1%;
        padding: 0.5rem !important;
    }

    .btn-group {
        display: flex;
        flex-wrap: nowrap;
    }

    .btn-group .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    .table thead th {
        background-color: #0C2D48 !important;
        color: white;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .table thead th:hover {
        background-color: #072540 !important;
    }

    .table tbody tr {
        transition: background-color 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: rgba(46, 139, 192, 0.05);
    }

    .btn, .btn-sm, .btn-xs {
        border-radius: 6px;
        transition: all 0.18s cubic-bezier(.4,0,.2,1);
        box-shadow: 0 2px 6px rgba(44,62,80,0.07);
    }

    .btn-xs {
        font-size: 0.72rem;
        padding: 0.21rem 0.6rem;
    }

    .modal-content {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        background-color: #f9fcff;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .modal-title {
        color: #145DA0;
        font-weight: 600;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .form-control, .form-select {
        border-radius: 8px;
        font-size: 0.85rem;
        padding: 0.375rem 0.75rem;
        transition: border-color 0.3s ease;
        height: 35px;
        min-height: 35px;
        width: 100%;
        max-width: 100%;
    }

    .form-control:focus, .form-select:focus {
        border-color: #2E8BC0;
        box-shadow: 0 0 0 0.2rem rgba(46, 139, 192, 0.25);
    }

    .form-label {
        font-size: 0.7rem;
        color: #6c757d; 
        font-weight: 500;
        margin-bottom: 0.3rem;
    }

    .invalid-feedback {
        font-size: 0.65rem;
    }

    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }

    .container {
        padding: 16px;
        background-color: #f9fcff;
        border-radius: 12px;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .expandable-row {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .expandable-row:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    .expandable-row::after {
        content: '';
        font-size: 0.7em;
        color: #6c757d;
        margin-left: 2px;
        transition: transform 0.2s;
        position: static;
        display: inline-flex;
    }
    .expandable-row.expanded::after {
        transform: rotate(180deg);
    }
    .deliverables-container {
        position: relative;
        width: 100%;
        min-height: 1.5em;
    }
    .deliverables-list {
        margin: 0;
        padding-left: 1rem;
        width: 100%;
    }
    .deliverables-list li:not(:last-child) {
        margin-bottom: 0.25rem;
    }
    .deliverables-list li.hidden-deliverable {
        display: none;
    }
    .expandable-row.expanded .hidden-deliverable {
        display: list-item;
    }
    .more-items {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        color: #0d6efd;
        font-size: 0.8em;
        font-style: italic;
        background: white;
        padding-left: 8px;
        pointer-events: none;
    }

    /* Notes cell styling */
    .notes-cell {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Prevent date wrapping in table cells */
    .table td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 150px; /* Adjust this value as needed */
    }
    
    /* Specific style for date columns */
    .table td:nth-child(3) { /* Target the setup date column */
        min-width: 100px;
    }

    .deliverables-list {
        list-style-type: disc;
        margin-bottom: 0;
    }
    .deliverables-list li {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 250px;
    }

    .modal-body ul {
        max-height: 60vh;
        overflow-y: auto;
    }
    .modal-body li {
        padding: 4px 0;
        border-bottom: 1px solid #eee;
    }
    .modal-body li:last-child {
        border-bottom: none;
    }

    .modal-body .bg-light {
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    /* Red styling for non-approved enquiries */
    .table tbody tr.non-approved-enquiry {
        background-color: rgba(220, 53, 69, 0.1) !important;
        border-left: 4px solid #dc3545;
    }

    .table tbody tr.non-approved-enquiry:hover {
        background-color: rgba(220, 53, 69, 0.15) !important;
    }
</style>

<div class="container-fluid p-2">

    <div class="px-3 mx-10 mt-2 w-100">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center">
                <div class="me-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('enquiries.index') }}">Enquiries</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Enquiries Log</li>
                        </ol>
                    </nav>
                    <h1 class="mb-0">
                        @if(isset($viewType) && $viewType === 'all')
                            All Enquiries
                        @elseif(isset($viewType) && $viewType === 'trashed')
                            Deleted Enquiries
                        @else
                            {{ auth()->user()->hasRole('po') ? 'My Enquiries' : 'Enquiries' }}
                        @endif
                    </h1>
                </div>
            </div>
            
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEnquiryModal">
                <i class="bi bi-plus-circle me-2"></i>New Enquiry
            </button>
        </div>
    <hr class="mb-4">
    
<!-- Search Form and Toggle Buttons Row -->
<div class="col mb-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <!-- Search Form -->
        <form method="GET" 
              action="{{ isset($viewType) && $viewType === 'all' ? route('enquiries.all') : route('enquiries.index') }}" 
              class="d-flex align-items-center flex-wrap gap-2">

            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search enquiries..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="bi bi-search"></i>
                </button>
                @if(request('search'))
                    <a href="{{ isset($viewType) && $viewType === 'all' ? route('enquiries.all') : route('enquiries.index') }}" 
                       class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </div>
        </form>

        <!-- Enquiry Toggle Buttons -->
        @hasanyrole('po|super-admin')
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('enquiries.index') }}" 
               class="btn {{ (!isset($viewType) || $viewType === 'assigned') ? 'btn-primary' : 'btn-outline-primary' }} btn-sm d-flex align-items-center gap-1">
                <i class="bi bi-person-check-fill"></i>
                <span>My Enquiries</span>
            </a>
            <span class="text-muted">|</span>
            <a href="{{ route('enquiries.all') }}" 
               class="btn {{ (isset($viewType) && $viewType === 'all') ? 'btn-primary' : 'btn-outline-primary' }} btn-sm d-flex align-items-center gap-1">
                <i class="bi bi-people-fill"></i>
                <span>All Enquiries</span>
            </a>
            @hasanyrole('admin|pm|super-admin')
            <span class="text-muted">|</span>
            <a href="{{ route('enquiries.trashed') }}" 
               class="btn {{ (isset($viewType) && $viewType === 'trashed') ? 'btn-danger' : 'btn-outline-danger' }} btn-sm d-flex align-items-center gap-1">
                <i class="bi bi-trash"></i>
                <span>Deleted</span>
            </a>
            @endhasanyrole
        </div>
        @endhasanyrole
    </div>
</div>



        <!-- Create Enquiry Modal -->
        <div class="modal fade" id="createEnquiryModal" tabindex="-1" aria-labelledby="createEnquiryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createEnquiryModalLabel">New Enquiry</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>There were some problems with your input.</strong>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="createEnquiryForm" action="{{ route('enquiries.store') }}" method="POST">
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_received" class="form-label">Date Received<span class="text-danger">*</span></label>
                                        <input type="datetime-local" name="date_received" id="date_received" class="form-control" required 
                                        value="{{ old('date_received', \Carbon\Carbon::now()->format('Y-m-d\TH:i')) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="expected_delivery_date" class="form-label">Expected Delivery Date<span class="text-danger">*</span></label>
                                        <input type="date" name="expected_delivery_date" id="expected_delivery_date" class="form-control" value="{{ old('expected_delivery_date') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="client_name" class="form-label">Client Name<span class="text-danger">*</span></label>
                                        <select name="client_name" id="client_name" class="form-select" required>
                                            <option value="">-- Select Client --</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->FullName }}" {{ old('client_name') == $client->FullName ? 'selected' : '' }}>
                                                    {{ $client->FullName }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="project_name" class="form-label text-muted">Project Name<span class="text-danger">*</span></label>
                                        <input type="text" name="project_name" id="project_name" list="projectNameSuggestions" class="form-control" value="{{ old('project_name') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="assigned_po" class="form-label">Project Officer<span class="text-danger">*</span></label>
                                        <select name="assigned_po" id="assigned_po" class="form-select" required>
                                            <option value="">-- Select Officer --</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->name }}" {{ old('assigned_po') == $user->name ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="project_deliverables" class="form-label">Project Deliverables</label>
                                        <textarea name="project_deliverables" id="project_deliverables" class="form-control" rows="4">{{ old('project_deliverables') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="follow_up_notes" class="form-label">Follow-Up Notes</label>
                                        <textarea name="follow_up_notes" id="follow_up_notes" class="form-control" rows="4">{{ old('follow_up_notes') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact_person" class="form-label">Contact Person</label>
                                        <input type="text" name="contact_person" id="contact_person" class="form-control" value="{{ old('contact_person') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="venue" class="form-label">Venue<span class="text-danger">*</span></label>
                                        <input type="text" name="venue" id="venue" class="form-control" value="{{ old('venue') }}" required>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-xs btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" form="createEnquiryForm" class="btn btn-xs btn-outline-success">Create Enquiry</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle shadow-sm">
                <thead>
                    <tr>
                        <th>Enquiry ID</th>
                        <th>Date Received</th>
                        <th>Setup Date</th>
                        <th>Client</th>
                        <th>Project</th>
                        <th>Venue</th>
                        <th>Deliverables</th>
                        <th>Contact</th>
                        
                        <th>PO</th>
                        <th>Notes</th>
                        <th class="text-nowrap">Actions</th>
                        <th>Converted To Project</th>
                    </tr>
                </thead>
                <tbody id="enquiryTableBody">
                    @php $rowIndex = 0; @endphp
                    @forelse($enquiries as $enquiry)
                        <tr class="{{ $enquiry->enquiryLog && $enquiry->enquiryLog->status !== 'Approved' ? 'non-approved-enquiry' : '' }}">
                            <td>{{ $enquiry->formatted_id }}</td>
                            <td>{{ $enquiry->date_received ? \Carbon\Carbon::parse($enquiry->date_received)->format('M d, Y') : '-' }}</td>
                            <td>{{ $enquiry->expected_delivery_date ? \Carbon\Carbon::parse($enquiry->expected_delivery_date)->format('M d, Y') : '-' }}</td>
                            <td>{{ $enquiry->client_name }}</td>
                            <td>{{ $enquiry->project_name }}</td>
                            <td>{{ $enquiry->venue ?? '-' }}</td>
                            <td>
                                @if($enquiry->project_deliverables)
                                    @php
                                        $deliverables = array_filter(
                                            preg_split('/\r\n|\r|\n/', $enquiry->project_deliverables),
                                            fn($item) => trim($item) !== ''
                                        );
                                        $totalDeliverables = count($deliverables);
                                    @endphp
                                    @if($totalDeliverables > 0)
                                        <button type="button" 
                                                class="btn btn-xs btn-outline-primary"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deliverablesModal{{ $enquiry->id }}">
                                            View Deliverables
                                        </button>

                                        <!-- Deliverables Modal -->
                                        <div class="modal fade" id="deliverablesModal{{ $enquiry->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Deliverables for {{ $enquiry->formatted_id }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <ul class="list-unstyled mb-0">
                                                            @foreach($deliverables as $deliverable)
                                                                <li class="mb-2">
                                                                    <i class="fas fa-check-circle text-success me-2"></i>
                                                                    {{ $deliverable }}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $enquiry->contact_person ?? '-' }}</td>
                            
                            <td>{{ $enquiry->assigned_po ?? '-' }}</td>
                            <td>
                                @if($enquiry->follow_up_notes)
                                    <button type="button" 
                                            class="btn btn-xs btn-outline-info"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#notesModal{{ $enquiry->id }}">
                                        View Notes
                                    </button>

                                    <!-- Notes Modal -->
                                    <div class="modal fade" id="notesModal{{ $enquiry->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Notes for {{ $enquiry->formatted_id }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="p-3 bg-light rounded">
                                                        {!! nl2br(e($enquiry->follow_up_notes)) !!}
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="actions">
                                @if(isset($viewType) && $viewType === 'trashed')
                                    <!-- Actions for trashed enquiries -->
                                    <div class="btn-group">
                                        <form action="{{ route('enquiries.restore', $enquiry->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-xs btn-outline-success" title="Restore" onclick="return confirm('Are you sure you want to restore this enquiry?')">
                                                <i class="bi bi-arrow-clockwise"></i>
                                            </button>
                                        </form>
                                        @hasrole('super-admin')
                                        <form action="{{ route('enquiries.force-delete', $enquiry->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-outline-danger" title="Permanently Delete" onclick="return confirm('Are you sure you want to permanently delete this enquiry? This action cannot be undone!')">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                        @endhasrole
                                    </div>
                                @else
                                    <!-- Actions for active enquiries -->
                                    <div class="btn-group">
                                        <a href="{{ (isset($enquiry) && is_object($enquiry) && isset($enquiry->id)) ? route('enquiries.files', $enquiry) : '#' }}" class="btn btn-xs btn-outline-primary" title="Files & Phases">
                                            <i class="bi bi-folder"></i>
                                        </a>
                                        <button type="button" class="btn btn-xs btn-outline-info" data-bs-toggle="modal" data-bs-target="#editEnquiryModal{{ $enquiry->id }}" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <x-delete-button :action="route('enquiries.destroy', ['enquiry' => $enquiry->id])">
                                                Delete
                                            </x-delete-button>
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($enquiry->converted_to_project_id)
                                    <div class="d-flex flex-column align-items-center gap-1">
                                        <!-- <span class="bg-info border rounded px-1 py-1 d-flex align-items-center">
                                            <i class="bi bi-check-circle-fill me-1"></i>
                                            Converted
                                        </span> -->
                                        <a href="{{ route('projects.index', $enquiry->converted_to_project_id) }}" 
                                        class="btn btn-sm btn-outline-primary btn-sm"
                                        data-bs-toggle="tooltip" 
                                        title="View Project #{{ $enquiry->converted_to_project_id }}">
                                            <i class="bi bi-box-arrow-up-right me-1"></i> View
                                        </a>
                                    </div>
                                @else
                                    @if($enquiry->areFirstFourPhasesCompleted())
                                        <div class="d-flex flex-column align-items-center gap-1">
                                            <span class="badge bg-success">Ready to Convert</span>
                                            <small class="text-muted">All phases completed</small>
                                            <form action="{{ route('enquiries.convert', $enquiry) }}" method="POST" class="d-inline" onsubmit="return confirm('Convert this enquiry to a project? This action cannot be undone.');">
                                                @csrf
                                                <button type="submit" class="btn btn-xs btn-success">
                                                    <i class="bi bi-arrow-up-circle me-1"></i>Convert to Project
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <div class="d-flex flex-column align-items-center gap-1">
                                            <span class="badge bg-warning">In Progress</span>
                                            <small class="text-muted">Complete phases first</small>
                                        </div>
                                    @endif
                                @endif
                                </td>
                            </tr>

                        <!-- Edit Enquiry Modal -->
                            <div class="modal fade" id="editEnquiryModal{{ $enquiry->id }}" tabindex="-1" aria-labelledby="editEnquiryModalLabel{{ $enquiry->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editEnquiryModalLabel{{ $enquiry->id }}">Edit Enquiry</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <strong>There were some problems with your input.</strong>
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <form id="editEnquiryForm{{ $enquiry->id }}" action="{{ route('enquiries.update', $enquiry) }}" method="POST" class="edit-enquiry-form">
                                            @csrf
                                            @method('PUT')

                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="date_received" class="form-label">Date Received<span class="text-danger">*</span></label>
                                                        <input type="datetime-local" name="date_received" id="date_received" class="form-control" required value="{{ $enquiry->date_received ? \Carbon\Carbon::parse($enquiry->date_received)->format('Y-m-d\TH:i') : '' }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="expected_delivery_date" class="form-label">Expected Delivery Date<span class="text-danger">*</span></label>
                                                        <input type="date" name="expected_delivery_date" id="expected_delivery_date" class="form-control" value="{{ $enquiry->expected_delivery_date ? \Carbon\Carbon::parse($enquiry->expected_delivery_date)->format('Y-m-d') : '' }}" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="client_name" class="form-label">Client Name<span class="text-danger">*</span></label>
                                                        <select name="client_name" id="client_name" class="form-select" required>
                                                            <option value="">-- Select Client --</option>
                                                            @foreach($clients as $client)
                                                                <option value="{{ $client->FullName }}" {{ $enquiry->client_name == $client->FullName ? 'selected' : '' }}>
                                                                    {{ $client->FullName }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="project_name" class="form-label">Project Name<span class="text-danger">*</span></label>
                                                        <input type="text" name="project_name" id="project_name" class="form-control" value="{{ $enquiry->project_name }}" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-12">
                                                    <label for="project_officer_id" class="form-label">Project Officer<span class="text-danger">*</span></label>
                                                    <select name="assigned_po" class="form-select" required>
                                                        <option value="">-- Select Officer --</option>
                                                        @foreach($users as $user)
                                                            <option value="{{ $user->name }}" {{ $enquiry->assigned_po == $user->name ? 'selected' : '' }}>
                                                                {{ $user->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="project_deliverables" class="form-label">Project Deliverables</label>
                                                        <textarea name="project_deliverables" id="project_deliverables" class="form-control" rows="4">{{ $enquiry->project_deliverables }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="follow_up_notes" class="form-label">Follow-Up Notes</label>
                                                        <textarea name="follow_up_notes" id="follow_up_notes" class="form-control" rows="4">{{ $enquiry->follow_up_notes }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="contact_person" class="form-label">Contact Person</label>
                                                        <input type="text" name="contact_person" id="contact_person" class="form-control" value="{{ $enquiry->contact_person }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="venue" class="form-label">Venue<span class="text-danger">*</span></label>
                                                        <input type="text" name="venue" id="venue" class="form-control" value="{{ $enquiry->venue }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-xs btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" form="editEnquiryForm{{ $enquiry->id }}" class="btn btn-xs btn-outline-info">Update Enquiry</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center">No enquiries found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between mt-3">
            <div>Showing {{ $enquiries->count() }} enquiries</div>
            {{ $enquiries->links('pagination::bootstrap-5') }}
        </div>

    

    @push('styles')
    @endpush
</div>

<script>
    $(document).ready(function() {
        // Add CSRF token to all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Handle form submissions
        $(document).on('submit', '#createEnquiryForm, form[id^="editEnquiryForm"]', function(e) {
            alert('Form submit event triggered!');
            console.log('=== FORM SUBMIT EVENT TRIGGERED ===');
            console.log('Form ID:', this.id);
            console.log('Form action:', this.action);
            console.log('Form method:', this.method);
            
            e.preventDefault();
            
            const $form = $(this);
            const $modal = $form.closest('.modal');
            const formData = $form.serialize();
            const url = $form.attr('action');
            const method = $form.find('input[name="_method"]').val() || 'POST';
            
            console.log('Form details:', { url, method, formData });
            
            // Find the submit button
            const $submitBtn = $modal.find('button[type="submit"]');
            const originalBtnText = $submitBtn.html();
            
            // Show loading state
            $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            
            // Clear previous errors
            $form.find('.alert').remove();
            
            console.log('Submitting form:', { url, method, formData });
            
            // Handle form submission
            $.ajax({
                url: url,
                type: method,
                data: formData,
                success: function(response) {
                    console.log('Success response:', response);
                    // Hide modal and reload page
                    $modal.modal('hide');
                    setTimeout(function() {
                        window.location.reload();
                    }, 500);
                },
                error: function(xhr, status, error) {
                    console.log('Error response:', { xhr, status, error });
                    
                    let errorHtml = '<div class="alert alert-danger"><ul>';
                    
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            errorHtml += '<li>' + value[0] + '</li>';
                        });
                    } else {
                        errorHtml += '<li>An error occurred. Please try again.</li>';
                    }
                    
                    errorHtml += '</ul></div>';
                    $form.prepend(errorHtml);
                    
                    // Re-enable the submit button
                    $submitBtn.prop('disabled', false).html(originalBtnText);
                }
            });
            
            return false;
        });

        // Reset form and clear errors when modal is hidden
        $('.modal').on('hidden.bs.modal', function () {
            const $form = $(this).find('form');
            $form[0].reset();
            $form.find('.alert').remove();
        });
    });
</script>

@endsection