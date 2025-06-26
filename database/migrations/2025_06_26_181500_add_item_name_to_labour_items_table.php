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
        Schema::table('labour_items', function (Blueprint $table) {
            if (!Schema::hasColumn('labour_items', 'item_name')) {
                $table->string('item_name')->nullable()->after('category');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('labour_items', function (Blueprint $table) {
            $table->dropColumn('item_name');
        });
    }
};
