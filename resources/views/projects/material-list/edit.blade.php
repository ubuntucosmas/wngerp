@extends('layouts.master')

@push('styles')
    <link href="{{ asset('css/material-list.css') }}" rel="stylesheet">
@endpush

@section('title', 'Edit Material List')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Edit Material List</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('projects.material-list.index', $project) }}">Material Lists</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('projects.material-list.update', [$project, $materialList]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start_date">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" 
                                   value="{{ old('start_date', $materialList->start_date->format('Y-m-d')) }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_date">End Date <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" 
                                   value="{{ old('end_date', $materialList->end_date->format('Y-m-d')) }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="approved_by">Approved By <span class="text-danger">*</span></label>
                            <input type="text" name="approved_by" id="approved_by" class="form-control @error('approved_by') is-invalid @enderror" 
                                   value="{{ old('approved_by', $materialList->approved_by) }}" required>
                            @error('approved_by')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="approved_departments">Approved Departments <span class="text-danger">*</span></label>
                            <input type="text" name="approved_departments" id="approved_departments" class="form-control @error('approved_departments') is-invalid @enderror" 
                                   value="{{ old('approved_departments', $materialList->approved_departments) }}" required>
                            @error('approved_departments')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Production Items --}}
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Production Items</h5>
                        <button type="button" class="btn btn-sm btn-primary add-production-item">
                            <i class="bi bi-plus-lg"></i> Add Item
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="production-items-container">
                            @forelse(old('production_items', $materialList->productionItems) as $pIndex => $item)
                                <div class="production-item-group border rounded p-3 mb-3" data-index="{{ $pIndex }}">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0">Production Item #<span class="item-number">{{ $loop->iteration }}</span></h6>
                                        <button type="button" class="btn btn-sm btn-danger remove-production-item" data-index="{{ $pIndex }}">
                                            <i class="bi bi-trash"></i> Remove
                                        </button>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Item Name <span class="text-danger">*</span></label>
                                        <input type="text" name="production_items[{{ $pIndex }}][item_name]" 
                                               class="form-control @error('production_items.'.$pIndex.'.item_name') is-invalid @enderror" 
                                               value="{{ old('production_items.'.$pIndex.'.item_name', is_object($item) ? $item->item_name : ($item['item_name'] ?? '')) }}" required>
                                        @error('production_items.'.$pIndex.'.item_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="particulars-container">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label>Particulars</label>
                                            <button type="button" class="btn btn-sm btn-outline-primary add-particular" data-index="{{ $pIndex }}">
                                                <i class="bi bi-plus"></i> Add Particular
                                            </button>
                                        </div>
                                        
                                        <div class="particulars-list">
                                            @php
                                                $particulars = is_object($item) ? $item->particulars : ($item['particulars'] ?? []);
                                                $particulars = old('production_items.'.$pIndex.'.particulars', $particulars);
                                            @endphp
                                            
                                            @if(count($particulars) > 0)
                                                @foreach($particulars as $partIndex => $particular)
                                                    <div class="particular-item row mb-2 align-items-end" data-index="{{ $partIndex }}">
                                                        <div class="col-md-4">
                                                            <label>Description <span class="text-danger">*</span></label>
                                                            <input type="text" 
                                                                   name="production_items[{{ $pIndex }}][particulars][{{ $partIndex }}][particular]" 
                                                                   class="form-control @error('production_items.'.$pIndex.'.particulars.'.$partIndex.'.particular') is-invalid @enderror" 
                                                                   value="{{ old('production_items.'.$pIndex.'.particulars.'.$partIndex.'.particular', is_object($particular) ? $particular->particular : ($particular['particular'] ?? '')) }}" 
                                                                   required>
                                                            @error('production_items.'.$pIndex.'.particulars.'.$partIndex.'.particular')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Quantity <span class="text-danger">*</span></label>
                                                            <input type="number" step="0.01" min="0" 
                                                                   name="production_items[{{ $pIndex }}][particulars][{{ $partIndex }}][quantity]" 
                                                                   class="form-control @error('production_items.'.$pIndex.'.particulars.'.$partIndex.'.quantity') is-invalid @enderror" 
                                                                   value="{{ old('production_items.'.$pIndex.'.particulars.'.$partIndex.'.quantity', is_object($particular) ? $particular->quantity : ($particular['quantity'] ?? '')) }}" 
                                                                   required>
                                                            @error('production_items.'.$pIndex.'.particulars.'.$partIndex.'.quantity')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Unit <span class="text-danger">*</span></label>
                                                            <input type="text" 
                                                                   name="production_items[{{ $pIndex }}][particulars][{{ $partIndex }}][unit]" 
                                                                   class="form-control @error('production_items.'.$pIndex.'.particulars.'.$partIndex.'.unit') is-invalid @enderror" 
                                                                   value="{{ old('production_items.'.$pIndex.'.particulars.'.$partIndex.'.unit', is_object($particular) ? $particular->unit : ($particular['unit'] ?? '')) }}" 
                                                                   required>
                                                            @error('production_items.'.$pIndex.'.particulars.'.$partIndex.'.unit')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Design Reference</label>
                                                            <input type="text" 
                                                                   name="production_items[{{ $pIndex }}][particulars][{{ $partIndex }}][design_reference]" 
                                                                   class="form-control" 
                                                                   value="{{ old('production_items.'.$pIndex.'.particulars.'.$partIndex.'.design_reference', is_object($particular) ? $particular->design_reference : ($particular['design_reference'] ?? '')) }}">
                                                        </div>
                                                        <div class="col-md-1">
                                                            <button type="button" class="btn btn-sm btn-outline-danger remove-particular" data-index="{{ $pIndex }}" data-particular-index="{{ $partIndex }}">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="alert alert-info">No particulars added yet.</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-info">No production items added yet. Click "Add Item" to get started.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
                                    <tr>
                                        <th>Particular</th>
                                        <th>Unit Of Measure</th>
                                        <th>Quantity</th>
                                        <th>Comment</th>
                                        <th>Design Reference</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="particulars-body">
                                    @foreach($item->particulars as $index => $particular)
                                        <tr>
                                            <td><input type="text" name="production_items[{{ $pIndex }}][particulars][{{ $index }}][particular]" class="form-control" value="{{ $particular->particular }}"></td>
                                            <td><input type="text" name="production_items[{{ $pIndex }}][particulars][{{ $index }}][unit]" class="form-control" value="{{ $particular->unit }}"></td>
                                            <td><input type="number" step="0.01" name="production_items[{{ $pIndex }}][particulars][{{ $index }}][quantity]" class="form-control" value="{{ $particular->quantity }}"></td>
                                            <td><input type="text" name="production_items[{{ $pIndex }}][particulars][{{ $index }}][comment]" class="form-control" value="{{ $particular->comment }}"></td>
                                            <td><input type="text" name="production_items[{{ $pIndex }}][particulars][{{ $index }}][design_reference]" class="form-control" value="{{ $particular->design_reference }}"></td>
                                            <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-success btn-sm add-particular">+ Add Particular</button>
                        </div>

                {{-- Materials for Hire --}}
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Materials for Hire</h5>
                        <button type="button" class="btn btn-sm btn-primary add-materials-hire">
                            <i class="bi bi-plus-lg"></i> Add Item
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="materials-hire-container">
                            @php
                                $materialsHire = old('materials_hire', $materialList->materialsHire);
                            @endphp
                            
                            @if(count($materialsHire) > 0)
                                @foreach($materialsHire as $mhIndex => $material)
                                    <div class="materials-hire-item border rounded p-3 mb-3" data-index="{{ $mhIndex }}">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Item Name <span class="text-danger">*</span></label>
                                                    <input type="text" 
                                                           name="materials_hire[{{ $mhIndex }}][item_name]" 
                                                           class="form-control @error('materials_hire.'.$mhIndex.'.item_name') is-invalid @enderror" 
                                                           value="{{ old('materials_hire.'.$mhIndex.'.item_name', is_object($material) ? $material->item_name : ($material['item_name'] ?? '')) }}" 
                                                           required>
                                                    @error('materials_hire.'.$mhIndex.'.item_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Particular <span class="text-danger">*</span></label>
                                                    <input type="text" 
                                                           name="materials_hire[{{ $mhIndex }}][particular]" 
                                                           class="form-control @error('materials_hire.'.$mhIndex.'.particular') is-invalid @enderror" 
                                                           value="{{ old('materials_hire.'.$mhIndex.'.particular', is_object($material) ? $material->particular : ($material['particular'] ?? '')) }}" 
                                                           required>
                                                    @error('materials_hire.'.$mhIndex.'.particular')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Quantity <span class="text-danger">*</span></label>
                                                    <input type="number" step="0.01" min="0" 
                                                           name="materials_hire[{{ $mhIndex }}][quantity]" 
                                                           class="form-control @error('materials_hire.'.$mhIndex.'.quantity') is-invalid @enderror" 
                                                           value="{{ old('materials_hire.'.$mhIndex.'.quantity', is_object($material) ? $material->quantity : ($material['quantity'] ?? '')) }}" 
                                                           required>
                                                    @error('materials_hire.'.$mhIndex.'.quantity')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Unit <span class="text-danger">*</span></label>
                                                    <input type="text" 
                                                           name="materials_hire[{{ $mhIndex }}][unit]" 
                                                           class="form-control @error('materials_hire.'.$mhIndex.'.unit') is-invalid @enderror" 
                                                           value="{{ old('materials_hire.'.$mhIndex.'.unit', is_object($material) ? $material->unit : ($material['unit'] ?? '')) }}" 
                                                           required>
                                                    @error('materials_hire.'.$mhIndex.'.unit')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end">
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-materials-hire">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert alert-info">No materials for hire added yet. Click "Add Item" to get started.</div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Labour & Other Categories --}}
                @php
                    $categories = ['Workshop labour', 'Site', 'Set down', 'Logistics'];
                @endphp
                @foreach($categories as $category)
                    <div class="mb-4">
                        <h5>{{ $category }}</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Particular</th>
                                    <th>Unit Of Measure</th>
                                    <th>Quantity</th>
                                    <th>Comment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($budget->items[$category] ?? [] as $index => $item)
                                    <tr>
                                        <td><input type="text" name="items[{{ $category }}][{{ $index }}][particular]" class="form-control" value="{{ $item['particular'] }}" readonly></td>
                                        <td><input type="text" name="items[{{ $category }}][{{ $index }}][unit]" class="form-control" value="{{ $item['unit'] }}"></td>
                                        <td><input type="number" step="0.01" name="items[{{ $category }}][{{ $index }}][quantity]" class="form-control" value="{{ $item['quantity'] }}"></td>
                                        <td><input type="text" name="items[{{ $category }}][{{ $index }}][comment]" class="form-control" value="{{ $item['comment'] }}"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach

                {{-- Approval Section --}}
                <div class="mb-4">
                    <label for="approved_by">Approved By</label>
                    <input type="text" name="approved_by" class="form-control mb-2" value="{{ $materialList->approved_by }}">

                    <label for="approved_departments">Departments (comma-separated)</label>
                    <input type="text" name="approved_departments" class="form-control" value="{{ $materialList->approved_departments }}">
                </div>

                {{-- Form actions --}}
                <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                    <a href="{{ route('projects.material-list.index', $materialList->project_id) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                    <div>
                        <button type="reset" class="btn btn-outline-secondary me-2">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Material-List
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
        
        // Counter for new items
        let materialHireCounter = {{ count(old('materials_hire', $materialList->materialsHire)) }};
        
        // Add new materials hire item
        $(document).on('click', '.add-materials-hire', function() {
            const container = $('#materials-hire-container');
            const index = materialHireCounter++;
            
            const html = `
                <div class="materials-hire-item border rounded p-3 mb-3" data-index="${index}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Item Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="materials_hire[${index}][item_name]" 
                                       class="form-control" 
                                       required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Particular <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="materials_hire[${index}][particular]" 
                                       class="form-control" 
                                       required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Quantity <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" 
                                       name="materials_hire[${index}][quantity]" 
                                       class="form-control" 
                                       required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Unit <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="materials_hire[${index}][unit]" 
                                       class="form-control" 
                                       required>
                            </div>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-sm btn-outline-danger remove-materials-hire">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Comment</label>
                                <input type="text" 
                                       name="materials_hire[${index}][comment]" 
                                       class="form-control">
                            </div>
                        </div>
                    </div>
                </div>`;
                
            // Remove the "no items" alert if it exists
            if (container.find('.alert-info').length) {
                container.empty();
            }
            
            container.append(html);
        });
        
        // Remove materials hire item
        $(document).on('click', '.remove-materials-hire', function() {
            const container = $(this).closest('.materials-hire-item');
            container.fadeOut(300, function() {
                $(this).remove();
                
                // Show "no items" message if container is empty
                if ($('#materials-hire-container').children().length === 0) {
                    $('#materials-hire-container').html('<div class="alert alert-info">No materials for hire added yet. Click "Add Item" to get started.</div>');
                }
            });
        });
        
        // Add production item
        let productionItemCounter = {{ count(old('production_items', $materialList->productionItems)) }};
        
        $(document).on('click', '.add-production-item', function() {
            const container = $('#production-items-container');
            const index = productionItemCounter++;
            
            const html = `
                <div class="production-item-group border rounded p-3 mb-3" data-index="${index}">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Production Item #<span class="item-number">${container.children().length + 1}</span></h6>
                        <button type="button" class="btn btn-sm btn-danger remove-production-item" data-index="${index}">
                            <i class="bi bi-trash"></i> Remove
                        </button>
                    </div>
                    <div class="form-group mb-3">
                        <label>Item Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="production_items[${index}][item_name]" 
                               class="form-control" 
                               required>
                    </div>
                    
                    <div class="particulars-container">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label>Particulars</label>
                            <button type="button" class="btn btn-sm btn-outline-primary add-particular" data-index="${index}">
                                <i class="bi bi-plus"></i> Add Particular
                            </button>
                        </div>
                        
                        <div class="particulars-list">
                            <div class="alert alert-info">No particulars added yet.</div>
                        </div>
                    </div>
                </div>`;
                
            // Remove the "no items" alert if it exists
            if (container.find('.alert-info').length) {
                container.empty();
            }
            
            container.append(html);
            updateItemNumbers();
        });
        
        // Remove production item
        $(document).on('click', '.remove-production-item', function() {
            const container = $(this).closest('.production-item-group');
            container.fadeOut(300, function() {
                $(this).remove();
                updateItemNumbers();
                
                // Show "no items" message if container is empty
                if ($('#production-items-container').children().length === 0) {
                    $('#production-items-container').html('<div class="alert alert-info">No production items added yet. Click "Add Item" to get started.</div>');
                }
            });
        });
        
        // Add particular to production item
        $(document).on('click', '.add-particular', function() {
            const pindex = $(this).data('index');
            const container = $(this).closest('.particulars-container').find('.particulars-list');
            const index = container.find('.particular-item').length;
            
            const html = `
                <div class="particular-item row mb-2 align-items-end" data-index="${index}">
                    <div class="col-md-4">
                        <label>Description <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="production_items[${pindex}][particulars][${index}][particular]" 
                               class="form-control" 
                               required>
                    </div>
                    <div class="col-md-2">
                        <label>Quantity <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" 
                               name="production_items[${pindex}][particulars][${index}][quantity]" 
                               class="form-control" 
                               required>
                    </div>
                    <div class="col-md-2">
                        <label>Unit <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="production_items[${pindex}][particulars][${index}][unit]" 
                               class="form-control" 
                               required>
                    </div>
                    <div class="col-md-3">
                        <label>Design Reference</label>
                        <input type="text" 
                               name="production_items[${pindex}][particulars][${index}][design_reference]" 
                               class="form-control">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-particular" data-index="${pindex}" data-particular-index="${index}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>`;
                
            // Remove the "no particulars" alert if it exists
            if (container.find('.alert-info').length) {
                container.empty();
            }
            
            container.append(html);
        });
        
        // Remove particular
        $(document).on('click', '.remove-particular', function() {
            const item = $(this).closest('.particular-item');
            const container = item.parent();
            
            item.fadeOut(300, function() {
                $(this).remove();
                
                // Show "no particulars" message if container is empty
                if (container.children().length === 0) {
                    container.html('<div class="alert alert-info">No particulars added yet.</div>');
                }
            });
        });
        
        // Update production item numbers
        function updateItemNumbers() {
            $('.production-item-group').each(function(index) {
                $(this).find('.item-number').text(index + 1);
            });
        }
    });
</script>
@endpush
