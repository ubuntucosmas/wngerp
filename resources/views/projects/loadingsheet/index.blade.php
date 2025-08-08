@extends('layouts.master')

@section('title', 'Loading Sheet - ' . $project->name)
@section('navbar-title', 'Loading Sheet')

@section('content')

@if ($errors->any())
    <div class="toast toast-error fade show" role="alert" aria-live="assertive" aria-atomic="true" style="position: fixed; top: 20px; right: 20px; z-index: 1050;">
        <div class="toast-header bg-danger text-white">
            <strong class="me-auto">Error</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<style>
    .toast {
        opacity: 1;
        animation: slideIn 0.3s ease-out;
    }
    
    .toast.toast-success {
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .toast.toast-error {
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>
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
        @if ($loadingsheet)
        <div class="btn-group">
            <a href="{{ route('projects.logistics.loading-sheet.print', ['project' => $project->id]) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-printer"></i> Print
            </a>
            <a href="{{ route('projects.logistics.loading-sheet.download', ['project' => $project->id]) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-download"></i> Download
            </a>
            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#loadingSheetModal">
                <i class="bi bi-pencil"></i> Edit
            </button>
        </div>
        @endif
    </div>

    <div class="card shadow-sm">
        <div class="card-body pt-4">
            @if ($loadingsheet)
                <!-- Project and Loading Info Header -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Project Details</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-1"><strong>Project:</strong> {{ $project->name }}</p>
                                <p class="mb-1"><strong>Client:</strong> {{ $project->client_name ?? ($project->client->name ?? 'N/A') }}</p>
                                <p class="mb-1"><strong>Project ID:</strong> {{ $project->project_id ?? 'N/A' }}</p>
                                <p class="mb-0"><strong>Location:</strong> {{ $project->venue ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Vehicle & Driver</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-1"><strong>Vehicle:</strong> {{ $loadingsheet['vehicle_number'] ?? 'N/A' }}</p>
                                <p class="mb-1"><strong>Driver:</strong> {{ $loadingsheet['driver_name'] ?? 'N/A' }}</p>
                                <p class="mb-0"><strong>Contact:</strong> {{ $loadingsheet['driver_phone'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Loading Details</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-1"><strong>From:</strong> {{ $loadingsheet['loading_point'] ?? 'N/A' }}</p>
                                <p class="mb-1"><strong>To:</strong> {{ $loadingsheet['unloading_point'] ?? 'N/A' }}</p>
                                <p class="mb-1"><strong>Loading:</strong> {{ isset($loadingsheet['loading_date']) ? \Carbon\Carbon::parse($loadingsheet['loading_date'])->format('M d, Y') : 'N/A' }}</p>
                                <p class="mb-0"><strong>Offloading:</strong> {{ isset($loadingsheet['unloading_date']) ? \Carbon\Carbon::parse($loadingsheet['unloading_date'])->format('M d, Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if (!empty($loadingsheet['special_instructions']))
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading">Special Instructions</h6>
                        <p class="mb-0">{{ $loadingsheet['special_instructions'] }}</p>
                    </div>
                @endif

                <!-- Items Table -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Items to be Loaded</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Description</th>
                                        <th>Quantity</th>
                                        <th>Unit</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse (($loadingsheet['items'] ?? []) as $i => $item)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $item['description'] ?? '' }}</td>
                                            <td>{{ $item['quantity'] ?? '' }}</td>
                                            <td>{{ $item['unit'] ?? '' }}</td>
                                            <td>{{ $item['notes'] ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center">No items found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center my-5 py-5" id="loadingSheetEmptyState">
                    <div class="mb-4">
                        <i class="bi bi-truck text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="text-muted mb-3">No Loading Sheet Found</h4>
                    <p class="text-muted mb-4">Get started by creating a new loading sheet for this project.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loadingSheetModal" id="emptyStateCreateBtn">
                        <i class="bi bi-plus-circle me-2"></i> Create Loading Sheet
                    </button>
                </div>
            @endif



            <!-- Loading Sheet Modal -->
            <div class="modal fade" id="loadingSheetModal" tabindex="-1" aria-labelledby="loadingSheetModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="loadingSheetModalLabel">Create Loading Sheet</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="loadingSheetForm" method="POST" action="{{ route('projects.logistics.loading-sheet', $project) }}" autocomplete="off">
                            @csrf
                            <input type="hidden" name="loading_sheet_id" id="loading_sheet_id" value="{{ $loadingsheet['id'] ?? '' }}">
                            <div class="modal-body">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <h5>Vehicle & Driver</h5>
                                        <div class="mb-3">
                                            <label for="vehicle_number" class="form-label">Vehicle Registration</label>
                                            <input type="text" class="form-control form-control-sm" id="vehicle_number" name="vehicle_number" 
                                                value="{{ $loadingsheet['vehicle_number'] ?? '' }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="driver_name" class="form-label">Driver Name</label>
                                            <input type="text" class="form-control form-control-sm" id="driver_name" name="driver_name" 
                                                value="{{ $loadingsheet['driver_name'] ?? '' }}" required>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <h5>Loading Details</h5>
                                        <div class="mb-3">
                                            <label for="loading_point" class="form-label">Loading Point</label>
                                            <input type="text" class="form-control form-control-sm" id="loading_point" name="loading_point" 
                                                value="{{ $loadingsheet['loading_point'] ?? '' }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="unloading_point" class="form-label">Offloading Point</label>
                                            <input type="text" class="form-control form-control-sm" id="unloading_point" name="unloading_point" 
                                                value="{{ $loadingsheet['unloading_point'] ?? '' }}" required>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="loading_date" class="form-label">Loading Date</label>
                                                    <input type="date" class="form-control form-control-sm" id="loading_date" name="loading_date" 
                                                        value="{{ isset($loadingsheet['loading_date']) ? \Carbon\Carbon::parse($loadingsheet['loading_date'])->format('Y-m-d') : '' }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="unloading_date" class="form-label">Offloading Date</label>
                                                    <input type="date" class="form-control form-control-sm" id="unloading_date" name="unloading_date" 
                                                        value="{{ isset($loadingsheet['unloading_date']) ? \Carbon\Carbon::parse($loadingsheet['unloading_date'])->format('Y-m-d') : '' }}" required>
                                                </div>
                                            </div>
                                        </div>
                                </div>


                                <hr>
                                <h5 class="mb-3">Items to be Loaded</h5>
                                <div class="table-responsive mb-3">
                                    <table class="table table-bordered" id="itemsTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Item Description</th>
                                                <th>Quantity</th>
                                                <th>Unit</th>
                                                <th>Notes</th>
                                                <th style="width: 40px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemsList">
                                            @php $itemCount = 0; @endphp
                                            @if(!empty($loadingsheet['items']))
                                                @foreach($loadingsheet['items'] as $index => $item)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>
                                                            <input type="text" class="form-control form-control-sm" 
                                                                name="items[{{ $index }}][description]" 
                                                                value="{{ $item['description'] }}" required>
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control form-control-sm" 
                                                                name="items[{{ $index }}][quantity]" 
                                                                value="{{ $item['quantity'] }}" min="0" step="0.01" required>
                                                        </td>
                                                        <td>
                                                            <select class="form-select form-select-sm" name="items[{{ $index }}][unit]" required>
                                                                @php
                                                                    $units = ['pcs' => 'Pieces', 'set' => 'Set', 'box' => 'Box', 'pallet' => 'Pallet', 
                                                                             'kg' => 'Kilogram (kg)', 'g' => 'Gram (g)', 'l' => 'Liter (l)', 
                                                                             'm' => 'Meter (m)', 'cm' => 'Centimeter (cm)'];
                                                                @endphp
                                                                @foreach($units as $value => $label)
                                                                    <option value="{{ $value }}" {{ ($item['unit'] ?? '') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control form-control-sm" 
                                                                name="items[{{ $index }}][notes]" 
                                                                value="{{ $item['notes'] ?? '' }}">
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-sm btn-danger remove-item">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @php $itemCount = $index + 1; @endphp
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td>1</td>
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm" 
                                                            name="items[0][description]" placeholder="Item description" required>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control form-control-sm" 
                                                            name="items[0][quantity]" placeholder="Qty" min="0" step="0.01" required>
                                                    </td>
                                                    <td>
                                                        <select class="form-select form-select-sm" name="items[0][unit]" required>
                                                            @php
                                                                $units = ['pcs' => 'Pieces', 'set' => 'Set', 'box' => 'Box', 'pallet' => 'Pallet', 
                                                                         'kg' => 'Kilogram (kg)', 'g' => 'Gram (g)', 'l' => 'Liter (l)', 
                                                                         'm' => 'Meter (m)', 'cm' => 'Centimeter (cm)'];
                                                            @endphp
                                                            @foreach($units as $value => $label)
                                                                <option value="{{ $value }}">{{ $label }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm" 
                                                            name="items[0][notes]" placeholder="Notes">
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-sm btn-danger remove-item">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @php $itemCount = 1; @endphp
                                            @endif
                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="addItem">
                                        <i class="bi bi-plus"></i> Add Item
                                    </button>
                                    <input type="hidden" id="itemCount" value="{{ $itemCount ?? 1 }}">
                                </div>
                                </div>
                                <div class="mt-3">
                                    <label for="special_instructions" class="form-label">Special Instructions</label>
                                    <textarea class="form-control form-control-sm" id="special_instructions" name="special_instructions" rows="2" placeholder="Any special loading instructions...">{{ $loadingsheet['special_instructions'] ?? '' }}</textarea>
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
<!-- jQuery (required for dynamic row add/remove) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Initialize when document is ready
    $(document).ready(function() {
        // Initialize nextIndex from hidden input (represents the next array index to use)
        let nextIndex = parseInt($('#itemCount').val()) || 1;
        
        // Initialize remove item functionality
        $(document).on('click', '.remove-item', function() {
            if ($(this).closest('tr').length) { // Check if button is inside a row
                if ($('#itemsList tr').length > 1) {
                    $(this).closest('tr').remove();
                    // Renumber rows
                    $('#itemsList tr').each(function(index) {
                        $(this).find('td:first').text(index + 1);
                    });
                    // Do not decrement nextIndex to avoid index collisions
                } else {
                    alert('At least one item is required');
                }
            }
        });

        // Add item row
        $('#addItem').off('click').on('click', function(e) {
            e.preventDefault(); // Prevent any default behavior
            
            // Create new row
            const rowNumber = $('#itemsList tr').length + 1;
            const newRow = $(`
                <tr>
                    <td>${rowNumber}</td>
                    <td>
                        <input type="text" class="form-control form-control-sm" 
                            name="items[${nextIndex}][description]" placeholder="Item description" required>
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm" 
                            name="items[${nextIndex}][quantity]" placeholder="Qty" min="0" step="0.01" required>
                    </td>
                    <td>
                        <select class="form-select form-select-sm" name="items[${nextIndex}][unit]" required>
                            <option value="pcs">Pieces</option>
                            <option value="set">Set</option>
                            <option value="box">Box</option>
                            <option value="pallet">Pallet</option>
                            <option value="kg">Kilogram (kg)</option>
                            <option value="g">Gram (g)</option>
                            <option value="l">Liter (l)</option>
                            <option value="m">Meter (m)</option>
                            <option value="cm">Centimeter (cm)</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" 
                            name="items[${nextIndex}][notes]" placeholder="Notes">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger remove-item">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
            
            // Append the new row
            $('#itemsList').append(newRow);
            
            // Update nextIndex and hidden input
            nextIndex++;
            $('#itemCount').val(nextIndex);
        });
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
