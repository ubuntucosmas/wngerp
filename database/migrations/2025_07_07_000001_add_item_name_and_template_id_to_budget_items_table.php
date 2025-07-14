<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('budget_items', 'item_name') || !Schema::hasColumn('budget_items', 'template_id')) {
            Schema::table('budget_items', function (Blueprint $table) {
                if (!Schema::hasColumn('budget_items', 'item_name')) {
                    $table->string('item_name')->nullable()->after('category');
                }

                if (!Schema::hasColumn('budget_items', 'template_id')) {
                    $table->unsignedBigInteger('template_id')->nullable()->after('item_name');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('budget_items', 'item_name') || Schema::hasColumn('budget_items', 'template_id')) {
            Schema::table('budget_items', function (Blueprint $table) {
                if (Schema::hasColumn('budget_items', 'item_name')) {
                    $table->dropColumn('item_name');
                }

                if (Schema::hasColumn('budget_items', 'template_id')) {
                    $table->dropColumn('template_id');
                }
            });
        }
    }
};
