// Wait for the document to be fully loaded
$(document).ready(function() {
    console.log('Document ready - initializing material list edit page');
    
    // Initialize Select2 if available
    if ($.fn.select2) {
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Select an option',
            allowClear: true
        });
    }
    
    // Initialize datepickers if available
    if ($.fn.datepicker) {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
            orientation: 'bottom auto'
        });
    }

    // Set minimum end date based on start date
    $('#start_date').on('change', function() {
        $('#end_date').attr('min', $(this).val());
    });
    
    // Initialize tooltips
    if ($.fn.tooltip) {
        $('[data-bs-toggle="tooltip"]').tooltip();
    }

    // Add new materials hire item
    let materialsHireIndex = $('#materialsHireContainer .materials-hire-item').length;
    
    $('#addMaterialsHire').on('click', function() {
        const template = `
            <div class="materials-hire-item border rounded p-3 mb-3" data-index="${materialsHireIndex}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Item Name</label>
                        <input type="text" 
                               name="materials_hire[${materialsHireIndex}][item_name]" 
                               class="form-control" 
                               required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Particular</label>
                        <input type="text" 
                               name="materials_hire[${materialsHireIndex}][particular]" 
                               class="form-control">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" step="0.01" min="0" 
                               name="materials_hire[${materialsHireIndex}][quantity]" 
                               class="form-control">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Unit</label>
                        <input type="text" 
                               name="materials_hire[${materialsHireIndex}][unit]" 
                               class="form-control">
                    </div>
                    <div class="col-md-1 d-flex align-items-end mb-3">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-materials-hire">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>`;

        $('#materialsHireContainer').append(template);
        $('.no-materials-hire').remove();
        materialsHireIndex++;
    });

    // Remove materials hire item
    $(document).on('click', '.remove-materials-hire', function() {
        $(this).closest('.materials-hire-item').remove();
        if ($('#materialsHireContainer .materials-hire-item').length === 0) {
            $('#materialsHireContainer').append('<div class="alert alert-info no-materials-hire">No materials for hire added yet.</div>');
        }
    });

    // Initialize production item index based on existing items or start from 0
    let productionItemIndex = $('.production-item-group').length;
    
    console.log('Initial production item index:', productionItemIndex);
    
    // Add new production item
    $(document).on('click', '#addProductionItem', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Add production item button clicked');
        
        // Calculate the next item number
        const itemNumber = $('.production-item-group').length + 1;
        const template = `
            <div class="production-item-group border rounded p-3 mb-4" data-index="${productionItemIndex}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Production Item #<span class="item-number">${itemNumber}</span></h6>
                    <button type="button" class="btn btn-sm btn-danger remove-production-item">
                        <i class="bi bi-trash me-1"></i> Remove
                    </button>
                </div>
                
                <div class="form-group mb-3">
                    <label class="form-label">Item Name</label>
                    <input type="text" 
                           name="production_items[${productionItemIndex}][item_name]" 
                           class="form-control">
                </div>

                <div class="particulars-container">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Particulars</h6>
                        <button type="button" class="btn btn-sm btn-outline-primary add-particular" data-item-index="${productionItemIndex}">
                            <i class="bi bi-plus me-1"></i> Add Particular
                        </button>
                    </div>
                    <div class="particulars-list">
                        <div class="alert alert-info no-particulars">No particulars added yet.</div>
                    </div>
                </div>
            </div>`;

        $('#productionItemsContainer').append(template);
        $('.no-production-items').remove();
        productionItemIndex++;
    });

    // Remove production item
    $(document).on('click', '.remove-production-item', function() {
        $(this).closest('.production-item-group').remove();
        updateProductionItemNumbers();
        
        if ($('#productionItemsContainer .production-item-group').length === 0) {
            $('#productionItemsContainer').append('<div class="alert alert-info no-production-items">No production items added yet.</div>');
        }
    });

    // Add particular to production item
    $(document).on('click', '.add-particular', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Add particular button clicked');
        
        const $button = $(this);
        const itemIndex = $button.data('item-index');
        const $container = $button.closest('.production-item-group').find('.particulars-list');
        const particularIndex = $container.find('.particular-item').length;
        
        console.log('Adding particular:', { itemIndex, particularIndex });
        
        const template = `
            <div class="particular-item border rounded p-3 mb-3" data-index="${particularIndex}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" 
                               name="production_items[${itemIndex}][particulars][${particularIndex}][particular]" 
                               class="form-control">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" step="0.01" min="0" 
                               name="production_items[${itemIndex}][particulars][${particularIndex}][quantity]" 
                               class="form-control">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Unit</label>
                        <input type="text" 
                               name="production_items[${itemIndex}][particulars][${particularIndex}][unit]" 
                               class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Design Reference</label>
                        <input type="text" 
                               name="production_items[${itemIndex}][particulars][${particularIndex}][design_reference]" 
                               class="form-control">
                    </div>
                    <div class="col-md-1 d-flex align-items-end mb-3">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-particular">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>`;

        $container.find('.no-particulars').remove();
        $container.append(template);
        
        // Scroll to the newly added particular
        $('html, body').animate({
            scrollTop: $container.find('.particular-item').last().offset().top - 100
        }, 500);
    });

    // Remove particular
    $(document).on('click', '.remove-particular', function() {
        const container = $(this).closest('.particulars-list');
        $(this).closest('.particular-item').remove();
        
        if (container.find('.particular-item').length === 0) {
            container.append('<div class="alert alert-info no-particulars">No particulars added yet.</div>');
        }
    });

    // Initialize labour category index based on existing categories or start from 0
    let labourCategoryIndex = $('.labour-category').length;
    
    console.log('Initial labour category index:', labourCategoryIndex);
    
    // Add new labour category
    $(document).on('click', '#addLabourCategory', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Add labour category button clicked');
        const template = `
            <div class="labour-category border rounded p-3 mb-4" data-category-index="${labourCategoryIndex}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-group mb-0 flex-grow-1 me-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" 
                               name="items[${labourCategoryIndex}][category]" 
                               class="form-control category-name" 
                               required>
                    </div>
                    <div>
                        <button type="button" class="btn btn-sm btn-outline-primary add-labour-item me-2" data-category-index="${labourCategoryIndex}">
                            <i class="bi bi-plus me-1"></i> Add Item
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-labour-category">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>

                <div class="labour-items">
                    <div class="alert alert-info no-labour-items">No items added yet.</div>
                </div>
            </div>`;

        $('#labourCategoriesContainer').append(template);
        $('.no-labour-categories').remove();
        labourCategoryIndex++;
    });

    // Remove labour category
    $(document).on('click', '.remove-labour-category', function() {
        $(this).closest('.labour-category').remove();
        
        if ($('#labourCategoriesContainer .labour-category').length === 0) {
            $('#labourCategoriesContainer').append('<div class="alert alert-info no-labour-categories">No labour categories added yet.</div>');
        }
    });

    // Add labour item to category
    $(document).on('click', '.add-labour-item', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Add labour item button clicked');
        
        const $button = $(this);
        const categoryIndex = $button.data('category-index');
        const $container = $button.closest('.labour-category').find('.labour-items');
        const itemIndex = $container.find('.labour-item').length;
        
        console.log('Adding labour item:', { categoryIndex, itemIndex });
        
        const template = `
            <div class="labour-item border rounded p-3 mb-3" data-index="${itemIndex}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Particular</label>
                        <input type="text" 
                               name="items[${categoryIndex}][items][${itemIndex}][particular]" 
                               class="form-control">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" step="0.01" min="0" 
                               name="items[${categoryIndex}][items][${itemIndex}][quantity]" 
                               class="form-control">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Unit</label>
                        <input type="text" 
                               name="items[${categoryIndex}][items][${itemIndex}][unit]" 
                               class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Comment</label>
                        <input type="text" 
                               name="items[${categoryIndex}][items][${itemIndex}][comment]" 
                               class="form-control">
                    </div>
                    <div class="col-md-1 d-flex align-items-end mb-3">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-labour-item">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>`;

        $container.find('.no-labour-items').remove();
        $container.append(template);
        
        // Scroll to the newly added labour item
        $('html, body').animate({
            scrollTop: $container.find('.labour-item').last().offset().top - 100
        }, 500);
    });

    // Remove labour item
    $(document).on('click', '.remove-labour-item', function() {
        const $container = $(this).closest('.labour-items');
        $(this).closest('.labour-item').remove();
        
        if ($container.find('.labour-item').length === 0) {
            $container.append('<div class="alert alert-info no-labour-items">No items added yet.</div>');
        }
    });
    
    // Helper function to update production item numbers
    function updateProductionItemNumbers() {
        console.log('Updating production item numbers');
        $('.production-item-group').each(function(index) {
            const newIndex = index + 1;
            $(this).find('.item-number').text(newIndex);
            // Update any data attributes if needed
            $(this).attr('data-index', index);
        });
    }
    
    // Initialize the page
    if ($('.production-item-group').length > 0) {
        updateProductionItemNumbers();
    }
    
    // Log initialization complete
    console.log('Material list edit page initialized');
});
