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
        Schema::table('close_out_reports', function (Blueprint $table) {
            // User tracking fields
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('status');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->after('created_by');
            $table->foreignId('rejected_by')->nullable()->constrained('users')->onDelete('set null')->after('approved_by');
            $table->timestamp('approved_at')->nullable()->after('rejected_by');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
            $table->text('rejection_reason')->nullable()->after('rejected_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('close_out_reports', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['rejected_by']);
            $table->dropColumn([
                'created_by',
                'approved_by', 
                'rejected_by',
                'approved_at',
                'rejected_at',
                'rejection_reason'
            ]);
        });
    }
};
