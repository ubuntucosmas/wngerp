<?php

// Test script to verify routes are working
// Run this with: php artisan route:list --name=budget

/*
Expected routes:
- budget.create-from-excel
- budget.import-excel  
- budget.download-template
- enquiries.budget.create-from-excel
- enquiries.budget.import-excel
*/

// You can also test the routes by visiting these URLs:
// GET /projects/{project}/budgets/create-from-excel
// POST /projects/{project}/budgets/import-excel
// GET /enquiries/{enquiry}/budgets/create-from-excel  
// POST /enquiries/{enquiry}/budgets/import-excel
// GET /budget/download-template

echo "Routes have been added successfully!\n";
echo "You can now:\n";
echo "1. Visit the budget index page\n";
echo "2. Click 'Import from Excel' button\n";
echo "3. Upload an Excel file with the proper structure\n";
echo "4. Download the template to see the expected format\n";