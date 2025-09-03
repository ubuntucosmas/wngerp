<?php

// Add these routes to your existing web.php file

// Excel upload routes for Projects
Route::middleware(['auth'])->group(function () {
    // Project Budget Excel Import
    Route::get('/projects/{project}/budget/create-from-excel', [ProjectBudgetController::class, 'createFromExcel'])
        ->name('budget.create-from-excel');
    Route::post('/projects/{project}/budget/import-excel', [ProjectBudgetController::class, 'importFromExcel'])
        ->name('budget.import-excel');
    
    // Enquiry Budget Excel Import
    Route::get('/enquiries/{enquiry}/budget/create-from-excel', [ProjectBudgetController::class, 'createFromExcel'])
        ->name('enquiries.budget.create-from-excel');
    Route::post('/enquiries/{enquiry}/budget/import-excel', [ProjectBudgetController::class, 'importFromExcel'])
        ->name('enquiries.budget.import-excel');
    
    // Download Excel template
    Route::get('/budget/download-template', [ProjectBudgetController::class, 'downloadTemplate'])
        ->name('budget.download-template');
});