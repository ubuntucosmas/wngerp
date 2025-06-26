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
        Schema::create('production_particulars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_item_id')->constrained()->onDelete('cascade');
            $table->string('particular');
            $table->string('unit')->nullable();
            $table->decimal('quantity', 10, 2)->default(0);
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
        Schema::dropIfExists('production_particulars');
    }
};
