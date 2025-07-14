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
        Schema::table('design_assets', function (Blueprint $table) {
            $table->foreignId('enquiry_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('design_assets', function (Blueprint $table) {
            $table->dropForeign(['enquiry_id']);
            $table->dropColumn('enquiry_id');
            $table->foreignId('project_id')->nullable(false)->change();
        });
    }
};