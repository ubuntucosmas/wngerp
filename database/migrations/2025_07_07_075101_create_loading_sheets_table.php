<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('loading_sheets')) {
            Schema::create('loading_sheets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->constrained()->onDelete('cascade');
                $table->string('vehicle_number')->nullable();
                $table->string('driver_name')->nullable();
                $table->string('driver_phone')->nullable();
                $table->string('loading_point')->nullable();
                $table->string('unloading_point')->nullable();
                $table->date('loading_date')->nullable();
                $table->date('unloading_date')->nullable();
                $table->text('special_instructions')->nullable();
                $table->json('items')->nullable(); // To store multiple items
                $table->timestamps();
                $table->softDeletes();

                // Optional indexes
                $table->index('project_id');
                $table->index('vehicle_number');
                $table->index('loading_date');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('loading_sheets')) {
            Schema::dropIfExists('loading_sheets');
        }
    }
};
