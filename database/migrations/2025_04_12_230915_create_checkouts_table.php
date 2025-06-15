<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('checkouts', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('inventory_id'); // Foreign key for inventory
            $table->string('check_out_id'); // Unique batch ID
            $table->string('checked_out_by'); // User performing the checkout
            $table->string('received_by'); // Recipient
            $table->string('destination'); // Destination
            $table->integer('quantity'); // Quantity of items checked out
            $table->timestamps(); // Created at and updated at
    
            // Add a foreign key constraint
            $table->foreign('inventory_id')->references('id')->on('inventory')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkouts');
    }
};
