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
            $table->foreignId('enquiry_id')->nullable()->constrained('enquiries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_phases', function (Blueprint $table) {
            $table->dropForeign(['enquiry_id']);
            $table->dropColumn('enquiry_id');
        });
    }
};
