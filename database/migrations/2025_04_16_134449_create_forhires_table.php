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
        Schema::create('forhires', function (Blueprint $table) {
            $table->id();
            $table->string('sku');
            $table->string('client');
            $table->string('contacts');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('hire_fee', 10, 2);
            $table->string('status')->default('Pending'); // You can set default status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forhires');
    }
};
