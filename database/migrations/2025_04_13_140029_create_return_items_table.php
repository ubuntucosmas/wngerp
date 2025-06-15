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
        Schema::create('return_items', function (Blueprint $table) {
            $table->id(); // Return ID
            $table->string('sku'); // SKU
            $table->string('item_name'); // Item Name
            $table->integer('quantity'); // Quantity
            $table->text('reason')->nullable(); // Reason
            $table->date('return_date'); // Return Date
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_items');
    }
};
