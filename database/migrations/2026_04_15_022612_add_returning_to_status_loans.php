<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE loans 
            MODIFY status ENUM('pending','active','returning','rejected','closed') 
            NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE loans 
            MODIFY status ENUM('pending','active','rejected','closed') 
            NOT NULL
        ");
    }
};
