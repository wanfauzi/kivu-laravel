<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $services = [
            'Redesign Logo Kafe' => ['price' => 350000, 'delivery_days' => 5],
            'Artikel Wisata Kuliner' => ['price' => 150000, 'delivery_days' => 3],
            'Landing Page Toko' => ['price' => 750000, 'delivery_days' => 7],
            'Video Editing Reels' => ['price' => 300000, 'delivery_days' => 4],
        ];

        foreach ($services as $title => $data) {
            DB::table('portfolios')
                ->where('title', $title)
                ->where('is_service', false)
                ->update([
                    'is_service' => true,
                    'price' => $data['price'],
                    'delivery_days' => $data['delivery_days'],
                ]);
        }
    }

    public function down(): void
    {
        DB::table('portfolios')
            ->where('is_service', true)
            ->whereIn('title', ['Redesign Logo Kafe', 'Artikel Wisata Kuliner', 'Landing Page Toko', 'Video Editing Reels'])
            ->update([
                'is_service' => false,
                'price' => null,
                'delivery_days' => null,
            ]);
    }
};
