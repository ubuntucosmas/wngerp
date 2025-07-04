<?php

return [

    'roles' => [
        'super-admin',
        'admin',
        'hr',
        'pm',       // Project Manager
        'po', 
        'design',
        'finance',      // Project Officer
        'store',    // Store Manager
        'user',     // General User
        'production', // Production Manager
    ],

    'permissions' => [

        // User Management
        'manage users',
        'view users',
        'assign roles',

        // Project
        'create project',
        'update project',
        'delete project',
        'view project',
        'assign project',

        // Store
        'manage store',
        'create stock',
        'update stock',
        'delete stock',
        'view stock',
        'view stock reports',

        // Reports
        'submit outcome report',
        'approve outcome report',

        // Module Access
        'access project module',
        'access store module',
        'access user module',
        'access reports module',
    ],

    'role_permissions' => [

        'super-admin' => ['*'], // wildcard = all permissions

        'admin' => [
            'access user module',
            'access project module',
            'access store module',
            'access reports module',
            'manage users',
            'view users',
            'assign roles',
            'create project',
            'update project',
            'view project',
            'assign project',
            'create stock',
            'update stock',
            'delete stock',
            'view stock',
            'view stock reports',
            'approve outcome report',
        ],

        'pm' => [
            'access project module',
            'create project',
            'update project',
            'view project',
            'assign project',
            'submit outcome report',
        ],

        'po' => [
            'access project module',
            'view project',
            'submit outcome report',
        ],

        'store' => [
            'access store module',
            'manage store',
            'create stock',
            'update stock',
            'delete stock',
            'view stock',
            'view stock reports',
        ],

        'user' => [
            'access project module',
            'view project',
        ],
    ],
];
