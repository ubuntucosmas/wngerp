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
            $table->string('task_name')->nullable();        // Task title
            $table->text('deliverables')->nullable();       // Requirements/expected outputs
            $table->enum('task_status', ['Pending', 'In Progress', 'Completed'])->default('Pending'); // Task progress
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phase_logs', function (Blueprint $table) {
            $table->dropColumn(['task_name', 'deliverables', 'task_status']);
        });
    }
};
