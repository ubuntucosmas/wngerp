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
        Schema::table('enquiries', function (Blueprint $table) {
            $table->unsignedBigInteger('converted_to_project_id')->nullable()->after('id');
            
            // Optional: add foreign key constraint
            $table->foreign('converted_to_project_id')
                  ->references('id')
                  ->on('projects')
                  ->onDelete('set null');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropForeign(['converted_to_project_id']);
            $table->dropColumn('converted_to_project_id');
        });
    }
};
