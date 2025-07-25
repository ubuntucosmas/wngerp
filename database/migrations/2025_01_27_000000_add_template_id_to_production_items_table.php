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
        Schema::table('production_items', function (Blueprint $table) {
            $table->foreignId('template_id')->nullable()->constrained('item_templates')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_items', function (Blueprint $table) {
            $table->dropForeign(['template_id']);
            $table->dropColumn('template_id');
        });
    }
}; 