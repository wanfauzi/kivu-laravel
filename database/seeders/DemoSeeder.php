<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Category;
use App\Models\Dispute;
use App\Models\Message;
use App\Models\Portfolio;
use App\Models\Project;
use App\Models\Review;
use App\Models\Skill;
use App\Models\Submission;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withdrawal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        foreach ([
            'messages', 'project_skill', 'disputes', 'portfolios', 'reviews', 'transactions', 'withdrawals',
            'wallets', 'submissions', 'applications', 'projects', 'users',
        ] as $table) {
            DB::table($table)->truncate();
        }
        Schema::enableForeignKeyConstraints();

        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');

        // ===== Akun (10) =====
        $admin = User::create([
            'name' => 'Administrator KIVU', 'email' => 'admin@kivu.id',
            'password' => Hash::make('password'), 'role' => 'admin', 'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $umkm = User::create([
            'name' => 'Warung Kopi Menangan', 'email' => 'umkm@kivu.id',
            'password' => Hash::make('password'), 'role' => 'umkm', 'status' => 'active',
            'business_name' => 'Warung Kopi Menangan', 'email_verified_at' => now(),
        ]);
        $batik = User::create([
            'name' => 'Batik Heritage', 'email' => 'batik@kivu.id',
            'password' => Hash::make('password'), 'role' => 'umkm', 'status' => 'active',
            'business_name' => 'Batik Heritage', 'email_verified_at' => now(),
        ]);
        $keripik = User::create([
            'name' => 'Keripik Singkong Sari', 'email' => 'keripik@kivu.id',
            'password' => Hash::make('password'), 'role' => 'umkm', 'status' => 'active',
            'business_name' => 'Keripik Singkong Sari', 'email_verified_at' => now(),
        ]);

        $budi = User::create([
            'name' => 'Budi Santoso', 'email' => 'talent@kivu.id',
            'password' => Hash::make('password'), 'role' => 'student', 'status' => 'active',
            'student_verified_at' => now(),
            'bio' => 'Desainer grafis & web developer. Fokus pada identitas visual dan landing page.',
            'skills' => ['Desain Grafis', 'UI/UX', 'Web Development'],
            'email_verified_at' => now(),
        ]);
        $sinta = User::create([
            'name' => 'Sinta Dewi', 'email' => 'sinta@univ.ac.id',
            'password' => Hash::make('password'), 'role' => 'student', 'status' => 'active',
            'student_verified_at' => now(),
            'bio' => 'Penulis konten dan copywriter. Suka menulis kuliner & lifestyle.',
            'skills' => ['Copywriting', 'SEO', 'Content Writing'],
            'email_verified_at' => now(),
        ]);
        $adi = User::create([
            'name' => 'Adi Wijaya', 'email' => 'adi@univ.ac.id',
            'password' => Hash::make('password'), 'role' => 'student', 'status' => 'active',
            'student_verified_at' => now(),
            'bio' => 'Web developer & editor video. Nyaman dengan Laravel dan Premiere.',
            'skills' => ['Web Development', 'Video Editing', 'Laravel'],
            'email_verified_at' => now(),
        ]);
        $dewi = User::create([
            'name' => 'Dewi Lestari', 'email' => 'dewi@gmail.com',
            'password' => Hash::make('password'), 'role' => 'student', 'status' => 'active',
            'student_verified_at' => now(),
            'bio' => 'Editor video dan fotografer produk untuk UMKM.',
            'skills' => ['Video Editing', 'Fotografi', 'Motion Graphics'],
        ]);
        $riko = User::create([
            'name' => 'Riko Pratama', 'email' => 'riko@gmail.com',
            'password' => Hash::make('password'), 'role' => 'student', 'status' => 'pending_ktm',
            'bio' => 'Mahasiswa teknik informatika, belajar desain.',
            'skills' => ['Desain Grafis'],
        ]);
        $fitri = User::create([
            'name' => 'Fitri Saputra', 'email' => 'fitri@gmail.com',
            'password' => Hash::make('password'), 'role' => 'student', 'status' => 'pending_ktm',
            'bio' => 'Suka fotografi produk dan editing foto.',
            'skills' => ['Fotografi', 'Editing Foto'],
        ]);

        // Dummy file KTM privat (Dewi sudah diverifikasi; Riko menunggu; Fitri belum unggah)
        foreach ([$dewi, $riko] as $u) {
            $path = 'ktm/'.$u->id.'/ktm.jpg';
            Storage::disk('local')->put($path, $png);
            $u->update(['ktm_path' => $path]);
        }

        // ===== Katalog referensi =====
        $cat = Category::pluck('id', 'slug');
        $skillId = Skill::pluck('id', 'slug');

        // ===== Proyek (9) =====
        $p1 = Project::create(['owner_id' => $umkm->id, 'category_id' => $cat['desain-grafis'], 'title' => 'Desain Logo Warung Kopi Menangan', 'description' => 'Desain logo modern untuk warung kopi. Output: file vector (AI/SVG) + PNG transparan.', 'budget' => 500000, 'due_date' => now()->addDays(14), 'status' => 'OPEN']);
        $p2 = Project::create(['owner_id' => $batik->id, 'category_id' => $cat['web-it'], 'title' => 'Landing Page Batik Heritage', 'description' => 'Landing page responsif untuk katalog batik dan kontak WhatsApp.', 'budget' => 1500000, 'due_date' => now()->addDays(30), 'status' => 'OPEN']);
        $p3 = Project::create(['owner_id' => $umkm->id, 'category_id' => $cat['penulisan-konten'], 'title' => 'Artikel Kuliner Nusantara', 'description' => 'Artikel 800-1000 kata tentang kuliner tradisional Indonesia.', 'budget' => 300000, 'due_date' => now()->addDays(7), 'status' => 'IN_PROGRESS']);
        $p4 = Project::create(['owner_id' => $keripik->id, 'category_id' => $cat['fotografi-produk'], 'title' => 'Foto Produk Keripik Singkong', 'description' => 'Foto produk untuk katalog online, 10 foto high-res.', 'budget' => 600000, 'due_date' => now()->addDays(10), 'status' => 'IN_PROGRESS']);
        $p5 = Project::create(['owner_id' => $keripik->id, 'category_id' => $cat['video-animasi'], 'title' => 'Video Promosi Keripik Singkong', 'description' => 'Video promosi 30 detik untuk media sosial.', 'budget' => 800000, 'due_date' => now()->addDays(12), 'status' => 'SUBMITTED']);
        $p6 = Project::create(['owner_id' => $batik->id, 'category_id' => $cat['desain-grafis'], 'title' => 'Branding Toko Batik', 'description' => 'Paket branding: logo, kartu nama, dan template feed.', 'budget' => 1000000, 'due_date' => now()->subDays(5), 'status' => 'COMPLETED']);
        $p7 = Project::create(['owner_id' => $umkm->id, 'category_id' => $cat['social-media-management'], 'title' => 'Desain Feed Instagram', 'description' => '9 template feed Instagram untuk promosi bulanan.', 'budget' => 400000, 'due_date' => now()->subDays(10), 'status' => 'COMPLETED']);
        $p8 = Project::create(['owner_id' => $keripik->id, 'category_id' => $cat['desain-grafis'], 'title' => 'Redesign Kemasan (Dibatalkan)', 'description' => 'Redesign kemasan keripik (proyek dibatalkan).', 'budget' => 250000, 'due_date' => now()->addDays(20), 'status' => 'CANCELLED']);
        $p9 = Project::create(['owner_id' => $umkm->id, 'category_id' => $cat['desain-grafis'], 'title' => 'Logo Toko Roti (Refund)', 'description' => 'Logo toko roti; dibuka kembali setelah sengketa.', 'budget' => 350000, 'due_date' => now()->addDays(25), 'status' => 'OPEN']);

        // ===== Keahlian proyek =====
        $p1->skills()->sync([$skillId['desain-grafis'], $skillId['branding']]);
        $p2->skills()->sync([$skillId['web-development'], $skillId['ui-ux']]);
        $p3->skills()->sync([$skillId['content-writing'], $skillId['seo']]);
        $p4->skills()->sync([$skillId['fotografi'], $skillId['editing-foto']]);
        $p5->skills()->sync([$skillId['video-editing'], $skillId['motion-graphics']]);
        $p6->skills()->sync([$skillId['branding'], $skillId['desain-grafis']]);
        $p7->skills()->sync([$skillId['social-media'], $skillId['desain-grafis']]);
        $p8->skills()->sync([$skillId['desain-grafis']]);
        $p9->skills()->sync([$skillId['desain-grafis'], $skillId['branding']]);

        // ===== Lamaran =====
        Application::create(['project_id' => $p1->id, 'student_id' => $budi->id, 'status' => 'PENDING', 'message' => 'Saya berpengalaman membuat logo untuk F&B.']);
        Application::create(['project_id' => $p1->id, 'student_id' => $riko->id, 'status' => 'PENDING', 'message' => 'Saya ingin mencoba, ini portofolio saya.']);
        Application::create(['project_id' => $p2->id, 'student_id' => $adi->id, 'status' => 'PENDING', 'message' => 'Sudah beberapa kali membuat landing page UMKM.']);
        Application::create(['project_id' => $p3->id, 'student_id' => $sinta->id, 'status' => 'ACCEPTED', 'message' => 'Saya penulis konten kuliner.']);
        Application::create(['project_id' => $p3->id, 'student_id' => $budi->id, 'status' => 'WITHDRAWN', 'message' => 'Tertarik, tapi saya tarik lamaran.']);
        Application::create(['project_id' => $p4->id, 'student_id' => $fitri->id, 'status' => 'ACCEPTED', 'message' => 'Saya fotografer produk.']);
        Application::create(['project_id' => $p5->id, 'student_id' => $dewi->id, 'status' => 'ACCEPTED', 'message' => 'Berpengalaman editing video promosi.']);
        Application::create(['project_id' => $p5->id, 'student_id' => $sinta->id, 'status' => 'REJECTED', 'message' => 'Saya juga bisa bantu.', 'rejection_note' => 'Terima kasih, portofolio video belum sesuai kebutuhan.']);
        Application::create(['project_id' => $p6->id, 'student_id' => $budi->id, 'status' => 'ACCEPTED', 'message' => 'Saya bisa mengerjakan paket branding lengkap.']);
        Application::create(['project_id' => $p7->id, 'student_id' => $adi->id, 'status' => 'ACCEPTED', 'message' => 'Template feed sudah biasa saya buat.']);
        Application::create(['project_id' => $p8->id, 'student_id' => $riko->id, 'status' => 'REJECTED', 'message' => 'Saya tertarik.', 'rejection_note' => 'Proyek dibatalkan oleh UMKM.']);
        Application::create(['project_id' => $p9->id, 'student_id' => $budi->id, 'status' => 'PENDING', 'message' => 'Lamaran direset setelah refund.']);

        // ===== Submission =====
        Submission::create(['project_id' => $p3->id, 'student_id' => $sinta->id, 'file_path' => null, 'link' => 'https://docs.google.com/document/d/contoh-artikel', 'note' => 'Draft artikel kuliner.', 'revision_note' => 'Mohon tambahkan referensi foto asli dan perbaiki subjudul.', 'status' => 'REVISION']);
        Submission::create(['project_id' => $p5->id, 'student_id' => $dewi->id, 'file_path' => 'submissions/video-promosi.mp4', 'link' => 'https://drive.google.com/file/d/contoh-video', 'note' => 'Video 30 detik siap.', 'status' => 'SUBMITTED']);
        Submission::create(['project_id' => $p6->id, 'student_id' => $budi->id, 'file_path' => 'submissions/branding-batik.zip', 'link' => null, 'note' => 'Paket branding lengkap.', 'status' => 'APPROVED']);
        Submission::create(['project_id' => $p7->id, 'student_id' => $adi->id, 'file_path' => null, 'link' => 'https://figma.com/file/contoh-feed', 'note' => '9 template feed.', 'status' => 'APPROVED']);

        // ===== Wallet =====
        $wBudi = Wallet::create(['student_id' => $budi->id, 'balance' => 500000]);
        Wallet::create(['student_id' => $sinta->id, 'balance' => 0]);
        Wallet::create(['student_id' => $adi->id, 'balance' => 400000]);
        Wallet::create(['student_id' => $dewi->id, 'balance' => 0]);
        Wallet::create(['student_id' => $riko->id, 'balance' => 0]);
        Wallet::create(['student_id' => $fitri->id, 'balance' => 0]);

        // ===== Withdrawal =====
        $wdApproved = Withdrawal::create(['student_id' => $budi->id, 'amount' => 300000, 'status' => 'APPROVED', 'bank_name' => 'BCA', 'bank_account' => '1234567890', 'note' => 'Ke rekening utama']);
        $wdPending = Withdrawal::create(['student_id' => $budi->id, 'amount' => 200000, 'status' => 'PENDING', 'bank_name' => 'GoPay', 'bank_account' => '081234567890', 'note' => 'Untuk kebutuhan kampus']);
        $wdCancelled = Withdrawal::create(['student_id' => $adi->id, 'amount' => 100000, 'status' => 'CANCELLED', 'bank_name' => 'Mandiri', 'bank_account' => '9876543210', 'note' => 'Dibatalkan mahasiswa']);
        $wdRejected = Withdrawal::create(['student_id' => $adi->id, 'amount' => 50000, 'status' => 'REJECTED', 'bank_name' => 'DANA', 'bank_account' => '081200000000', 'note' => 'Data rekening tidak valid']);

        // ===== Transaksi (payment / refund / withdrawal) =====
        Transaction::create(['project_id' => $p6->id, 'student_id' => $budi->id, 'amount' => 1000000, 'type' => 'payment', 'status' => 'SUCCESS']);
        Transaction::create(['project_id' => $p7->id, 'student_id' => $adi->id, 'amount' => 400000, 'type' => 'payment', 'status' => 'SUCCESS']);
        Transaction::create(['project_id' => $p9->id, 'student_id' => $budi->id, 'amount' => 350000, 'type' => 'payment', 'status' => 'SUCCESS']);
        Transaction::create(['project_id' => $p9->id, 'student_id' => $budi->id, 'amount' => 350000, 'type' => 'refund', 'status' => 'SUCCESS']);
        Transaction::create(['withdrawal_id' => $wdApproved->id, 'student_id' => $budi->id, 'amount' => 300000, 'type' => 'withdrawal', 'status' => 'SUCCESS']);
        Transaction::create(['withdrawal_id' => $wdRejected->id, 'student_id' => $adi->id, 'amount' => 50000, 'type' => 'withdrawal', 'status' => 'REJECTED']);

        // ===== Ulasan =====
        Review::create(['project_id' => $p6->id, 'reviewer_id' => $batik->id, 'reviewee_id' => $budi->id, 'rating' => 5, 'comment' => 'Hasil rapi, komunikatif, dan tepat waktu. Recommended!']);
        Review::create(['project_id' => $p7->id, 'reviewer_id' => $umkm->id, 'reviewee_id' => $adi->id, 'rating' => 4, 'comment' => 'Bagus, ada sedikit revisi tapi cepat diselesaikan.']);

        // ===== Sengketa =====
        Dispute::create(['project_id' => $p5->id, 'reporter_id' => $keripik->id, 'against_id' => $dewi->id, 'reason' => 'work_not_as_agreed', 'description' => 'Video belum sesuai brief (durasi dan musik).', 'status' => 'OPEN']);
        Dispute::create(['project_id' => $p9->id, 'reporter_id' => $umkm->id, 'against_id' => $budi->id, 'reason' => 'work_not_as_agreed', 'description' => 'Logo kurang sesuai; disepakati refund.', 'status' => 'RESOLVED', 'resolution' => 'refund', 'resolved_by' => $admin->id, 'resolved_at' => now()]);
        Dispute::create(['project_id' => $p7->id, 'reporter_id' => $adi->id, 'against_id' => $umkm->id, 'reason' => 'not_paid', 'description' => 'Pembayaran tertunda; diputuskan release.', 'status' => 'RESOLVED', 'resolution' => 'release', 'resolved_by' => $admin->id, 'resolved_at' => now()]);
        Dispute::create(['project_id' => $p4->id, 'reporter_id' => $fitri->id, 'against_id' => $keripik->id, 'reason' => 'other', 'description' => 'Dicabut setelah komunikasi.', 'status' => 'CANCELLED']);

        // ===== Portofolio (8) — 4 di antaranya sebagai jasa berbayar =====
        $portfolio = [
            [$budi, 'Redesign Logo Kafe', 'Identitas visual kafe lokal.', 'https://example.com/logo-kafe', 350000, 5],
            [$budi, 'Poster Event Kampus', 'Poster acara musik kampus.', null, null, null],
            [$sinta, 'Artikel Wisata Kuliner', 'Artikel blog 1000 kata.', 'https://example.com/artikel-kuliner', 150000, 3],
            [$sinta, 'Desain Feed Instagram', 'Template feed untuk brand fashion.', null, null, null],
            [$adi, 'Landing Page Toko', 'Landing page UMKM dengan WhatsApp.', 'https://example.com/landing-toko', 750000, 7],
            [$adi, 'Banner Promo', 'Banner promo diskon akhir tahun.', null, null, null],
            [$dewi, 'Video Editing Reels', 'Reels produk UMKM 30 detik.', 'https://example.com/reels', 300000, 4],
            [$dewi, 'Foto Produk', 'Sesi foto produk makanan.', null, null, null],
        ];
        foreach ($portfolio as $i => [$student, $title, $desc, $url, $price, $deliveryDays]) {
            $filePath = null;
            if ($i % 2 === 0) {
                $filePath = 'portfolio/'.$student->id.'/demo-'.$i.'.jpg';
                Storage::disk('public')->put($filePath, $png);
            }
            Portfolio::create([
                'student_id' => $student->id,
                'title' => $title,
                'description' => $desc,
                'url' => $url,
                'file_path' => $filePath,
                'is_service' => $price !== null,
                'price' => $price,
                'delivery_days' => $deliveryDays,
            ]);
        }

        // ===== Pesan (inbox demo) =====
        Message::create(['project_id' => $p1->id, 'sender_id' => $budi->id, 'body' => 'Halo, saya sudah melamar. Berikut portofolio logo F&B saya.']);
        Message::create(['project_id' => $p1->id, 'sender_id' => $umkm->id, 'body' => 'Terima kasih Budi, akan saya review hari ini.', 'read_at' => now()]);
        Message::create(['project_id' => $p1->id, 'sender_id' => $budi->id, 'body' => 'Siap, ditunggu kabarnya.']);
        Message::create(['project_id' => $p5->id, 'sender_id' => $keripik->id, 'body' => 'Dewi, video 30 detiknya sudah jadi?', 'read_at' => now()]);
        Message::create(['project_id' => $p5->id, 'sender_id' => $dewi->id, 'body' => 'Sudah saya kirim di submission ya, mohon dicek.']);
    }
}
