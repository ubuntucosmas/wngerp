@extends('layouts.master')

@section('title', 'Edit {{ isset($enquiry) ? "Enquiry" : "Project" }} Budget')

@section('content')
@hasanyrole('finance|po|pm|super-admin')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    @if(isset($enquiry))
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.index') }}">Enquiries</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.files', $enquiry) }}">{{ $enquiry->project_name }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.files.quotation', $enquiry) }}">Budget & Quotation</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.budget.index', $enquiry) }}">Budgets</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Budget</li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}">{{ $project->name }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('projects.quotation.index', $project) }}">Budget & Quotation</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('budget.index', $project) }}">Budgets</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Budget</li>
                    @endif
                </ol>
            </nav>
            <h2 class="mb-0">Edit Budget</h2>
        </div>
        <a href="{{ (isset($enquiry) && is_object($enquiry) && isset($enquiry->id)) ? route('enquiries.budget.index', $enquiry) : route('budget.index', $project) }}" class="btn btn-primary">
            <i class="bi bi-arrow-left me-2"></i>Back to Budgets
        </a>
    </div>

    @error('start_date')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    @error('end_date')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <form action="{{ isset($enquiry) ? route('enquiries.budget.update', [$enquiry, $budget]) : (isset($project) ? route('budget.update', [$project, $budget]) : '#') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-container">
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="#basic-details" class="active">Basic Details</a></li>
                    <li><a href="#materials-production">Materials - Production</a></li>
                    <li><a href="#materials-hire">Materials for Hire</a></li>
                    <li><a href="#workshop-labour">Workshop Labour</a></li>
                    <li><a href="#site-labour">Site Labour</a></li>
                    <li><a href="#setdown-labour">Set Down Labour</a></li>
                    <li><a href="#logistics">Logistics</a></li>
                    <li><a href="#approval">Approval</a></li>
                </ul>
            </nav>

            <div class="form-content">
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
                                <input type="date" class="form-control" name="start_date" value="{{ old('start_date', $budget->start_date ? $budget->start_date->format('Y-m-d') : '') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="end_date">End Date</label>
                                <input type="date" class="form-control" name="end_date" value="{{ old('end_date', $budget->end_date ? $budget->end_date->format('Y-m-d') : '') }}">
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
                        <div id="budget-items-wrapper">
                            @php $prodItemIdx = 0; @endphp
                            @foreach(($budget->productionItems ?? []) as $item)
                                <div class="item-group border rounded p-3 mb-3" data-item-idx="{{ $prodItemIdx }}">
                                    <div class="mb-2">
                                        <label>Item Name</label>
                                        <input type="text" name="production_items[{{ $prodItemIdx }}][item_name]" class="form-control" value="{{ $item->item_name }}" placeholder="e.g. Stage Truss" required>
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
                                            @foreach($item->particulars as $pIdx => $particular)
                                                <tr>
                                                    <td><input type="text" name="production_items[{{ $prodItemIdx }}][particulars][{{ $pIdx }}][particular]" class="form-control" value="{{ $particular->particular }}"></td>
                                                    <td><input type="text" name="production_items[{{ $prodItemIdx }}][particulars][{{ $pIdx }}][unit]" class="form-control" value="{{ $particular->unit }}"></td>
                                                    <td><input type="number" step="0.01" name="production_items[{{ $prodItemIdx }}][particulars][{{ $pIdx }}][quantity]" class="form-control" value="{{ $particular->quantity }}"></td>
                                                    <td><input type="number" step="0.01" name="production_items[{{ $prodItemIdx }}][particulars][{{ $pIdx }}][unit_price]" class="form-control" value="{{ $particular->unit_price }}"></td>
                                                    <td><input type="number" step="0.01" name="production_items[{{ $prodItemIdx }}][particulars][{{ $pIdx }}][budgeted_cost]" class="form-control" value="{{ $particular->budgeted_cost }}"></td>
                                                    <td><input type="text" name="production_items[{{ $prodItemIdx }}][particulars][{{ $pIdx }}][comment]" class="form-control" value="{{ $particular->comment }}"></td>
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

                @php
                    $otherCategories = ['Materials for Hire', 'Workshop labour', 'Site', 'Set down', 'Logistics'];
                @endphp
                @foreach($otherCategories as $cat)
                    <div id="{{ Str::slug($cat) }}" class="form-section-card section-{{ Str::slug($cat) }}">
                        <div class="form-section-card-header">
                            <h5><i class="bi bi-tools me-2"></i>{{ $cat }}</h5>
                        </div>
                        <div class="form-section-card-body">
                            <table class="table table-bordered" id="table_{{ Str::slug($cat) }}">
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
                                    @php $rows = ($budget->labourItems ?? collect())->where('category', $cat); @endphp
                                    @foreach($rows as $i => $row)
                                        <tr>
                                            <td><input type="text" name="items[{{ $cat }}][{{ $i }}][particular]" class="form-control" value="{{ $row->particular }}"></td>
                                            <td><input type="text" name="items[{{ $cat }}][{{ $i }}][unit]" class="form-control" value="{{ $row->unit }}"></td>
                                            <td><input type="number" step="0.01" name="items[{{ $cat }}][{{ $i }}][quantity]" class="form-control" value="{{ $row->quantity }}"></td>
                                            <td><input type="number" step="0.01" name="items[{{ $cat }}][{{ $i }}][unit_price]" class="form-control" value="{{ $row->unit_price }}"></td>
                                            <td><input type="number" step="0.01" name="items[{{ $cat }}][{{ $i }}][budgeted_cost]" class="form-control" value="{{ $row->budgeted_cost }}"></td>
                                            <td><input type="text" name="items[{{ $cat }}][{{ $i }}][comment]" class="form-control" value="{{ $row->comment }}"></td>
                                            <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-success btn-sm add-row" data-category="{{ $cat }}">+ Add Row</button>
                        </div>
                    </div>
                @endforeach

                <!-- Approval -->
                <div id="approval" class="form-section-card">
                    <div class="form-section-card-header">
                        <h5><i class="bi bi-check-circle me-2"></i>Approval</h5>
                    </div>
                    <div class="form-section-card-body">
                        <div class="mb-4">
                            <label for="approved_by">Prepared By:</label>
                            <input type="text" name="approved_by" value="{{ old('approved_by', $budget->approved_by) }}" class="form-control mb-2 required" required>

                            <label for="approved_departments">Departments (comma-separated)</label>
                            <input type="text" name="approved_departments" value="{{ old('approved_departments', $budget->approved_departments) }}" class="form-control" placeholder="Production, Finance" required>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                    <a href="{{ (isset($enquiry) && is_object($enquiry) && isset($enquiry->id)) ? route('enquiries.budget.index', $enquiry) : route('budget.index', $project) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                    <div>
                        <button type="reset" class="btn btn-outline-secondary me-2">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Budget
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endhasanyrole

@endsection

@push('styles')
<style>
    .form-container {
        display: flex;
        gap: 2rem;
        margin-top: 2rem;
    }

    .sidebar-nav {
        position: sticky;
        top: 20px; /* Adjust as needed */
        height: fit-content;
        width: 250px;
        flex-shrink: 0;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 1.5rem;
    }

    .sidebar-nav ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-nav li {
        margin-bottom: 0.75rem;
    }

    .sidebar-nav a {
        display: block;
        padding: 0.5rem 1rem;
        color: #6E6F71;
        text-decoration: none;
        border-radius: 6px;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .sidebar-nav a:hover {
        background-color: rgba(11, 173, 211, 0.1);
        color: #0BADD3;
    }

    .sidebar-nav a.active {
        background-color: #0BADD3;
        color: white;
        box-shadow: 0 2px 8px rgba(11, 173, 211, 0.2);
    }

    .form-content {
        flex-grow: 1;
    }

    .form-section-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 2.5rem;
        overflow: hidden;
    }

    .form-section-card-header {
        background: linear-gradient(135deg, #0BADD3, #0897c4);
        color: white;
        padding: 1.25rem 1.5rem;
        border-bottom: none;
    }

    .form-section-card-header h5 {
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        font-size: 1.25rem;
    }

    .form-section-card-header h5:before {
        content: '';
        display: inline-block;
        width: 8px;
        height: 20px;
        background: #C8DA30;
        margin-right: 12px;
        border-radius: 4px;
    }

    .form-section-card-body {
        padding: 2rem;
    }

    .section-production {
        border-left: 4px solid #4e73df;
    }
    
    .section-hire {
        border-left: 4px solid #36b9cc;
    }
    
    .section-labor {
        border-left: 4px solid #1cc88a;
    }
    
    .section-header {
        color: #2e59d9;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e3e6f0;
    }
    
    .btn-add-item {
        border-radius: 20px;
        font-weight: 500;
        padding: 0.4rem 1.25rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 992px) {
        .form-container {
            flex-direction: column;
        }
        .sidebar-nav {
            position: static;
            width: 100%;
            margin-bottom: 2rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for sidebar navigation
    document.querySelectorAll('.sidebar-nav a').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });

            // Update active class
            document.querySelectorAll('.sidebar-nav a').forEach(link => link.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Highlight active sidebar link on scroll
    const sections = document.querySelectorAll('.form-section-card');
    const navLinks = document.querySelectorAll('.sidebar-nav a');

    window.addEventListener('scroll', () => {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (pageYOffset >= sectionTop - 100) { // Adjust offset as needed
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href').includes(current)) {
                link.classList.add('active');
            }
        });
    });

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
        document.querySelectorAll('.budget-section tbody tr').forEach(row => {
            total += updateRowCost(row);
        });
        document.getElementById('grandTotal').textContent = total.toFixed(2);

        const invoice = parseFloat(document.getElementById('invoiceAmount')?.value || 0);
        document.getElementById('profitAmount').value = (invoice - total).toFixed(2);
    }

    // Initial load
    updateTotals();

    document.querySelectorAll('.quantity, .unit-price').forEach(input => {
        input.addEventListener('input', updateTotals);
    });

    document.getElementById('invoiceAmount').addEventListener('input', updateTotals);

    document.querySelectorAll('.remove-row').forEach(button => {
        button.addEventListener('click', (e) => {
            const row = e.target.closest('tr');
            row.remove();
            updateTotals();
        });
    });

    // Add Row
    document.querySelectorAll('.add-row-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const section = btn.dataset.section;
            const tbody = document.querySelector(`tbody[data-section="${section}"]`);
            const timestamp = Date.now(); // Unique ID for new row
            
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>
                    <input type="hidden" name="items[${section}][new_${timestamp}][id]" value="">
                    <input type="text" name="items[${section}][new_${timestamp}][particular]" class="form-control" required>
                </td>
                <td><input type="text" name="items[${section}][new_${timestamp}][unit]" class="form-control"></td>
                <td><input type="number" step="0.01" name="items[${section}][new_${timestamp}][quantity]" class="form-control quantity" required></td>
                <td><input type="number" step="0.01" name="items[${section}][new_${timestamp}][unit_price]" class="form-control unit-price" required></td>
                <td><input type="number" step="0.01" name="items[${section}][new_${timestamp}][budgeted_cost]" class="form-control cost" readonly></td>
                <td><input type="text" name="items[${section}][new_${timestamp}][comment]" class="form-control"></td>
                <td><button type="button" class="btn btn-sm btn-outline-danger remove-row">×</button></td>
            `;
            tbody.appendChild(newRow);

            // Add event listeners
            const quantityInput = newRow.querySelector('.quantity');
            const priceInput = newRow.querySelector('.unit-price');
            
            quantityInput.addEventListener('input', updateTotals);
            priceInput.addEventListener('input', updateTotals);
            
            newRow.querySelector('.remove-row').addEventListener('click', () => {
                newRow.remove();
                updateTotals();
            });
            
            // Focus on the particular field for better UX
            newRow.querySelector('input[name$="[particular]"]').focus();
        });
    });
    
    // Handle form submission
    document.querySelector('form').addEventListener('submit', function(e) {
        // Add any validation here if needed
    });
});
</script>
@endpush
