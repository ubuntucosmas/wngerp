@extends('layouts.master')
@section('title', 'Create Project Material-List')

@section('content')
<div class="container-fluid p-0">
    <div class="mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Material-List</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 m-0">Create Project Material-List</h1>
            <div>
                <a href="{{ route('projects.files.index', $project) }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Back to Project Files
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Save Material-List
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
            <form action="{{ route('projects.material-list.store', $project) }}" method="POST">
                @csrf
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
                            <input type="date" class="form-control" name="start_date" value="{{ old('start_date', $project->start_date) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="end_date">End Date</label>
                            <input type="date" class="form-control" name="end_date" value="{{ old('end_date', $project->end_date) }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5>Materials - Production</h5>
                        <div id="items-wrapper">
                            <div class="item-group border rounded p-3 mb-3">
                                <div class="mb-2">
                                    <label>Item</label>
                                    <input type="text" name="production_items[0][item_name]" class="form-control" placeholder="e.g. Table">
                                </div>
                                <table class="table table-bordered">
                                    <thead>
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
                                        <tr>
                                            <td><input type="text" name="production_items[0][particulars][0][particular]" class="form-control"></td>
                                            <td><input type="text" name="production_items[0][particulars][0][unit]" class="form-control"></td>
                                            <td><input type="number" step="0.01" name="production_items[0][particulars][0][quantity]" class="form-control"></td>
                                            <td><input type="text" name="production_items[0][particulars][0][comment]" class="form-control"></td>
                                            <td><input type="text" name="production_items[0][particulars][0][design_reference]" class="form-control"></td>
                                            <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-success btn-sm add-particular">+ Add Particular</button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm" id="addItemGroup">+ Add Item</button>
                    </div>

                    <div class="mb-4">
                        <h5>Materials for Hire</h5>
                        <table class="table table-bordered" id="materialsHireTable">
                            <thead>
                                <tr>
                                    <th>Particular</th>
                                    <th>Unit Of Measure</th>
                                    <th>Quantity</th>
                                    <th>Comment</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="materialsHireBody">
                                <tr>
                                    <td><input type="text" name="materials_hire[0][particular]" class="form-control"></td>
                                    <td><input type="text" name="materials_hire[0][unit]" class="form-control"></td>
                                    <td><input type="number" step="0.01" name="materials_hire[0][quantity]" class="form-control"></td>
                                    <td><input type="text" name="materials_hire[0][comment]" class="form-control"></td>
                                    <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-success btn-sm" id="addHireRow">+ Add Row</button>
                    </div>

                    @php
                        $subCategories = [
                            'Workshop labour' => ['Technicians', 'Carpenter', 'CNC', 'Welders', 'Project Officer','Meals'],
                            'Site' => ['Technicians', 'Pasters', 'Electricians','Off loaders','Project Officer','Meals'],
                            'Set down' => ['Technicians', 'Off loaders', 'Electricians', 'Meals'],
                            'Logistics' => ['Delivery to site', 'Delivery from site', 'Team transport to and from site set up', 'Team transport to and from set down','Materials Collection'],
                        ];
                    @endphp

                    @foreach($subCategories as $category => $roles)
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
                                    @foreach($roles as $index => $role)
                                        <tr>
                                            <td><input type="text" name="items[{{ $category }}][{{ $index }}][particular]" class="form-control" value="{{ $role }}" readonly></td>
                                            <td><input type="text" name="items[{{ $category }}][{{ $index }}][unit]" class="form-control"></td>
                                            <td><input type="number" step="0.01" name="items[{{ $category }}][{{ $index }}][quantity]" class="form-control"></td>
                                            <td><input type="text" name="items[{{ $category }}][{{ $index }}][comment]" class="form-control"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach

                    <div class="mb-4">
                        <label for="approved_by">Approved By</label>
                        <input type="text" name="approved_by" class="form-control mb-2">

                        <label for="approved_departments">Departments (comma-separated)</label>
                        <input type="text" name="approved_departments" class="form-control" placeholder="Production, Finance">
                    </div>

                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                        <a href="{{ route('projects.files.index', $project) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                        <div>
                            <button type="reset" class="btn btn-outline-secondary me-2">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Material-List
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let itemIndex = {{ isset($materialList->production_items) && is_array($materialList->production_items) ? count($materialList->production_items) : 0 }} > 0 ? {{ isset($materialList->production_items) && is_array($materialList->production_items) ? count($materialList->production_items) : 1 }} : 1;
        let hireIndex = {{ isset($materialList->materials_hire) && is_array($materialList->materials_hire) ? count($materialList->materials_hire) : 0 }} > 0 ? {{ isset($materialList->materials_hire) && is_array($materialList->materials_hire) ? count($materialList->materials_hire) : 1 }} : 1;
        let particularCounters = {};

        // Initialize particular counters for existing items
        $('.item-group').each(function(index) {
            particularCounters[index] = $(this).find('.particulars-body tr').length;
        });

        // Add new item group
        $('#addItemGroup').on('click', function() {
            const newGroup = `
            <div class="item-group border rounded p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="flex-grow-1 me-2">
                        <label class="form-label">Item</label>
                        <input type="text" name="production_items[${itemIndex}][item_name]" class="form-control" placeholder="e.g. Table" required>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm remove-item-group" style="margin-top: 1.5rem;">
                        <i class="bi bi-trash"></i> Remove Item
                    </button>
                </div>
                <table class="table table-bordered">
                    <thead>
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
                        <tr>
                            <td><input type="text" name="production_items[${itemIndex}][particulars][0][particular]" class="form-control" required></td>
                            <td><input type="text" name="production_items[${itemIndex}][particulars][0][unit]" class="form-control" required></td>
                            <td><input type="number" step="0.01" name="production_items[${itemIndex}][particulars][0][quantity]" class="form-control" required></td>
                            <td><input type="text" name="production_items[${itemIndex}][particulars][0][comment]" class="form-control"></td>
                            <td><input type="text" name="production_items[${itemIndex}][particulars][0][design_reference]" class="form-control"></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="bi bi-trash"></i></button></td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="btn btn-success btn-sm add-particular">
                    <i class="bi bi-plus-circle"></i> Add Particular
                </button>
            </div>`;
            
            $('#items-wrapper').append(newGroup);
            particularCounters[itemIndex] = 1; // Initialize counter for this item
            itemIndex++;
        });

        // Add particular to item group
        $(document).on('click', '.add-particular', function() {
            const group = $(this).closest('.item-group');
            const groupIndex = $('.item-group').index(group);
            const tbody = group.find('.particulars-body');
            
            // Initialize counter for this group if it doesn't exist
            if (typeof particularCounters[groupIndex] === 'undefined') {
                particularCounters[groupIndex] = tbody.find('tr').length;
            }
            
            const newRow = `
            <tr>
                <td><input type="text" name="production_items[${groupIndex}][particulars][${particularCounters[groupIndex]}][particular]" class="form-control" required></td>
                <td><input type="text" name="production_items[${groupIndex}][particulars][${particularCounters[groupIndex]}][unit]" class="form-control" required></td>
                <td><input type="number" step="0.01" name="production_items[${groupIndex}][particulars][${particularCounters[groupIndex]}][quantity]" class="form-control" required></td>
                <td><input type="text" name="production_items[${groupIndex}][particulars][${particularCounters[groupIndex]}][comment]" class="form-control"></td>
                <td><input type="text" name="production_items[${groupIndex}][particulars][${particularCounters[groupIndex]}][design_reference]" class="form-control"></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="bi bi-trash"></i></button></td>
            </tr>`;
            
            tbody.append(newRow);
            particularCounters[groupIndex]++;
        });

        // Add hire row
        $('#addHireRow').on('click', function() {
            const newRow = `
                <tr>
                    <td><input type="text" name="materials_hire[${hireIndex}][particular]" class="form-control" required></td>
                    <td><input type="text" name="materials_hire[${hireIndex}][unit]" class="form-control" required></td>
                    <td><input type="number" step="0.01" name="materials_hire[${hireIndex}][quantity]" class="form-control" required></td>
                    <td><input type="text" name="materials_hire[${hireIndex}][comment]" class="form-control"></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="bi bi-trash"></i></button></td>
                </tr>`;
            $('#materialsHireBody').append(newRow);
            hireIndex++;
        });

        // Remove row
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
        });

        // Remove item group
        $(document).on('click', '.remove-item-group', function() {
            if (confirm('Are you sure you want to remove this item and all its particulars?')) {
                $(this).closest('.item-group').remove();
                // Re-index remaining items
                reindexItems();
            }
        });

        // Re-index items after removal
        function reindexItems() {
            const newCounters = {};
            $('.item-group').each(function(newIndex) {
                const oldIndex = $('.item-group').index(this);
                newCounters[newIndex] = particularCounters[oldIndex] || 1;
                
                // Update item name
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
            
            // Validate required fields
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
    });
</script>
@endpush
