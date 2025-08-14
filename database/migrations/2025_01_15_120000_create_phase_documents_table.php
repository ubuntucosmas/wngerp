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
        Schema::create('phase_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_phase_id');
            $table->unsignedBigInteger('project_id');
            $table->string('phase_name');
            $table->string('original_filename');
            $table->string('stored_filename');
            $table->string('file_path');
            $table->bigInteger('file_size');
            $table->string('mime_type');
            $table->string('file_extension', 10);
            $table->unsignedBigInteger('uploaded_by');
            $table->text('description')->nullable();
            $table->string('document_type', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('project_phase_id')->references('id')->on('project_phases')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');

            // Indexes
            $table->index(['project_id', 'phase_name']);
            $table->index(['project_phase_id']);
            $table->index(['uploaded_by']);
            $table->index(['is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phase_documents');
    }
};