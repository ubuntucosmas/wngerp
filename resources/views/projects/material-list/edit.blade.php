@extends('layouts.master')
@section('title', 'Edit Project Material-List')

@section('content')
<div class="container-fluid p-2">
    <div class="mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                <li class="breadcrumb-item"><a href="{{ route('projects.material-list.index', $project) }}">Material Lists</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Material-List #{{ $materialList->id }}</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 m-0">Edit Project Material-List</h1>
            <div>
                <a href="{{ route('projects.material-list.show', [$project, $materialList]) }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Back to View
                </a>
                <button type="submit" form="materialListForm" class="btn btn-primary">
                    <i class="bi bi-save"></i> Update Material-List
                </button>
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
            <form action="{{ route('projects.material-list.update', [$project, $materialList]) }}" method="POST" id="materialListForm">
                @csrf
                @method('PUT')
                <div class="container">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="project_name">Project Name</label>
                            <input type="text" class="form-control" name="project_name" value="{{ $project->name }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="client">Client</label>
                            <input type="text" class="form-control" name="client" value="{{ $project->client_name }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="start_date">Start Date</label>
                            <input type="date" 
                                   class="form-control @error('start_date') is-invalid @enderror" 
                                   name="start_date" 
                                   value="{{ old('start_date', $materialList->start_date->format('Y-m-d')) }}" 
                                   required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="end_date">End Date</label>
                            <input type="date" 
                                   class="form-control @error('end_date') is-invalid @enderror" 
                                   name="end_date" 
                                   value="{{ old('end_date', $materialList->end_date->format('Y-m-d')) }}" 
                                   required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="approved_by">Approved By</label>
                            <input type="text" 
                                   class="form-control @error('approved_by') is-invalid @enderror" 
                                   name="approved_by" 
                                   value="{{ old('approved_by', $materialList->approved_by) }}" 
                                   required>
                            @error('approved_by')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="approved_departments">Approved Departments</label>
                            <input type="text" 
                                   class="form-control @error('approved_departments') is-invalid @enderror" 
                                   name="approved_departments" 
                                   value="{{ old('approved_departments', $materialList->approved_departments) }}" 
                                   required>
                            @error('approved_departments')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <hr class="my-4">

                    <!-- Production Items Section -->
                    <div class="section-card section-production">
                        <h5 class="section-header">
                            <i class="bi bi-box-seam me-2"></i>Materials - Production
                        </h5>
                        <div id="items-wrapper">
                            @php $piIndex = 0; @endphp
                            @forelse($materialList->productionItems as $item)
                                <div class="item-group border rounded p-3 mb-4" data-index="{{ $piIndex }}">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="flex-grow-1 me-3">
                                            <label class="form-label">Item Name</label>
                                            <input type="text" 
                                                   name="production_items[{{ $piIndex }}][item_name]" 
                                                   class="form-control" 
                                                   value="{{ old('production_items.'.$piIndex.'.item_name', $item->item_name) }}"
                                                   required>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-item-group">
                                            <i class="bi bi-trash"></i> Remove
                                        </button>
                                    </div>

                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Particular</th>
                                                <th width="15%">Unit of Measure</th>
                                                <th width="15%">Quantity</th>
                                                <th>Comment</th>
                                                <th width="100px" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="particulars-body">
                                            @php $partIndex = 0; @endphp
                                            @foreach($item->particulars as $particular)
                                                <tr data-index="{{ $partIndex }}">
                                                    <td>
                                                        <input type="text" 
                                                               name="production_items[{{ $piIndex }}][particulars][{{ $partIndex }}][particular]" 
                                                               class="form-control" 
                                                               value="{{ old('production_items.'.$piIndex.'.particulars.'.$partIndex.'.particular', $particular->particular) }}"
                                                               required>
                                                    </td>
                                                    <td>
                                                        <input type="text" 
                                                               name="production_items[{{ $piIndex }}][particulars][{{ $partIndex }}][unit]" 
                                                               class="form-control unit-field" 
                                                               value="{{ old('production_items.'.$piIndex.'.particulars.'.$partIndex.'.unit', $particular->unit) }}" 
                                                               required>
                                                    </td>
                                                    <td>
                                                        <input type="number" 
                                                               name="production_items[{{ $piIndex }}][particulars][{{ $partIndex }}][quantity]" 
                                                               class="form-control" 
                                                               value="{{ old('production_items.'.$piIndex.'.particulars.'.$partIndex.'.quantity', $particular->quantity) }}" 
                                                               min="0.01" 
                                                               step="0.01" 
                                                               required>
                                                    </td>
                                                    <td>
                                                        <input type="text" 
                                                               name="production_items[{{ $piIndex }}][particulars][{{ $partIndex }}][comment]" 
                                                               class="form-control" 
                                                               value="{{ old('production_items.'.$piIndex.'.particulars.'.$partIndex.'.comment', $particular->comment) }}"
                                                               placeholder="Optional">
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-sm btn-outline-danger remove-row" data-bs-toggle="tooltip" title="Remove">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @php $partIndex++; @endphp
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <div class="mt-3">
                                        <button type="button" class="btn btn-sm btn-outline-primary add-particular" data-item-index="{{ $piIndex }}">
                                            <i class="bi bi-plus-circle"></i> Add Particular
                                        </button>
                                    </div>
                                </div>
                                @php $piIndex++; @endphp
                            @empty
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>No production items added yet
                                </div>
                            @endforelse
                        </div>

                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-primary" id="addItemGroupBtn">
                                <i class="bi bi-plus-circle"></i> Add Item Group
                            </button>
                        </div>
                    </div>

                    <!-- Materials for Hire Section -->
                    <div class="section-card mt-4">
                        <h5 class="section-header">
                            <i class="bi bi-tools me-2"></i>Materials for Hire
                        </h5>
                        <div id="materials-hire-wrapper">
                            @forelse($materialList->materialsHire as $index => $item)
                                <div class="material-hire-item border rounded p-3 mb-3" data-index="{{ $index }}">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Item Name</label>
                                                <input type="text" class="form-control" name="materials_hire[{{ $index }}][item_name]" value="{{ $item->item_name }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label">Unit</label>
                                                <input type="text" class="form-control" name="materials_hire[{{ $index }}][unit]" value="{{ $item->unit }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label">Quantity</label>
                                                <input type="number" class="form-control" name="materials_hire[{{ $index }}][quantity]" value="{{ $item->quantity }}" min="0" step="0.01" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label">Comment</label>
                                                <input type="text" class="form-control" name="materials_hire[{{ $index }}][comment]" value="{{ $item->comment }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-outline-danger remove-material-hire" data-index="{{ $index }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>No materials for hire added yet
                                </div>
                            @endforelse
                        </div>
                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-primary" id="addMaterialHireBtn">
                                <i class="bi bi-plus-circle"></i> Add Material for Hire
                            </button>
                        </div>
                    </div>

                    <!-- Labour Section -->
                    <div class="section-card mt-4">
                        <h5 class="section-header">
                            <i class="bi bi-people me-2"></i>Labour
                        </h5>
                        
                        @php
                            $labourCategories = [
                                'skilled' => 'Skilled',
                                'unskilled' => 'Unskilled',
                                'supervisors' => 'Supervisors',
                                'other' => 'Other'
                            ];
                        @endphp

                        @foreach($labourCategories as $categoryKey => $categoryName)
                            <div class="labour-category border rounded p-3 mb-4">
                                <h6 class="mb-3">{{ $categoryName }}</h6>
                                <div class="labour-items-wrapper" data-category="{{ $categoryKey }}">
                                    @php
                                        $categoryItems = $labourItemsByCategory[$categoryKey] ?? [];
                                    @endphp
                                    
                                    @forelse($categoryItems as $index => $item)
                                        <div class="labour-item row mb-3" data-index="{{ $index }}">
                                            <input type="hidden" name="items[{{ $categoryKey }}][{{ $index }}][category]" value="{{ $categoryKey }}">
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="items[{{ $categoryKey }}][{{ $index }}][particular]" value="{{ $item->particular }}" placeholder="Particular" required>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" name="items[{{ $categoryKey }}][{{ $index }}][unit]" value="{{ $item->unit }}" placeholder="Unit" required>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" class="form-control" name="items[{{ $categoryKey }}][{{ $index }}][quantity]" value="{{ $item->quantity }}" placeholder="Qty" min="0" step="0.01" required>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" class="form-control" name="items[{{ $categoryKey }}][{{ $index }}][rate]" value="{{ $item->rate }}" placeholder="Rate" min="0" step="0.01" required>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" name="items[{{ $categoryKey }}][{{ $index }}][comment]" value="{{ $item->comment }}" placeholder="Comment">
                                            </div>
                                            <div class="col-md-2 d-flex align-items-center">
                                                <button type="button" class="btn btn-outline-danger remove-labour-item" data-category="{{ $categoryKey }}" data-index="{{ $index }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="alert alert-info">
                                            <i class="bi bi-info-circle me-2"></i>No {{ strtolower($categoryName) }} items added yet
                                        </div>
                                    @endforelse
                                </div>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary add-labour-item" data-category="{{ $categoryKey }}">
                                        <i class="bi bi-plus-circle"></i> Add {{ $categoryName }} Item
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 text-end">
                        <a href="{{ route('projects.material-list.show', [$project, $materialList]) }}" class="btn btn-outline-secondary me-2">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Material List
                        </button>
                    </div>