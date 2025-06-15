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
        Schema::create('enquiry_logs', function (Blueprint $table) {
            $table->id();
            $table->string('project_name');
            $table->string('venue');
            $table->date('date_received');
            $table->string('client_name');
            $table->json('project_scope_summary'); // Array of items
            $table->string('contact_person')->nullable();
            $table->enum('status', ['Open', 'Quoted', 'Approved', 'Declined'])->default('Open');
            $table->string('assigned_to')->nullable();
            $table->text('follow_up_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquiry_logs');
    }
};
