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
        Schema::table('phase_logs', function (Blueprint $table) {
            $table->json('deliverables')->nullable()->change(); // assumes it was a text field before
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phase_logs', function (Blueprint $table) {
            //
        });
    }
};
