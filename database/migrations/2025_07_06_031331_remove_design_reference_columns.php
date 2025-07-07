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
        // Remove design_reference column from production_particulars table
        Schema::table('production_particulars', function (Blueprint $table) {
            $table->dropColumn('design_reference');
        });

        // Remove design_reference column from material_list_items table
        Schema::table('material_list_items', function (Blueprint $table) {
            $table->dropColumn('design_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back design_reference column to production_particulars table
        Schema::table('production_particulars', function (Blueprint $table) {
            $table->string('design_reference')->nullable();
        });

        // Add back design_reference column to material_list_items table
        Schema::table('material_list_items', function (Blueprint $table) {
            $table->string('design_reference')->nullable();
        });
    }
};
