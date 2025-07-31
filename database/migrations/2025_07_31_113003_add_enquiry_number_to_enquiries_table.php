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
        Schema::table('enquiries', function (Blueprint $table) {
            // Check if the column doesn't already exist
            if (!Schema::hasColumn('enquiries', 'enquiry_number')) {
                $table->integer('enquiry_number')->unsigned()->nullable()->after('project_name');
                $table->index(['enquiry_number', 'created_at'], 'enquiry_number_date_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            if (Schema::hasColumn('enquiries', 'enquiry_number')) {
                $table->dropIndex('enquiry_number_date_index');
                $table->dropColumn('enquiry_number');
            }
        });
    }
};
