<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('item'); // Common item name
            $table->string('material');
            $table->text('specification')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('quantity', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->string('design_reference')->nullable(); // Expecting a URL
            $table->string('approved_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};

