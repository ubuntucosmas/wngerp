@extends('layouts.master')
@section('title', 'Edit Project Material-List')

@section('content')
<div class="container-fluid d-flex justify-content-center align-items-start py-4" style="min-height: 100vh; background: #f4f6fa;">
    <div class="material-list-card card shadow-sm w-100" style="max-width: 1100px;">
        <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom-0" style="border-radius: 16px 16px 0 0;">
            <h2 class="mb-0 fs-5 fw-bold">Edit Material List</h2>
            <a href="{{ (isset($enquiry) && is_object($enquiry) && isset($enquiry->id)) ? route('enquiries.material-list.index', $enquiry) : route('projects.material-list.index', $project) }}"
               class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1"
               data-bs-toggle="tooltip" data-bs-placement="bottom" title="Back to Material Lists" aria-label="Back to Material Lists">
                <i class="bi bi-arrow-left"></i> <span class="d-none d-md-inline">Back to Material Lists</span>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="row g-0">
                <div class="col-md-2 sidebar-col">
                    <nav class="sidebar-nav sticky-top card h-100 shadow-sm mb-0" style="top: 80px; z-index: 100; border-radius: 12px 0 0 12px;">
                        <ul class="nav flex-column py-3 px-2">
                            <li class="nav-item"><a href="#basic-details" class="nav-link active" data-bs-toggle="tooltip" title="Go to Basic Details" aria-label="Go to Basic Details">Basic Details</a></li>
                            <li class="nav-item"><a href="#materials-production" class="nav-link" data-bs-toggle="tooltip" title="Go to Materials - Production" aria-label="Go to Materials - Production">Materials - Production</a></li>
                            <li class="nav-item"><a href="#materials-hire" class="nav-link" data-bs-toggle="tooltip" title="Go to Items for Hire" aria-label="Go to Items for Hire">Items for Hire</a></li>
                            <!-- <li class="nav-item"><a href="#labour-workshop" class="nav-link" data-bs-toggle="tooltip" title="Go to Workshop Labour" aria-label="Go to Workshop Labour">Workshop Labour</a></li>
                            <li class="nav-item"><a href="#labour-site" class="nav-link" data-bs-toggle="tooltip" title="Go to Site Labour" aria-label="Go to Site Labour">Site Labour</a></li>
                            <li class="nav-item"><a href="#labour-setdown" class="nav-link" data-bs-toggle="tooltip" title="Go to Set Down Labour" aria-label="Go to Set Down Labour">Set Down Labour</a></li>
                            <li class="nav-item"><a href="#logistics" class="nav-link" data-bs-toggle="tooltip" title="Go to Logistics" aria-label="Go to Logistics">Logistics</a></li>
                            <li class="nav-item"><a href="#labour-outsourced" class="nav-link" data-bs-toggle="tooltip" title="Go to Outsourced" aria-label="Go to Outsourced">Outsourced</a></li> -->
                            <li class="nav-item"><a href="#approval" class="nav-link" data-bs-toggle="tooltip" title="Go to Approval" aria-label="Go to Approval">Approval</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-md-10 form-content-col">
                    <form action="{{ isset($enquiry) ? route('enquiries.material-list.update', [$enquiry, $materialList]) : route('projects.material-list.update', [$project, $materialList]) }}" method="POST" class="p-3 position-relative" id="materialListForm">
                        @csrf
                        @method('PUT')
                        <div class="form-container">
                            <!-- Basic Details -->
                            <div id="basic-details" class="form-section-card">
                                <div class="form-section-card-header">
                                    <h5><i class="fas fa-info-circle me-2"></i>Basic Details</h5>
                                </div>
                                <div class="form-section-card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="project_name">{{ isset($enquiry) ? 'Enquiry' : 'Project' }} Name</label>
                                            <input type="text" class="form-control" name="project_name" value="{{ isset($enquiry) ? $enquiry->project_name : $project->name }}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="client">Client</label>
                                            <input type="text" class="form-control" name="client" value="{{ isset($enquiry) ? $enquiry->client_name : $project->client_name }}" readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label for="start_date">Start Date</label>
                                            <input type="date" class="form-control" name="start_date" value="{{ old('start_date', $materialList->start_date ? $materialList->start_date->format('Y-m-d') : '') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="end_date">End Date</label>
                                            <input type="date" class="form-control" name="end_date" value="{{ old('end_date', $materialList->end_date ? $materialList->end_date->format('Y-m-d') : '') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Materials - Production -->
                            <div id="materials-production" class="form-section-card section-production">
                                <div class="form-section-card-header">
                                    <h5><i class="bi bi-box-seam me-2"></i>Materials - Production</h5>
                                </div>
                                <div class="form-section-card-body">
                                    <div id="items-wrapper">
                                        @if($materialList->productionItems && count($materialList->productionItems))
                                            @foreach($materialList->productionItems as $piIndex => $item)
                                                <div class="item-group border rounded p-3 mb-3" data-item-index="{{ $piIndex }}">
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-semibold">Select Template</label>
                                                            <select class="form-select template-select" name="production_items[{{ $piIndex }}][template_id]" data-item-index="{{ $piIndex }}">
                                                                <option value="">-- Select Project Items --</option>
                                                            </select>
                                                            <!-- <small class="form-text text-muted">Select a template to auto-fill particulars, or leave empty to enter manually</small> -->
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-semibold">Item Name</label>
                                                            <input type="text" name="production_items[{{ $piIndex }}][item_name]" class="form-control item-name" value="{{ old('production_items.'.$piIndex.'.item_name', $item->item_name) }}" readonly>
                                                        </div>
                                                    </div>
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Particular</th>
                                                                <th>Unit Of Measure</th>
                                                                <th>Quantity</th>
                                                                <!-- <th>Unit Price</th> -->
                                                                <th>Comment</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="particulars-body">
                                                            @foreach($item->particulars as $partIndex => $particular)
                                                                <tr>
                                                                    <td>
                                                                        <select name="production_items[{{ $piIndex }}][particulars][{{ $partIndex }}][particular]" class="form-select inventory-dropdown" required>
                                                                            <option value="" disabled>-- Loading items --</option>
                                                                            <option value="{{ $particular->particular }}" selected>{{ $particular->particular }}</option>
                                                                        </select>
                                                                    </td>
                                                                    <td><input type="text" name="production_items[{{ $piIndex }}][particulars][{{ $partIndex }}][unit]" class="form-control unit-field" value="{{ old('production_items.'.$piIndex.'.particulars.'.$partIndex.'.unit', $particular->unit) }}" readonly></td>
                                                                    <td><input type="number" step="0.01" name="production_items[{{ $piIndex }}][particulars][{{ $partIndex }}][quantity]" class="form-control" value="{{ old('production_items.'.$piIndex.'.particulars.'.$partIndex.'.quantity', $particular->quantity) }}" required></td>
                                                                    <!-- <td><input type="number" step="0.01" name="production_items[{{ $piIndex }}][particulars][{{ $partIndex }}][unit_price]" class="form-control" value="{{ old('production_items.'.$piIndex.'.particulars.'.$partIndex.'.unit_price', $particular->unit_price ?? '0.00') }}" required></td> -->
                                                                    <td><input type="text" name="production_items[{{ $piIndex }}][particulars][{{ $partIndex }}][comment]" class="form-control" value="{{ old('production_items.'.$piIndex.'.particulars.'.$partIndex.'.comment', $particular->comment) }}"></td>
                                                                    <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <button type="button" class="btn btn-success btn-sm add-particular">
                                                            <i class="bi bi-plus-circle me-1"></i>Add Particular
                                                        </button>
                                                        <button type="button" class="btn btn-outline-danger btn-sm remove-item-group">
                                                            <i class="bi bi-trash me-1"></i>Remove Item
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="item-group border rounded p-3 mb-3" data-item-index="0">
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Select Template</label>
                                                        <select class="form-select template-select" name="production_items[0][template_id]" data-item-index="0">
                                                            <option value="">-- Choose a template or enter manually --</option>
                                                        </select>
                                                        <small class="form-text text-muted">Select a template to auto-fill particulars, or leave empty to enter manually</small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Item Name</label>
                                                        <input type="text" name="production_items[0][item_name]" class="form-control item-name" placeholder="e.g. Table" required>
                                                    </div>
                                                </div>
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Particular</th>
                                                            <th>Unit Of Measure</th>
                                                            <th>Quantity</th>
                                                            <!-- <th>Unit Price</th> -->
                                                            <th>Comment</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="particulars-body">
                                                        <tr>
                                                            <td>
                                                                <select name="production_items[0][particulars][0][particular]" class="form-select inventory-dropdown" required>
                                                                    <option value="" selected disabled>-- Loading items --</option>
                                                                </select>
                                                            </td>
                                                            <td><input type="text" name="production_items[0][particulars][0][unit]" class="form-control unit-field" readonly></td>
                                                            <td><input type="number" step="0.01" name="production_items[0][particulars][0][quantity]" class="form-control" required></td>
                                                            <!-- <td><input type="number" step="0.01" name="production_items[0][particulars][0][unit_price]" class="form-control" required></td> -->
                                                            <td><input type="text" name="production_items[0][particulars][0][comment]" class="form-control"></td>
                                                            <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="bi bi-trash"></i></button></td>
                                                        </tr>
                                                    </tbody>
                                                                            </table>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <button type="button" class="btn btn-success btn-sm add-particular">
                                                        <i class="bi bi-plus-circle me-1"></i>Add Particular
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-item-group">
                                                        <i class="bi bi-trash me-1"></i>Remove Item
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-primary btn-sm btn-add-item" id="addItemGroup">
                                        <i class="bi bi-plus-circle"></i> Add Item
                                        </button>
                                </div>
                            </div>

                            <!-- Materials for Hire -->
                            <div id="materials-hire" class="form-section-card section-hire">
                                <div class="form-section-card-header">
                                        <h5><i class="bi bi-tools me-2"></i>Items for Hire</h5>
                                </div>
                                <div class="form-section-card-body">
                                    <table class="table table-bordered" id="materialsHireTable">
                                        <thead>
                                            <tr>
                                                <th>Particular</th>
                                                <th>Unit Of Measure</th>
                                                <th>Quantity</th>
                                                <!-- <th>Unit Price</th> -->
                                                <th>Comment</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="materialsHireBody">
                                            @if($materialList->materialsHire && count($materialList->materialsHire))
                                                @foreach($materialList->materialsHire as $index => $item)
                                                    <tr>
                                                        <td>
                                                            <select name="materials_hire[{{ $index }}][particular]" class="form-select inventory-dropdown" required>
                                                                <option value="" disabled>-- Loading items --</option>
                                                                <option value="{{ $item->particular }}" selected>{{ $item->particular }}</option>
                                                            </select>
                                                            <input type="hidden" name="materials_hire[{{ $index }}][item_name]" value="{{ $item->particular }}">
                                                        </td>
                                                        <td><input type="text" name="materials_hire[{{ $index }}][unit]" class="form-control" value="{{ old('materials_hire.'.$index.'.unit', $item->unit) }}"></td>
                                                        <td><input type="number" step="0.01" name="materials_hire[{{ $index }}][quantity]" class="form-control" value="{{ old('materials_hire.'.$index.'.quantity', $item->quantity) }}"></td>
                                                        <!-- <td><input type="number" step="0.01" name="materials_hire[{{ $index }}][unit_price]" class="form-control" value="{{ old('materials_hire.'.$index.'.unit_price', $item->unit_price ?? '0.00') }}"></td> -->
                                                        <td><input type="text" name="materials_hire[{{ $index }}][comment]" class="form-control" value="{{ old('materials_hire.'.$index.'.comment', $item->comment) }}"></td>
                                                        <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-success btn-sm btn-add-item" id="addHireRow">
                                        <i class="bi bi-plus-circle"></i> Add Row
                                        </button>
                                </div>

                                @php
                                    $subCategories = [
                                        'Workshop labour' => ['Technicians', 'Carpenter', 'CNC', 'Welders', 'Project Officer','Meals'],
                                        'Site' => ['Technicians', 'Pasters', 'Electricians','Off loaders','Project Officer','Meals'],
                                        'Set down' => ['Technicians', 'Off loaders', 'Electricians', 'Meals'],
                                        'Logistics' => ['Delivery to site', 'Delivery from site', 'Team transport to and from site set up', 'Team transport to and from set down','Materials Collection'],
                                        'Outsourced' => ['Subcontractors', 'External Services', 'Specialized Equipment', 'Third-party Vendors', 'Consultants', 'Freelancers'],
                                        ];
                                    @endphp

                                <!-- @foreach($subCategories as $category => $roles)
                                    <div class="section-card section-labor">
                                        <h5 class="section-header">
                                            <i class="bi bi-people me-2"></i>{{ $category }}
                                        </h5>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Particular</th>
                                                    <th>Unit Of Measure</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Price</th>
                                                    <th>Comment</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $labourItems = $labourItemsByCategory[$category] ?? []; @endphp
                                                @foreach($roles as $index => $role)
                                                    <tr>
                                                        <td><input type="text" name="items[{{ $category }}][{{ $index }}][particular]" class="form-control" value="{{ $labourItems[$index]->particular ?? $role }}" {{ isset($labourItems[$index]) ? '' : 'readonly' }}></td>
                                                        <td><input type="text" name="items[{{ $category }}][{{ $index }}][unit]" class="form-control" value="{{ $labourItems[$index]->unit ?? ($category === 'Logistics' ? 'Trips' : 'pax') }}"></td>
                                                        <td><input type="number" step="0.01" name="items[{{ $category }}][{{ $index }}][quantity]" class="form-control" value="{{ $labourItems[$index]->quantity ?? '' }}"></td>
                                                        <td><input type="number" step="0.01" name="items[{{ $category }}][{{ $index }}][unit_price]" class="form-control" value="{{ $labourItems[$index]->unit_price ?? '' }}"></td>
                                                        <td><input type="text" name="items[{{ $category }}][{{ $index }}][comment]" class="form-control" value="{{ $labourItems[$index]->comment ?? '' }}"></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        </div>
                                    @endforeach -->

                                <div class="mb-4">
                                    <label for="approved_by">Approved By:</label>
                                    <input type="text" name="approved_by" class="form-control mb-2 required" value="{{ old('approved_by', $materialList->approved_by) }}" required>

                                    <label for="approved_departments">Department</label>
                                    <input type="text" name="approved_departments" class="form-control" placeholder="Production, Finance" value="{{ old('approved_departments', $materialList->approved_departments) }}" required>
                                </div>

                            </div>
                        </div>
                        <!-- Sticky Action Bar -->
                        <div class="sticky-action-bar d-flex justify-content-end gap-2 mt-4 pt-3 border-top bg-white" style="position:sticky; bottom:0; z-index:1100; border-radius:0 0 12px 12px;">
                            <a href="{{ (isset($enquiry) && is_object($enquiry) && isset($enquiry->id)) ? route('enquiries.material-list.show', [$enquiry, $materialList]) : route('projects.material-list.show', [$project, $materialList]) }}"
                               class="btn btn-outline-secondary d-flex align-items-center gap-1"
                               data-bs-toggle="tooltip" title="Cancel and go back" aria-label="Cancel and go back">
                                <i class="bi bi-x-circle"></i> <span class="d-none d-md-inline">Cancel</span>
                            </a>
                            <button type="reset" class="btn btn-outline-secondary d-flex align-items-center gap-1" data-bs-toggle="tooltip" title="Reset Form" aria-label="Reset Form">
                                <i class="bi bi-arrow-counterclockwise"></i> <span class="d-none d-md-inline">Reset</span>
                            </button>
                            <button type="submit" class="btn btn-primary d-flex align-items-center gap-1" id="submitBtn" data-bs-toggle="tooltip" title="Update Material-List" aria-label="Update Material-List">
                                <span class="spinner-border spinner-border-sm me-1 d-none" id="submitSpinner" role="status" aria-hidden="true"></span>
                                <i class="bi bi-save"></i> <span class="d-none d-md-inline">Update Material-List</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .section-card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 2rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .section-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }
    .section-production { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-left: 4px solid #4e73df; }
    .section-hire { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-left: 4px solid #36b9cc; }
    .section-labor { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-left: 4px solid #1cc88a; }
    .section-header { color: #2e59d9; font-weight: 600; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid #e3e6f0; }
    .btn-add-item { border-radius: 20px; font-weight: 500; padding: 0.4rem 1.25rem; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); }
    .material-list-card {
        border-radius: 16px;
        background: #fff;
        margin: 0 auto;
    }
    .sidebar-col {
        min-width: 180px;
        max-width: 220px;
    }
    .sidebar-nav {
        border-radius: 12px 0 0 12px;
        background: #f8f9fb;
        border: none;
        min-height: 100%;
    }
    .sidebar-nav .nav-link {
        color: #4e73df;
        font-weight: 500;
        border-radius: 8px;
        margin-bottom: 4px;
        transition: background 0.2s;
    }
    .sidebar-nav .nav-link.active, .sidebar-nav .nav-link:hover {
        background: #e3e6f0;
        color: #224abe;
    }
    .form-content-col {
        padding-left: 0;
    }
    .sticky-action-bar {
        padding: 1rem 1.5rem 1rem 1.5rem;
        box-shadow: 0 -2px 8px rgba(0,0,0,0.04);
        margin-left: -1.5rem;
        margin-right: -1.5rem;
    }
    @media (max-width: 900px) {
        .material-list-card { max-width: 100vw; }
        .sidebar-col { display: none; }
        .form-content-col { width: 100%; }
    }
    @media (max-width: 600px) {
        .sticky-action-bar {
            padding: 0.5rem 0.5rem;
            margin-left: 0;
            margin-right: 0;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // --- Production Items ---
    let itemIndex = $('#items-wrapper .item-group').length;
    let particularCounters = {};
    $('#items-wrapper .item-group').each(function(index) {
        particularCounters[index] = $(this).find('.particulars-body tr').length;
    });

    // Add new item group
    $('#addItemGroup').on('click', function() {
        const newGroup = `
        <div class="item-group border rounded p-3 mb-3" data-item-index="${itemIndex}">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Select Template</label>
                    <select class="form-select template-select" name="production_items[${itemIndex}][template_id]" data-item-index="${itemIndex}">
                        <option value="">-- Choose a template or enter manually --</option>
                    </select>
                    <small class="form-text text-muted">Select a template to auto-fill particulars, or leave empty to enter manually</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Item Name</label>
                    <input type="text" name="production_items[${itemIndex}][item_name]" class="form-control item-name" placeholder="e.g. Table" required>
                </div>
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Particular</th>
                        <th>Unit Of Measure</th>
                        <th>Quantity</th>
                        <!-- <th>Unit Price</th> -->
                        <th>Comment</th>
                        <th>Design Reference</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="particulars-body">
                    <!-- Rows will be added dynamically -->
                            </tbody>
        </table>
        <div class="d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-success btn-sm add-particular">
                <i class="bi bi-plus-circle me-1"></i>Add Particular
            </button>
            <button type="button" class="btn btn-outline-danger btn-sm remove-item-group">
                <i class="bi bi-trash me-1"></i>Remove Item
            </button>
        </div>
    </div>`;
        const $newGroup = $(newGroup);
        $('#items-wrapper').append($newGroup);
        particularCounters[itemIndex] = 0;
        // Add the first particular row
        $newGroup.find('.add-particular').trigger('click');
        itemIndex++;
    });

    // Add particular to item group
    $(document).on('click', '.add-particular', function() {
        const $itemGroup = $(this).closest('.item-group');
        const itemIndex = $itemGroup.data('item-index');
        const particularIndex = particularCounters[itemIndex] || 0;
        const newRow = `
            <tr>
                <td>
                    <select name="production_items[${itemIndex}][particulars][${particularIndex}][particular]" class="form-select inventory-dropdown" required>
                        <option value="" selected disabled>-- Loading items --</option>
                    </select>
                </td>
                <td><input type="text" name="production_items[${itemIndex}][particulars][${particularIndex}][unit]" class="form-control unit-field" readonly></td>
                <td><input type="number" step="0.01" name="production_items[${itemIndex}][particulars][${particularIndex}][quantity]" class="form-control" required></td>
                <!-- <td><input type="number" step="0.01" name="production_items[${itemIndex}][particulars][${particularIndex}][unit_price]" class="form-control" required></td> -->
                <td><input type="text" name="production_items[${itemIndex}][particulars][${particularIndex}][comment]" class="form-control"></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
            </tr>`;
        const $newRow = $(newRow);
        $itemGroup.find('.particulars-body').append($newRow);
        particularCounters[itemIndex] = particularIndex + 1;
        initializeProductionRow($newRow);
    });

    // Function to load inventory items into dropdown
    function loadInventoryDropdown(selectElement, selectedValue = '') {
        selectElement.prop('disabled', true).html('<option value="">Loading items...</option>');
        $.ajax({
            url: '{{ route("api.inventory.particulars-items") }}',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                const currentValue = selectElement.val();
                selectElement.empty();
                selectElement.append($('<option>', {
                    value: '',
                    text: '-- Select an item --',
                    disabled: true,
                    selected: !currentValue && !selectedValue
                }));
                data.forEach(item => {
                    const option = $('<option>', {
                        value: item.name,
                        text: item.name,
                        'data-unit': item.unit_of_measure || ''
                    });
                    if ((selectedValue && item.name === selectedValue) || (!selectedValue && currentValue === item.name)) {
                        option.prop('selected', true);
                    }
                    selectElement.append(option);
                });
                selectElement.prop('disabled', false);
                if (selectedValue || currentValue) {
                    selectElement.trigger('change');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading inventory items:', error);
                selectElement.empty().append($('<option>', {
                    value: '',
                    text: 'Error loading items. Please refresh the page.',
                    disabled: true
                }));
            }
        });
    }

    // Function to initialize a production row
    function initializeProductionRow($row) {
        const $select = $row.find('.inventory-dropdown');
        const $unitField = $row.find('.unit-field');
        $unitField.prop('readonly', true);
        if (!$select.data('initialized')) {
            loadInventoryDropdown($select, $select.find('option[selected]').val());
            $select.data('initialized', true);
        }
    }

    // Initialize all existing particulars dropdowns on page load
    $('.item-group .particulars-body tr').each(function() {
        initializeProductionRow($(this));
    });

    // Initialize the default particular row that's already in the HTML (for new items)
    $('.item-group:first .particulars-body tr').each(function() {
        if (!$(this).find('.inventory-dropdown').data('initialized')) {
            initializeProductionRow($(this));
        }
    });

    // Handle dropdown change for particulars
    $(document).on('change', '.inventory-dropdown', function() {
        const $this = $(this);
        const selectedOption = $this.find('option:selected');
        const unit = selectedOption.data('unit') || '';
        const $unitField = $this.closest('tr').find('.unit-field').first();
        $unitField.val(unit);
    });

    // --- Materials for Hire ---
    let hireIndex = $('#materialsHireBody tr').length;
    $('#addHireRow').on('click', function() {
        const newRow = `
            <tr>
                <td>
                    <select name="materials_hire[${hireIndex}][particular]" class="form-select inventory-dropdown" required>
                        <option value="" selected disabled>-- Loading items --</option>
                    </select>
                    <input type="hidden" name="materials_hire[${hireIndex}][item_name]" value="">
                </td>
                <td><input type="text" name="materials_hire[${hireIndex}][unit]" class="form-control unit-field" readonly></td>
                <td><input type="number" step="0.01" name="materials_hire[${hireIndex}][quantity]" class="form-control"></td>
                <!-- <td><input type="number" step="0.01" name="materials_hire[${hireIndex}][unit_price]" class="form-control"></td> -->
                <td><input type="text" name="materials_hire[${hireIndex}][comment]" class="form-control"></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
            </tr>`;
        const $newRow = $(newRow);
        $('#materialsHireBody').append($newRow);
        initializeHireRow($newRow);
        hireIndex++;
    });

    // Function to initialize a new hire row
    function initializeHireRow($row) {
        const $select = $row.find('.inventory-dropdown');
        const $unitField = $row.find('.unit-field');
        $unitField.prop('readonly', true);
        if (!$select.data('initialized')) {
            loadInventoryDropdown($select, $select.find('option[selected]').val());
            $select.data('initialized', true);
        }
    }

    // Initialize existing hire rows on page load
    $('#materialsHireBody tr').each(function() {
        initializeHireRow($(this));
    });

    // Remove row for hire
    $(document).on('click', '.remove-row', function() {
        $(this).closest('tr').remove();
    });

    // Remove item group
    $(document).on('click', '.remove-item-group', function() {
        if (confirm('Are you sure you want to remove this item and all its particulars?')) {
            $(this).closest('.item-group').remove();
            reindexItems();
        }
    });

    // Re-index items after removal
    function reindexItems() {
        const newCounters = {};
        $('.item-group').each(function(newIndex) {
            const oldIndex = $('.item-group').index(this);
            newCounters[newIndex] = particularCounters[oldIndex] || 1;
            $(this).find('input[name^="production_items["]').each(function() {
                const newName = $(this).attr('name').replace(
                    /production_items\[\d+\]/,
                    `production_items[${newIndex}]`
                );
                $(this).attr('name', newName);
            });
        });
        particularCounters = newCounters;
    }

    // Form validation
    $('form').on('submit', function(e) {
        let isValid = true;
        $(this).find('[required]').each(function() {
            if (!$(this).val()) {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });

    // Update the dropdown change handler for materials for hire to update the hidden item_name field
    $(document).on('change', '.inventory-dropdown', function() {
        const $this = $(this);
        const selectedOption = $this.find('option:selected');
        const unit = selectedOption.data('unit') || '';
        const $unitField = $this.closest('tr').find('.unit-field').first();
        $unitField.val(unit);
        // If this is a materials for hire row, update the hidden item_name field
        if ($this.closest('tr').find('input[name*="materials_hire"][name*="item_name"]').length) {
            $this.closest('tr').find('input[name*="materials_hire"][name*="item_name"]').val($this.val());
        }
    });

    // Submit button loading state and feedback
    $('#materialListForm').on('submit', function(e) {
        var $btn = $('#submitBtn');
        var $spinner = $('#submitSpinner');
        $btn.prop('disabled', true);
        $spinner.removeClass('d-none');
        setTimeout(function() {
            $btn.prop('disabled', false);
            $spinner.addClass('d-none');
        }, 2000); // Simulate loading, replace with actual logic if needed
    });
    // Sidebar navigation active state
    $('.sidebar-nav .nav-link').on('click', function(e) {
        e.preventDefault();
        const target = $(this).attr('href');
        if ($(target).length) {
            $('html, body').animate({ scrollTop: $(target).offset().top - 80 }, 400);
        }
        $('.sidebar-nav .nav-link').removeClass('active');
        $(this).addClass('active');
    });
});
</script>
@endpush
