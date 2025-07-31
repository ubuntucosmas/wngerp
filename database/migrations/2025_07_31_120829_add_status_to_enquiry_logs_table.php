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
        if (!Schema::hasColumn('enquiry_logs', 'status')) {
            Schema::table('enquiry_logs', function (Blueprint $table) {
                $table->enum('status', ['Open', 'Quoted', 'Approved', 'Declined'])->default('Open')->after('contact_person');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('enquiry_logs', 'status')) {
            Schema::table('enquiry_logs', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
