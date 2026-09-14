<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE disputes MODIFY status ENUM('OPEN', 'RESOLVED', 'REJECTED', 'CANCELLED') NOT NULL DEFAULT 'OPEN'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE disputes MODIFY status ENUM('OPEN', 'RESOLVED', 'REJECTED') NOT NULL DEFAULT 'OPEN'");
    }
};
