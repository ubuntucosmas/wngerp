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
        Schema::table('handover_reports', function (Blueprint $table) {
            $table->renameColumn('title', 'client_name');
            $table->renameColumn('description', 'contact_person');
            $table->renameColumn('google_drive_link', 'client_comments');
            $table->date('acknowledgment_date')->after('contact_person');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('handover_reports', function (Blueprint $table) {
            $table->renameColumn('client_name', 'title');
            $table->renameColumn('contact_person', 'description');
            $table->renameColumn('client_comments', 'google_drive_link');
            $table->dropColumn('acknowledgment_date');
        });
    }
};
