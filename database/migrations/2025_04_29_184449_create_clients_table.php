<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id('ClientID'); // Auto-incrementing primary key
            $table->string('FullName'); // Individual or company name
            $table->string('ContactPerson')->nullable(); // For corporate clients, optional
            $table->string('Email')->unique(); // Email address, unique
            $table->string('Phone'); // Phone number
            $table->string('AltContact')->nullable(); // Alternative contact number, optional
            $table->text('Address'); // Physical address
            $table->string('City'); // City/Town
            $table->string('County'); // County/Region
            $table->string('PostalAddress')->nullable(); // Optional postal info
            $table->enum('CustomerType', ['Individual', 'Business', 'Organization']); // Enum for customer type
            $table->enum('LeadSource', ['Walk-in', 'Referral', 'Social Media', 'Website', 'Advertisement', 'Other']); // Enum for lead source
            $table->enum('PreferredContact', ['Email', 'Phone', 'WhatsApp']); // Enum for preferred contact method
            $table->string('Industry')->nullable(); // Sector if company, optional
            $table->timestamp('CreatedAt')->useCurrent(); // When they were added
            $table->string('CreatedBy'); // User who logged the entry
            $table->timestamps(); // Laravel's created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
