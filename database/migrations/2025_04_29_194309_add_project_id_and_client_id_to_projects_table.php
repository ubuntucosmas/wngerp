<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('project_id')->unique()->after('id'); // Add project_id, unique
            $table->unsignedBigInteger('client_id')->after('client_name'); // Add client_id
            $table->foreign('client_id')->references('ClientID')->on('clients')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn(['project_id', 'client_id']);
        });
    }
};
