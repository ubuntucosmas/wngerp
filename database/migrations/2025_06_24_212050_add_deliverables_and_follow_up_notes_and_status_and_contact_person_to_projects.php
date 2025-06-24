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
        Schema::table('projects', function (Blueprint $table) {
            $table->text('deliverables')->nullable()->after('venue');
            $table->text('follow_up_notes')->nullable()->after('deliverables');
            $table->string('contact_person')->nullable()->after('follow_up_notes');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['deliverables', 'follow_up_notes', 'contact_person']);
        });
    }
    
};
