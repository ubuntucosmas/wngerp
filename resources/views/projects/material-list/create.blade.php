@extends('layouts.master')
@section('title', 'Create Project Material-List')

@section('content')
<div class="container-fluid d-flex justify-content-center align-items-start py-4" style="min-height: 100vh; background: #f4f6fa;">
    <div class="material-list-card card shadow-sm w-100" style="max-width: 1100px;">
        <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom-0" style="border-radius: 16px 16px 0 0;">
            <h2 class="mb-0 fs-5 fw-bold">Create Material List</h2>
            <a href="{{ (isset($enquiry) && is_object($enquiry) && isset($enquiry->id)) ? route('enquiries.material-list.index', $enquiry) : route('projects.material-list.index', $project) }}"
               class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1"
               data-bs-toggle="tooltip" data-bs-placement="bottom" title="Back to Material Lists" aria-label="Back to Material Lists">
                <i class="bi bi-arrow-left"></i> <span class="d-none d-md-inline">Back to Material Lists</span>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="row g-0">
                <div class="col-md-3 sidebar-col">
                    <nav class="sidebar-nav sticky-top card h-100 shadow-sm mb-0" style="top: 80px; z-index: 1020; border-radius: 12px 0 0 12px;">
                        <ul class="nav flex-column py-3 px-2">
                            <li class="nav-item"><a href="#basic-details" class="nav-link active" data-bs-toggle="tooltip" title="Go to Basic Details" aria-label="Go to Basic Details">Basic Details</a></li>
                            <li class="nav-item"><a href="#materials-production" class="nav-link" data-bs-toggle="tooltip" title="Go to Materials - Production" aria-label="Go to Materials - Production">Materials - Production</a></li>
                            <li class="nav-item"><a href="#materials-hire" class="nav-link" data-bs-toggle="tooltip" title="Go to Items for Hire" aria-label="Go to Items for Hire">Items for Hire</a></li>
                            <li class="nav-item"><a href="#labour-workshop" class="nav-link" data-bs-toggle="tooltip" title="Go to Workshop Labour" aria-label="Go to Workshop Labour">Workshop Labour</a></li>
                            <li class="nav-item"><a href="#labour-site" class="nav-link" data-bs-toggle="tooltip" title="Go to Site Labour" aria-label="Go to Site Labour">Site Labour</a></li>
                            <li class="nav-item"><a href="#labour-setdown" class="nav-link" data-bs-toggle="tooltip" title="Go to Set Down Labour" aria-label="Go to Set Down Labour">Set Down Labour</a></li>
                            <li class="nav-item"><a href="#logistics" class="nav-link" data-bs-toggle="tooltip" title="Go to Logistics" aria-label="Go to Logistics">Logistics</a></li>
                            <li class="nav-item"><a href="#labour-outsourced" class="nav-link" data-bs-toggle="tooltip" title="Go to Outsourced" aria-label="Go to Outsourced">Outsourced</a></li>
                            <li class="nav-item"><a href="#approval" class="nav-link" data-bs-toggle="tooltip" title="Go to Approval" aria-label="Go to Approval">Approval</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-md-9 form-content-col">
                    <form action="{{ isset($enquiry) ? route('enquiries.material-list.store', $enquiry) : route('projects.material-list.store', $project) }}" method="POST" class="p-3 position-relative" id="materialListForm">
                        @csrf
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
                                        <!-- Basic Details Content -->
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="project_name">{{ isset($enquiry) ? 'Enquiry' : 'Project' }} Name</label>
                                                <input type="text" class="form-control" name="project_name" value="{{ isset($enquiry) ? $enquiry->project_name : (isset($project) && $project->exists ? $project->name : '') }}" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="client">Client</label>
                                                <input type="text" class="form-control" name="client" value="{{ isset($enquiry) ? $enquiry->client_name : (isset($project) && $project->exists ? $project->client_name : '') }}" readonly>
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
                                        <!-- Materials - Production Content -->
                                        <div id="items-wrapper">
                                            @if(old('production_items'))
                                                @foreach(old('production_items') as $itemIndex => $item)
                                                    {{-- Render item group for each old input item --}}
                                                    <div class="item-group border rounded p-3 mb-3" data-item-index="{{ $itemIndex }}">
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Select Template</label>
                                                                <select class="form-select template-select" name="production_items[{{ $itemIndex }}][template_id]" data-item-index="{{ $itemIndex }}">
                                                                    <option value="">-- Choose a template or enter manually --</option>
                                                                </select>
                                                                <small class="form-text text-muted">Select a template to auto-fill particulars, or leave empty to enter manually</small>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label fw-semibold">Item Name</label>
                                                                <input type="text" name="production_items[{{ $itemIndex }}][item_name]" class="form-control item-name" value="{{ $item['item_name'] ?? '' }}" required>
                                                            </div>
                                                        </div>
                                                        <table class="table table-bordered">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th>Particular</th>
                                                                    <th>Unit Of Measure</th>
                                                                    <th>Quantity</th>
                                                                    <th>Unit Price</th>
                                                                    <th>Comment</th>
                                                                    <th width="80">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="particulars-body">
                                                                @if(isset($item['particulars']))
                                                                    @foreach($item['particulars'] as $particularIndex => $particular)
                                                                        <tr>
                                                                            <td><input type="text" name="production_items[{{ $itemIndex }}][particulars][{{ $particularIndex }}][particular]" class="form-control" value="{{ $particular['particular'] ?? '' }}" required></td>
                                                                            <td><input type="text" name="production_items[{{ $itemIndex }}][particulars][{{ $particularIndex }}][unit]" class="form-control unit-field" value="{{ $particular['unit'] ?? '' }}"></td>
                                                                            <td><input type="number" step="0.01" name="production_items[{{ $itemIndex }}][particulars][{{ $particularIndex }}][quantity]" class="form-control" value="{{ $particular['quantity'] ?? '' }}" required></td>
                                                                            <td><input type="number" step="0.01" name="production_items[{{ $itemIndex }}][particulars][{{ $particularIndex }}][unit_price]" class="form-control" value="{{ $particular['unit_price'] ?? '' }}" required></td>
                                                                            <td><input type="text" name="production_items[{{ $itemIndex }}][particulars][{{ $particularIndex }}][comment]" class="form-control" value="{{ $particular['comment'] ?? '' }}"></td>
                                                                            <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="bi bi-trash"></i></button></td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endif
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
                                            @endif
                                            {{-- No default item group unless old input exists --}}
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-primary btn-sm btn-add-item" id="addItemGroup">
                                                <i class="bi bi-plus-circle me-1"></i>Add Item
                                            </button>
                                            <button type="button" class="btn btn-outline-primary btn-sm" id="addFromTemplate" data-bs-toggle="modal" data-bs-target="#templateModal">
                                                <i class="bi bi-file-earmark-plus me-1"></i>Add from Template
                                            </button>
                                        </div>
                                    </div>
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
                                                    <th>Unit Price</th>
                                                    <th>Comment</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="materialsHireBody">
                                                <!-- Rows will be added dynamically -->
                                            </tbody>
                                        </table>
                                        <button type="button" class="btn btn-success btn-sm btn-add-item" id="addHireRow">
                                            <i class="bi bi-plus-circle"></i> Add Row
                                        </button>
                                    </div>
                                </div>
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

                            @foreach($subCategories as $category => $roles)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingLabour{{ Str::slug($category) }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLabour{{ Str::slug($category) }}" aria-expanded="false" aria-controls="collapseLabour{{ Str::slug($category) }}">
                                            <i class="bi bi-people me-2"></i>{{ $category }}
                                        </button>
                                    </h2>
                                    <div id="collapseLabour{{ Str::slug($category) }}" class="accordion-collapse collapse" aria-labelledby="headingLabour{{ Str::slug($category) }}" data-bs-parent="#materialListAccordion">
                                        <div class="accordion-body">
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
                                                    @foreach($roles as $index => $role)
                                                        <tr>
                                                            <td><input type="text" name="items[{{ $category }}][{{ $index }}][particular]" class="form-control" value="{{ $role }}" readonly></td>
                                                            <td><input type="text" name="items[{{ $category }}][{{ $index }}][unit]" class="form-control" value="{{ old('items.'.$category.'.'.$index.'.unit', $category === 'Logistics' ? 'Trips' : 'pax') }}"></td>
                                                            <td><input type="number" step="0.01" name="items[{{ $category }}][{{ $index }}][quantity]" class="form-control"></td>
                                                            <td><input type="number" step="0.01" name="items[{{ $category }}][{{ $index }}][unit_price]" class="form-control"></td>
                                                            <td><input type="text" name="items[{{ $category }}][{{ $index }}][comment]" class="form-control"></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

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
                                            <input type="text" name="approved_by" value="{{ auth()->user()->name }}" class="form-control mb-2 required" required readonly>

                                            <label for="approved_departments">Departments</label>
                                            <input type="text" name="approved_departments" value="{{ auth()->user()->department }}" class="form-control" placeholder="Production, Finance" required readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Floating Summary Bar -->
                        <div id="floatingSummaryBar" class="floating-summary-bar card shadow-sm" aria-live="polite">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Total Items:</strong> <span id="totalItems">0</span>
                                    <span class="mx-3">|</span>
                                    <strong>Grand Total:</strong> <span id="grandTotal">KSh 0.00</span>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh Totals" aria-label="Refresh Totals">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Sticky Action Bar -->
                        <div class="sticky-action-bar d-flex justify-content-end gap-2 mt-4 pt-3 border-top bg-white" style="position:sticky; bottom:0; z-index:1100; border-radius:0 0 12px 12px;">
                            <a href="{{ (isset($enquiry) && is_object($enquiry) && isset($enquiry->id)) ? route('enquiries.material-list.index', $enquiry) : route('projects.material-list.index', $project) }}"
                               class="btn btn-outline-secondary d-flex align-items-center gap-1"
                               data-bs-toggle="tooltip" title="Cancel and go back" aria-label="Cancel and go back">
                                <i class="bi bi-x-circle"></i> <span class="d-none d-md-inline">Cancel</span>
                            </a>
                            <button type="reset" class="btn btn-outline-secondary d-flex align-items-center gap-1" data-bs-toggle="tooltip" title="Reset Form" aria-label="Reset Form">
                                <i class="bi bi-arrow-counterclockwise"></i> <span class="d-none d-md-inline">Reset</span>
                            </button>
                            <button type="submit" class="btn btn-primary d-flex align-items-center gap-1" id="submitBtn" data-bs-toggle="tooltip" title="Save Material-List" aria-label="Save Material-List">
                                <span class="spinner-border spinner-border-sm me-1 d-none" id="submitSpinner" role="status" aria-hidden="true"></span>
                                <i class="bi bi-save"></i> <span class="d-none d-md-inline">Save Material-List</span>
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
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Global variables
    let itemIndex = {{ isset($materialList->production_items) && is_array($materialList->production_items) ? count($materialList->production_items) : 0 }} > 0 ? {{ isset($materialList->production_items) && is_array($materialList->production_items) ? count($materialList->production_items) : 1 }} : 1;
    let hireIndex = {{ isset($materialList->materials_hire) && is_array($materialList->materials_hire) ? count($materialList->materials_hire) : 0 }} > 0 ? {{ isset($materialList->materials_hire) && is_array($materialList->materials_hire) ? count($materialList->materials_hire) : 1 }} : 1;
    let particularCounters = {};
    let inventoryItems = [];
    let templates = [];
    let itemGroupToRemove = null;

    // Function to fetch inventory items
    function fetchInventoryItems() {
        return new Promise((resolve, reject) => {
            if (inventoryItems.length > 0) {
                resolve(inventoryItems);
                return;
            }
            $.ajax({
                url: '{{ route("api.inventory.items") }}',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    inventoryItems = data;
                    resolve(data);
                },
                error: function(xhr, status, error) {
                    console.error('Error loading inventory items:', error);
                    reject(error);
                }
            });
        });
    }

    // Function to fetch templates for dropdown
    function fetchTemplates() {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '{{ url("/templates/templates-all") }}',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    templates = data.data || [];
                    resolve(templates);
                },
                error: function(xhr, status, error) {
                    console.error('Error loading templates:', error);
                    reject(error);
                }
            });
        });
    }

    // Function to populate template dropdowns
    function populateTemplateDropdowns() {
        $('.template-select').each(function() {
            const $select = $(this);
            if (!$select.data('templates-loaded')) {
                $select.empty();
                $select.append('<option value="">-- Choose a template or enter manually --</option>');
                templates.forEach(template => {
                    const option = $('<option>', {
                        value: template.id,
                        text: `${template.name} (${template.category ? template.category.name : 'No Category'})`,
                        'data-template': JSON.stringify(template)
                    });
                    $select.append(option);
                });
                $select.data('templates-loaded', true);
            }
        });
    }

    // Function to handle template selection
    function handleTemplateSelection($select) {
        const selectedValue = $select.val();
        const $itemGroup = $select.closest('.item-group');
        const $itemName = $itemGroup.find('.item-name');
        const $particularsBody = $itemGroup.find('.particulars-body');
        if (selectedValue) {
            const template = templates.find(t => t.id == selectedValue);
            if (template) {
                $itemName.val(template.name);
                $particularsBody.empty();
                if (template.particulars && template.particulars.length > 0) {
                    template.particulars.forEach((particular, index) => {
                        const particularRow = `
                            <tr>
                                <td><input type="text" name="production_items[${$itemGroup.data('item-index')}][particulars][${index}][particular]" class="form-control" value="${particular.particular}" required></td>
                                <td><input type="text" name="production_items[${$itemGroup.data('item-index')}][particulars][${index}][unit]" class="form-control unit-field" value="${particular.unit || ''}"></td>
                                <td><input type="number" step="0.01" name="production_items[${$itemGroup.data('item-index')}][particulars][${index}][quantity]" class="form-control" value="${particular.default_quantity}" required></td>
                                <td><input type="number" step="0.01" name="production_items[${$itemGroup.data('item-index')}][particulars][${index}][unit_price]" class="form-control" value="${particular.unit_price || '0.00'}" required></td>
                                <td><input type="text" name="production_items[${$itemGroup.data('item-index')}][particulars][${index}][comment]" class="form-control" value="${particular.comment || ''}"></td>
                                <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="bi bi-trash"></i></button></td>
                            </tr>
                        `;
                        $particularsBody.append(particularRow);
                    });
                    particularCounters[$itemGroup.data('item-index')] = template.particulars.length;
                }
                showNotification('Template loaded successfully!', 'success');
                updateTotals();
            }
        } else {
            $itemName.val('');
            $particularsBody.empty();
            particularCounters[$itemGroup.data('item-index')] = 0;
            updateTotals();
        }
    }

    // Function to show notifications
    function showNotification(message, type = 'info') {
        const alertClass = type === 'success' ? 'alert-success' : (type === 'error' ? 'alert-danger' : 'alert-info');
        const notification = `
            <div class="alert ${alertClass} alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert" style="z-index: 2000; min-width: 250px;">
                <i class="bi bi-check-circle me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        $('body').append(notification);
        setTimeout(() => {
            $('.alert').fadeOut();
        }, 3000);
    }

    // Function to find unit of measure for an item
    function findUnitOfMeasure(itemName) {
        if (!itemName) return '';
        const item = inventoryItems.find(i => i.name === itemName);
        return item ? (item.unit_of_measure || '') : '';
    }

    // Populate a single dropdown with cached items
    function populateDropdown($select, selectedValue = '') {
        const currentValue = selectedValue || $select.val();
        const $row = $select.closest('tr');
        $select.empty();
        $select.append($('<option>', {
            value: '',
            text: '-- Select an item --',
            disabled: true,
            selected: !currentValue
        }));
        inventoryItems.forEach(item => {
            const option = $('<option>', {
                value: item.name,
                text: item.name,
                'data-unit': item.unit_of_measure || ''
            });
            if (currentValue === item.name) {
                option.prop('selected', true);
                $row.find('.unit-field').val(item.unit_of_measure || '');
            }
            $select.append(option);
        });
        $select.prop('disabled', false);
        if (currentValue && !$select.val()) {
            const unit = findUnitOfMeasure(currentValue);
            if (unit) {
                $row.find('.unit-field').val(unit);
            }
        }
    }

    // Initialize all dropdowns on page load
    async function initializeDropdowns() {
        try {
            await fetchInventoryItems();
            $('.inventory-dropdown').each(function() {
                const $select = $(this);
                if (!$select.data('initialized')) {
                    const currentValue = $select.val();
                    if (currentValue) {
                        const unit = findUnitOfMeasure(currentValue);
                        if (unit) {
                            $select.closest('tr').find('.unit-field').val(unit);
                        }
                    }
                    populateDropdown($select);
                    $select.data('initialized', true);
                }
            });
        } catch (error) {
            console.error('Failed to initialize dropdowns:', error);
        }
    }

    // Function to initialize a production row
    function initializeProductionRow($row) {
        const $select = $row.find('.inventory-dropdown');
        const $unitField = $row.find('.unit-field');
        $unitField.prop('readonly', true);
        if (!$select.data('initialized')) {
            populateDropdown($select);
            $select.data('initialized', true);
        }
    }

    // Function to initialize a new hire row
    function initializeHireRow($row) {
        const $select = $row.find('.inventory-dropdown');
        const $unitField = $row.find('.unit-field');
        $unitField.prop('readonly', true);
        if (!$select.data('initialized')) {
            populateDropdown($select);
            $select.data('initialized', true);
        }
    }

    // Add template to material list (from modal)
    function addTemplateToMaterialList(templateId) {
        $.ajax({
            url: '{{ route("templates.templates.show", ":templateId") }}'.replace(':templateId', templateId),
            type: 'GET',
            success: function(template) {
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
                                <input type="text" name="production_items[${itemIndex}][item_name]" class="form-control item-name" value="${template.name}" required>
                            </div>
                        </div>
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Particular</th>
                                    <th>Unit Of Measure</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Comment</th>
                                    <th width="80">Action</th>
                                </tr>
                            </thead>
                            <tbody class="particulars-body">
                                ${template.particulars.map((particular, index) => `
                                    <tr>
                                        <td><input type="text" name="production_items[${itemIndex}][particulars][${index}][particular]" class="form-control" value="${particular.particular}" required></td>
                                        <td><input type="text" name="production_items[${itemIndex}][particulars][${index}][unit]" class="form-control" value="${particular.unit || ''}"></td>
                                        <td><input type="number" step="0.01" name="production_items[${itemIndex}][particulars][${index}][quantity]" class="form-control" value="${particular.default_quantity}" required></td>
                                        <td><input type="number" step="0.01" name="production_items[${itemIndex}][particulars][${index}][unit_price]" class="form-control" value="${particular.unit_price || '0.00'}" required></td>
                                        <td><input type="text" name="production_items[${itemIndex}][particulars][${index}][comment]" class="form-control" value="${particular.comment || ''}"></td>
                                        <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="bi bi-trash"></i></button></td>
                                    </tr>
                                `).join('')}
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
                `;
                const $newGroup = $(newGroup);
                $('#items-wrapper').append($newGroup);
                populateTemplateDropdowns();
                $newGroup.find('.template-select').val(template.id);
                particularCounters[itemIndex] = template.particulars.length;
                itemIndex++;
                $('#templateModal').modal('hide');
                showNotification('Template added successfully!', 'success');
                updateTotals();
            },
            error: function() {
                showNotification('Failed to load template details', 'error');
            }
        });
    }

    // Update totals in the floating summary bar
    function updateTotals() {
        let totalItems = 0;
        let grandTotal = 0;
        // Count particulars in all item groups
        $('.particulars-body').each(function() {
            $(this).find('tr').each(function() {
                totalItems++;
                const qty = parseFloat($(this).find('input[name*="[quantity]"]').val()) || 0;
                const price = parseFloat($(this).find('input[name*="[unit_price]"]').val()) || 0;
                grandTotal += qty * price;
            });
        });
        // Count hire items
        $('#materialsHireBody tr').each(function() {
            totalItems++;
            const qty = parseFloat($(this).find('input[name*="[quantity]"]').val()) || 0;
            const price = parseFloat($(this).find('input[name*="[unit_price]"]').val()) || 0;
            grandTotal += qty * price;
        });
        $('#totalItems').text(totalItems);
        $('#grandTotal').text('KSh ' + grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
    }

    // Document ready
    $(function () {
        // Enable tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();

        // Confirmation modal for remove item group
        $(document).on('click', '.remove-item-group', function(e) {
            e.preventDefault();
            itemGroupToRemove = $(this).closest('.item-group');
            $('#confirmRemoveModal').modal('show');
        });
        $('#confirmRemoveBtn').on('click', function() {
            if (itemGroupToRemove) {
                itemGroupToRemove.remove();
                itemGroupToRemove = null;
                $('#confirmRemoveModal').modal('hide');
                updateTotals();
            }
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
                        <thead class="table-light">
                            <tr>
                                <th>Particular</th>
                                <th>Unit Of Measure</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Comment</th>
                                <th width="80">Action</th>
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
            populateTemplateDropdowns();
            $newGroup.find('.add-particular').trigger('click');
            itemIndex++;
            updateTotals();
        });

        // Handle template selection
        $(document).on('change', '.template-select', function() {
            handleTemplateSelection($(this));
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
                    <td><input type="number" step="0.01" name="production_items[${itemIndex}][particulars][${particularIndex}][unit_price]" class="form-control" required></td>
                    <td><input type="text" name="production_items[${itemIndex}][particulars][${particularIndex}][comment]" class="form-control"></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="bi bi-trash"></i></button></td>
                </tr>`;
            const $newRow = $(newRow);
            $itemGroup.find('.particulars-body').append($newRow);
            particularCounters[itemIndex] = particularIndex + 1;
            initializeProductionRow($newRow);
            updateTotals();
        });

        // Add new hire row
        $('#addHireRow').on('click', function() {
            const newRow = `
                <tr>
                    <td>
                        <select name="materials_hire[${hireIndex}][particular]" class="form-select inventory-dropdown" required>
                            <option value="" selected disabled>-- Loading items --</option>
                        </select>
                    </td>
                    <td><input type="text" name="materials_hire[${hireIndex}][unit]" class="form-control unit-field" readonly></td>
                    <td><input type="number" step="0.01" name="materials_hire[${hireIndex}][quantity]" class="form-control"></td>
                    <td><input type="number" step="0.01" name="materials_hire[${hireIndex}][unit_price]" class="form-control"></td>
                    <td><input type="text" name="materials_hire[${hireIndex}][comment]" class="form-control"></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                </tr>`;
            const $newRow = $(newRow);
            $('#materialsHireBody').append($newRow);
            initializeHireRow($newRow);
            hireIndex++;
            updateTotals();
        });

        // Remove row
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
            updateTotals();
        });

        // Remove item group (handled by confirmation modal above)

        // Re-index items after removal
        function reindexItems() {
            const newCounters = {};
            $('.item-group').each(function(newIndex) {
                const oldIndex = $('.item-group').index(this);
                newCounters[newIndex] = particularCounters[oldIndex] || 1;
                $(this).find('input[name^="production_items["], select[name^="production_items["]').each(function() {
                    const newName = $(this).attr('name').replace(
                        /production_items\[\d+\]/,
                        `production_items[${newIndex}]`
                    );
                    $(this).attr('name', newName);
                });
                $(this).attr('data-item-index', newIndex);
                $(this).find('.template-select').attr('data-item-index', newIndex);
            });
            particularCounters = newCounters;
        }

        // Handle dropdown change
        $(document).on('change', '.inventory-dropdown', function() {
            const $this = $(this);
            const selectedOption = $this.find('option:selected');
            const unit = selectedOption.data('unit') || findUnitOfMeasure($this.val());
            const $unitField = $this.closest('tr').find('.unit-field').first();
            $unitField.val(unit || '');
            updateTotals();
        });

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
                showNotification('Please fill in all required fields.', 'error');
            }
        });

        // Initialize everything
        async function initialize() {
            try {
                await Promise.all([
                    fetchInventoryItems(),
                    fetchTemplates()
                ]);
                initializeDropdowns();
                populateTemplateDropdowns();
                updateTotals();
            } catch (error) {
                console.error('Failed to initialize:', error);
            }
        }
        initialize();

        // Template modal functionality (search/filter)
        $('#templateSearch').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('.template-card').each(function() {
                const templateName = $(this).data('name');
                if (templateName.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
        // Add template from modal
        $(document).on('click', '.add-template-btn', function() {
            const templateId = $(this).data('template-id');
            const template = templates.find(t => t.id == templateId);
            if (template) {
                addTemplateToMaterialList(template.id);
            }
        });
        // Refresh totals button
        $(document).on('click', '[aria-label="Refresh Totals"]', function() {
            updateTotals();
        });
        // Accordion: scroll to section on sidebar click
        $('.sidebar-nav .nav-link').on('click', function(e) {
            e.preventDefault();
            const target = $(this).attr('href');
            if ($(target).length) {
                $('html, body').animate({ scrollTop: $(target).offset().top - 80 }, 400);
            }
            $('.sidebar-nav .nav-link').removeClass('active');
            $(this).addClass('active');
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
    });
</script>
@endpush
