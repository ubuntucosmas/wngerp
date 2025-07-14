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
            // Add the polymorphic columns
            $table->unsignedBigInteger('phaseable_id')->nullable()->after('id');
            $table->string('phaseable_type')->nullable()->after('phaseable_id');

            // Drop the old foreign key columns
            $table->dropForeign(['project_id']);
            $table->dropForeign(['enquiry_id']);
            $table->dropColumn(['project_id', 'enquiry_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_phases', function (Blueprint $table) {
            // Re-add the old foreign key columns
            $table->unsignedBigInteger('project_id')->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->unsignedBigInteger('enquiry_id')->nullable();
            $table->foreign('enquiry_id')->references('id')->on('enquiries')->onDelete('cascade');

            // Drop the polymorphic columns
            $table->dropColumn(['phaseable_id', 'phaseable_type']);
        });
    }
};