<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE transactions MODIFY status ENUM('RECORDED', 'SUCCESS', 'REJECTED') NOT NULL DEFAULT 'RECORDED'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE transactions MODIFY status ENUM('RECORDED', 'SUCCESS') NOT NULL DEFAULT 'RECORDED'");
    }
};
