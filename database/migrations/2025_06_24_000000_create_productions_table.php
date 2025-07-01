<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('job_number');
            $table->string('project_title');
            $table->string('client_name');
            $table->date('briefing_date');
            $table->string('briefed_by');
            $table->date('delivery_date');
            $table->text('production_team');
            $table->text('materials_required')->nullable();
            $table->text('key_instructions')->nullable();
            $table->text('special_considerations')->nullable();
            $table->boolean('files_received');
            $table->text('additional_notes')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->text('status_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('production_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->date('due_date')->nullable();
            $table->string('assigned_to')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('production_tasks');
        Schema::dropIfExists('productions');
    }
};
