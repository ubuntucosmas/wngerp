<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('site_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            
            // Basic Information
            $table->date('site_visit_date');
            $table->string('project_manager');
            $table->string('client_name');
            $table->string('location');
            $table->json('attendees')->nullable();

            // General Information
            $table->string('client_contact_person');
            $table->string('client_phone');
            $table->string('client_email')->nullable();
            $table->text('project_description')->nullable();
            $table->text('objectives')->nullable();

            // Site Assessment
            $table->text('current_condition')->nullable();
            $table->text('existing_branding')->nullable();
            $table->text('access_logistics')->nullable();
            $table->string('parking_availability')->nullable();
            $table->text('size_accessibility')->nullable();
            $table->text('lifts')->nullable();
            $table->text('door_sizes')->nullable();
            $table->text('loading_areas')->nullable();
            $table->text('site_measurements')->nullable();
            $table->text('room_size')->nullable();
            $table->text('constraints')->nullable();
            $table->text('electrical_outlets')->nullable();
            $table->text('food_refreshment')->nullable();

            // Client Requirements
            $table->text('branding_preferences')->nullable();
            $table->text('material_preferences')->nullable();
            $table->text('color_scheme')->nullable();
            $table->text('brand_guidelines')->nullable();
            $table->text('special_instructions')->nullable();

            // Project Timeline
            $table->dateTime('project_start_date')->nullable();
            $table->dateTime('project_deadline')->nullable();
            $table->text('milestones')->nullable();

            // Health and Safety
            $table->text('safety_conditions')->nullable();
            $table->text('potential_hazards')->nullable();
            $table->text('safety_requirements')->nullable();

            // Additional Notes
            $table->text('additional_notes')->nullable();
            $table->text('special_requests')->nullable();
            $table->json('action_items')->nullable();

            // Signatures
            $table->string('prepared_by')->nullable();
            $table->string('prepared_signature')->nullable();
            $table->date('prepared_date')->nullable();
            $table->string('client_approval')->nullable();
            $table->string('client_signature')->nullable();
            $table->date('client_approval_date')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('site_surveys');
    }
};