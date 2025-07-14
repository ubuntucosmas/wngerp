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
        Schema::table('item_template_particulars', function (Blueprint $table) {
            $table->decimal('unit_price', 8, 2)->default(0.00)->after('default_quantity');
        });

        Schema::table('item_templates', function (Blueprint $table) {
            $table->dropColumn('estimated_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_template_particulars', function (Blueprint $table) {
            $table->dropColumn('unit_price');
        });

        Schema::table('item_templates', function (Blueprint $table) {
            $table->decimal('estimated_cost', 8, 2)->nullable()->after('description');
        });
    }
};
