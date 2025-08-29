@extends('layouts.master')
@section('title', 'Edit Project Material-List')

@section('content')
<div class="container-fluid d-flex justify-content-center align-items-start py-4" style="min-height: 100vh; background: #f4f6fa;">
    <div class="material-list-card card shadow-sm w-100" style="max-width: 1600px;">
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
                <div class="col-md-1 sidebar-col">
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
                <div class="col-md-11 form-content-col">
                    <form action="{{ isset($enquiry) ? route('enquiries.material-list.update', [$enquiry, $materialList]) : route('projects.material-list.update', [$project, $materialList]) }}" method="POST" class="p-3 position-relative" id="materialListForm">
                        @csrf
                        @method('PUT')
                        <div class="accordion compact-accordion" id="materialListAccordion">
                            <!-- Basic Details -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingBasicDetails">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBasicDetails" aria-expanded="true" aria-controls="collapseBasicDetails">
                                        <i class="fas fa-info-circle me-2"></i>Basic Details
                                    </button>
                                </h2>
                                <div id="collapseBasicDetails" class="accordion-collapse collapse show" aria-labelledby="headingBasicDetails" data-bs-parent="#materialListAccordion">
                                    <div class="accordion-body">
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
                            </div>

                            <!-- Materials - Production -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingMaterialsProduction">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMaterialsProduction" aria-expanded="false" aria-controls="collapseMaterialsProduction">
                                        <i class="bi bi-box-seam me-2"></i>Materials - Production
                                    </button>
                                </h2>
                                <div id="collapseMaterialsProduction" class="accordion-collapse collapse" aria-labelledby="headingMaterialsProduction" data-bs-parent="#materialListAccordion">
                                    <div class="accordion-body">
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
                                                                        <div class="particular-input-group">
                                                                            <div class="input-group">
                                                                                <input type="text" 
                                                                                       name="production_items[{{ $piIndex }}][particulars][{{ $partIndex }}][particular]" 
                                                                                       class="form-control particular-input" 
                                                                                       value="{{ old('production_items.'.$piIndex.'.particulars.'.$partIndex.'.particular', $particular->particular) }}" 
                                                                                       placeholder="Type or select particular..."
                                                                                       required>
                                                                                <button class="btn btn-outline-secondary dropdown-toggle" 
                                                                                        type="button" 
                                                                                        data-bs-toggle="dropdown" 
                                                                                        aria-expanded="false"
                                                                                        title="Select from inventory">
                                                                                    <i class="bi bi-list"></i>
                                                                                </button>
                                                                                <ul class="dropdown-menu inventory-dropdown-menu" style="max-height: 200px; overflow-y: auto;">
                                                                                    <li><span class="dropdown-item-text text-muted small">Loading items...</span></li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
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
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingMaterialsHire">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMaterialsHire" aria-expanded="false" aria-controls="collapseMaterialsHire">
                                        <i class="bi bi-tools me-2"></i>Items for Hire
                                    </button>
                                </h2>
                                <div id="collapseMaterialsHire" class="accordion-collapse collapse" aria-labelledby="headingMaterialsHire" data-bs-parent="#materialListAccordion">
                                    <div class="accordion-body">
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
                                </div>
                            </div>

                            <!-- Approval -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingApproval">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseApproval" aria-expanded="false" aria-controls="collapseApproval">
                                        <i class="bi bi-check-circle me-2"></i>Approval
                                    </button>
                                </h2>
                                <div id="collapseApproval" class="accordion-collapse collapse" aria-labelledby="headingApproval" data-bs-parent="#materialListAccordion">
                                    <div class="accordion-body">
                                        <div class="mb-4">
                                            <label for="approved_by">Prepared By:</label>
                                            <input type="text" name="approved_by" class="form-control mb-2 required" value="{{ old('approved_by', $materialList->approved_by) }}" required>

                                            <label for="approved_departments">Departments</label>
                                            <input type="text" name="approved_departments" class="form-control" placeholder="Production, Finance" value="{{ old('approved_departments', $materialList->approved_departments) }}" required>
                                        </div>
                                    </div>
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
    <!-- Template Selection Modal -->
    <div class="modal fade" id="templateModal" tabindex="-1" aria-labelledby="templateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="templateModalLabel">
                        <i class="bi bi-file-earmark-plus me-2"></i>Add Item from Template
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="templateCategory" class="form-label">Category</label>
                            <select class="form-select" id="templateCategory">
                                <option value="">All Categories</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="templateSearch" class="form-label">Search Templates</label>
                            <input type="text" class="form-control" id="templateSearch" placeholder="Search templates...">
                        </div>
                    </div>
                    
                    <div id="templatesList" class="row">
                        <!-- Templates will be loaded here -->
                    </div>
                    
                    <div id="templateLoading" class="text-center py-4" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading templates...</p>
                    </div>
                    
                    <div id="templateEmpty" class="text-center py-4" style="display: none;">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <h6 class="mt-3">No templates found</h6>
                        <p class="text-muted">Create templates first to use this feature.</p>
                        <a href="{{ route('templates.templates.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Create Template
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Confirmation Modal for Remove Item Group -->
<div class="modal fade" id="confirmRemoveModal" tabindex="-1" aria-labelledby="confirmRemoveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmRemoveModalLabel">Confirm Removal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to remove this item and all its particulars?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmRemoveBtn">Remove</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    body { background: #f4f6fa; }
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
    .compact-accordion .accordion-item {
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        margin-bottom: 1rem;
        border: none;
        background: #f8f9fa;
    }
    .compact-accordion .accordion-button {
        border-radius: 10px 10px 0 0;
        padding: 0.75rem 1.25rem;
        font-size: 1.05rem;
        background: #f4f6fa;
    }
    .compact-accordion .accordion-body {
        padding: 1rem 1.25rem;
        background: #fff;
        border-radius: 0 0 10px 10px;
    }
    .floating-summary-bar {
        position: sticky;
        bottom: 0;
        left: 0;
        width: 100%;
        background: #f8f9fb;
        box-shadow: 0 -2px 8px rgba(0,0,0,0.08);
        padding: 0.5rem 1.5rem;
        z-index: 1050;
        border-top: 1px solid #e3e6f0;
        border-radius: 0 0 12px 12px;
        margin-top: 1.5rem;
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
        .compact-accordion .accordion-body, .compact-accordion .accordion-button {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        .floating-summary-bar { padding: 0.5rem 0.5rem; }
        .sticky-action-bar {
            padding: 0.5rem 0.5rem;
            margin-left: 0;
            margin-right: 0;
        }
    }

    /* Enhanced styles for the hybrid particular input */
    .particular-input-group {
        position: relative;
    }
    .particular-input-group .input-group {
        width: 100%;
    }
    .particular-input {
        border-right: none;
    }
    .particular-input:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        z-index: 3;
    }
    .particular-input-group .dropdown-toggle {
        border-left: none;
        background: #f8f9fa;
        border-color: #ced4da;
    }
    .particular-input-group .dropdown-toggle:hover {
        background: #e9ecef;
    }
    .inventory-dropdown-menu {
        width: 100%;
        min-width: 250px;
    }
    .inventory-dropdown-menu .dropdown-item {
        padding: 0.5rem 1rem;
        cursor: pointer;
    }
    .inventory-dropdown-menu .dropdown-item:hover {
        background-color: #f8f9fa;
    }
    .inventory-dropdown-menu .dropdown-item:active {
        background-color: #e9ecef;
    }
    .inventory-dropdown-menu .dropdown-item-text {
        padding: 0.5rem 1rem;
    }
    /* Highlight matching text in dropdown */
    .inventory-dropdown-menu .dropdown-item mark {
        background-color: #fff3cd;
        padding: 0;
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
                    <div class="particular-input-group">
                        <div class="input-group">
                            <input type="text" 
                                   name="production_items[${itemIndex}][particulars][${particularIndex}][particular]" 
                                   class="form-control particular-input" 
                                   placeholder="Type or select particular..."
                                   required>
                            <button class="btn btn-outline-secondary dropdown-toggle" 
                                    type="button" 
                                    data-bs-toggle="dropdown" 
                                    aria-expanded="false"
                                    title="Select from inventory">
                                <i class="bi bi-list"></i>
                            </button>
                            <ul class="dropdown-menu inventory-dropdown-menu" style="max-height: 200px; overflow-y: auto;">
                                <li><span class="dropdown-item-text text-muted small">Loading items...</span></li>
                            </ul>
                        </div>
                    </div>
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
        initializeHybridInput($newRow);
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

    // Function to initialize a production row (old dropdown system)
    function initializeProductionRow($row) {
        const $select = $row.find('.inventory-dropdown');
        const $unitField = $row.find('.unit-field');
        $unitField.prop('readonly', true);
        if (!$select.data('initialized')) {
            loadInventoryDropdown($select, $select.find('option[selected]').val());
            $select.data('initialized', true);
        }
    }

    // Function to initialize hybrid input system
    function initializeHybridInput($row) {
        const $input = $row.find('.particular-input');
        const $dropdownMenu = $row.find('.inventory-dropdown-menu');
        const $unitField = $row.find('.unit-field');
        const $dropdownToggle = $row.find('.dropdown-toggle');
        
        if ($input.data('initialized')) return;
        
        // Load inventory items into dropdown menu
        loadInventoryForHybrid($dropdownMenu, $input, $unitField);
        
        // Handle input changes for filtering
        $input.on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            filterInventoryItems($dropdownMenu, searchTerm);
        });
        
        // Handle dropdown item selection
        $dropdownMenu.on('click', '.dropdown-item', function(e) {
            e.preventDefault();
            const selectedText = $(this).text().trim();
            const selectedUnit = $(this).data('unit') || '';
            
            $input.val(selectedText);
            $unitField.val(selectedUnit);
            $dropdownToggle.dropdown('hide');
        });
        
        $input.data('initialized', true);
    }
    
    // Function to load inventory items for hybrid input
    function loadInventoryForHybrid($menu, $input, $unitField) {
        $.ajax({
            url: '{{ route("api.inventory.particulars-items") }}',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $menu.empty();
                if (data.length === 0) {
                    $menu.append('<li><span class="dropdown-item-text text-muted small">No items found</span></li>');
                    return;
                }
                
                data.forEach(item => {
                    const $item = $('<li><a class="dropdown-item" href="#" data-unit="' + 
                                  (item.unit_of_measure || '') + '">' + 
                                  item.name + '</a></li>');
                    $menu.append($item);
                });
                
                // Store original data for filtering
                $menu.data('original-items', data);
            },
            error: function(xhr, status, error) {
                console.error('Error loading inventory items:', error);
                $menu.html('<li><span class="dropdown-item-text text-danger small">Error loading items</span></li>');
            }
        });
    }
    
    // Function to filter inventory items based on search term
    function filterInventoryItems($menu, searchTerm) {
        const originalItems = $menu.data('original-items') || [];
        $menu.empty();
        
        if (!searchTerm) {
            // Show all items if no search term
            originalItems.forEach(item => {
                const $item = $('<li><a class="dropdown-item" href="#" data-unit="' + 
                              (item.unit_of_measure || '') + '">' + 
                              item.name + '</a></li>');
                $menu.append($item);
            });
            return;
        }
        
        // Filter items based on search term
        const filteredItems = originalItems.filter(item => 
            item.name.toLowerCase().includes(searchTerm)
        );
        
        if (filteredItems.length === 0) {
            $menu.append('<li><span class="dropdown-item-text text-muted small">No matching items</span></li>');
            return;
        }
        
        filteredItems.forEach(item => {
            const highlightedName = item.name.replace(
                new RegExp(searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'gi'),
                '<mark>$&</mark>'
            );
            const $item = $('<li><a class="dropdown-item" href="#" data-unit="' + 
                          (item.unit_of_measure || '') + '">' + 
                          highlightedName + '</a></li>');
            $menu.append($item);
        });
    }

    // Initialize all existing particulars (both hybrid and dropdown systems) on page load
    $('.item-group .particulars-body tr').each(function() {
        const $row = $(this);
        // Check if this row uses hybrid input or traditional dropdown
        if ($row.find('.particular-input-group').length > 0) {
            // This is a hybrid input row
            initializeHybridInput($row);
        } else {
            // This is a traditional dropdown row
            initializeProductionRow($row);
        }
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
