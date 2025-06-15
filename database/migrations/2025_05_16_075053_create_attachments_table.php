<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentsTable extends Migration
{
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comment_id');  // associated comment (or adjust to task if needed)
            $table->string('file_path');  // storage path of the attachment
            $table->string('file_name');  // original file name
            $table->string('file_type')->nullable(); // optional, mime type or extension
            $table->timestamps();

            // Foreign key
            $table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('attachments');
    }
}
