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
use App\Models\Inventory;
use Illuminate\Http\Request;

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
    
    // Load handover data
    Route::get('/data', [\App\Http\Controllers\projects\HandoverController::class, 'getHandoverData'])
        ->name('data');
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

// Close Out Report Routes
Route::prefix('projects/{project}/close-out-report')->name('projects.close-out-report.')->group(function () {
    Route::get('/', [\App\Http\Controllers\projects\CloseOutReportController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\projects\CloseOutReportController::class, 'showCreatePage'])->name('create');
    Route::post('/generate', [\App\Http\Controllers\projects\CloseOutReportController::class, 'create'])->name('generate');
    Route::get('/{report}', [\App\Http\Controllers\projects\CloseOutReportController::class, 'show'])->name('show');
    Route::get('/{report}/edit', [\App\Http\Controllers\projects\CloseOutReportController::class, 'edit'])->name('edit');
    Route::put('/{report}', [\App\Http\Controllers\projects\CloseOutReportController::class, 'update'])->name('update');
    Route::delete('/{report}', [\App\Http\Controllers\projects\CloseOutReportController::class, 'destroy'])->name('destroy');
    Route::get('/{report}/download', [\App\Http\Controllers\projects\CloseOutReportController::class, 'download'])->name('download');
    Route::get('/{report}/print', [\App\Http\Controllers\projects\CloseOutReportController::class, 'print'])->name('print');
    Route::get('/{report}/attachments/{attachment}/download', [\App\Http\Controllers\projects\CloseOutReportController::class, 'downloadAttachment'])->name('attachments.download');
    Route::delete('/{report}/attachments/{attachment}', [\App\Http\Controllers\projects\CloseOutReportController::class, 'destroyAttachment'])->name('attachments.destroy');
    Route::post('/{report}/submit', [\App\Http\Controllers\projects\CloseOutReportController::class, 'submit'])->name('submit');
    Route::post('/{report}/approve', [\App\Http\Controllers\projects\CloseOutReportController::class, 'approve'])->name('approve');
    Route::post('/{report}/reject', [\App\Http\Controllers\projects\CloseOutReportController::class, 'reject'])->name('reject');
    Route::post('/{report}/bulk-download', [\App\Http\Controllers\projects\CloseOutReportController::class, 'bulkDownload'])->name('bulk-download');
    Route::post('/{report}/export-word', [\App\Http\Controllers\projects\CloseOutReportController::class, 'exportWord'])->name('export-word');
    Route::post('/{report}/export-all-excel', [\App\Http\Controllers\projects\CloseOutReportController::class, 'exportAllExcel'])->name('export-all-excel');
    Route::post('/{report}/email', [\App\Http\Controllers\projects\CloseOutReportController::class, 'emailReport'])->name('email');
});

//endof francis














// Project Manager / Project Officer Routes
Route::middleware(['auth', 'role:pm|po|super-admin'])->group(function () {
    Route::get('/projects/overview', [ProjectController::class, 'overview'])->name('projects.overview');
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/all', [ProjectController::class, 'allProjects'])->name('projects.all');
    Route::get('/projects/trashed', [ProjectController::class, 'trashed'])->name('projects.trashed');
    Route::post('/projects/{id}/restore', [ProjectController::class, 'restore'])->name('projects.restore');
    Route::delete('/projects/{id}/force-delete', [ProjectController::class, 'forceDelete'])->name('projects.force-delete');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::put('/projects/{project}/assign', [ProjectController::class, 'assignProjectOfficer'])->name('projects.assign');
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::get('/projects/active', [ProjectController::class, 'active'])->name('projects.active');
});


    // Enquiries Routes
    Route::get('/projects/enquiry', [EnquiryController::class, 'index'])->name('enquiries.index');
    Route::get('/projects/enquiry/all', [EnquiryController::class, 'allEnquiries'])->name('enquiries.all');
    Route::get('/projects/enquiry/trashed', [EnquiryController::class, 'trashed'])->name('enquiries.trashed');
    Route::post('/projects/enquiry/{id}/restore', [EnquiryController::class, 'restore'])->name('enquiries.restore');
    Route::delete('/projects/enquiry/{id}/force-delete', [EnquiryController::class, 'forceDelete'])->name('enquiries.force-delete');
    Route::post('/projects/enquiry', [EnquiryController::class, 'store'])->name('enquiries.store');
    Route::get('/projects/enquiry/create', [EnquiryController::class, 'create'])->name('enquiries.create');

    Route::get('/projects/enquiry/{enquiry}/files', [EnquiryController::class, 'files'])->name('enquiries.files');
    Route::get('/projects/enquiry/{enquiry}/edit', [EnquiryController::class, 'edit'])->name('enquiries.edit');
    
    // Enquiry Log routes for enquiries
    Route::get('/projects/enquiry/{enquiry}/enquiry-log/create', [EnquiryController::class, 'createEnquiryLog'])->name('enquiries.enquiry-log.create');
    Route::post('/projects/enquiry/{enquiry}/enquiry-log', [EnquiryController::class, 'storeEnquiryLog'])->name('enquiries.enquiry-log.store');
    Route::get('/projects/enquiry/{enquiry}/enquiry-log/{enquiryLog}', [EnquiryController::class, 'showEnquiryLog'])->name('enquiries.enquiry-log.show');
    Route::get('/projects/enquiry/{enquiry}/enquiry-log/{enquiryLog}/edit', [EnquiryController::class, 'editEnquiryLog'])->name('enquiries.enquiry-log.edit');
    Route::put('/projects/enquiry/{enquiry}/enquiry-log/{enquiryLog}', [EnquiryController::class, 'updateEnquiryLog'])->name('enquiries.enquiry-log.update');
    Route::delete('/projects/enquiry/{enquiry}/enquiry-log/{enquiryLog}', [EnquiryController::class, 'destroyEnquiryLog'])->name('enquiries.enquiry-log.destroy');
    Route::get('/projects/enquiry/{enquiry}/enquiry-log/{enquiryLog}/download', [EnquiryController::class, 'downloadEnquiryLog'])->name('enquiries.enquiry-log.download');
    Route::get('/projects/enquiry/{enquiry}/enquiry-log/{enquiryLog}/print', [EnquiryController::class, 'printEnquiryLog'])->name('enquiries.enquiry-log.print');
    Route::get('/projects/enquiry/{enquiry}/enquiry-log/{enquiryLog}/export', [EnquiryController::class, 'exportEnquiryLog'])->name('enquiries.enquiry-log.export');
    
    // Site Survey routes for enquiries
    Route::get('/projects/enquiry/{enquiry}/site-survey/create', [EnquiryController::class, 'createSiteSurvey'])->name('enquiries.site-survey.create');
    Route::post('/projects/enquiry/{enquiry}/site-survey', [EnquiryController::class, 'storeSiteSurvey'])->name('enquiries.site-survey.store');
    Route::get('/projects/enquiry/{enquiry}/site-survey/{siteSurvey}', [EnquiryController::class, 'showSiteSurvey'])->name('enquiries.site-survey.show');
    Route::get('/projects/enquiry/{enquiry}/site-survey/{siteSurvey}/edit', [App\Http\Controllers\EnquiryController::class, 'editSiteSurvey'])->name('enquiries.site-survey.edit');
    Route::put('/projects/enquiry/{enquiry}/site-survey/{siteSurvey}', [App\Http\Controllers\EnquiryController::class, 'updateSiteSurvey'])->name('enquiries.site-survey.update');
    Route::delete('/projects/enquiry/{enquiry}/site-survey/{siteSurvey}', [App\Http\Controllers\EnquiryController::class, 'destroySiteSurvey'])->name('enquiries.site-survey.destroy');
    Route::get('/projects/enquiry/{enquiry}/site-survey/{siteSurvey}/download', [EnquiryController::class, 'downloadSiteSurvey'])->name('enquiries.site-survey.download');
    Route::get('/projects/enquiry/{enquiry}/site-survey/{siteSurvey}/print', [EnquiryController::class, 'printSiteSurvey'])->name('enquiries.site-survey.print');
    Route::get('/projects/enquiry/{enquiry}/edit', [EnquiryController::class, 'edit'])->name('enquiries.edit');
    Route::put('/projects/enquiry/{enquiry}', [EnquiryController::class, 'update'])->name('enquiries.update');
    Route::delete('/projects/enquiry/{enquiry}', [EnquiryController::class, 'destroy'])->name('enquiries.destroy');
    
    // Additional enquiry routes for phase functionality
    Route::get('/projects/enquiry/{enquiry}/files/client-engagement', [EnquiryController::class, 'showClientEngagement'])->name('enquiries.files.client-engagement');
    Route::post('/projects/enquiry/{enquiry}/files/skip-site-survey', [EnquiryController::class, 'skipSiteSurvey'])->name('enquiries.files.skip-site-survey');
    Route::post('/projects/enquiry/{enquiry}/files/unskip-site-survey', [EnquiryController::class, 'unskipSiteSurvey'])->name('enquiries.files.unskip-site-survey');
    Route::get('/projects/enquiry/{enquiry}/files/design-concept', [EnquiryController::class, 'showDesignConcept'])->name('enquiries.files.design-concept');
    Route::get('/projects/enquiry/{enquiry}/files/setup', [EnquiryController::class, 'showSetup'])->name('enquiries.files.setup');
    Route::get('/projects/enquiry/{enquiry}/files/archival', [EnquiryController::class, 'showArchival'])->name('enquiries.files.archival');
    Route::get('/projects/enquiry/{enquiry}/files/quotation', [EnquiryController::class, 'showQuotation'])->name('enquiries.files.quotation');
    
    
    // Placeholder routes for enquiry functionality
    Route::get('/projects/enquiry/{enquiry}/material-list', [EnquiryController::class, 'materialList'])->name('enquiries.material-list.index');
    Route::get('/projects/enquiry/{enquiry}/material-list/{materialList}', [EnquiryController::class, 'showMaterialList'])->name('enquiries.material-list.show');
    Route::get('/projects/enquiry/{enquiry}/logistics', [EnquiryController::class, 'logistics'])->name('enquiries.logistics.index');
    Route::get('/projects/enquiry/{enquiry}/quotation', [EnquiryController::class, 'quotation'])->name('enquiries.quotation.index');
    Route::get('/projects/enquiry/{enquiry}/handover', [EnquiryController::class, 'handover'])->name('enquiries.handover.index');
    Route::get('/projects/enquiry/{enquiry}/set-down-return', [EnquiryController::class, 'setDownReturn'])->name('enquiries.set-down-return.index');
    Route::get('/projects/enquiry/{enquiry}/production', [EnquiryController::class, 'production'])->name('enquiries.production.index');

    Route::post('/enquiries/{enquiry}/convert-to-project', [ProjectController::class, 'convertFromEnquiry'])->name('projects.convertFromEnquiry');
    Route::post('/enquiries/{enquiry}/convert', [EnquiryController::class, 'convertToProject'])->name('enquiries.convert');

    // Enquiry Budgets
    Route::prefix('enquiries/{enquiry}')->group(function () {
        Route::get('budgets', [ProjectBudgetController::class, 'index'])->name('enquiries.budget.index');
        Route::get('budgets/create', [ProjectBudgetController::class, 'create'])->name('enquiries.budget.create');
        Route::post('budgets', [ProjectBudgetController::class, 'store'])->name('enquiries.budget.store');
        Route::get('budgets/{budget}', [ProjectBudgetController::class, 'show'])->name('enquiries.budget.show');
        Route::get('budgets/{budget}/edit', [ProjectBudgetController::class, 'edit'])->name('enquiries.budget.edit');
        Route::put('budgets/{budget}', [ProjectBudgetController::class, 'update'])->name('enquiries.budget.update');
        Route::delete('budgets/{budget}', [ProjectBudgetController::class, 'destroy'])->name('enquiries.budget.destroy');
        Route::get('budgets/{budget}/export', [ProjectBudgetController::class, 'export'])->name('enquiries.budget.export');
        Route::get('budgets/{budget}/download', [ProjectBudgetController::class, 'download'])->name('enquiries.budget.download');
        Route::get('budgets/{budget}/print', [ProjectBudgetController::class, 'print'])->name('enquiries.budget.print');
        Route::post('budgets/{budget}/approve', [ProjectBudgetController::class, 'approve'])->name('enquiries.budget.approve');
    });

    // Enquiry Budgets
    Route::prefix('enquiries/{enquiry}')->group(function () {
        Route::get('budgets', [ProjectBudgetController::class, 'index'])->name('enquiries.budget.index');
        Route::get('budgets/create', [ProjectBudgetController::class, 'create'])->name('enquiries.budget.create');
        Route::post('budgets', [ProjectBudgetController::class, 'store'])->name('enquiries.budget.store');
        Route::get('budgets/{budget}', [ProjectBudgetController::class, 'show'])->name('enquiries.budget.show');
        Route::get('budgets/{budget}/edit', [ProjectBudgetController::class, 'edit'])->name('enquiries.budget.edit');
        Route::put('budgets/{budget}', [ProjectBudgetController::class, 'update'])->name('enquiries.budget.update');
        Route::delete('budgets/{budget}', [ProjectBudgetController::class, 'destroy'])->name('enquiries.budget.destroy');
        Route::get('budgets/{budget}/export', [ProjectBudgetController::class, 'export'])->name('enquiries.budget.export');
        Route::get('budgets/{budget}/download', [ProjectBudgetController::class, 'download'])->name('enquiries.budget.download');
        Route::get('budgets/{budget}/print', [ProjectBudgetController::class, 'print'])->name('enquiries.budget.print');
        Route::post('budgets/{budget}/approve', [ProjectBudgetController::class, 'approve'])->name('enquiries.budget.approve');
    });

    

    

    

    // Enquiry Quotes
    Route::prefix('enquiries/{enquiry}')->group(function () {
        Route::get('quotes', [QuoteController::class, 'index'])->name('enquiries.quotes.index');
        Route::get('quotes/create', [QuoteController::class, 'create'])->name('enquiries.quotes.create');
        Route::post('quotes', [QuoteController::class, 'store'])->name('enquiries.quotes.store');
        Route::get('quotes/{quote}', [QuoteController::class, 'show'])->name('enquiries.quotes.show');
        Route::get('quotes/{quote}/edit', [QuoteController::class, 'edit'])->name('enquiries.quotes.edit');
        Route::put('quotes/{quote}', [QuoteController::class, 'update'])->name('enquiries.quotes.update');
        Route::delete('quotes/{quote}', [QuoteController::class, 'destroy'])->name('enquiries.quotes.destroy');
        Route::get('quotes/{quote}/download', [QuoteController::class, 'downloadQuote'])->name('enquiries.quotes.download');
        Route::get('quotes/{quote}/print', [QuoteController::class, 'printQuote'])->name('enquiries.quotes.print');
        Route::get('quotes/{quote}/excel', [QuoteController::class, 'exportExcel'])->name('enquiries.quotes.excel');
    });

    // Enquiry Material List Routes
    Route::prefix('enquiries/{enquiry}/material-list')->name('enquiries.material-list.')->group(function () {
        Route::get('/', [MaterialListController::class, 'index'])->name('index');
        Route::get('/create', [MaterialListController::class, 'create'])->name('create');
        Route::post('/', [MaterialListController::class, 'store'])->name('store');
        Route::get('/{materialList}/show', [MaterialListController::class, 'show'])->name('show');
        Route::get('/{materialList}/download', [MaterialListController::class, 'downloadPdf'])->name('download');
        Route::get('/{materialList}/export-excel', [MaterialListController::class, 'exportExcel'])->name('exportExcel');
        Route::get('/{materialList}/print', [MaterialListController::class, 'printPdf'])->name('print');
        Route::get('/{materialList}/edit', [MaterialListController::class, 'edit'])->name('edit');
        Route::put('/{materialList}', [MaterialListController::class, 'update'])->name('update')->where('materialList', '[0-9]+');
        Route::delete('/{materialList}', [MaterialListController::class, 'destroy'])->name('destroy')->where('materialList', '[0-9]+');
        Route::post('/{materialList}/approve', [MaterialListController::class, 'approve'])->name('approve')->where('materialList', '[0-9]+');
        Route::get('/{materialList}/export/{format?}', [MaterialListController::class, 'export'])->name('export')->where('materialList', '[0-9]+');
    });

    // Enquiry Design Assets
    Route::post('enquiries/{enquiry}/files/design-assets', [ProjectFileController::class, 'storeDesignAsset'])->name('enquiries.files.design-assets.store');
    Route::put('enquiries/{enquiry}/files/design-assets/{design_asset}', [ProjectFileController::class, 'updateDesignAsset'])->name('enquiries.files.design-assets.update');
    Route::delete('enquiries/{enquiry}/files/design-assets/{design_asset}', [ProjectFileController::class, 'destroyDesignAsset'])->name('enquiries.files.design-assets.destroy');

    // Enquiry-specific file routes (similar to project routes but for enquiries)
    Route::prefix('projects/enquiry/{enquiry}')->middleware(['role:pm|po|super-admin'])->group(function () {
        // Enquiry Files Routes
        Route::get('files/client-engagement', [EnquiryController::class, 'showClientEngagement'])->name('enquiries.files.client-engagement');
        Route::get('files/design-concept', [EnquiryController::class, 'showDesignConcept'])->name('enquiries.files.design-concept');
        Route::get('files/setup', [SetupController::class, 'index'])->name('enquiries.files.setup');
        Route::get('files/mockups', [EnquiryController::class, 'showMockups'])->name('enquiries.files.mockups');
        Route::post('files/design-assets', [ProjectFileController::class, 'storeDesignAsset'])->name('enquiries.files.design-assets.store');
        Route::put('files/design-assets/{design_asset}', [ProjectFileController::class, 'updateDesignAsset'])->name('enquiries.files.design-assets.update');
        Route::delete('files/design-assets/{design_asset}', [ProjectFileController::class, 'destroyDesignAsset'])->name('enquiries.files.design-assets.destroy');
    });

    // Project Manager / Project Officer Routes
    Route::middleware(['auth', 'role:pm|po|super-admin'])->group(function () {
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
        Route::get('/projects/{projectId}/create-remaining-phases', [\App\Http\Controllers\projects\PhaseStatusController::class, 'createRemainingPhases'])->name('phases.create-remaining');
    Route::post('/tasks/{task}/deliverables', [DeliverableController::class, 'store'])->name('tasks.deliverables.store');
    Route::post('/phases/{phase}/attachments', [PhaseController::class, 'storeAttachment'])->name('phases.storeAttachment');
    Route::delete('/attachments/{id}', [PhaseController::class, 'deleteAttachment'])->name('attachments.delete');

    Route::resource('clients', ClientController::class);
    });

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
        Route::post('files/skip-site-survey', [ProjectFileController::class, 'skipSiteSurvey'])->name('projects.files.skip-site-survey');
        Route::post('files/unskip-site-survey', [ProjectFileController::class, 'unskipSiteSurvey'])->name('projects.files.unskip-site-survey');
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
                
            // Export material list to Excel
            Route::get('/{materialList}/export-excel', [MaterialListController::class, 'exportExcel'])
                ->name('exportExcel');
            
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

        

        // Item Templates Routes (for using templates in material lists)
        Route::prefix('templates')->name('projects.templates.')->group(function () {
            // Get templates for material list creation
            Route::get('categories-all', [\App\Http\Controllers\ItemCategoryController::class, 'getAll'])->name('categories.all');
            Route::get('templates-by-category/{categoryId}', [\App\Http\Controllers\ItemTemplateController::class, 'getTemplatesByCategory'])->name('templates.by-category');
            Route::get('templates/{itemTemplate}', [\App\Http\Controllers\ItemTemplateController::class, 'show'])->name('templates.show');
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



        // Logistics Routes
        Route::prefix('logistics')->name('projects.logistics.')->group(function () {
            Route::get('/', [\App\Http\Controllers\projects\LogisticsController::class, 'index'])->name('index');
            Route::get('/loading-sheet', [\App\Http\Controllers\projects\LoadingSheetController::class, 'index'])->name('loading-sheet');
            Route::post('/loading-sheet', [\App\Http\Controllers\projects\LoadingSheetController::class, 'store'])->name('loading-sheet.store');
            Route::get('/loading-sheet/{id}', [\App\Http\Controllers\projects\LoadingSheetController::class, 'show'])->name('loading-sheet.show');
            Route::get('/loading-sheet/print', [\App\Http\Controllers\projects\LoadingSheetController::class, 'print'])->name('loading-sheet.print');
            Route::get('/loading-sheet/download', [\App\Http\Controllers\projects\LoadingSheetController::class, 'download'])->name('loading-sheet.download');
            Route::get('/booking-sheet', [\App\Http\Controllers\projects\LogisticsController::class, 'showBookingSheet'])->name('booking-sheet');
        });

        // Enquiry log routes for projects
        Route::get('enquiry-log', [EnquiryLogController::class, 'show'])->name('projects.enquiry-log.show');
        Route::get('enquiry-log/create', [EnquiryLogController::class, 'create'])->name('projects.enquiry-log.create');
        Route::post('enquiry-log', [EnquiryLogController::class, 'store'])->name('projects.enquiry-log.store');
        Route::get('enquiry-log/{enquiryLog}/edit', [EnquiryLogController::class, 'edit'])->name('projects.enquiry-log.edit');
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
            Route::put('quotes/{quote}', [QuoteController::class, 'update'])->name('update');
            Route::delete('quotes/{quote}', [QuoteController::class, 'destroy'])->name('destroy');
            Route::get('quotes/{quote}/download', [QuoteController::class, 'downloadQuote'])->name('download');
            Route::get('quotes/{quote}/print', [QuoteController::class, 'printQuote'])->name('print');
            Route::get('quotes/{quote}/excel', [QuoteController::class, 'exportExcel'])->name('excel');
        });

        

        Route::get('budgets', [ProjectBudgetController::class, 'index'])->name('budget.index');
        Route::get('budgets/create', [ProjectBudgetController::class, 'create'])->name('budget.create');
        Route::post('budgets', [ProjectBudgetController::class, 'store'])->name('budget.store');
        Route::get('budgets/{budget}', [ProjectBudgetController::class, 'show'])->name('budget.show');
        
     
        Route::get('budgets/{budget}/edit', [ProjectBudgetController::class, 'edit'])->name('budget.edit');
        Route::put('budgets/{budget}', [ProjectBudgetController::class, 'update'])->name('budget.update');
        Route::delete('budgets/{budget}', [ProjectBudgetController::class, 'destroy'])->name('budget.destroy');
        
        Route::get('budgets/{budget}/export', [ProjectBudgetController::class, 'export'])->name('budget.export');
        Route::get('budgets/{budget}/download', [ProjectBudgetController::class, 'download'])->name('budget.download');
        Route::get('budgets/{budget}/print', [ProjectBudgetController::class, 'print'])->name('budget.print');
        Route::post('budgets/{budget}/approve', [ProjectBudgetController::class, 'approve'])->name('budget.approve');
        
        });

    

    

    

//francis
        
//francis

        // Production Routes
        Route::prefix('production/{project}')->name('projects.production.')->group(function () {
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
    ///end of francis

// API Routes
Route::get('/api/inventory/items', [\App\Http\Controllers\API\InventoryController::class, 'index'])
    ->middleware('auth')
    ->name('api.inventory.items');

Route::get('/api/inventory/particulars-items', [\App\Http\Controllers\API\InventoryController::class, 'particularsItems'])
    ->middleware('auth')
    ->name('api.inventory.particulars-items');

// Test route to verify filtering is working
Route::get('/test-inventory-filter', function() {
    $controller = new \App\Http\Controllers\API\InventoryController();
    
    echo "<h3>Testing Inventory Filtering</h3>";
    
    // Test without filter
    $request1 = new \Illuminate\Http\Request();
    $response1 = $controller->index($request1);
    $data1 = $response1->getData();
    echo "<p><strong>Without filter:</strong> " . count($data1) . " items</p>";
    
    // Test with filter
    $request2 = new \Illuminate\Http\Request(['filter' => 'material-list']);
    $response2 = $controller->index($request2);
    $data2 = $response2->getData();
    echo "<p><strong>With material-list filter:</strong> " . count($data2) . " items</p>";
    
    echo "<h4>Filtered Items:</h4>";
    foreach($data2 as $item) {
        echo "<li>" . $item->name . " (" . $item->unit_of_measure . ")</li>";
    }
    
})->middleware('auth');

// Test route for debugging
Route::get('/test-templates', function() {
    return response()->json(['message' => 'Test route working']);
})->name('test.templates');

// Debug route for production items with templates
Route::get('/debug-production-items', function() {
    $items = App\Models\ProductionItem::with('template.category')->get();
    return response()->json([
        'items' => $items->map(function($item) {
            return [
                'id' => $item->id,
                'item_name' => $item->item_name,
                'template_id' => $item->template_id,
                'template' => $item->template ? [
                    'id' => $item->template->id,
                    'name' => $item->template->name,
                    'category' => $item->template->category ? $item->template->category->name : null
                ] : null
            ];
        })
    ]);
})->name('debug.production.items');

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
    
        // Client Management Routes
    Route::prefix('clients')->name('clients.')->group(function () {
        // List all clients (GET /clients)
        Route::get('/', [ClientController::class, 'index'])->name('index');
        
        // Show single client (GET /clients/{id})
        Route::get('/{client}', [ClientController::class, 'show'])->name('show');
        
        // Show create client form (GET /clients/create)
        Route::get('/create', [ClientController::class, 'create'])->name('create');
        
        // Store new client (POST /clients)
        Route::post('/', [ClientController::class, 'store'])->name('store');
        
        // Show edit client form (GET /clients/{client}/edit)
        Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
        
        // Update client (PUT/PATCH /clients/{client})
        Route::put('/{client}', [ClientController::class, 'update'])->name('update');
        
        // Delete client (DELETE /clients/{client})
        Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy');
    });
    
    
    // Global Item Templates Management Routes
    Route::prefix('templates')->name('templates.')->group(function () {
        // Categories
        Route::resource('categories', \App\Http\Controllers\ItemCategoryController::class);
        Route::get('categories-all', [\App\Http\Controllers\ItemCategoryController::class, 'getAll'])->name('categories.all');



        
        // Templates
        Route::resource('templates', \App\Http\Controllers\ItemTemplateController::class)->parameters(['templates' => 'itemTemplate']);
        Route::get('templates-by-category/{categoryId}', [\App\Http\Controllers\ItemTemplateController::class, 'getTemplatesByCategory'])->name('templates.by-category');
        Route::get('templates-all', [\App\Http\Controllers\ItemTemplateController::class, 'getAllTemplates'])->name('templates.all');
        Route::post('templates/{itemTemplate}/duplicate', [\App\Http\Controllers\ItemTemplateController::class, 'duplicate'])->name('templates.duplicate');
    });
});

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

Route::get('/', function () {
    return redirect()->route('projects.index');
})->middleware(['auth'])->name('home');

// Phase skip/unskip routes
Route::post('/phases/{phaseId}/skip', [\App\Http\Controllers\projects\PhaseStatusController::class, 'skipPhase'])->name('phases.skip')->middleware(['auth']);
Route::post('/phases/{phaseId}/unskip', [\App\Http\Controllers\projects\PhaseStatusController::class, 'unskipPhase'])->name('phases.unskip')->middleware(['auth']);

// Quote Approval Route
Route::post('/quotes/{projectOrEnquiryId}/{quote}/approve', [QuoteController::class, 'approve'])->middleware(['auth'])->name('quotes.approve');
