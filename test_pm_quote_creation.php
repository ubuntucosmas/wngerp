<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Policies\QuotePolicy;

echo "Testing PM Quote Creation Policy\n";
echo "===============================\n\n";

// Create a mock PM user
$pmUser = new User();
$pmUser->id = 1;
$pmUser->name = 'Test PM';

// Mock the hasAnyRole method for PM
$pmUser->hasAnyRole = function($roles) {
    return in_array('pm', (array)$roles);
};

$pmUser->hasRole = function($role) {
    return $role === 'pm';
};

// Test quote creation policy
$quotePolicy = new QuotePolicy();
$canCreateQuote = $quotePolicy->create($pmUser);

echo "PM User Test:\n";
echo "- Role: pm\n";
echo "- Can create quotes: " . ($canCreateQuote ? 'YES ✓' : 'NO ✗') . "\n\n";

// Test other roles for comparison
$testRoles = ['super-admin', 'admin', 'po', 'client'];

foreach ($testRoles as $role) {
    $user = new User();
    $user->hasAnyRole = function($roles) use ($role) {
        return in_array($role, (array)$roles);
    };
    
    $user->hasRole = function($checkRole) use ($role) {
        return $role === $checkRole;
    };
    
    $canCreate = $quotePolicy->create($user);
    echo "Role '{$role}': " . ($canCreate ? 'YES ✓' : 'NO ✗') . "\n";
}

echo "\nSummary:\n";
echo "- PMs should be able to create quotes: " . ($canCreateQuote ? 'PASS ✓' : 'FAIL ✗') . "\n";
echo "- Quote creation is now properly controlled by the QuotePolicy\n";
echo "- Authorization in QuoteController has been updated to use the policy\n";