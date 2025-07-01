@extends('layouts.master')

@section('title', 'Loading Sheet - ' . $project->name)
@section('navbar-title', 'Loading Sheet')

@section('content')
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="px-3 mx-10 w-100">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
        <div>
            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary me-2 btn-sm">
                <i class="bi bi-arrow-left"></i> Back to Projects
            </a>
            <a href="{{ route('projects.files.index', $project) }}" class="btn btn-info me-2 btn-sm">
                <i class="bi bi-folder"></i> Project Files
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body pt-4">
            <!-- Empty State -->
            @if ($logistics)
                <!-- Logistics Preview (clean info display) -->
                <div class="card mb-3">
                    <div class="card-body d-flex justify-content-end gap-2">
                        <button class="btn btn-outline-secondary btn-sm" id="printLogisticsBtn">
                            <i class="bi bi-printer"></i> Print
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" id="downloadLogisticsBtn">
                            <i class="bi bi-download"></i> Download
                        </button>
                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#logisticsModal">
                            <i class="bi bi-pencil"></i> Add/Edit Logistics Info
                        </button>
                    </div>
                </div>
                <hr>
                <!-- Info Cards Row -->
                <div class="row mb-4 align-items-center">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="card-title text-muted mb-2">Project Details</h6>
                                <div><strong>Project Name:</strong> {{ $project->name }}</div>
                                <div><strong>Client:</strong> {{ $project->client_name ?? ($project->client->name ?? 'N/A') }}</div>
                                <div><strong>Project ID:</strong> {{ $project->project_id ?? 'N/A' }}</div>
                                <div><strong>Location:</strong> {{ $project->venue ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-3 w-100">
                            <div class="card-body">
                                <h6 class="card-title text-muted mb-2">Loading Information</h6>
                                <div><strong>Vehicle Number:</strong> {{ $logistics->vehicle_number }}</div>
                                <div><strong>Driver Name:</strong> {{ $logistics->driver_name }}</div>
                                <div><strong>Contact:</strong> {{ $logistics->contact }}</div>
                                <div><strong>Departure Time:</strong> {{ \Carbon\Carbon::parse($logistics->departure_time)->format('d/m/Y H:i') }}</div>
                                <div><strong>Expected Arrival:</strong> {{ \Carbon\Carbon::parse($logistics->expected_arrival)->format('d/m/Y H:i') }}</div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Items Table -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="card-title text-muted mb-2">Items to be Loaded</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Description</th>
                                        <th>Quantity</th>
                                        <th>Unit</th>
                                        <th>Notes</th>
                                        <th>Loaded</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($logistics->items as $i => $item)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $item->description }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ $item->unit }}</td>
                                            <td>{{ $item->notes }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center">No items found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if (!empty($logistics->special_instructions))
                            <div class="alert alert-info mt-3 mb-0"><strong>Special Instructions:</strong> {{ $logistics->special_instructions }}</div>
                        @endif
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center my-5" id="logisticsEmptyState">
                    <i class="bi bi-truck display-4 text-muted"></i>
                    <div class="mt-3 fs-5">No Logistics Info Found</div>
                    <button class="btn btn-outline-primary mt-3 btn-sm" data-bs-toggle="modal" data-bs-target="#logisticsModal" id="emptyStateCreateBtn">
                        <i class="bi bi-plus-circle"></i> Add Logistics Info
                    </button>
                </div>
            @endif

            {{-- @else
                <div id="logisticsEmptyState" class="d-flex flex-column align-items-center justify-content-center py-5" style="min-height: 300px;">
                    <div class="mb-3">
                        <i class="bi bi-truck display-4 text-secondary"></i>
                    </div>
                    <h5 class="mb-2">No Logistics Info Found</h5>
                    <p class="text-muted mb-3">There is no logistics info associated with this project.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#logisticsModal">
                        <i class="bi bi-plus-lg"></i> Create Logistics Info
                    </button>
                </div>
            @endif --}} 

            <!-- Logistics Modal -->
            <div class="modal fade" id="logisticsModal" tabindex="-1" aria-labelledby="logisticsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="logisticsModalLabel">Create Logistics Info</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="logisticsForm" method="POST" action="{{ route('projects.logistics.store', $project) }}" autocomplete="off">
    @csrf
                            <div class="modal-body">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <h5>Vehicle Details</h5>
                                        <div class="mb-3">
                                            <label for="vehicle_number" class="form-label">Vehicle Registration Number</label>
                                            <input type="text" class="form-control form-control-sm" id="vehicle_number" name="vehicle_number" placeholder="Enter vehicle registration" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="driver_name" class="form-label">Driver Name</label>
                                            <input type="text" class="form-control form-control-sm" id="driver_name" name="driver_name" placeholder="Enter driver name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Timing</h5>
                                        <div class="mb-3">
                                            <label for="departure_time" class="form-label">Scheduled Departure Time</label>
                                            <input type="datetime-local" class="form-control form-control-sm" id="departure_time" name="departure_time" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="expected_arrival" class="form-label">Expected Arrival Time</label>
                                            <input type="datetime-local" class="form-control form-control-sm" id="expected_arrival" name="expected_arrival" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="contact" class="form-label">Contact</label>
                                            <input type="text" class="form-control form-control-sm" id="contact" name="contact" placeholder="Enter contact" required>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h5 class="mb-3">Items to be Loaded</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="itemsTable">
                                        <thead>
                                            <tr class="table-dark">
                                                <th>#</th>
                                                <th>Item Description</th>
                                                <th>Quantity</th>
                                                <th>Unit</th>
                                                <th>Notes</th>
                                                <th>Loaded</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemsList"><!-- Modal items table body -->
                                        <tr>
                                            <td>1</td>
                                            <td><input type="text" class="form-control form-control-sm" name="items[0][description]" placeholder="Item description" required></td>
                                            <td><input type="number" class="form-control form-control-sm" name="items[0][quantity]" placeholder="Qty" min="1" required></td>
                                            <td>
                                                <select class="form-select form-select-sm" name="items[0][unit]" required>
                                                    <option>PCS</option>
                                                    <option>SET</option>
                                                    <option>BOX</option>
                                                    <option>PACK</option>
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control form-control-sm" name="items[0][notes]" placeholder="Any notes"></td>
                                            <td class="text-center">
                                                <div class="form-check d-flex justify-content-center">
                                                    <input class="form-check-input" type="checkbox" name="items[0][loaded]" value="1">
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-item" style="line-height: 1;">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-2">
                                        <button type="button" class="btn btn-sm btn-primary" id="addItemBtn">
                                            <i class="bi bi-plus-lg"></i> Add Item
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label for="special_instructions" class="form-label">Special Instructions</label>
                                    <textarea class="form-control form-control-sm" id="special_instructions" name="special_instructions" rows="3" placeholder="Any special loading instructions..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary btn-sm">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

           

@push('scripts')
<script>
    console.log('[Logistics Modal] Script loaded');
    document.addEventListener('DOMContentLoaded', function() {
        // No toggling needed; modal is handled by Bootstrap
        // Add new item row
        const addItemBtn = document.getElementById('addItemBtn');
        if (addItemBtn) {
            console.log('[Logistics Modal] Found addItemBtn, attaching click handler');
            document.getElementById('addItemBtn').addEventListener('click', function() {
                console.log('[Logistics Modal] Add Item button clicked');
            const tbody = document.getElementById('itemsList');
            const rowCount = tbody.rows.length;

            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${rowCount + 1}</td>
                <td><input type="text" class="form-control form-control-sm" name="items[${rowCount}][description]" placeholder="Item description" required></td>
                <td><input type="number" class="form-control form-control-sm" name="items[${rowCount}][quantity]" placeholder="Qty" min="1" required></td>
                <td>
                    <select class="form-select form-select-sm" name="items[${rowCount}][unit]" required>
                        <option>PCS</option>
                        <option>SET</option>
                        <option>BOX</option>
                        <option>PACK</option>
                    </select>
                </td>
                <td><input type="text" class="form-control form-control-sm" name="items[${rowCount}][notes]" placeholder="Any notes"></td>
                <td class="text-center">
                    <div class="form-check d-flex justify-content-center">
                        <input class="form-check-input" type="checkbox" name="items[${rowCount}][loaded]" value="1">
                    </div>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-item" style="line-height: 1;">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
            
            tbody.appendChild(newRow);
            
            // Add event listener to the new remove button
            newRow.querySelector('.remove-item').addEventListener('click', function() {
                this.closest('tr').remove();
                // Update row numbers
                updateRowNumbers();
            });
        });
        
        // Add event listeners to existing remove buttons
        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('tr').remove();
                // Update row numbers
                updateRowNumbers();
            });
        });
        
        // Function to update row numbers
        function updateRowNumbers() {
            const rows = document.querySelectorAll('#itemsList tr');
            rows.forEach((row, index) => {
                row.cells[0].textContent = index + 1;
            });
        }

        // Toggle form visibility (no header toggle button)
        const form = document.getElementById('logisticsForm');
        const preview = document.getElementById('logisticsPreview');
        const emptyState = document.getElementById('logisticsEmptyState');
        const emptyStateCreateBtn = document.getElementById('emptyStateCreateBtn');

        let formVisible = false;
        let hasLogisticsData = false;

        emptyStateCreateBtn.addEventListener('click', function() {
            formVisible = true;
            form.style.display = 'block';
            emptyState.style.display = 'none';
        });

        document.getElementById('cancelLogisticsForm').addEventListener('click', function() {
            formVisible = false;
            form.style.display = 'none';
            if (hasLogisticsData) {
                preview.style.display = 'block';
            } else {
                emptyState.style.display = 'block';
            }
        });
    }

});

</script>
@endpush

<style>
    .signature-box {
        min-height: 150px;
    }
    .card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
    }
    .card-header {
        border-bottom: none;
    }
    .table th {
        background-color: #2f4f7f;
        color: #fff;
    }
    .table th, .table td {
        padding: 0.5rem;
    }
    .form-control-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        border-radius: 0.2rem;
    }
    .form-select-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        border-radius: 0.2rem;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        border-radius: 0.2rem;
    }
</style>
@endsection
