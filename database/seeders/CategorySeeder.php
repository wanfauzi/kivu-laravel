<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public const CATEGORIES = [
        ['name' => 'Desain Grafis', 'icon' => 'pencil'],
        ['name' => 'Web & IT', 'icon' => 'layout-grid'],
        ['name' => 'Penulisan & Konten', 'icon' => 'file-text'],
        ['name' => 'Digital Marketing', 'icon' => 'trending-up'],
        ['name' => 'Video & Animasi', 'icon' => 'sparkles'],
        ['name' => 'Fotografi Produk', 'icon' => 'star'],
        ['name' => 'Social Media Management', 'icon' => 'message-square'],
        ['name' => 'Input Data & Admin', 'icon' => 'layout-dashboard'],
        ['name' => 'Penerjemahan', 'icon' => 'external-link'],
        ['name' => 'Konsultasi Bisnis', 'icon' => 'briefcase'],
    ];

    public function run(): void
    {
        foreach (self::CATEGORIES as $index => $category) {
            Category::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'icon' => $category['icon'],
                    'sort_order' => $index + 1,
                ]
            );
        }
    }
}
