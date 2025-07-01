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
        Schema::create('logistics_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('logistics_id');
            $table->string('description');
            $table->integer('quantity');
            $table->string('unit');
            $table->string('notes')->nullable();
            $table->boolean('loaded')->default(false);
            $table->timestamps();
            $table->foreign('logistics_id')->references('id')->on('logistics')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logistics_items');
    }
};
