<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('enquiry_logs', 'enquiry_id')) {
            Schema::table('enquiry_logs', function (Blueprint $table) {
                $table->foreignId('enquiry_id')
                      ->nullable()
                      ->after('id') // adjust position as you prefer
                      ->constrained()
                      ->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('enquiry_logs', 'enquiry_id')) {
            Schema::table('enquiry_logs', function (Blueprint $table) {
                $table->dropForeign(['enquiry_id']);
                $table->dropColumn('enquiry_id');
            });
        }
    }
};
