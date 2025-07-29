<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING PHASE STATUS FIX ===\n\n";

// Get first project
$project = App\Models\Project::first();
if (!$project) {
    echo "❌ No projects found\n";
    exit;
}

echo "Testing Project: {$project->name} (ID: {$project->id})\n\n";

// Check current phase statuses
echo "BEFORE UPDATE:\n";
$phases = $project->phases()->whereIn('name', ['Project Material List', 'Budget & Quotation'])->get();
foreach ($phases as $phase) {
    echo "- {$phase->name}: '{$phase->status}'\n";
}

// Check data counts
$materialLists = $project->materialLists()->count();
$budgets = App\Models\ProjectBudget::where('project_id', $project->id)->count();
$quotes = $project->quotes()->count();

echo "\nDATA COUNTS:\n";
echo "Material Lists: {$materialLists}\n";
echo "Budgets: {$budgets}\n";
echo "Quotes: {$quotes}\n";

// Check if converted from enquiry
$enquirySource = $project->enquirySource;
if ($enquirySource) {
    $enquiryMaterialLists = App\Models\MaterialList::where('enquiry_id', $enquirySource->id)->count();
    $enquiryBudgets = App\Models\ProjectBudget::where('enquiry_id', $enquirySource->id)->count();
    $enquiryQuotes = App\Models\Quote::where('enquiry_id', $enquirySource->id)->count();
    
    echo "Enquiry Material Lists: {$enquiryMaterialLists}\n";
    echo "Enquiry Budgets: {$enquiryBudgets}\n";
    echo "Enquiry Quotes: {$enquiryQuotes}\n";
    
    $totalMaterialLists = $materialLists + $enquiryMaterialLists;
    $totalBudgets = $budgets + $enquiryBudgets;
    $totalQuotes = $quotes + $enquiryQuotes;
} else {
    $totalMaterialLists = $materialLists;
    $totalBudgets = $budgets;
    $totalQuotes = $quotes;
}

echo "TOTAL COMBINED:\n";
echo "Total Material Lists: {$totalMaterialLists}\n";
echo "Total Budgets: {$totalBudgets}\n";
echo "Total Quotes: {$totalQuotes}\n\n";

// Clear logs
file_put_contents(storage_path('logs/laravel.log'), '');

// Trigger the update by calling the controller
echo "TRIGGERING UPDATE...\n";
$controller = new App\Http\Controllers\projects\ProjectFileController();
$response = $controller->index($project);

// Check logs
$logContent = file_get_contents(storage_path('logs/laravel.log'));
if (!empty($logContent)) {
    echo "LOG ENTRIES:\n";
    $lines = explode("\n", $logContent);
    foreach ($lines as $line) {
        if (strpos($line, 'Phase status calculation') !== false || 
            strpos($line, 'Phase status updated') !== false) {
            // Extract just the JSON part
            if (preg_match('/\{.*\}/', $line, $matches)) {
                $data = json_decode($matches[0], true);
                if ($data && isset($data['phase_name'])) {
                    if (in_array($data['phase_name'], ['Project Material List', 'Budget & Quotation'])) {
                        echo "- {$data['phase_name']}: {$data['completed_items']}/{$data['total_items']} = {$data['completion_percentage']}% -> {$data['new_status']}\n";
                    }
                }
            }
        }
    }
}

// Check final statuses
echo "\nAFTER UPDATE:\n";
$project->refresh();
$updatedPhases = $project->phases()->whereIn('name', ['Project Material List', 'Budget & Quotation'])->get();
foreach ($updatedPhases as $phase) {
    echo "- {$phase->name}: '{$phase->status}'\n";
}

echo "\n=== TEST COMPLETED ===\n";