<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->boolean('is_service')->default(false)->after('file_path');
            $table->unsignedInteger('price')->nullable()->after('is_service');
            $table->unsignedTinyInteger('delivery_days')->nullable()->after('price');
            $table->foreignId('category_id')->nullable()->after('delivery_days')->constrained('categories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(\App\Models\Category::class, 'category_id');
            $table->dropColumn(['is_service', 'price', 'delivery_days']);
        });
    }
};
