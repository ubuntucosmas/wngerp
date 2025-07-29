<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== PHASE STATUS DEBUG ANALYSIS ===\n\n";

// Get a project to test
$project = App\Models\Project::first();

if (!$project) {
    echo "❌ No projects found\n";
    exit;
}

echo "🔍 Testing Project: {$project->name} (ID: {$project->id})\n";
echo "📅 Created: {$project->created_at}\n\n";

// 1. Check project phases
echo "1. PROJECT PHASES:\n";
echo "==================\n";
$phases = $project->phases;
if ($phases->count() === 0) {
    echo "❌ No phases found for this project!\n\n";
} else {
    foreach ($phases as $phase) {
        echo "✓ {$phase->name}: '{$phase->status}'\n";
    }
    echo "\n";
}

// 2. Check material lists
echo "2. MATERIAL LISTS:\n";
echo "==================\n";
$materialLists = $project->materialLists;
echo "Direct project material lists: {$materialLists->count()}\n";

// Check if converted from enquiry
$enquirySource = $project->enquirySource;
if ($enquirySource) {
    echo "🔄 Project converted from enquiry ID: {$enquirySource->id}\n";
    $enquiryMaterialLists = App\Models\MaterialList::where('enquiry_id', $enquirySource->id)->get();
    echo "Enquiry source material lists: {$enquiryMaterialLists->count()}\n";
    $totalMaterialLists = $materialLists->count() + $enquiryMaterialLists->count();
} else {
    echo "📝 Regular project (not converted from enquiry)\n";
    $totalMaterialLists = $materialLists->count();
}
echo "Total material lists: {$totalMaterialLists}\n\n";

// 3. Check budgets
echo "3. BUDGETS:\n";
echo "===========\n";
$projectBudgets = App\Models\ProjectBudget::where('project_id', $project->id)->get();
echo "Direct project budgets: {$projectBudgets->count()}\n";

if ($enquirySource) {
    $enquiryBudgets = App\Models\ProjectBudget::where('enquiry_id', $enquirySource->id)->get();
    echo "Enquiry source budgets: {$enquiryBudgets->count()}\n";
    $totalBudgets = $projectBudgets->count() + $enquiryBudgets->count();
} else {
    $totalBudgets = $projectBudgets->count();
}
echo "Total budgets: {$totalBudgets}\n\n";

// 4. Check quotes
echo "4. QUOTES:\n";
echo "==========\n";
$projectQuotes = $project->quotes;
echo "Direct project quotes: {$projectQuotes->count()}\n";

if ($enquirySource) {
    $enquiryQuotes = App\Models\Quote::where('enquiry_id', $enquirySource->id)->get();
    echo "Enquiry source quotes: {$enquiryQuotes->count()}\n";
    $totalQuotes = $projectQuotes->count() + $enquiryQuotes->count();
} else {
    $totalQuotes = $projectQuotes->count();
}
echo "Total quotes: {$totalQuotes}\n\n";

// 5. Test completion logic manually
echo "5. COMPLETION LOGIC TEST:\n";
echo "=========================\n";

// Material List Phase
$materialListPhase = $project->phases()->where('name', 'Project Material List')->first();
if ($materialListPhase) {
    echo "✓ 'Project Material List' phase found\n";
    echo "  Current status: '{$materialListPhase->status}'\n";
    echo "  Should be: " . ($totalMaterialLists > 0 ? "'Completed'" : "'Not Started'") . "\n";
    
    if ($totalMaterialLists > 0 && $materialListPhase->status !== 'Completed') {
        echo "  ❌ STATUS MISMATCH! Has {$totalMaterialLists} material lists but status is '{$materialListPhase->status}'\n";
    } elseif ($totalMaterialLists === 0 && $materialListPhase->status !== 'Not Started') {
        echo "  ❌ STATUS MISMATCH! Has no material lists but status is '{$materialListPhase->status}'\n";
    } else {
        echo "  ✅ Status is correct\n";
    }
} else {
    echo "❌ 'Project Material List' phase NOT FOUND!\n";
}

echo "\n";

// Budget & Quotation Phase
$budgetQuotationPhase = $project->phases()->where('name', 'Budget & Quotation')->first();
if ($budgetQuotationPhase) {
    echo "✓ 'Budget & Quotation' phase found\n";
    echo "  Current status: '{$budgetQuotationPhase->status}'\n";
    
    $expectedStatus = 'Not Started';
    if ($totalBudgets > 0 && $totalQuotes > 0) {
        $expectedStatus = 'Completed';
    } elseif ($totalBudgets > 0 || $totalQuotes > 0) {
        $expectedStatus = 'In Progress';
    }
    
    echo "  Should be: '{$expectedStatus}' (Budgets: {$totalBudgets}, Quotes: {$totalQuotes})\n";
    
    if ($budgetQuotationPhase->status !== $expectedStatus) {
        echo "  ❌ STATUS MISMATCH!\n";
    } else {
        echo "  ✅ Status is correct\n";
    }
} else {
    echo "❌ 'Budget & Quotation' phase NOT FOUND!\n";
}

echo "\n";

// 6. Test the automatic update system
echo "6. TESTING AUTOMATIC UPDATE SYSTEM:\n";
echo "====================================\n";

try {
    // Simulate the ProjectFileController logic
    $controller = new App\Http\Controllers\projects\ProjectFileController();
    
    // Use reflection to access private method
    $reflection = new ReflectionClass($controller);
    $getCompletionsMethod = $reflection->getMethod('getPhaseCompletions');
    $getCompletionsMethod->setAccessible(true);
    
    $updateStatusesMethod = $reflection->getMethod('updatePhaseStatuses');
    $updateStatusesMethod->setAccessible(true);
    
    echo "Getting phase completions...\n";
    $completions = $getCompletionsMethod->invoke($controller, $project);
    
    // Check Material List completion data
    if (isset($completions['Project Material List'])) {
        $materialData = $completions['Project Material List'];
        echo "\nMaterial List completion data:\n";
        foreach ($materialData as $key => $item) {
            $completed = $item['completed'] ? 'YES' : 'NO';
            echo "  - {$key}: {$completed}\n";
        }
        
        $totalItems = count($materialData);
        $completedItems = 0;
        foreach ($materialData as $item) {
            if ($item['completed']) $completedItems++;
        }
        $percentage = $totalItems > 0 ? ($completedItems / $totalItems) * 100 : 0;
        echo "  Calculation: {$completedItems}/{$totalItems} = {$percentage}%\n";
    }
    
    // Check Budget & Quotation completion data
    if (isset($completions['Budget & Quotation'])) {
        $budgetData = $completions['Budget & Quotation'];
        echo "\nBudget & Quotation completion data:\n";
        foreach ($budgetData as $key => $item) {
            $completed = $item['completed'] ? 'YES' : 'NO';
            echo "  - {$key}: {$completed}\n";
        }
        
        $totalItems = count($budgetData);
        $completedItems = 0;
        foreach ($budgetData as $item) {
            if ($item['completed']) $completedItems++;
        }
        $percentage = $totalItems > 0 ? ($completedItems / $totalItems) * 100 : 0;
        echo "  Calculation: {$completedItems}/{$totalItems} = {$percentage}%\n";
    }
    
    echo "\nRunning automatic phase status update...\n";
    $updateStatusesMethod->invoke($controller, $project, $completions);
    
    echo "✅ Automatic update completed\n";
    
    // Check if statuses changed
    $project->refresh();
    $updatedMaterialPhase = $project->phases()->where('name', 'Project Material List')->first();
    $updatedBudgetPhase = $project->phases()->where('name', 'Budget & Quotation')->first();
    
    if ($updatedMaterialPhase) {
        echo "Material List phase after update: '{$updatedMaterialPhase->status}'\n";
    }
    if ($updatedBudgetPhase) {
        echo "Budget & Quotation phase after update: '{$updatedBudgetPhase->status}'\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error testing automatic update: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== END DEBUG ANALYSIS ===\n";