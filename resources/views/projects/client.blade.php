@extends('layouts.master')

@section('title', 'Clients Overview')
@section('navbar-title', 'Clients Information')

@section('content')
<style>
    h2 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #145DA0;
    }

    .btn-primary {
        background-color: #2E8BC0;
        border: none;
        border-radius: 12px;
        transition: all 0.3s ease-in-out;
    }

    .btn-primary:hover {
        background-color: #145DA0;
        box-shadow: 0 0 10px rgba(46, 139, 192, 0.5);
    }

    .modal-content {
        border-radius: 16px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        animation: fadeIn 0.3s ease-out;
    }

    .form-control, .form-select {
        border-radius: 8px;
        font-size: 0.85rem;
        padding: 0.375rem 0.75rem;
        transition: border-color 0.3s ease;
        height: 35px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #2E8BC0;
        box-shadow: 0 0 0 0.2rem rgba(46, 139, 192, 0.25);
    }

    .form-label {
        font-size: 0.7rem;
        color: #333;
        font-weight: 500;
        margin-bottom: 0.3rem;
    }

    .table {
        font-size: 0.875rem;
        border-radius: 12px;
        overflow: hidden;
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        animation: slideUp 0.5s ease-out;
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

    .btn-outline-info {
        font-size: 0.75rem;
        border-radius: 10px;
        padding: 4px 10px;
        transition: all 0.2s ease-in-out;
    }

    .btn-outline-info:hover {
        background-color: #2E8BC0;
        color: white;
    }

    .modal-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #0C2D48;
    }

    .modal-footer .btn {
        border-radius: 8px;
        font-size: 0.8rem;
        padding: 5px 14px;
        transition: all 0.2s ease;
    }

    .container {
        padding: 16px;
        background-color: #f9fcff;
        border-radius: 12px;
    }

    .search-box {
        max-width: 300px;
        margin-bottom: 16px;
    }

    .action-buttons button {
        margin-right: 5px;
    }

    .action-buttons .btn-danger {
        border-color: #dc3545;
        color: #dc3545;
    }

    .action-buttons .btn-danger:hover {
        background-color: #dc3545;
        color: white;
    }

    .action-buttons .btn-warning {
        border-color: #ffc107;
        color: #ffc107;
    }

    .action-buttons .btn-warning:hover {
        background-color: #ffc107;
        color: black;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .compact-section {
        margin-top: 0.5rem;
    }

    .compact-section h6 {
        margin-bottom: 0.25rem;
        font-size: 0.85rem;
    }

    .compact-section hr {
        margin-top: 0.25rem;
        margin-bottom: 0.5rem;
    }

    .invalid-feedback {
        font-size: 0.65rem;
    }
</style>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Clients</h2>
        <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#addClientModal">
            <i class="bi bi-person-plus"></i> New Client
        </button>
    </div>

    <div class="search-box">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control border-start-0" placeholder="Search clients..." id="clientSearch">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle shadow-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Full Name <i class="bi bi-sort-alpha-down ms-1"></i></th>
                    <th>Contact Person</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>City</th>
                    <th>Client Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="clientTableBody">
                @foreach($clients as $client)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $client->FullName }}</td>
                    <td>{{ $client->ContactPerson }}</td>
                    <td>{{ $client->Email }}</td>
                    <td>{{ $client->Phone }}</td>
                    <td>{{ $client->City }}</td>
                    <td>{{ $client->CustomerType }}</td>
                    <td class="action-buttons">
                        <button class="btn btn-sm btn-outline-info" onclick="viewClient({{ $client->id }})"><i class="bi bi-eye"></i> View</button>
                        <button class="btn btn-sm btn-outline-warning" onclick="editClient({{ $client->id }})"><i class="bi bi-pencil"></i> Edit</button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteClient({{ $client->id }})"><i class="bi bi-trash"></i> Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="d-flex justify-content-between mt-3">
        <div>Showing {{ count($clients) }} clients</div>
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm">
                <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </nav>
    </div>
</div>

<!-- Add Client Modal -->
<div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form method="POST" action="{{ route('clients.store') }}" id="addClientForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pb-2">
                    <h5 class="modal-title" id="addClientModalLabel">New Client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body pt-0 px-3">
                    <div class="row g-2">
                        @php
                            $fields = [
                                ['FullName', 'Full Name'], ['ContactPerson', 'Contact Person'],
                                ['Email', 'Email', 'email'], ['Phone', 'Phone'],
                                ['AltContact', 'Alt Contact'], ['Address', 'Address'],
                                ['City', 'City'], ['County', 'County'],
                                ['PostalAddress', 'Postal Address'], ['LeadSource', 'Lead Source'],
                                ['Industry', 'Industry']
                            ];
                        @endphp

                        <!-- Personal Information -->
                        <div class="col-12 compact-section">
                            <h6 class="text-primary">Personal Info</h6>
                            <hr style="border-color: rgba(46, 139, 192, 0.2);">
                        </div>
                        @foreach($fields as $field)
                            @if(in_array($field[0], ['FullName', 'ContactPerson', 'Email', 'Phone', 'AltContact']))
                                <div class="col-md-{{ in_array($field[0], ['FullName', 'Email']) ? '6' : '4' }}">
                                    <label class="form-label">{{ $field[1] }} {{ in_array($field[0], ['FullName', 'Email']) ?  : '' }}
                                        @if($field[0] == 'Email')
                                            <i class="bi bi-info-circle text-info ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="This email will be used for communication"></i>
                                        @endif
                                    </label>
                                    <input type="{{ $field[2] ?? 'text' }}" name="{{ $field[0] }}" class="form-control" {{ in_array($field[0], ['FullName', 'Email']) ? 'required' : '' }}>
                                    @error('{{ $field[0] }}')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                        @endforeach

                        <!-- Address Information -->
                        <div class="col-12 compact-section">
                            <h6 class="text-primary">Address</h6>
                            <hr style="border-color: rgba(46, 139, 192, 0.2);">
                        </div>
                        @foreach($fields as $field)
                            @if(in_array($field[0], ['Address', 'City', 'County', 'PostalAddress']))
                                <div class="col-md-{{ in_array($field[0], ['City', 'County', 'PostalAddress']) ? '3' : '6' }}">
                                    <label class="form-label">{{ $field[1] }}
                                        @if($field[0] == 'PostalAddress')
                                            <i class="bi bi-info-circle text-info ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Include postal code"></i>
                                        @endif
                                    </label>
                                    <input type="{{ $field[2] ?? 'text' }}" name="{{ $field[0] }}" class="form-control">
                                    @error('{{ $field[0] }}')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                        @endforeach

                        <!-- Additional Information -->
                        <div class="col-12 compact-section">
                            <h6 class="text-primary">Additional</h6>
                            <hr style="border-color: rgba(46, 139, 192, 0.2);">
                        </div>
                        @foreach($fields as $field)
                            @if(in_array($field[0], ['LeadSource', 'Industry']))
                                <div class="col-md-6">
                                    <label class="form-label">{{ $field[1] }}</label>
                                    <input type="{{ $field[2] ?? 'text' }}" name="{{ $field[0] }}" class="form-control">
                                    @error('{{ $field[0] }}')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                        @endforeach

                        <div class="col-md-3">
                            <label class="form-label">Customer Type <span class="text-danger">*</span></label>
                            <select name="CustomerType" class="form-select" required>
                                <option value="">Select</option>
                                <option value="Individual">Individual</option>
                                <option value="Business">Business</option>
                                <option value="Organization">Organization</option>
                            </select>
                            @error('CustomerType')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Pref. Contact <span class="text-danger">*</span></label>
                            <select name="PreferredContact" class="form-select" required>
                                <option value="">Select</option>
                                <option value="Email">Email</option>
                                <option value="Phone">Phone</option>
                                <option value="WhatsApp">WhatsApp</option>
                            </select>
                            @error('PreferredContact')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <input type="hidden" name="CreatedBy" value="{{ auth()->id() }}">
                    </div>
                </div>

                <div class="modal-footer border-top-0 pt-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-outline-success">Save Client</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Simple client-side search functionality
    document.getElementById('clientSearch').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#clientTableBody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
    
    // Placeholder functions for action buttons
    function viewClient(id) {
        alert('View client details for ID: ' + id);
        // Implement actual view functionality here
    }
    
    function editClient(id) {
        alert('Edit client details for ID: ' + id);
        // Implement actual edit functionality here
    }
    
    function deleteClient(id) {
        if(confirm('Are you sure you want to delete this client?')) {
            alert('Deleted client ID: ' + id);
            // Implement actual delete functionality here
        }
    }
    
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection
