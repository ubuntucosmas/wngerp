@extends('layouts.master')
@section('title', 'Create Project Material-List')

@section('content')
<div class="container-fluid p-2">
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
                    <hr class="my-4">
                    <div class="section-card section-production">
                        <h5 class="section-header">
                            <i class="bi bi-box-seam me-2"></i>Materials - Production
                        </h5>
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
                                        <!-- Rows will be added dynamically -->
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-success btn-sm add-particular">+ Add Particular</button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm btn-add-item" id="addItemGroup">
                            <i class="bi bi-plus-circle"></i> Add Item
                        </button>
                    </div>
                    <hr class="my-4">
                    <div class="section-card section-hire">
                        <h5 class="section-header">
                            <i class="bi bi-tools me-2"></i>Materials for Hire
                        </h5>
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
                                <!-- Rows will be added dynamically -->
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
                        ];
                    @endphp

                    @foreach($subCategories as $category => $roles)
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
                        <input type="text" name="approved_by" class="form-control mb-2 required" required>

                        <label for="approved_departments">Departments (comma-separated)</label>
                        <input type="text" name="approved_departments" class="form-control" placeholder="Production, Finance" required>
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
    
    .section-hire {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-left: 4px solid #36b9cc;
    }
    
    .section-labor {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
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
</style>
@endpush

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
                <div class="mb-2">
                    <label>Item</label>
                    <input type="text" name="production_items[${itemIndex}][item_name]" class="form-control" placeholder="e.g. Table">
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
                        <!-- Rows will be added dynamically -->
                    </tbody>
                </table>
                <button type="button" class="btn btn-success btn-sm add-particular">
                    <i class="bi bi-plus-circle"></i> Add Particular
                </button>
            </div>`;
            
            const $newGroup = $(newGroup);
            $('#items-wrapper').append($newGroup);
            particularCounters[itemIndex] = 0; // Initialize counter for this item
            
            // Add the first particular row
            $newGroup.find('.add-particular').trigger('click');
            
            itemIndex++;
        });

        // Function to load inventory items into dropdown
        function loadInventoryDropdown(selectElement, selectedValue = '') {
            // Show loading state
            selectElement.prop('disabled', true).html('<option value="">Loading items...</option>');
            
            $.ajax({
                url: '{{ route("api.inventory.items") }}',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    // Store current value
                    const currentValue = selectElement.val();
                    
                    // Clear existing options
                    selectElement.empty();
                    
                    // Add default option
                    selectElement.append($('<option>', {
                        value: '',
                        text: '-- Select an item --',
                        disabled: true,
                        selected: !currentValue && !selectedValue
                    }));
                    
                    // Add inventory items
                    data.forEach(item => {
                        const option = $('<option>', {
                            value: item.name,
                            text: item.name,
                            'data-unit': item.unit_of_measure || ''
                        });
                        
                        // Mark as selected if it matches the current or selected value
                        if ((selectedValue && item.name === selectedValue) || 
                            (!selectedValue && currentValue === item.name)) {
                            option.prop('selected', true);
                        }
                        
                        selectElement.append(option);
                    });
                    
                    // Enable the select
                    selectElement.prop('disabled', false);
                    
                    // Trigger change to update unit field if needed
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

        // Add particular to item group
        $(document).on('click', '.add-particular', function() {
            const $itemGroup = $(this).closest('.item-group');
            const itemIndex = $itemGroup.index();
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
                    <td><input type="text" name="production_items[${itemIndex}][particulars][${particularIndex}][comment]" class="form-control"></td>
                    <td><input type="text" name="production_items[${itemIndex}][particulars][${particularIndex}][design_reference]" class="form-control"></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="bi bi-trash"></i></button></td>
                </tr>`;
            
            const $newRow = $(newRow);
            $itemGroup.find('.particulars-body').append($newRow);
            particularCounters[itemIndex] = particularIndex + 1;
            
            // Initialize the new row
            initializeProductionRow($newRow);
        });

        // Function to initialize a production row
        function initializeProductionRow($row) {
            const $select = $row.find('.inventory-dropdown');
            const $unitField = $row.find('.unit-field');
            
            // Set up the unit field to be read-only
            $unitField.prop('readonly', true);
            
            // Initialize the dropdown
            if (!$select.data('initialized')) {
                populateDropdown($select);
                $select.data('initialized', true);
            }
        }

        // Function to initialize a new hire row
        function initializeHireRow($row) {
            const $select = $row.find('.inventory-dropdown');
            const $unitField = $row.find('.unit-field');
            
            // Set up the unit field to be read-only
            $unitField.prop('readonly', true);
            
            // Initialize the dropdown
            if (!$select.data('initialized')) {
                populateDropdown($select);
                $select.data('initialized', true);
            }
        }
        
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
                    <td><input type="text" name="materials_hire[${hireIndex}][comment]" class="form-control"></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                </tr>`;
            
            const $newRow = $(newRow);
            $('#materialsHireBody').append($newRow);
            
            // Initialize the new row
            initializeHireRow($newRow);
            
            hireIndex++;
        });
        
        // Initialize existing hire rows on page load
        $('#materialsHireBody tr').each(function() {
            initializeHireRow($(this));
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

    // Load inventory items once and cache them
    let inventoryItems = [];
    
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
    
    // Initialize all dropdowns on page load
    async function initializeDropdowns() {
        try {
            await fetchInventoryItems();
            
            $('.inventory-dropdown').each(function() {
                const $select = $(this);
                if (!$select.data('initialized')) {
                    const currentValue = $select.val();
                    // If dropdown has a value but wasn't initialized yet, find and set the unit
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
    
    // Function to find unit of measure for an item
    function findUnitOfMeasure(itemName) {
        if (!itemName) return '';
        const item = inventoryItems.find(i => i.name === itemName);
        return item ? (item.unit_of_measure || '') : '';
    }

    // Populate a single dropdown with cached items
    function populateDropdown($select, selectedValue = '') {
        // Store current value if not provided
        const currentValue = selectedValue || $select.val();
        const $row = $select.closest('tr');
        
        // Clear existing options
        $select.empty();
        
        // Add default option
        $select.append($('<option>', {
            value: '',
            text: '-- Select an item --',
            disabled: true,
            selected: !currentValue
        }));
        
        // Add inventory items
        inventoryItems.forEach(item => {
            const option = $('<option>', {
                value: item.name,
                text: item.name,
                'data-unit': item.unit_of_measure || ''
            });
            
            if (currentValue === item.name) {
                option.prop('selected', true);
                // Update unit field immediately for selected item
                $row.find('.unit-field').val(item.unit_of_measure || '');
            }
            
            $select.append(option);
        });
        
        // Enable the select
        $select.prop('disabled', false);
        
        // If we have a current value but no selection was made (item not found in list),
        // still try to set the unit of measure
        if (currentValue && !$select.val()) {
            const unit = findUnitOfMeasure(currentValue);
            if (unit) {
                $row.find('.unit-field').val(unit);
            }
        }
    }

    // Handle dropdown change
    $(document).on('change', '.inventory-dropdown', function() {
        const $this = $(this);
        const selectedOption = $this.find('option:selected');
        const unit = selectedOption.data('unit') || findUnitOfMeasure($this.val());
        const $unitField = $this.closest('tr').find('.unit-field').first();
        
        // Update the unit field
        $unitField.val(unit || '');
    });

    // Initialize dropdowns when new rows are added
    $(document).on('rowAdded', '.inventory-dropdown', function() {
        const $select = $(this);
        if (!$select.data('initialized')) {
            populateDropdown($select);
            $select.data('initialized', true);
        }
    });

    // Initialize on page load
    $(document).ready(function() {
        initializeDropdowns();
    });

</script>
@endpush
