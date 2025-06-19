<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('design_assets', function (Blueprint $table) {
            $table->dropColumn(['file_type', 'file_size']);
        });
    }

    public function down()
    {
        Schema::table('design_assets', function (Blueprint $table) {
            $table->string('file_type')->nullable();  // adjust type as per original
            $table->integer('file_size')->nullable(); // adjust type as per original
        });
    }
};
