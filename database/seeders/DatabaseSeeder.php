<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@kivu.id'],
            [
                'name' => 'Admin Kivu',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $umkm = User::updateOrCreate(
            ['email' => 'umkm@kivu.id'],
            [
                'name' => 'UMKM Kivu',
                'password' => Hash::make('password'),
                'role' => 'umkm',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $student = User::updateOrCreate(
            ['email' => 'talent@kivu.id'],
            [
                'name' => 'Talent Kivu',
                'password' => Hash::make('password'),
                'role' => 'student',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        Wallet::updateOrCreate(
            ['student_id' => $student->id],
            ['balance' => 0]
        );

        $projects = [
            [
                'title' => 'Desain Logo UMKM Kopi',
                'description' => 'Desain logo modern dan minimalis untuk UMKM kopi lokal. Output: file vector (AI/SVG) + PNG transparan.',
                'budget' => 500000,
            ],
            [
                'title' => 'Buat Landing Page UMKM Batik',
                'description' => 'Landing page responsif untuk UMKM batik dengan katalog produk dan kontak WhatsApp.',
                'budget' => 1000000,
            ],
            [
                'title' => 'Penulisan Artikel Kuliner',
                'description' => 'Artikel 800-1000 kata tentang kuliner tradisional Indonesia untuk blog UMKM.',
                'budget' => 300000,
            ],
        ];

        foreach ($projects as $data) {
            Project::updateOrCreate(
                ['title' => $data['title'], 'owner_id' => $umkm->id],
                [
                    'description' => $data['description'],
                    'budget' => $data['budget'],
                    'status' => 'OPEN',
                ]
            );
        }
    }
}
