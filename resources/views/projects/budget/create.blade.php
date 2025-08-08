@extends('layouts.master')

@section('title', 'Create {{ isset($enquiry) ? "Enquiry" : "Project" }} Budget')

@section('content')
@hasanyrole('finance|po|pm|super-admin')
<div class="container-fluid d-flex justify-content-center align-items-start py-4" style="min-height: 100vh; background: #f4f6fa;">
    <div class="material-list-card card shadow-sm w-100" style="max-width: 1600px;">
        <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom-0" style="border-radius: 16px 16px 0 0;">
            <h2 class="mb-0 fs-5 fw-bold">Create {{ isset($enquiry) ? 'Enquiry' : 'Project' }} Budget</h2>
            <a href="{{ (isset($enquiry) && is_object($enquiry) && isset($enquiry->id)) ? route('enquiries.files', $enquiry) : (isset($project) && is_object($project) && isset($project->id) ? route('projects.files.index', $project->id) : '#') }}"
               class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1"
               data-bs-toggle="tooltip" data-bs-placement="bottom" title="Back to Files" aria-label="Back to Files">
                <i class="bi bi-arrow-left"></i> <span class="d-none d-md-inline">Back to Files</span>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="row g-0">
                <div class="col-md-1 sidebar-col">
                    <nav class="sidebar-nav sticky-top card h-100 shadow-sm mb-0" style="top: 80px; z-index: 1020; border-radius: 12px 0 0 12px;">
                        <ul class="nav flex-column py-3 px-2">
                            <li class="nav-item"><a href="#basic-details" class="nav-link active" data-bs-toggle="tooltip" title="Go to Basic Details" aria-label="Go to Basic Details">Basic Details</a></li>
                            <li class="nav-item"><a href="#materials-production" class="nav-link" data-bs-toggle="tooltip" title="Go to Materials - Production" aria-label="Go to Materials - Production">Materials - Production</a></li>
                            <li class="nav-item"><a href="#materials-hire" class="nav-link" data-bs-toggle="tooltip" title="Go to Materials for Hire" aria-label="Go to Materials for Hire">Items for Hire</a></li>
                            <li class="nav-item"><a href="#workshop-labour" class="nav-link" data-bs-toggle="tooltip" title="Go to Workshop Labour" aria-label="Go to Workshop Labour">Workshop Labour</a></li>
                            <li class="nav-item"><a href="#site" class="nav-link" data-bs-toggle="tooltip" title="Go to Site" aria-label="Go to Site">Site</a></li>
                            <li class="nav-item"><a href="#set-down" class="nav-link" data-bs-toggle="tooltip" title="Go to Set Down" aria-label="Go to Set Down">Set Down</a></li>
                            <li class="nav-item"><a href="#logistics" class="nav-link" data-bs-toggle="tooltip" title="Go to Logistics" aria-label="Go to Logistics">Logistics</a></li>
                            <li class="nav-item"><a href="#outsourced" class="nav-link" data-bs-toggle="tooltip" title="Go to Outsourced" aria-label="Go to Outsourced">Outsourced</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-md-11 form-content-col">
                    <form action="{{ isset($enquiry) ? route('enquiries.budget.store', $enquiry) : (isset($project) ? route('budget.store', $project) : '#') }}" method="POST" class="p-3 position-relative" id="budgetForm">
                        @csrf
                        @if(isset($materialList))
                            <input type="hidden" name="material_list_id" value="{{ $materialList->id }}">
                        @endif
                        <div class="accordion compact-accordion" id="budgetAccordion">
                            <!-- Basic Details -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingBasicDetails">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBasicDetails" aria-expanded="true" aria-controls="collapseBasicDetails">
                                        <i class="fas fa-info-circle me-2"></i>Basic Details
                                    </button>
                                </h2>
                                <div id="collapseBasicDetails" class="accordion-collapse collapse show" aria-labelledby="headingBasicDetails" data-bs-parent="#budgetAccordion">
                                    <div class="accordion-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="project_name">{{ isset($enquiry) ? 'Enquiry' : 'Project' }} Name</label>
                                                <input type="text" class="form-control" name="project_name" value="{{ isset($enquiry) ? $enquiry->project_name : (isset($project) ? $project->name : 'Unknown') }}" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="client">Client</label>
                                                <input type="text" class="form-control" name="client" value="{{ isset($enquiry) ? $enquiry->client_name : (isset($project) ? $project->client_name : 'N/A') }}" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <label for="start_date">Start Date</label>
                                                <input type="date" class="form-control" name="start_date" value="{{
                                                    old('start_date', isset($enquiry)
                                                        ? ($enquiry->date_received ?? '')
                                                        : (isset($project) && $project->start_date ? $project->start_date->format('Y-m-d') : '')
                                                    )
                                                }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="end_date">End Date</label>
                                                <input type="date" class="form-control" name="end_date" value="{{
                                                    old('end_date', isset($enquiry)
                                                        ? ($enquiry->expected_delivery_date ?? '')
                                                        : (isset($project) && $project->end_date ? $project->end_date->format('Y-m-d') : '')
                                                    )
                                                }}" required>
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
                                <div id="collapseMaterialsProduction" class="accordion-collapse collapse" aria-labelledby="headingMaterialsProduction" data-bs-parent="#budgetAccordion">
                                    <div class="accordion-body">
                                        <div id="budget-items-wrapper">
                                            @php $prodGroups = $grouped['Materials - Production'] ?? collect();
                                            $prodByItem = $prodGroups->groupBy('item_name');
                                            $prodItemIdx = 0;
                                            @endphp
                                            @foreach($prodByItem as $itemName => $particulars)
                                                <div class="item-group border rounded p-3 mb-3" data-item-idx="{{ $prodItemIdx }}">
                                                    <div class="mb-2">
                                                        <label>Item Name</label>
                                                        <input type="text" name="production_items[{{ $prodItemIdx }}][item_name]" class="form-control" value="{{ $itemName }}" placeholder="e.g. Stage Truss" required>
                                                    </div>
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Particular</th>
                                                                <th>Unit Of Measure</th>
                                                                <th>Quantity</th>
                                                                <th>Unit Price</th>
                                                                <th>Budgeted Cost</th>
                                                                <th>Comment</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="particulars-body">
                                                            @foreach($particulars as $pIdx => $particular)
                                                                <tr>
                                                                    <td><input type="text" name="production_items[{{ $prodItemIdx }}][particulars][{{ $pIdx }}][particular]" class="form-control" value="{{ $particular['particular'] }}"></td>
                                                                    <td><input type="text" name="production_items[{{ $prodItemIdx }}][particulars][{{ $pIdx }}][unit]" class="form-control" value="{{ $particular['unit'] }}"></td>
                                                                    <td><input type="number" step="0.01" name="production_items[{{ $prodItemIdx }}][particulars][{{ $pIdx }}][quantity]" class="form-control" value="{{ $particular['quantity'] }}"></td>
                                                                    <td><input type="number" step="0.01" name="production_items[{{ $prodItemIdx }}][particulars][{{ $pIdx }}][unit_price]" class="form-control" value="{{ $particular['unit_price'] ?? '' }}"></td>
                                                                    <td><input type="number" step="0.01" name="production_items[{{ $prodItemIdx }}][particulars][{{ $pIdx }}][budgeted_cost]" class="form-control" value="{{ $particular['budgeted_cost'] ?? '' }}"></td>
                                                                    <td><input type="text" name="production_items[{{ $prodItemIdx }}][particulars][{{ $pIdx }}][comment]" class="form-control" value="{{ $particular['comment'] }}"></td>
                                                                    <td><button type="button" class="btn btn-danger btn-sm remove-particular">Remove</button></td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    <button type="button" class="btn btn-success btn-sm add-particular">+ Add Particular</button>
                                                </div>
                                                @php $prodItemIdx++; @endphp
                                            @endforeach
                                            <button type="button" class="btn btn-primary btn-sm btn-add-item" id="addBudgetItemGroup">
                                                <i class="bi bi-plus-circle"></i> Add Item
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @php
                                $otherCategories = ['Items for Hire', 'Workshop labour', 'Site', 'Set down', 'Logistics', 'Outsourced'];
                            @endphp
                            @foreach($otherCategories as $cat)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ str_replace(' ', '', $cat) }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ str_replace(' ', '', $cat) }}" aria-expanded="false" aria-controls="collapse{{ str_replace(' ', '', $cat) }}">
                                            <i class="bi bi-tools me-2"></i>{{ $cat }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ str_replace(' ', '', $cat) }}" class="accordion-collapse collapse" aria-labelledby="heading{{ str_replace(' ', '', $cat) }}" data-bs-parent="#budgetAccordion">
                                        <div class="accordion-body">
                                            <table class="table table-bordered" id="table_{{ str_replace(' ', '_', strtolower($cat)) }}">
                                                <thead>
                                                    <tr>
                                                        <th>Particular</th>
                                                        <th>Unit Of Measure</th>
                                                        <th>Quantity</th>
                                                        <th>Unit Price</th>
                                                        <th>Budgeted Cost</th>
                                                        <th>Comment</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $rows = $grouped[$cat] ?? collect(); @endphp
                                                    @foreach($rows as $i => $row)
                                                        <tr>
                                                            <td><input type="text" name="items[{{ $cat }}][{{ $i }}][particular]" class="form-control" value="{{ $row['particular'] }}"></td>
                                                            <td><input type="text" name="items[{{ $cat }}][{{ $i }}][unit]" class="form-control" value="{{ $row['unit'] }}"></td>
                                                            <td><input type="number" step="0.01" name="items[{{ $cat }}][{{ $i }}][quantity]" class="form-control" value="{{ $row['quantity'] }}"></td>
                                                            <td><input type="number" step="0.01" name="items[{{ $cat }}][{{ $i }}][unit_price]" class="form-control" value="{{ $row['unit_price'] ?? '' }}"></td>
                                                            <td><input type="number" step="0.01" name="items[{{ $cat }}][{{ $i }}][budgeted_cost]" class="form-control" value="{{ $row['budgeted_cost'] ?? '' }}"></td>
                                                            <td><input type="text" name="items[{{ $cat }}][{{ $i }}][comment]" class="form-control" value="{{ $row['comment'] }}"></td>
                                                            <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <button type="button" class="btn btn-success btn-sm add-row" data-category="{{ $cat }}">+ Add Row</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingApproval">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseApproval" aria-expanded="false" aria-controls="collapseApproval">
                                        <i class="bi bi-check-circle me-2"></i>Approval
                                    </button>
                                </h2>
                                <div id="collapseApproval" class="accordion-collapse collapse" aria-labelledby="headingApproval" data-bs-parent="#budgetAccordion">
                                    <div class="accordion-body">
                                        <div class="mb-4">
                                            <label for="approved_by">Prepared By:</label>
                                            <input type="text" name="approved_by" value="{{ auth()->user()->name }}" class="form-control mb-2 required" required>
                                            <label for="approved_departments">Department</label>
                                            <input type="text" name="approved_departments" value="{{ old('approved_departments', auth()->user()->department ?? '') }}" class="form-control" placeholder="Production, Finance" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Floating Summary Bar -->
                        <div id="floatingSummaryBar" class="floating-summary-bar card shadow-sm" aria-live="polite">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Total Budget:</strong> <span class="text-primary" id="grandTotal">0.00</span>
                                </div>
                            </div>
                        </div>
                        <!-- Sticky Action Bar -->
                        <div class="sticky-action-bar d-flex justify-content-end gap-2 mt-4 pt-3 border-top bg-white" style="position:sticky; bottom:0; z-index:1100; border-radius:0 0 12px 12px;">
                            <a href="{{ (isset($enquiry) && is_object($enquiry) && isset($enquiry->id)) ? route('enquiries.files', $enquiry) : (isset($project) && is_object($project) && isset($project->id) ? route('projects.files.index', $project->id) : '#') }}"
                               class="btn btn-outline-secondary d-flex align-items-center gap-1"
                               data-bs-toggle="tooltip" title="Cancel and go back" aria-label="Cancel and go back">
                                <i class="bi bi-x-circle"></i> <span class="d-none d-md-inline">Cancel</span>
                            </a>
                            <button type="reset" class="btn btn-outline-secondary d-flex align-items-center gap-1" data-bs-toggle="tooltip" title="Reset Form" aria-label="Reset Form">
                                <i class="bi bi-arrow-counterclockwise"></i> <span class="d-none d-md-inline">Reset</span>
                            </button>
                            <button type="submit" class="btn btn-primary d-flex align-items-center gap-1" id="submitBtn" data-bs-toggle="tooltip" title="Save Budget" aria-label="Save Budget">
                                <span class="spinner-border spinner-border-sm me-1 d-none" id="submitSpinner" role="status" aria-hidden="true"></span>
                                <i class="bi bi-save"></i> <span class="d-none d-md-inline">Save Budget</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@else
    <div class="alert alert-danger mt-5">You do not have permission to create a budget.</div>
@endhasanyrole

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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate Budgeted Cost for all editable rows
    function updateBudgetedCost(row) {
        const qty = parseFloat(row.querySelector('[name*="[quantity]"]').value) || 0;
        const unitPrice = parseFloat(row.querySelector('[name*="[unit_price]"]').value) || 0;
        const costInput = row.querySelector('[name*="[budgeted_cost]"]');
        if (costInput) {
            costInput.value = (qty * unitPrice).toFixed(2);
        }
    }

    function updateGrandTotal() {
        let total = 0;
        document.querySelectorAll('input[name*="[budgeted_cost]"]').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('grandTotal').textContent = total.toFixed(2);
    }

    // Attach event listeners to all relevant inputs
    document.querySelectorAll('input[name*="[quantity]"], input[name*="[unit_price]"]').forEach(input => {
        input.addEventListener('input', function() {
            const row = this.closest('tr');
            if (row) {
                updateBudgetedCost(row);
                updateGrandTotal();
            }
        });
    });

    // Add row functionality
    document.querySelectorAll('.add-row').forEach(btn => {
        btn.addEventListener('click', function() {
            const cat = this.getAttribute('data-category');
            const table = document.getElementById('table_' + cat.replace(/ /g, '_').toLowerCase());
            const tbody = table.querySelector('tbody');
            const rowCount = tbody.querySelectorAll('tr').length;
            const row = document.createElement('tr');
            const defaultUnit = cat === 'Logistics' ? 'Trips' : '';
            
            row.innerHTML = `
                <td><input type="text" name="items[${cat}][${rowCount}][particular]" class="form-control"></td>
                <td><input type="text" name="items[${cat}][${rowCount}][unit]" class="form-control" value="${defaultUnit}"></td>
                <td><input type="number" step="0.01" name="items[${cat}][${rowCount}][quantity]" class="form-control"></td>
                <td><input type="number" step="0.01" name="items[${cat}][${rowCount}][unit_price]" class="form-control"></td>
                <td><input type="number" step="0.01" name="items[${cat}][${rowCount}][budgeted_cost]" class="form-control" readonly></td>
                <td><input type="text" name="items[${cat}][${rowCount}][comment]" class="form-control"></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
            `;
            tbody.appendChild(row);

            // Add event listeners to new row
            const quantityInput = row.querySelector('[name*="[quantity]"]');
            const priceInput = row.querySelector('[name*="[unit_price]"]');
            
            quantityInput.addEventListener('input', function() {
                updateBudgetedCost(row);
                updateGrandTotal();
            });
            priceInput.addEventListener('input', function() {
                updateBudgetedCost(row);
                updateGrandTotal();
            });
        });
    });

    // Remove row functionality
    document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-row')) {
            const row = e.target.closest('tr');
            row.remove();
            updateGrandTotal();
            }
    });

    // Initial calculation
    updateGrandTotal();

    // Submit button loading state and feedback
    $('#budgetForm').on('submit', function(e) {
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
