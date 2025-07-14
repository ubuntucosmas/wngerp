@extends('layouts.master')

@section('title', 'Create {{ isset($enquiry) ? "Enquiry" : "Project" }} Budget')

@section('content')
@hasanyrole('finance|po|pm|super-admin')
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-2 rounded">
            <li class="breadcrumb-item"><a href="{{ isset($enquiry) ? route('enquiries.index') : route('projects.index') }}">{{ isset($enquiry) ? 'Enquiries' : 'Projects' }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Create Budget</li>
        </ol>
    </nav>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 m-0">Create {{ isset($enquiry) ? 'Enquiry' : 'Project' }} Budget</h1>
            <div>
                <a href="{{ (isset($enquiry) && is_object($enquiry) && isset($enquiry->id)) ? route('enquiries.files', $enquiry) : (isset($project) && is_object($project) && isset($project->id) ? route('projects.files.index', $project->id) : '#') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Back to {{ isset($enquiry) ? 'Enquiry' : 'Project' }} Files
                </a>
            </div>
        </div>
    </div>

    @error('start_date')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    @error('end_date')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="card">
        <div class="card-body">
            <form action="{{ isset($enquiry) ? route('enquiries.budget.store', $enquiry) : (isset($project) ? route('budget.store', $project) : '#') }}" method="POST">
                @csrf
                @if(isset($materialList))
                    <input type="hidden" name="material_list_id" value="{{ $materialList->id }}">
                @endif
                <div class="container">
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
                <input type="date" class="form-control" name="start_date" value="{{ old('start_date', isset($project) ? ($project->start_date ?? '') : (isset($enquiry) ? ($enquiry->start_date ?? '') : '')) }}">
            </div>
            <div class="col-md-6">
                <label for="end_date">End Date</label>
                <input type="date" class="form-control" name="end_date" value="{{ old('end_date', isset($project) ? ($project->end_date ?? '') : (isset($enquiry) ? ($enquiry->end_date ?? '') : '')) }}">
            </div>
        </div>
                    <hr class="my-4">
                    <div class="section-card section-production">
                        <h5 class="section-header">
                            <i class="bi bi-box-seam me-2"></i>Materials - Production
                        </h5>
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
                    <hr class="my-4">
                    @php
                        $otherCategories = ['Materials for Hire', 'Workshop labour', 'Site', 'Set down', 'Logistics'];
                    @endphp
                    @foreach($otherCategories as $cat)
                        <div class="section-card section-{{ str_replace(' ', '-', strtolower($cat)) }}">
                            <h5 class="section-header">
                                <i class="bi bi-tools me-2"></i>{{ $cat }}
                            </h5>
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
        @endforeach

        <div class="mb-4">
                <label for="approved_by">Prepared By:</label>
                        <input type="text" name="approved_by" value="{{ auth()->user()->name }}" class="form-control mb-2 required" required>

                        <label for="approved_departments">Department</label>
                        <input type="text" name="approved_departments" value="{{ auth()->user()->department }}" class="form-control" placeholder="Production, Finance" required>
            </div>
                    <div class="d-flex justify-content-end mt-4">
                        <h4 class="text-end fw-bold text-success">Total Budget: Ksh <span class="text-primary" id="grandTotal">0.00</span></h4>
        </div>

        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
            <a href="{{ (isset($enquiry) && is_object($enquiry) && isset($enquiry->id)) ? route('enquiries.files', $enquiry) : (isset($project) && is_object($project) && isset($project->id) ? route('projects.files.index', $project->id) : '#') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle"></i> Cancel
            </a>
            <div>
                <button type="reset" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Save Budget
                </button>
            </div>
        </div>
                </div>
            </form>
        </div>
    </div>
</div>
@else
    <div class="alert alert-danger mt-5">You do not have permission to create a budget.</div>
@endhasanyrole

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
    .section-production {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-left: 4px solid #4e73df;
    }
</style>
@endpush

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
        document.querySelectorAll('[name*="[budgeted_cost]"]').forEach(function(input) {
            const val = parseFloat(input.value);
            if (!isNaN(val)) total += val;
        });
        document.getElementById('grandTotal').textContent = total.toFixed(2);
    }

    // Attach event listeners to all relevant tables
    document.querySelectorAll('table').forEach(function(table) {
        table.addEventListener('input', function(e) {
            if (e.target.name && (e.target.name.includes('[quantity]') || e.target.name.includes('[unit_price]'))) {
                const row = e.target.closest('tr');
                updateBudgetedCost(row);
                updateGrandTotal();
            }
        });
    });

    // Also update on page load for any pre-filled values
    document.querySelectorAll('table tbody tr').forEach(function(row) {
        updateBudgetedCost(row);
    });
    updateGrandTotal();

    // Materials - Production add item/particular logic
    let itemIndex = {{ $prodItemIdx ?? 0 }};
    const wrapper = document.getElementById('budget-items-wrapper');
    document.getElementById('addBudgetItemGroup').addEventListener('click', function() {
        itemIndex++;
        const group = document.createElement('div');
        group.className = 'item-group border rounded p-3 mb-3';
        group.innerHTML = `
            <div class=\"mb-2\">
                <label>Item Name</label>
                <input type=\"text\" name=\"production_items[${itemIndex}][item_name]\" class=\"form-control\" placeholder=\"e.g. Table\">
            </div>
            <table class=\"table table-bordered\">
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
                <tbody class=\"particulars-body\">
                    <tr>
                        <td><input type=\"text\" name=\"production_items[${itemIndex}][particulars][0][particular]\" class=\"form-control\"></td>
                        <td><input type=\"text\" name=\"production_items[${itemIndex}][particulars][0][unit]\" class=\"form-control\"></td>
                        <td><input type=\"number\" step=\"0.01\" name=\"production_items[${itemIndex}][particulars][0][quantity]\" class=\"form-control\"></td>
                        <td><input type=\"number\" step=\"0.01\" name=\"production_items[${itemIndex}][particulars][0][unit_price]\" class=\"form-control\"></td>
                        <td><input type=\"number\" step=\"0.01\" name=\"production_items[${itemIndex}][particulars][0][budgeted_cost]\" class=\"form-control\"></td>
                        <td><input type=\"text\" name=\"production_items[${itemIndex}][particulars][0][comment]\" class=\"form-control\"></td>
                        <td><button type=\"button\" class=\"btn btn-danger btn-sm remove-particular\">Remove</button></td>
                    </tr>
                </tbody>
            </table>
            <button type=\"button\" class=\"btn btn-success btn-sm add-particular\">+ Add Particular</button>
        `;
        wrapper.appendChild(group);
    });
    wrapper.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-particular')) {
            const group = e.target.closest('.item-group');
            const tbody = group.querySelector('.particulars-body');
            const rows = tbody.querySelectorAll('tr');
            const itemIdx = group.getAttribute('data-item-idx') || Array.from(wrapper.children).indexOf(group);
            const newIdx = rows.length;
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type=\"text\" name=\"production_items[${itemIdx}][particulars][${newIdx}][particular]\" class=\"form-control\"></td>
                <td><input type=\"text\" name=\"production_items[${itemIdx}][particulars][${newIdx}][unit]\" class=\"form-control\"></td>
                <td><input type=\"number\" step=\"0.01\" name=\"production_items[${itemIdx}][particulars][${newIdx}][quantity]\" class=\"form-control\"></td>
                <td><input type=\"number\" step=\"0.01\" name=\"production_items[${itemIdx}][particulars][${newIdx}][unit_price]\" class=\"form-control\"></td>
                <td><input type=\"number\" step=\"0.01\" name=\"production_items[${itemIdx}][particulars][${newIdx}][budgeted_cost]\" class=\"form-control\"></td>
                <td><input type=\"text\" name=\"production_items[${itemIdx}][particulars][${newIdx}][comment]\" class=\"form-control\"></td>
                <td><button type=\"button\" class=\"btn btn-danger btn-sm remove-particular\">Remove</button></td>
            `;
            tbody.appendChild(row);
        }
        if (e.target.classList.contains('remove-particular')) {
            e.target.closest('tr').remove();
        }
    });

    // Add row logic for other categories
    document.querySelectorAll('.add-row').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const cat = btn.getAttribute('data-category');
            const table = document.getElementById('table_' + cat.replace(/ /g, '_').toLowerCase());
            const tbody = table.querySelector('tbody');
            const rowCount = tbody.querySelectorAll('tr').length;
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type=\"text\" name=\"items[${cat}][${rowCount}][particular]\" class=\"form-control\"></td>
                <td><input type=\"text\" name=\"items[${cat}][${rowCount}][unit]\" class=\"form-control\"></td>
                <td><input type=\"number\" step=\"0.01\" name=\"items[${cat}][${rowCount}][quantity]\" class=\"form-control\"></td>
                <td><input type=\"number\" step=\"0.01\" name=\"items[${cat}][${rowCount}][unit_price]\" class=\"form-control\"></td>
                <td><input type=\"number\" step=\"0.01\" name=\"items[${cat}][${rowCount}][budgeted_cost]\" class=\"form-control\"></td>
                <td><input type=\"text\" name=\"items[${cat}][${rowCount}][comment]\" class=\"form-control\"></td>
                <td><button type=\"button\" class=\"btn btn-danger btn-sm remove-row\">Remove</button></td>
            `;
            tbody.appendChild(row);
        });
    });
    document.querySelectorAll('tbody').forEach(function(tbody) {
        tbody.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-row')) {
                e.target.closest('tr').remove();
            }
        });
    });
    });
</script>
