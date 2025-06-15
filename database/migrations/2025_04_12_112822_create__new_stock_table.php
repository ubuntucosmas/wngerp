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
        Schema::create('NewStock', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('sku'); // SKU linked to the inventory table
            $table->string('item_name');
            $table->integer('quantity');
            $table->string('supplier');
            $table->timestamp('added_on')->default(now()); // When the stock was added
            $table->timestamps(); // Includes created_at and updated_at    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('NewStock');
    }
};
