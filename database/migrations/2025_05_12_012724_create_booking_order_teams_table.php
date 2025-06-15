<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingOrderTeamsTable extends Migration
{
    public function up()
    {
        Schema::create('booking_order_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_order_id')->constrained()->onDelete('cascade');
            $table->enum('team_type', ['set_down', 'pasting', 'technical']);
            $table->string('member_name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking_order_teams');
    }
}

