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
        Schema::table('project_budgets', function (Blueprint $table) {
            $table->foreignId('material_list_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_budgets', function (Blueprint $table) {
            $table->dropForeign(['material_list_id']);
            $table->dropColumn('material_list_id');
        });
    }
};
