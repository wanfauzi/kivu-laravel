<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE submissions MODIFY status ENUM('SUBMITTED', 'APPROVED', 'REVISION') NOT NULL DEFAULT 'SUBMITTED'");
        }
        Schema::table('submissions', function (Blueprint $table) {
            $table->text('revision_note')->nullable()->after('note');
        });
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn('revision_note');
        });
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE submissions MODIFY status ENUM('SUBMITTED', 'APPROVED') NOT NULL DEFAULT 'SUBMITTED'");
        }
    }
};
