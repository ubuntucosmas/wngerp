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
    FileUploadController, QuoteController, EnquiryController
};

Route::get('/', fn () => view('auth.login'));

Route::get('/admin/dashboard', fn () => view('admin.dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('admin.dashboard'); 

// Admin Routes
Route::middleware(['auth', 'role:admin|super-admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
    Route::get('/users', [AdminController::class, 'showUsers'])->name('users');
    Route::get('/manage-users', [AdminController::class, 'manageUsers'])->name('manage-users');
    Route::get('/users/{user}/edit', [AdminController::class, 'showEditUser'])->name('users.edit');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::post('/users', [AdminController::class, 'createUser'])->name('users.create');
    Route::get('/logs', [AdminController::class, 'viewLogs'])->name('logs');
});

// Inventory Routes
Route::middleware(['auth', 'role:store|storeadmin|procurement'])->prefix('inventory')->name('inventory.')->group(function () {
    Route::get('/dashboard', [InventoryController::class, 'dashboard'])->name('dashboard');
    Route::get('/index', [InventoryController::class, 'index'])->name('index');

    // Checkin
    Route::get('/checkin', [InventoryController::class, 'showCheckIn'])->name('checkin');
    Route::post('/checkin', [InventoryController::class, 'checkIn'])->name('checkin');

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

// Project Manager / Project Officer Routes
Route::middleware(['auth', 'role:pm|po'])->group(function () {
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
    Route::post('/tasks/{task}/deliverables', [DeliverableController::class, 'store'])->name('tasks.deliverables.store');
    Route::post('/phases/{phase}/attachments', [PhaseController::class, 'storeAttachment'])->name('phases.storeAttachment');
    Route::delete('/attachments/{id}', [PhaseController::class, 'deleteAttachment'])->name('attachments.delete');

    Route::resource('clients', ClientController::class);

    Route::prefix('projects/{project}')->middleware(['role:pm|po'])->group(function () {
        // Site Survey Routes
        Route::get('site-survey', [SiteSurveyController::class, 'create'])->name('projects.site-survey.create');
        Route::post('site-survey', [SiteSurveyController::class, 'store'])->name('projects.site-survey.store');
        Route::get('site-survey/{siteSurvey}', [SiteSurveyController::class, 'show'])->name('projects.site-survey.show');
        Route::get('site-survey/{siteSurvey}/edit', [SiteSurveyController::class, 'edit'])->name('projects.site-survey.edit');
        Route::put('site-survey/{siteSurvey}', [SiteSurveyController::class, 'update'])->name('projects.site-survey.update');
        Route::delete('site-survey/{siteSurvey}', [SiteSurveyController::class, 'destroy'])->name('projects.site-survey.destroy');

        // Project Files Routes
        Route::get('files', [ProjectFileController::class, 'index'])->name('projects.files.index');
        Route::get('files/mockups', [ProjectFileController::class, 'showMockups'])->name('projects.files.mockups');
        Route::post('files/design-assets', [ProjectFileController::class, 'storeDesignAsset'])->name('projects.files.design-assets.store');
        Route::put('files/design-assets/{design_asset}', [ProjectFileController::class, 'updateDesignAsset'])->name('projects.files.design-assets.update');
        Route::delete('files/design-assets/{design_asset}', [ProjectFileController::class, 'destroyDesignAsset'])->name('projects.files.design-assets.destroy');
        Route::get('files/download-template/{template}', [ProjectFileController::class, 'downloadTemplate'])->name('projects.files.download-template');
        Route::get('files/print-template/{template}', [ProjectFileController::class, 'printTemplate'])->name('projects.files.print-template');
        
        // Booking Orders Routes
        Route::get('booking-order', [BookingOrderController::class, 'index'])->name('projects.booking-order.index');
        Route::get('booking-order/create', [BookingOrderController::class, 'create'])->name('projects.booking-order.create');
        Route::post('booking-order', [BookingOrderController::class, 'store'])->name('projects.booking-order.store');
        Route::get('booking-order/{bookingOrder}/edit', [BookingOrderController::class, 'edit'])->name('projects.booking-order.edit');
        Route::put('booking-order/{bookingOrder}', [BookingOrderController::class, 'update'])->name('projects.booking-order.update');
        Route::delete('booking-order/{bookingOrder}', [BookingOrderController::class, 'destroy'])->name('projects.booking-order.destroy');
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


        // Quotation Routes
        Route::get('quotation', [FileUploadController::class, 'showQuotation'])->name('projects.files.quotation');
        Route::post('projects/files/quotation/upload', [FileUploadController::class, 'uploadQuotation'])->name('projects.files.quotation.upload');

        // Quotes Routes
        Route::prefix('quotes')->name('quotes.')->group(function () {
            Route::get('/', [QuoteController::class, 'index'])->name('index');
            Route::get('/create', [QuoteController::class, 'create'])->name('create');
            Route::post('/', [QuoteController::class, 'store'])->name('store');
            Route::get('/{quote}', [QuoteController::class, 'show'])->name('show');
            Route::get('/{quote}/edit', [QuoteController::class, 'edit'])->name('edit');
            Route::put('/{quote}', [QuoteController::class, 'update'])->name('update');
            Route::delete('/{quote}', [QuoteController::class, 'destroy'])->name('destroy');
        });


    });
});

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

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

require __DIR__ . '/auth.php';
