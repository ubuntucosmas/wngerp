<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnquiriesTable extends Migration
{
    public function up()
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->date('date_received');
            $table->date('expected_delivery_date')->nullable();
            $table->string('client_name');
            $table->text('project_deliverables')->nullable();
            $table->string('contact_person')->nullable();
            $table->enum('status', ['Open', 'Quoted', 'Approved', 'Declined'])->default('Open');
            $table->string('assigned_po')->nullable();
            $table->text('follow_up_notes')->nullable();
            $table->string('project_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('enquiries');
    }
}