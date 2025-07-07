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
        Schema::create('item_template_particulars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_template_id')->constrained('item_templates')->onDelete('cascade');
            $table->string('particular');
            $table->string('unit')->nullable();
            $table->decimal('default_quantity', 10, 2)->default(1);
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_template_particulars');
    }
};
