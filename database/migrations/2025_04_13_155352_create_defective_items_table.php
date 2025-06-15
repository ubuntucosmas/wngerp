<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefectiveItemsTable extends Migration
{
    public function up()
    {
        Schema::create('defective_items', function (Blueprint $table) {
            $table->id(); // Defect ID
            $table->string('sku'); // SKU
            $table->string('item_name'); // Item Name
            $table->integer('quantity'); // Defective quantity
            $table->string('defect_type'); // Defect type (e.g., broken, expired)
            $table->string('reported_by'); // Reported by
            $table->date('date_reported'); // Date reported
            $table->text('remarks')->nullable(); // Additional remarks
            $table->string('status')->default('Pending'); // Status (default: Pending)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('defective_items');
    }
}
