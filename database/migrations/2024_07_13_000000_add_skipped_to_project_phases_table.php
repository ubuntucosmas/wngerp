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
        Schema::table('project_phases', function (Blueprint $table) {
            $table->boolean('skipped')->default(false)->after('status');
            $table->string('skip_reason')->nullable()->after('skipped');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_phases', function (Blueprint $table) {
            $table->dropColumn(['skipped', 'skip_reason']);
        });
    }
}; 