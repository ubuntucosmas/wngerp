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
        Schema::create('material_list_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_list_id')->constrained()->onDelete('cascade');
            $table->string('category')->nullable(); // e.g. Site, Logistics, etc.
            $table->string('item_name')->nullable(); // only for production items
            $table->string('particular');
            $table->string('unit')->nullable();
            $table->decimal('quantity', 8, 2)->nullable();
            $table->string('comment')->nullable();
            $table->string('design_reference')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_list_items');
    }
};
