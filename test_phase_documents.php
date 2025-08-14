<?php

// Simple test script to verify phase document functionality
require_once 'vendor/autoload.php';

use App\Models\Project;
use App\Models\ProjectPhase;
use App\Models\PhaseDocument;

echo "Testing Phase Document Functionality\n";
echo "====================================\n\n";

// Test 1: Check if models exist and are properly configured
echo "1. Testing Model Relationships...\n";

try {
    // Check if we can create a test project
    $project = Project::first();
    if ($project) {
        echo "✓ Project model accessible\n";
        
        // Check phases
        $phases = $project->phases;
        echo "✓ Project has " . $phases->count() . " phases\n";
        
        // Find Design & Concept Development phase
        $designPhase = $phases->where('name', 'Design & Concept Development')->first();
        if ($designPhase) {
            echo "✓ Design & Concept Development phase found\n";
            
            // Check documents relationship
            $documents = $designPhase->activeDocuments;
            echo "✓ Phase has " . $documents->count() . " active documents\n";
        } else {
            echo "⚠ Design & Concept Development phase not found\n";
        }
    } else {
        echo "⚠ No projects found in database\n";
    }
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n2. Testing File Type Detection...\n";

// Test file type detection
$testFiles = [
    'design.pdf' => 'pdf',
    'mockup.jpg' => 'image',
    'concept.psd' => 'design',
    'requirements.docx' => 'document',
    'budget.xlsx' => 'spreadsheet',
    'presentation.pptx' => 'presentation',
    'drawing.dwg' => 'cad',
    'archive.zip' => 'archive',
    'video.mp4' => 'video',
    'audio.mp3' => 'audio',
    'unknown.xyz' => 'other',
];

foreach ($testFiles as $filename => $expectedType) {
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    
    // Simulate the document type detection logic
    $types = [
        'jpg' => 'image', 'jpeg' => 'image', 'png' => 'image', 'gif' => 'image', 'bmp' => 'image', 'svg' => 'image', 'webp' => 'image',
        'pdf' => 'pdf', 'doc' => 'document', 'docx' => 'document', 'txt' => 'document', 'rtf' => 'document',
        'xls' => 'spreadsheet', 'xlsx' => 'spreadsheet', 'csv' => 'spreadsheet',
        'ppt' => 'presentation', 'pptx' => 'presentation',
        'dwg' => 'cad', 'dxf' => 'cad', 'ai' => 'design', 'psd' => 'design', 'sketch' => 'design',
        'zip' => 'archive', 'rar' => 'archive', '7z' => 'archive', 'tar' => 'archive',
        'mp4' => 'video', 'avi' => 'video', 'mov' => 'video', 'wmv' => 'video', 'flv' => 'video',
        'mp3' => 'audio', 'wav' => 'audio', 'flac' => 'audio', 'aac' => 'audio',
    ];
    
    $detectedType = $types[strtolower($extension)] ?? 'other';
    
    if ($detectedType === $expectedType) {
        echo "✓ $filename -> $detectedType\n";
    } else {
        echo "✗ $filename -> Expected: $expectedType, Got: $detectedType\n";
    }
}

echo "\n3. Testing File Size Formatting...\n";

$testSizes = [
    1024 => '1 KB',
    1048576 => '1 MB',
    1073741824 => '1 GB',
    500 => '500 B',
    1536 => '1.5 KB',
];

foreach ($testSizes as $bytes => $expected) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    
    for ($i = 0; $bytes > 1024; $i++) {
        $bytes /= 1024;
    }
    
    $formatted = round($bytes, 2) . ' ' . $units[$i];
    
    if (strpos($expected, $formatted) !== false || strpos($formatted, rtrim($expected, ' B')) !== false) {
        echo "✓ Size formatting correct\n";
    } else {
        echo "✗ Expected: $expected, Got: $formatted\n";
    }
}

echo "\n4. Testing Route Definitions...\n";

// Check if routes are properly defined
$routes = [
    'projects.phases.documents.index',
    'projects.phases.documents.store',
    'projects.phases.documents.download',
    'projects.phases.documents.destroy',
    'projects.phases.documents.bulk-download',
];

foreach ($routes as $routeName) {
    try {
        $route = route($routeName, [1, 1, 1]); // Test with dummy IDs
        echo "✓ Route '$routeName' is defined\n";
    } catch (Exception $e) {
        echo "✗ Route '$routeName' not found\n";
    }
}

echo "\nTest completed!\n";
echo "================\n\n";

echo "Next Steps:\n";
echo "1. Run 'php artisan migrate' to create the phase_documents table\n";
echo "2. Ensure storage/app/public/phase-documents directory is writable\n";
echo "3. Test file upload functionality through the web interface\n";
echo "4. Verify user permissions for different roles (PO, PM, Design)\n";