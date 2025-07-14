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
        Schema::table('quotes', function (Blueprint $table) {
            $table->foreignId('enquiry_id')->nullable()->constrained('enquiries')->onDelete('cascade');
            // Assuming project_id might be added later or exists in another migration
            // If project_id exists and is not nullable, you might need to change it here too
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropForeign(['enquiry_id']);
            $table->dropColumn('enquiry_id');
        });
    }
};