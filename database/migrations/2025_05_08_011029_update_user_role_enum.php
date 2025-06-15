<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('super-admin', 'admin', 'pm', 'po', 'store', 'logistics', 'procurement', 'User') DEFAULT 'User'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('pm', 'po', 'store', 'User') DEFAULT 'User'");
    }
};
