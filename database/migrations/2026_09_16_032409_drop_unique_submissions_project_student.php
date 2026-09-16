<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (\Illuminate\Support\Facades\DB::getDriverName() === 'sqlite') {
            try {
                Schema::table('submissions', function (Blueprint $table) {
                    $table->dropUnique(['project_id', 'student_id']);
                });
            } catch (\Throwable $e) {
                // ignore if not exists in sqlite
            }
            return;
        }

        try {
            $exists = collect(\Illuminate\Support\Facades\DB::select("SHOW INDEX FROM submissions WHERE Key_name = 'submissions_project_id_student_id_unique'"))->isNotEmpty();
            if ($exists) {
                \Illuminate\Support\Facades\DB::statement('ALTER TABLE submissions DROP FOREIGN KEY submissions_project_id_foreign');
                Schema::table('submissions', function (Blueprint $table) {
                    $table->dropUnique('submissions_project_id_student_id_unique');
                });
                \Illuminate\Support\Facades\DB::statement('ALTER TABLE submissions ADD CONSTRAINT submissions_project_id_foreign FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE');
            }
        } catch (\Throwable $e) {
            // fallback
        }
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->unique(['project_id', 'student_id']);
        });
    }
};