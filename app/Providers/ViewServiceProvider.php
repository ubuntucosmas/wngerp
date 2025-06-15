<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Inventory;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Share low-stock items with the master layout
        View::composer('layouts.master', function ($view) {
            $lowStockItems = Inventory::where('stock_on_hand', '<=', 10)->get(); // Fetch low-stock items
            $view->with('lowStockItems', $lowStockItems); // Attach data to the view
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
