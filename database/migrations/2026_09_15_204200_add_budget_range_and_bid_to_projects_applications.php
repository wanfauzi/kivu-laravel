<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedBigInteger('min_budget')->nullable()->after('budget');
            $table->unsignedBigInteger('max_budget')->nullable()->after('min_budget');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->unsignedBigInteger('bid_amount')->nullable()->after('message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('bid_amount');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['min_budget', 'max_budget']);
        });
    }
};
