<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE disputes MODIFY status ENUM('OPEN', 'RESOLVED', 'REJECTED', 'CANCELLED') NOT NULL DEFAULT 'OPEN'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE disputes MODIFY status ENUM('OPEN', 'RESOLVED', 'REJECTED') NOT NULL DEFAULT 'OPEN'");
        }
    }
};
