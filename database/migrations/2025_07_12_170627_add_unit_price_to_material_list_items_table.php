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
        Schema::table('material_list_items', function (Blueprint $table) {
            $table->decimal('unit_price', 8, 2)->default(0.00)->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('material_list_items', function (Blueprint $table) {
            $table->dropColumn('unit_price');
        });
    }
};
