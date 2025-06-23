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
        Schema::table('project_budgets', function (Blueprint $table) {
            $table->string('status')->default('draft')->after('project_id');
        });
    }
    
    public function down()
    {
        Schema::table('project_budgets', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
    
};
