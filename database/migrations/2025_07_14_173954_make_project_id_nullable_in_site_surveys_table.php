<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Make sure doctrine/dbal is installed before using `change()`
        if (Schema::hasColumn('site_surveys', 'project_id')) {
            Schema::table('site_surveys', function (Blueprint $table) {
                $table->foreignId('project_id')
                      ->nullable()
                      ->change(); // change to nullable
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('site_surveys', 'project_id')) {
            Schema::table('site_surveys', function (Blueprint $table) {
                $table->foreignId('project_id')
                      ->nullable(false)
                      ->change(); // revert back to not nullable if needed
            });
        }
    }
};
