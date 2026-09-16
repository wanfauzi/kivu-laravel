<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE projects MODIFY status ENUM('DRAFT', 'OPEN', 'IN_PROGRESS', 'SUBMITTED', 'COMPLETED', 'CANCELLED') NOT NULL DEFAULT 'DRAFT'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE projects MODIFY status ENUM('OPEN', 'IN_PROGRESS', 'SUBMITTED', 'COMPLETED', 'CANCELLED') NOT NULL DEFAULT 'OPEN'");
        }
    }
};
