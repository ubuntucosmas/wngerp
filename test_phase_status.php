<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== COMPREHENSIVE PHASE STATUS TEST ===\n\n";

// Get a project to test
$project = App\Models\Project::first();

if (!$project) {
    echo "❌ No projects found. Creating a test project...\n";
    
    // Create a test project
    $project = App\Models\Project::create([
        'project_id' => 'TEST001',
        'name' => 'Test Project for Phase Status',
        'client_name' => 'Test Client',
        'venue' => 'Test Venue',
        'start_date' => now(),
        'end_date' => now()->addDays(30),
        'project_manager_id' => 1,
        'status' => 'active'
    ]);
    
    // Create phases for the project
    $phases = config('project_process_phases');
    foreach ($phases as $phase) {
        $project->phases()->create([
            'name' => $phase['name'],
            'icon' => $phase['icon'] ?? null,
            'summary' => $phase['summary'] ?? null,
            'status' => $phase['status'],
        ]);
    }
    
    echo "✅ Test project created with ID: {$project->id}\n\n";
}

echo "🔍 Testing Project: {$project->name} (ID: {$project->id})\n\n";

// 1. Check current phase statuses
echo "1. CURRENT PHASE STATUSES:\n";
echo "==========================\n";
$phases = $project->phases()->orderBy('id')->get();
foreach ($phases as $phase) {
    echo "- {$phase->name}: '{$phase->status}'\n";
}
echo "\n";

// 2. Check data counts
echo "2. DATA COUNTS:\n";
echo "===============\n";
$materialListsCount = $project->materialLists()->count();
$budgetsCount = App\Models\ProjectBudget::where('project_id', $project->id)->count();
$quotesCount = $project->quotes()->count();

echo "Material Lists: {$materialListsCount}\n";
echo "Budgets: {$budgetsCount}\n";
echo "Quotes: {$quotesCount}\n";

// Check if converted from enquiry
$enquirySource = $project->enquirySource;
if ($enquirySource) {
    echo "\n🔄 Project converted from enquiry ID: {$enquirySource->id}\n";
    $enquiryMaterialLists = App\Models\MaterialList::where('enquiry_id', $enquirySource->id)->count();
    $enquiryBudgets = App\Models\ProjectBudget::where('enquiry_id', $enquirySource->id)->count();
    $enquiryQuotes = App\Models\Quote::where('enquiry_id', $enquirySource->id)->count();
    
    echo "Enquiry Material Lists: {$enquiryMaterialLists}\n";
    echo "Enquiry Budgets: {$enquiryBudgets}\n";
    echo "Enquiry Quotes: {$enquiryQuotes}\n";
    
    $totalMaterialLists = $materialListsCount + $enquiryMaterialLists;
    $totalBudgets = $budgetsCount + $enquiryBudgets;
    $totalQuotes = $quotesCount + $enquiryQuotes;
} else {
    $totalMaterialLists = $materialListsCount;
    $totalBudgets = $budgetsCount;
    $totalQuotes = $quotesCount;
}

echo "\nTotal Material Lists: {$totalMaterialLists}\n";
echo "Total Budgets: {$totalBudgets}\n";
echo "Total Quotes: {$totalQuotes}\n\n";

// 3. Test the completion logic
echo "3. TESTING COMPLETION LOGIC:\n";
echo "============================\n";

try {
    $controller = new App\Http\Controllers\projects\ProjectFileController();
    $reflection = new ReflectionClass($controller);
    
    // Get the private method
    $method = $reflection->getMethod('getPhaseCompletions');
    $method->setAccessible(true);
    
    // Call the method
    $completions = $method->invoke($controller, $project);
    
    // Check Material List completion
    if (isset($completions['Project Material List'])) {
        echo "✅ 'Project Material List' completion data found:\n";
        $materialData = $completions['Project Material List'];
        foreach ($materialData as $key => $item) {
            $completed = $item['completed'] ? 'YES' : 'NO';
            echo "  - {$key}: {$completed}\n";
        }
        
        // Calculate what the status should be
        $totalItems = count($materialData);
        $completedItems = 0;
        foreach ($materialData as $item) {
            if (isset($item['completed']) && $item['completed'] === true) {
                $completedItems++;
            }
        }
        $percentage = $totalItems > 0 ? ($completedItems / $totalItems) * 100 : 0;
        
        $expectedStatus = 'Not Started';
        if ($percentage >= 100) {
            $expectedStatus = 'Completed';
        } elseif ($percentage >= 25) {
            $expectedStatus = 'In Progress';
        }
        
        echo "  Calculation: {$completedItems}/{$totalItems} = {$percentage}%\n";
        echo "  Expected Status: '{$expectedStatus}'\n";
    } else {
        echo "❌ 'Project Material List' completion data NOT FOUND!\n";
    }
    
    echo "\n";
    
    // Check Budget & Quotation completion
    if (isset($completions['Budget & Quotation'])) {
        echo "✅ 'Budget & Quotation' completion data found:\n";
        $budgetData = $completions['Budget & Quotation'];
        foreach ($budgetData as $key => $item) {
            $completed = $item['completed'] ? 'YES' : 'NO';
            echo "  - {$key}: {$completed}\n";
        }
        
        // Calculate what the status should be
        $totalItems = count($budgetData);
        $completedItems = 0;
        foreach ($budgetData as $item) {
            if (isset($item['completed']) && $item['completed'] === true) {
                $completedItems++;
            }
        }
        $percentage = $totalItems > 0 ? ($completedItems / $totalItems) * 100 : 0;
        
        $expectedStatus = 'Not Started';
        if ($percentage >= 100) {
            $expectedStatus = 'Completed';
        } elseif ($percentage >= 25) {
            $expectedStatus = 'In Progress';
        }
        
        echo "  Calculation: {$completedItems}/{$totalItems} = {$percentage}%\n";
        echo "  Expected Status: '{$expectedStatus}'\n";
    } else {
        echo "❌ 'Budget & Quotation' completion data NOT FOUND!\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error testing completion logic: " . $e->getMessage() . "\n";
}

echo "\n";

// 4. Test the automatic update system
echo "4. TESTING AUTOMATIC UPDATE SYSTEM:\n";
echo "===================================\n";

try {
    // Clear the log file first
    file_put_contents(storage_path('logs/laravel.log'), '');
    
    echo "Calling ProjectFileController index method...\n";
    
    // Simulate visiting the project files page
    $request = new Illuminate\Http\Request();
    $response = $controller->index($project);
    
    echo "✅ ProjectFileController index method completed\n";
    
    // Check the logs
    $logContent = file_get_contents(storage_path('logs/laravel.log'));
    if (empty($logContent)) {
        echo "⚠️  No logs generated\n";
    } else {
        echo "📋 Log entries generated:\n";
        $lines = explode("\n", $logContent);
        foreach ($lines as $line) {
            if (strpos($line, 'Phase status calculation') !== false || 
                strpos($line, 'Phase status updated') !== false ||
                strpos($line, 'Phase not found') !== false) {
                echo "  " . trim($line) . "\n";
            }
        }
    }
    
    // Check if phases were updated
    $project->refresh();
    echo "\n📊 Phase statuses after automatic update:\n";
    $updatedPhases = $project->phases()->orderBy('id')->get();
    foreach ($updatedPhases as $phase) {
        echo "- {$phase->name}: '{$phase->status}'\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error testing automatic update: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n";

// 5. Create test data and retest
echo "5. CREATING TEST DATA AND RETESTING:\n";
echo "====================================\n";

try {
    // Create a test material list
    echo "Creating test material list...\n";
    $materialList = $project->materialLists()->create([
        'start_date' => now(),
        'end_date' => now()->addDays(7),
        'approved_by' => 'Test User',
        'approved_departments' => 'Test Department'
    ]);
    echo "✅ Material list created with ID: {$materialList->id}\n";
    
    // Create a test budget
    echo "Creating test budget...\n";
    $budget = App\Models\ProjectBudget::create([
        'project_id' => $project->id,
        'start_date' => now(),
        'end_date' => now()->addDays(7),
        'budget_total' => 10000.00,
        'invoice' => 11600.00,
        'profit' => 1600.00,
        'approved_by' => 'Test User',
        'approved_departments' => 'Test Department',
        'status' => 'draft'
    ]);
    echo "✅ Budget created with ID: {$budget->id}\n";
    
    // Clear logs and test again
    file_put_contents(storage_path('logs/laravel.log'), '');
    
    echo "\nRetesting with data...\n";
    $response = $controller->index($project);
    
    // Check logs again
    $logContent = file_get_contents(storage_path('logs/laravel.log'));
    if (!empty($logContent)) {
        echo "📋 New log entries:\n";
        $lines = explode("\n", $logContent);
        foreach ($lines as $line) {
            if (strpos($line, 'Phase status calculation') !== false || 
                strpos($line, 'Phase status updated') !== false ||
                strpos($line, 'Phase not found') !== false) {
                echo "  " . trim($line) . "\n";
            }
        }
    }
    
    // Check final phase statuses
    $project->refresh();
    echo "\n📊 Final phase statuses:\n";
    $finalPhases = $project->phases()->orderBy('id')->get();
    foreach ($finalPhases as $phase) {
        echo "- {$phase->name}: '{$phase->status}'\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error creating test data: " . $e->getMessage() . "\n";
}

echo "\n=== TEST COMPLETED ===\n";