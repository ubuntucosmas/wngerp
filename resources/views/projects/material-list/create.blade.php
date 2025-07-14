@extends('layouts.master')
@section('title', 'Create Project Material-List')

@section('content')
<div class="container-fluid p-2">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    @if(isset($enquiry))
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.index') }}">Enquiries</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.files', $enquiry) }}">{{ $enquiry->project_name }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.material-list.index', $enquiry) }}">Project Material List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create Material List</li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}">{{ $project->name }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('projects.material-list.index', $project) }}">Project Material List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create Material List</li>
                    @endif
                </ol>
            </nav>
            <h2 class="mb-0">Create Material List</h2>
        </div>
        <a href="{{ (isset($enquiry) && is_object($enquiry) && isset($enquiry->id)) ? route('enquiries.material-list.index', $enquiry) : route('projects.material-list.index', $project) }}" class="btn btn-primary">
            <i class="bi bi-arrow-left me-2"></i>Back to Material Lists
        </a>
    </div>

    @error('start_date')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    @error('end_date')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <form action="{{ isset($enquiry) ? route('enquiries.material-list.store', $enquiry) : route('projects.material-list.store', $project) }}" method="POST">
        @csrf
        <div class="form-container">
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="#basic-details" class="active">Basic Details</a></li>
                    <li><a href="#materials-production">Materials - Production</a></li>
                    <li><a href="#materials-hire">Itens for Hire</a></li>
                    <li><a href="#labour-workshop">Workshop Labour</a></li>
                    <li><a href="#labour-site">Site Labour</a></li>
                    <li><a href="#labour-setdown">Set Down Labour</a></li>
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

                <!-- Materials - Production -->
                <div id="materials-production" class="form-section-card section-production">
                    <div class="form-section-card-header">
                        <h5><i class="bi bi-box-seam me-2"></i>Materials - Production</h5>
                    </div>
                    <div class="form-section-card-body">
                        <div id="items-wrapper">
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
                                        <tr>
                                            <td>
                                                <select name="production_items[0][particulars][0][particular]" class="form-select inventory-dropdown" required>
                                                    <option value="" selected disabled>-- Loading items --</option>
                                                </select>
                                            </td>
                                            <td><input type="text" name="production_items[0][particulars][0][unit]" class="form-control unit-field" readonly></td>
                                            <td><input type="number" step="0.01" name="production_items[0][particulars][0][quantity]" class="form-control" required></td>
                                            <td><input type="number" step="0.01" name="production_items[0][particulars][0][unit_price]" class="form-control" required></td>
                                            <td><input type="text" name="production_items[0][particulars][0][comment]" class="form-control"></td>
                                            <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="bi bi-trash"></i></button></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-between align-items-center">
                                    <button type="button" class="btn btn-success btn-sm add-particular">
                                        <i class="bi bi-plus-circle me-1"></i>Add Particular
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-item-group" style="display: none;">
                                        <i class="bi bi-trash me-1"></i>Remove Item
                                    </button>
                                </div>
                            </div>
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

                @php
                    $subCategories = [
                        'Workshop labour' => ['Technicians', 'Carpenter', 'CNC', 'Welders', 'Project Officer','Meals'],
                        'Site' => ['Technicians', 'Pasters', 'Electricians','Off loaders','Project Officer','Meals'],
                        'Set down' => ['Technicians', 'Off loaders', 'Electricians', 'Meals'],
                        'Logistics' => ['Delivery to site', 'Delivery from site', 'Team transport to and from site set up', 'Team transport to and from set down','Materials Collection'],
                    ];
                @endphp

                @foreach($subCategories as $category => $roles)
                    <div id="labour-{{ Str::slug($category) }}" class="form-section-card section-labor">
                        <div class="form-section-card-header">
                            <h5><i class="bi bi-people me-2"></i>{{ $category }}</h5>
                        </div>
                        <div class="form-section-card-body">
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
                                            <td><input type="text" name="items[{{ $category }}][{{ $index }}][unit]" class="form-control" value="{{ old('items.'.$category.'.'.$index.'.unit', 'pax') }}"></td>
                                            <td><input type="number" step="0.01" name="items[{{ $category }}][{{ $index }}][quantity]" class="form-control"></td>
                                            <td><input type="number" step="0.01" name="items[{{ $category }}][{{ $index }}][unit_price]" class="form-control"></td>
                                            <td><input type="text" name="items[{{ $category }}][{{ $index }}][comment]" class="form-control"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
                            <input type="text" name="approved_by" value="{{ auth()->user()->name }}" class="form-control mb-2 required" required>

                            <label for="approved_departments">Departments (comma-separated)</label>
                            <input type="text" name="approved_departments" value="{{ auth()->user()->department }}" class="form-control" placeholder="Production, Finance" required>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                    <a href="{{ (isset($enquiry) && is_object($enquiry) && isset($enquiry->id)) ? route('enquiries.material-list.index', $enquiry) : route('projects.material-list.index', $project) }}" class="btn btn-outline-secondary">
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
        </div>
    </form>

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
    // Global variables
    let itemIndex = {{ isset($materialList->production_items) && is_array($materialList->production_items) ? count($materialList->production_items) : 0 }} > 0 ? {{ isset($materialList->production_items) && is_array($materialList->production_items) ? count($materialList->production_items) : 1 }} : 1;
    let hireIndex = {{ isset($materialList->materials_hire) && is_array($materialList->materials_hire) ? count($materialList->materials_hire) : 0 }} > 0 ? {{ isset($materialList->materials_hire) && is_array($materialList->materials_hire) ? count($materialList->materials_hire) : 1 }} : 1;
    let particularCounters = {};
    let inventoryItems = [];
    let templates = []; // Store templates for dropdown

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
            console.log('Fetching templates...');
            $.ajax({
                url: '{{ url("/templates/templates-all") }}',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log('Templates fetched successfully:', data);
                    templates = data.data || [];
                    console.log('Templates array:', templates);
                    resolve(templates);
                },
                error: function(xhr, status, error) {
                    console.error('Error loading templates:', error);
                    console.error('Status:', status);
                    console.error('Response:', xhr.responseText);
                    console.error('Status code:', xhr.status);
                    reject(error);
                }
            });
        });
    }

    // Function to populate template dropdowns
    function populateTemplateDropdowns() {
        console.log('Populating template dropdowns...');
        console.log('Templates available:', templates);
        console.log('Template selects found:', $('.template-select').length);
        
        $('.template-select').each(function() {
            const $select = $(this);
            console.log('Processing template select:', $select);
            
            if (!$select.data('templates-loaded')) {
                $select.empty();
                $select.append('<option value="">-- Choose a template or enter manually --</option>');
                
                templates.forEach(template => {
                    console.log('Adding template option:', template);
                    const option = $('<option>', {
                        value: template.id,
                        text: `${template.name} (${template.category ? template.category.name : 'No Category'})`,
                        'data-template': JSON.stringify(template)
                    });
                    $select.append(option);
                });
                
                $select.data('templates-loaded', true);
                console.log('Template dropdown populated with', templates.length, 'options');
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
                // Auto-fill item name
                $itemName.val(template.name);
                
                // Clear existing particulars
                $particularsBody.empty();
                
                // Add particulars from template
                if (template.particulars && template.particulars.length > 0) {
                    template.particulars.forEach((particular, index) => {
                        const particularRow = `
                            <tr>
                                <td><input type="text" name="production_items[${$itemGroup.data('item-index')}][particulars][${index}][particular]" class="form-control" value="${particular.particular}" required></td>
                                <td><input type="text" name="production_items[${$itemGroup.data('item-index')}][particulars][${index}][unit]" class="form-control" value="${particular.unit || ''}"></td>
                                <td><input type="number" step="0.01" name="production_items[${$itemGroup.data('item-index')}][particulars][${index}][quantity]" class="form-control" value="${particular.default_quantity}" required></td>
                                <td><input type="number" step="0.01" name="production_items[${$itemGroup.data('item-index')}][particulars][${index}][unit_price]" class="form-control" value="${particular.unit_price || '0.00'}" required></td>
                                <td><input type="text" name="production_items[${$itemGroup.data('item-index')}][particulars][${index}][comment]" class="form-control" value="${particular.comment || ''}"></td>
                                <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="bi bi-trash"></i></button></td>
                            </tr>
                        `;
                        $particularsBody.append(particularRow);
                    });
                    
                    // Update particular counter
                    particularCounters[$itemGroup.data('item-index')] = template.particulars.length;
                }
                
                // Show success message
                showNotification('Template loaded successfully!', 'success');
            }
        } else {
            // Clear item name and particulars when no template is selected
            $itemName.val('');
            $particularsBody.empty();
            particularCounters[$itemGroup.data('item-index')] = 0;
        }
    }

    // Function to show notifications
    function showNotification(message, type = 'info') {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-info';
        const notification = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Insert notification at the top of the form
        $('.card-body form').prepend(notification);
        
        // Auto-hide after 3 seconds
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

    // Load template categories
    function loadTemplateCategories() {
        $.ajax({
            url: '{{ route("templates.categories.all") }}',
            type: 'GET',
            success: function(categories) {
                const $select = $('#templateCategory');
                $select.find('option:not(:first)').remove();
                
                categories.forEach(category => {
                    $select.append(`<option value="${category.id}">${category.name}</option>`);
                });
            },
            error: function() {
                console.error('Failed to load categories');
            }
        });
    }

    // Load templates
    function loadTemplates() {
        const categoryId = $('#templateCategory').val();
        const $templatesList = $('#templatesList');
        const $loading = $('#templateLoading');
        const $empty = $('#templateEmpty');
        
        $templatesList.hide();
        $empty.hide();
        $loading.show();
        
        let url = '{{ route("templates.templates.index") }}';
        if (categoryId) {
            url = '{{ route("templates.templates.by-category", ":categoryId") }}'.replace(':categoryId', categoryId);
        }
        
        $.ajax({
            url: url,
            type: 'GET',
            success: function(data) {
                $loading.hide();
                
                if (data.data && data.data.length > 0) {
                    displayTemplates(data.data);
                    $templatesList.show();
                } else {
                    $empty.show();
                }
            },
            error: function() {
                $loading.hide();
                $empty.show();
                console.error('Failed to load templates');
            }
        });
    }

    // Display templates in modal
    function displayTemplates(templates) {
        const $templatesList = $('#templatesList');
        $templatesList.empty();
        
        templates.forEach(template => {
            const templateCard = `
                <div class="col-md-6 mb-3 template-card" data-name="${template.name.toLowerCase()}">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-title">${template.name}</h6>
                            <p class="card-text small text-muted">${template.category ? template.category.name : ''}</p>
                            ${template.description ? `<p class="card-text small">${template.description}</p>` : ''}
                            <div class="mb-2">
                                <span class="badge bg-primary">${template.particulars ? template.particulars.length : 0} items</span>
                                ${template.estimated_cost ? `<span class="badge bg-success">KSh ${parseFloat(template.estimated_cost).toFixed(2)}</span>` : ''}
                            </div>
                            <button type="button" class="btn btn-primary btn-sm w-100" onclick="addTemplateToMaterialList(${template.id})">
                                <i class="bi bi-plus-circle"></i> Add to Material List
                            </button>
                        </div>
                    </div>
                </div>
            `;
            $templatesList.append(templateCard);
        });
    }

    // Filter templates by search term
    function filterTemplates() {
        const searchTerm = $('#templateSearch').val().toLowerCase();
        
        $('.template-card').each(function() {
            const $card = $(this);
            const cardName = $card.data('name');
            
            if (cardName.includes(searchTerm)) {
                $card.show();
            } else {
                $card.hide();
            }
        });
    }

    // Add template to material list
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
                
                // Populate template dropdown for the new group
                populateTemplateDropdowns();
                
                // Set the selected template
                $newGroup.find('.template-select').val(template.id);
                
                particularCounters[itemIndex] = template.particulars.length;
                itemIndex++;
                
                $('#templateModal').modal('hide');
                showNotification('Template added successfully!', 'success');
            },
            error: function() {
                showNotification('Failed to load template details', 'error');
            }
        });
    }

    $(document).ready(function() {
        // Initialize particular counters for existing items
        $('.item-group').each(function(index) {
            particularCounters[index] = $(this).find('.particulars-body tr').length;
        });

        // Initialize the default particular row that's already in the HTML
        $('.item-group:first .particulars-body tr').each(function() {
            initializeProductionRow($(this));
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
            
            // Populate template dropdown for the new group
            populateTemplateDropdowns();
            
            // Add the first particular row
            $newGroup.find('.add-particular').trigger('click');
            
            itemIndex++;
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
            
            // Initialize the new row
            initializeProductionRow($newRow);
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
                
                // Update item name and template select
                $(this).find('input[name^="production_items["], select[name^="production_items["]').each(function() {
                    const newName = $(this).attr('name').replace(
                        /production_items\[\d+\]/,
                        `production_items[${newIndex}]`
                    );
                    $(this).attr('name', newName);
                });
                
                // Update data-item-index
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
            
            // Update the unit field
            $unitField.val(unit || '');
        });

        // Form validation
        $('form').on('submit', function(e) {
            let isValid = true;
            
            // Debug: Log form data
            console.log('Form submission - Production items data:');
            const formData = new FormData(this);
            for (let [key, value] of formData.entries()) {
                if (key.includes('production_items')) {
                    console.log(key, value);
                }
            }
            
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

        // Initialize everything
        async function initialize() {
            try {
                await Promise.all([
                    fetchInventoryItems(),
                    fetchTemplates()
                ]);
                
                initializeDropdowns();
                populateTemplateDropdowns();
                
                // Template modal functionality
                loadTemplateCategories();
                loadTemplates();
            } catch (error) {
                console.error('Failed to initialize:', error);
            }
        }

        // Filter templates in modal
        function filterTemplates() {
            const searchTerm = $('#templateSearch').val().toLowerCase();
            $('.template-card').each(function() {
                const templateName = $(this).data('name');
                if (templateName.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        // Add template to production items
        function addTemplateToProduction(template) {
            const newGroup = `
                <div class="item-group card mb-3" data-item-index="${itemIndex}">
                    <div class="card-body">
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
                                <input type="text" name="production_items[${itemIndex}][item_name]" class="form-control item-name" placeholder="e.g. Table" value="${template.name}" required>
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
                </div>`;
            
            const $newGroup = $(newGroup);
            $('#items-wrapper').append($newGroup);
            particularCounters[itemIndex] = template.particulars.length;
            
            // Populate template dropdown for the new group
            populateTemplateDropdowns();
            
            itemIndex++;
            
            // Close modal
            $('#templateModal').modal('hide');
            
            showNotification('Template added successfully!', 'success');
        }

        // Category filter
        $('#templateCategory').on('change', function() {
            loadTemplates();
        });
        
        // Search filter
        $('#templateSearch').on('keyup', function() {
            filterTemplates();
        });

        // Add template from modal
        $(document).on('click', '.add-template-btn', function() {
            const templateId = $(this).data('template-id');
            const template = templates.find(t => t.id == templateId);
            if (template) {
                addTemplateToProduction(template);
            }
        });

        // Initialize when document is ready
        $(document).ready(function() {
            initialize();
        });
    });
</script>
@endpush
