<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['student', 'umkm', 'admin'])->default('student')->after('email');
            $table->enum('status', ['active', 'pending_ktm', 'suspended'])->default('active')->after('role');
            $table->string('ktm_path')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'status', 'ktm_path']);
        });
    }
};
