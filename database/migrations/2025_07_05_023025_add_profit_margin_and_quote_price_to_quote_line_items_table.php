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
        Schema::table('quote_line_items', function (Blueprint $table) {
            $table->decimal('profit_margin', 5, 2)->default(0)->after('total');
            $table->decimal('quote_price', 12, 2)->default(0)->after('profit_margin');
            $table->decimal('total_cost', 12, 2)->default(0)->after('quote_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quote_line_items', function (Blueprint $table) {
            $table->dropColumn(['profit_margin', 'quote_price', 'total_cost']);
        });
    }
};
