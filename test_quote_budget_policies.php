<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Project;
use App\Models\Quote;
use App\Policies\QuotePolicy;
use App\Policies\ProjectPolicy;

echo "Testing Quote and Budget Policies\n";
echo "=================================\n\n";

// Test roles that should be able to create quotes and manage budgets
$testRoles = ['super-admin', 'admin', 'pm', 'po'];

foreach ($testRoles as $role) {
    echo "Testing role: {$role}\n";
    
    // Create a mock user with the role
    $user = new User();
    $user->id = 1;
    
    // Mock the hasAnyRole method
    $user->hasAnyRole = function($roles) use ($role) {
        return in_array($role, (array)$roles);
    };
    
    $user->hasRole = function($checkRole) use ($role) {
        return $role === $checkRole;
    };
    
    // Test quote creation
    $quotePolicy = new QuotePolicy();
    $canCreateQuote = $quotePolicy->create($user);
    echo "  - Can create quotes: " . ($canCreateQuote ? 'YES' : 'NO') . "\n";
    
    // Test budget management
    $project = new Project();
    $project->project_officer_id = 1; // Same as user ID for PO test
    
    $projectPolicy = new ProjectPolicy();
    $canManageBudget = $projectPolicy->manageBudget($user, $project);
    echo "  - Can manage budget: " . ($canManageBudget ? 'YES' : 'NO') . "\n";
    
    echo "\n";
}

echo "Policy Test Summary:\n";
echo "- All tested roles (super-admin, admin, pm, po) should be able to create quotes\n";
echo "- All tested roles should be able to manage budgets for their assigned/accessible projects\n";
echo "- POs have additional restrictions based on project assignment\n";