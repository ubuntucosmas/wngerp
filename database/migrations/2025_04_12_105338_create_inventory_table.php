<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->string('sku');
            $table->string('item_name');
            $table->string('category');
            $table->string('unit_of_measure');
            $table->integer('stock_on_hand')->default(0);
            $table->integer('quantity_checked_in')->default(0);
            $table->integer('quantity_checked_out')->default(0);
            $table->integer('returns')->default(0);
            $table->string('supplier');
            $table->decimal('unit_price', 8, 2);
            $table->decimal('total_value', 10, 2)->default(0);
            $table->date('order_date')->nullable();
            $table->timestamps();
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
