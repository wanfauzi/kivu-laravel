<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE projects MODIFY status ENUM('OPEN', 'IN_PROGRESS', 'SUBMITTED', 'COMPLETED', 'CANCELLED') NOT NULL DEFAULT 'OPEN'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE projects MODIFY status ENUM('OPEN', 'IN_PROGRESS', 'SUBMITTED', 'COMPLETED') NOT NULL DEFAULT 'OPEN'");
    }
};
