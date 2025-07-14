<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('site_surveys', 'enquiry_id')) {
            Schema::table('site_surveys', function (Blueprint $table) {
                $table->foreignId('enquiry_id')
                      ->nullable()
                      ->after('project_id') // Position the column right after project_id
                      ->constrained()
                      ->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('site_surveys', 'enquiry_id')) {
            Schema::table('site_surveys', function (Blueprint $table) {
                $table->dropForeign(['enquiry_id']);
                $table->dropColumn('enquiry_id');
            });
        }
    }
};
