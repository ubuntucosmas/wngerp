<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\{
    ProfileController, admin\AdminController,
    stores\InventoryController, stores\CheckoutController, stores\CategoryController,
    stores\ReturnController, stores\DefectiveItemController, stores\ForHireController,
    projects\PhaseController, projects\ClientController, projects\DeliverableController,
    projects\ProjectController, Auth\AuthenticatedSessionController,
    projects\BookingOrderController, projects\ProjectFileController, projects\EnquiryLogController, projects\SiteSurveyController,
    FileUploadController, QuoteController, EnquiryController, projects\ProjectBudgetController, MaterialListController, projects\SetupController,
    projects\HandoverController, projects\SetDownReturnController, projects\ArchivalReportController,
};

Route::get('/', fn () => view('auth.login'));

Route::get('/admin/dashboard', fn () => view('admin.dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('admin.dashboard'); 

// Admin Routes
Route::middleware(['auth', 'role:admin|super-admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/users/index', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/users/{user}/edit', [AdminController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('users.destroy');
    Route::post('/users', [AdminController::class, 'store'])->name('users.store');
    Route::get('/users/create', [AdminController::class, 'create'])->name('users.create');

    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::post('/set-department', [AdminController::class, 'setDepartment'])->name('setDepartment');
    Route::get('/logs', [AdminController::class, 'viewLogs'])->name('logs');
});

// Inventory Routes
Route::middleware(['auth', 'role:store|storeadmin|procurement|super-admin'])->prefix('inventory')->name('inventory.')->group(function () {
    Route::get('/dashboard', [InventoryController::class, 'dashboard'])->name('dashboard');
    Route::get('/index', [InventoryController::class, 'index'])->name('index');

    // Checkin
    Route::get('/checkin/show', [InventoryController::class, 'showCheckIn'])->name('checkin.show');
    Route::post('/checkin/store', [InventoryController::class, 'checkIn'])->name('checkin.store');

    // New Stock
    Route::get('/new-stock', [InventoryController::class, 'newstock'])->name('newstock');
    Route::post('/newstock', [InventoryController::class, 'storeNewStock'])->name('store.newstock');
    Route::delete('/inventory/{id}/softDelete', [InventoryController::class, 'softDelete'])->name('softDelete');
    Route::get('/inventory/trash', [InventoryController::class, 'trash'])->name('trash');
    Route::post('/inventory/{id}/restore', [InventoryController::class, 'restore'])->name('restore');
    Route::delete('/inventory/{id}/forceDelete', [InventoryController::class, 'forceDelete'])->name('forceDelete');
    Route::put('/inventory/{id}', [InventoryController::class, 'update'])->name('update');
    //Route::get('/sku-search', [InventoryController::class, 'search'])->name('sku.search');


    // Checkout
    Route::get('/checkouts', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkouts', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/export', [CheckoutController::class, 'exportCheckout'])->name('checkout.export');

    // Export/Import
    Route::get('/export', [InventoryController::class, 'export'])->name('export');
    Route::post('/import', [InventoryController::class, 'import'])->name('import');

    // Returns
    Route::get('/returns', [ReturnController::class, 'index'])->name('returns');
    Route::post('/returns', [ReturnController::class, 'store'])->name('returns.store');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');

    // For Hire
    Route::get('/hires', [ForHireController::class, 'index'])->name('hires.index');
    Route::post('/hires', [ForHireController::class, 'store'])->name('hires.store');

    // Defective Items
    Route::get('/defective-items', [DefectiveItemController::class, 'index'])->name('defective_items.index');
    Route::post('/defective-items', [DefectiveItemController::class, 'store'])->name('defective_items.store');
});


// francis
// Setup Module Routes
Route::prefix('projects/{project}/setup')->name('projects.setup.')->group(function () {
    // Setup Reports
    Route::get('/', [\App\Http\Controllers\projects\SetupController::class, 'index'])
        ->name('index');
    
    // Store new setup report
    Route::post('/', [\App\Http\Controllers\projects\SetupController::class, 'store'])
        ->name('store');
    
    // Delete setup report
    Route::delete('/{setupReport}', [\App\Http\Controllers\projects\SetupController::class, 'destroy'])
        ->name('destroy');
});

// Handover Module Routes
Route::prefix('projects/{project}/handover')->name('projects.handover.')->group(function () {
    // Handover Reports
    Route::get('/', [\App\Http\Controllers\projects\HandoverController::class, 'index'])
        ->name('index');
    
    // Store new handover report
    Route::post('/', [\App\Http\Controllers\projects\HandoverController::class, 'store'])
        ->name('store');
    
    // Delete handover report
    Route::delete('/{handoverReport}', [\App\Http\Controllers\projects\HandoverController::class, 'destroy'])
        ->name('destroy');
});

// Set Down & Return Module Routes
Route::prefix('projects/{project}/set-down-return')->name('projects.set-down-return.')->group(function () {
    // Set Down & Return Reports
    Route::get('/', [\App\Http\Controllers\projects\SetDownReturnController::class, 'index'])
        ->name('index');
    
    // Store new set down return report
    Route::post('/', [\App\Http\Controllers\projects\SetDownReturnController::class, 'store'])
        ->name('store');
    
    // Delete set down return report
    Route::delete('/{setDownReturn}', [\App\Http\Controllers\projects\SetDownReturnController::class, 'destroy'])
        ->name('destroy');
});

// Archival & Reporting Module Routes
Route::prefix('projects/{project}/archival')->name('projects.archival.')->group(function () {
    // View all archival reports
    Route::get('/', [\App\Http\Controllers\projects\ArchivalReportController::class, 'index'])
        ->name('index');
    
    // Store new archival report
    Route::post('/', [\App\Http\Controllers\projects\ArchivalReportController::class, 'store'])
        ->name('store');
    
    // Delete archival report
    Route::delete('/{archivalReport}', [\App\Http\Controllers\projects\ArchivalReportController::class, 'destroy'])
        ->name('destroy');
});

//endof francis














// Project Manager / Project Officer Routes
Route::middleware(['auth', 'role:pm|po|super-admin'])->group(function () {
    Route::get('/projects/overview', [ProjectController::class, 'overview'])->name('projects.overview');
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/all', [ProjectController::class, 'allProjects'])->name('projects.all');
    Route::get('/projects/all', [ProjectController::class, 'allProjects'])->name('projects.all');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::put('/projects/{project}/assign', [ProjectController::class, 'assignProjectOfficer'])->name('projects.assign');
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::get('/projects/active', [ProjectController::class, 'active'])->name('projects.active');


    // Enquiries Routes
    Route::get('/projects/enquiry', [EnquiryController::class, 'index'])->name('enquiries.index');
    Route::post('/projects/enquiry', [EnquiryController::class, 'store'])->name('enquiries.store');
    Route::get('/projects/enquiry/create', [EnquiryController::class, 'create'])->name('enquiries.create');
    Route::get('/projects/enquiry/{enquiry}', [EnquiryController::class, 'show'])->name('enquiries.show');
    Route::get('/projects/enquiry/{enquiry}/edit', [EnquiryController::class, 'edit'])->name('enquiries.edit');
    Route::put('/projects/enquiry/{enquiry}', [EnquiryController::class, 'update'])->name('enquiries.update');
    Route::delete('/projects/enquiry/{enquiry}', [EnquiryController::class, 'destroy'])->name('enquiries.destroy');
    Route::post('/enquiries/{enquiry}/convert-to-project', [ProjectController::class, 'convertFromEnquiry'])->name('projects.convertFromEnquiry');


    // Phases & Tasks
    Route::post('/phases', [PhaseController::class, 'store'])->name('phases.store');
    Route::get('/phases/{id}/edit', [PhaseController::class, 'edit'])->name('phases.edit');
    Route::put('/phases/{id}', [PhaseController::class, 'update'])->name('phases.update');
    Route::delete('/phases/{id}', [PhaseController::class, 'destroy'])->name('phases.destroy');
    Route::get('/phases/{id}', [PhaseController::class, 'showPhase'])->name('phases.show');

    Route::post('/phases/store-task', [PhaseController::class, 'storeTask'])->name('phases.tasks.store');
    Route::put('/phases/tasks/{task}', [PhaseController::class, 'updateTask'])->name('phases.tasks.update');
    Route::delete('/phases/tasks/{task}', [PhaseController::class, 'deleteTask'])->name('tasks.destroy');
    Route::put('/phases/tasks/{task}/deliverables', [PhaseController::class, 'updateDeliverables'])->name('phases.updateDeliverables');
    
    // Project Phase Status Updates
    Route::post('/phases/{phaseId}/update-status', [\App\Http\Controllers\projects\PhaseStatusController::class, 'updateStatus'])->name('phases.update-status');
    Route::post('/phases/{phaseId}/update-status-simple', [\App\Http\Controllers\projects\PhaseStatusController::class, 'updateStatusSimple'])->name('phases.update-status-simple');
    Route::get('/phases/{phaseId}/status/{status}', [\App\Http\Controllers\projects\PhaseStatusController::class, 'updateStatusDirect'])->name('phases.update-status-direct');
    Route::post('/tasks/{task}/deliverables', [DeliverableController::class, 'store'])->name('tasks.deliverables.store');
    Route::post('/phases/{phase}/attachments', [PhaseController::class, 'storeAttachment'])->name('phases.storeAttachment');
    Route::delete('/attachments/{id}', [PhaseController::class, 'deleteAttachment'])->name('attachments.delete');

    Route::resource('clients', ClientController::class);

    Route::prefix('projects/{project}')->middleware(['role:pm|po|super-admin'])->group(function () {
        // Site Survey Routes
        Route::get('site-survey', [SiteSurveyController::class, 'create'])->name('projects.site-survey.create');
        Route::post('site-survey', [SiteSurveyController::class, 'store'])->name('projects.site-survey.store');
        
        // Print and Download routes must come before the {siteSurvey} parameter routes
        Route::get('site-survey/print', [SiteSurveyController::class, 'printSiteSurvey'])->name('projects.site-survey.print');
        Route::get('site-survey/download', [SiteSurveyController::class, 'downloadSiteSurvey'])->name('projects.site-survey.download');
        
        // Parameterized routes come last
        Route::get('site-survey/{siteSurvey}', [SiteSurveyController::class, 'show'])->name('projects.site-survey.show');
        Route::get('site-survey/{siteSurvey}/edit', [SiteSurveyController::class, 'edit'])->name('projects.site-survey.edit');
        Route::put('site-survey/{siteSurvey}', [SiteSurveyController::class, 'update'])->name('projects.site-survey.update');
        Route::delete('site-survey/{siteSurvey}', [SiteSurveyController::class, 'destroy'])->name('projects.site-survey.destroy');

        // Project Files Routes
        Route::get('quotation', [ProjectFileController::class, 'showQuotation'])->name('projects.quotation.index');
        Route::get('files', [ProjectFileController::class, 'index'])->name('projects.files.index');
        Route::get('files/mockups', [ProjectFileController::class, 'showMockups'])->name('projects.files.mockups');
        Route::post('files/design-assets', [ProjectFileController::class, 'storeDesignAsset'])->name('projects.files.design-assets.store');
        Route::put('files/design-assets/{design_asset}', [ProjectFileController::class, 'updateDesignAsset'])->name('projects.files.design-assets.update');
        Route::delete('files/design-assets/{design_asset}', [ProjectFileController::class, 'destroyDesignAsset'])->name('projects.files.design-assets.destroy');
        Route::get('files/download-template/{template}', [ProjectFileController::class, 'downloadTemplate'])->name('projects.files.download-template');
        Route::get('files/print-template/{template}', [ProjectFileController::class, 'printTemplate'])->name('projects.files.print-template');


        // Setup & Execution
        Route::get('setup', [SetupController::class, 'index'])->name('setup');
        Route::get('files/setup', [SetupController::class, 'index'])->name('projects.files.setup');



        Route::get('files/client-engagement', [ProjectFileController::class, 'showClientEngagement'])->name('projects.files.client-engagement');
        Route::get('files/design-concept', [ProjectFileController::class, 'showDesignConcept'])->name('projects.files.design-concept');
                // Logistics Routes
        Route::prefix('logistics')->name('projects.logistics.')->group(function () {
            Route::get('/', [\App\Http\Controllers\projects\LogisticsController::class, 'index'])->name('index');
            Route::get('/loading-sheet', [\App\Http\Controllers\projects\LogisticsController::class, 'showLoadingSheet'])->name('loading-sheet');
            Route::get('/booking-sheet', [\App\Http\Controllers\projects\LogisticsController::class, 'showBookingSheet'])->name('booking-sheet');
        });

        // Material List Routes
        Route::prefix('material-list')->name('projects.material-list.')->group(function () {
            // List all material lists
            Route::get('/', [MaterialListController::class, 'index'])->name('index');
            
            // Create new material list
            Route::get('/create', [MaterialListController::class, 'create'])->name('create');
            Route::post('/', [MaterialListController::class, 'store'])->name('store');
            
            // Show specific material list - must come before other {materialList} routes
            Route::get('/{materialList}/show', [MaterialListController::class, 'show'])
                ->name('show');
                
            // Download material list as PDF
            Route::get('/{materialList}/download', [MaterialListController::class, 'downloadPdf'])
                ->name('download');
                
            // Print material list (view in browser)
            Route::get('/{materialList}/print', [MaterialListController::class, 'printPdf'])
                ->name('print');
            
            // Edit material list
            Route::get('/{materialList}/edit', [MaterialListController::class, 'edit'])
                ->name('edit');
                
            // Update/delete material list
            Route::put('/{materialList}', [MaterialListController::class, 'update'])
                ->name('update')
                ->where('materialList', '[0-9]+');
                
            Route::delete('/{materialList}', [MaterialListController::class, 'destroy'])
                ->name('destroy')
                ->where('materialList', '[0-9]+');
                
            // Additional actions
            Route::post('/{materialList}/approve', [MaterialListController::class, 'approve'])
                ->name('approve')
                ->where('materialList', '[0-9]+');
                
            Route::get('/{materialList}/export/{format?}', [MaterialListController::class, 'export'])
                ->name('export')
                ->where('materialList', '[0-9]+');
        });
        

 

        // Booking Orders Routes
        Route::get('logistics/booking-order', [BookingOrderController::class, 'index'])->name('projects.logistics.booking-orders.index');
        Route::get('logistics/booking-order/create', [BookingOrderController::class, 'create'])->name('projects.logistics.booking-orders.create');
        Route::post('logistics/booking-order', [BookingOrderController::class, 'store'])->name('projects.logistics.booking-orders.store');
        Route::get('logistics/booking-order/{bookingOrder}/edit', [BookingOrderController::class, 'edit'])->name('projects.booking-order.edit');
        Route::put('logistics/booking-order/{bookingOrder}', [BookingOrderController::class, 'update'])->name('projects.booking-order.update');
        Route::delete('logistics/booking-order/{bookingOrder}', [BookingOrderController::class, 'destroy'])->name('projects.booking-order.destroy');
        Route::get('booking-order-download', [BookingOrderController::class, 'downloadBookingOrder'])->name('projects.booking-order.download');
        Route::get('booking-order-print', [BookingOrderController::class, 'printBookingOrder'])->name('projects.booking-order.print');

        // Show inquiry log for a specific project 
        Route::get('enquiry-log/{enquiryLog}/edit', [EnquiryLogController::class, 'edit'])->name('projects.enquiry-log.edit');
        Route::get('enquiry-log', [EnquiryLogController::class, 'show'])->name('projects.enquiry-log.show');
        Route::get('enquiry-log/create', [EnquiryLogController::class, 'create'])->name('projects.enquiry-log.create');
        Route::post('enquiry-log', [EnquiryLogController::class, 'store'])->name('projects.enquiry-log.store');
        Route::put('enquiry-log/{enquiryLog}', [EnquiryLogController::class, 'update'])->name('projects.enquiry-log.update');
        Route::delete('enquiry-log/{enquiryLog}', [EnquiryLogController::class, 'destroy'])->name('projects.enquiry-log.destroy');
        Route::get('enquiry-log/download', [EnquiryLogController::class, 'downloadEnquiryLog'])->name('projects.enquiry-log.download');
        Route::get('enquiry-log/print', [EnquiryLogController::class, 'printEnquiryLog'])->name('projects.enquiry-log.print');


        // Quotes Routes
        Route::prefix('quotes')->name('quotes.')->group(function () {
            Route::get('/', [QuoteController::class, 'index'])->name('index');
            Route::get('/create', [QuoteController::class, 'create'])->name('create');
            Route::post('/', [QuoteController::class, 'store'])->name('store');
            Route::get('/{quote}', [QuoteController::class, 'show'])->name('show');
            Route::get('/{quote}/edit', [QuoteController::class, 'edit'])->name('edit');
            Route::put('/{quote}', [QuoteController::class, 'update'])->name('update');
            Route::delete('/{quote}', [QuoteController::class, 'destroy'])->name('destroy');
            Route::get('/{quote}/download', [QuoteController::class, 'downloadQuote'])->name('download');
            Route::get('/{quote}/print', [QuoteController::class, 'printQuote'])->name('print');
        });

        Route::get('budgets', [ProjectBudgetController::class, 'index'])->name('budget.index');
        Route::get('budgets/create', [ProjectBudgetController::class, 'create'])->name('budget.create');
        Route::post('budgets', [ProjectBudgetController::class, 'store'])->name('budget.store');
        Route::get('budgets/{budget}', [ProjectBudgetController::class, 'show'])->name('budget.show');
        
     
        Route::get('budgets/{budget}/edit', [ProjectBudgetController::class, 'edit'])->name('budget.edit');
        Route::put('budgets/{budget}', [ProjectBudgetController::class, 'update'])->name('budget.update');
        Route::delete('budgets/{budget}', [ProjectBudgetController::class, 'destroy'])->name('budget.destroy');
        
        Route::get('budgets/{budget}/export', [\App\Http\Controllers\Projects\ProjectBudgetController::class, 'export'])->name('budget.export');
        
        Route::post('budgets/{budget}/approve', [\App\Http\Controllers\Projects\ProjectBudgetController::class, 'approve'])->name('budget.approve');
        
//francis

        // Production Routes
        Route::prefix('production')->name('projects.production.')->group(function () {
            // Main production dashboard
            Route::get('/', [\App\Http\Controllers\projects\ProductionController::class, 'index'])->name('index');
            
            // Job Brief Routes
            Route::get('job-brief', [\App\Http\Controllers\projects\ProductionController::class, 'showJobBrief'])
                ->name('job-brief');
            Route::post('job-brief', [\App\Http\Controllers\projects\ProductionController::class, 'storeJobBrief'])
                ->name('job-brief.store');
            Route::get('job-brief/{production}/edit', [\App\Http\Controllers\projects\ProductionController::class, 'editJobBrief'])
                ->name('job-brief.edit');
            Route::put('job-brief/{production}', [\App\Http\Controllers\projects\ProductionController::class, 'updateJobBrief'])
                ->name('job-brief.update');
            
            // Status Update Route
            Route::post('status', [\App\Http\Controllers\projects\ProductionController::class, 'updateStatus'])
                ->name('status.update');
            
            // Production File Routes
            Route::get('files', [\App\Http\Controllers\projects\ProductionController::class, 'showFiles'])->name('files');
            Route::put('files/{production}', [\App\Http\Controllers\projects\ProductionController::class, 'updateFiles'])->name('files.update');
            Route::delete('files/{production}', [\App\Http\Controllers\projects\ProductionController::class, 'destroyFiles'])->name('files.destroy');
            Route::get('files/download', [\App\Http\Controllers\projects\ProductionController::class, 'download'])->name('download');
            Route::get('files/print', [\App\Http\Controllers\projects\ProductionController::class, 'print'])->name('print');
            
            // Delete Production Record
            Route::delete('{production}', [\App\Http\Controllers\projects\ProductionController::class, 'destroy'])->name('destroy');
        });


        // Archival Routes
        Route::get('files/archival', [ProjectFileController::class, 'showArchival'])->name('projects.files.archival');
        Route::post('files/archival', [ProjectFileController::class, 'storeArchival'])->name('projects.files.archival.store');
        Route::put('files/archival/{archivalId}', [ProjectFileController::class, 'updateArchival'])->name('projects.files.archival.update');
        Route::delete('files/archival/{archivalId}', [ProjectFileController::class, 'destroyArchival'])->name('projects.files.archival.destroy');
        
        Route::get('files/download-template/{template}', [ProjectFileController::class, 'downloadTemplate'])->name('projects.files.download-template');
        Route::get('files/print-template/{template}', [ProjectFileController::class, 'printTemplate'])->name('projects.files.print-template');



    });
    ///end of francis
});

// API Routes
Route::get('/api/inventory/items', [\App\Http\Controllers\API\InventoryController::class, 'index'])
    ->middleware('auth')
    ->name('api.inventory.items');

// Authenticated User Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

use App\Models\Inventory;
use Illuminate\Http\Request;

Route::get('/api/search-inventory', function (Request $request) {
    $q = $request->input('q');

    return Inventory::search($q)
        ->take(10)
        ->get()
        ->pluck('item_name')
        ->unique()
        ->values();
});



Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

require __DIR__ . '/auth.php';
