<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('close_out_report_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('close_out_report_id')->constrained('close_out_reports')->onDelete('cascade');
            $table->string('filename');
            $table->string('path');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('close_out_report_attachments');
    }
};


