<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('booking_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
    
            $table->string('project_name');
            $table->string('contact_person');
            $table->string('project_manager');
            $table->string('project_captain');
            $table->string('project_assistant_captain');
            $table->string('phone_number');
            $table->date('set_down_date');
            $table->string('set_down_time');
            $table->string('event_venue');
            $table->string('set_up_time');
            $table->string('estimated_set_up_period');
    
            $table->json('set_down_team')->nullable();
            $table->json('pasting_team')->nullable();
            $table->json('technical_team')->nullable();
    
            $table->string('logistics_designated_truck');
            $table->string('driver');
            $table->boolean('loading_team_confirmed')->default(false);
            $table->boolean('printed_collateral_shared')->default(false);
            $table->boolean('approved_mock_up_shared')->default(false);
            $table->text('fabrication_preparation');
            $table->string('time_of_loading_departure');
            $table->string('safety_gear_checker');
    
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_orders');
    }
};
