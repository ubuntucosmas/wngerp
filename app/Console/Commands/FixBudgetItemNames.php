<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BudgetItem;

class FixBudgetItemNames extends Command
{
    protected $signature = 'fix:budget-item-names';
    protected $description = 'Fix budget_items by setting item_name to particular for production items if missing';

    public function handle()
    {
        $count = BudgetItem::where('category', 'Materials - Production')
            ->where(function($q) {
                $q->whereNull('item_name')->orWhere('item_name', '');
            })
            ->update(['item_name' => \DB::raw('particular')]);

        $this->info("Updated $count budget_items.");
    }
} 