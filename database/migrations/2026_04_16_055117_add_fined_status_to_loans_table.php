<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
        ALTER TABLE loans 
        MODIFY status ENUM('pending','active','returning','rejected','closed','fined','fine_pending')
        NOT NULL
    ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
        ALTER TABLE loans 
        MODIFY status ENUM('pending','active','returning','rejected','closed')
        NOT NULL
    ");
    }
};
