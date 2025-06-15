<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('phase_id')->nullable();     // Task phase
            $table->unsignedBigInteger('user_id')->nullable();      // Creator
            $table->string('assigned_to')->nullable();  // Assignee

            $table->string('name');
            $table->string('status')->default('Pending');
            $table->text('description')->nullable();
            $table->text('comment')->nullable();
            $table->string('file')->nullable(); // Path to uploaded file

            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('phase_id')->references('id')->on('phases')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
