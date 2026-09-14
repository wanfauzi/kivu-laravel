# KIVU — Dokumen Keamanan (security.md)

> Acuan keamanan aplikasi KIVU (Marketplace Micro-Freelance Mahasiswa × UMKM).
> Ditulis dengan bahasa sederhana agar mudah dipahami, namun lengkap untuk developer, penguji, dan juri.
> **Versi:** 1.0 · **Terakhir diperbarui:** 14 September 2026

---

## 1. Ringkasan

Dokumen ini menjelaskan:
1. **Apa yang sudah diamankan** di KIVU dan di mana kodenya.
2. **Risiko yang mungkin terjadi**, serta langkah mitigasinya.
3. **Rekomendasi lanjutan** (untuk demo dan untuk produksi).
4. **Checklist** yang bisa dicentang sebelum demo/rilis.

**Kesimpulan singkat:** Untuk kebutuhan **demo**, keamanan inti KIVU sudah memadai — login terlindungi, hak akses per-peran ditegakkan, uang tidak bisa diproses ganda, dan data pribadi (KTM) tersimpan privat. Yang tersisa adalah **penyempurnaan** (header keamanan, enkripsi sesi, verifikasi email, HTTPS) yang lebih relevan untuk produksi.

Prinsip yang dipegang: **keamanan berlapis (defense in depth)** — satu lapisan gagal, lapisan lain tetap melindungi.

---

## 2. Istilah Dasar (penjelasan awam)

| Istilah | Arti sederhana | Contoh di KIVU |
|---|---|---|
| **Authentication** | Memastikan Anda siapa (login). | Email + password di `/login`. |
| **Authorization** | Memastikan Anda **boleh** melakukan sesuatu. | Mahasiswa tak bisa membuka menu admin. |
| **Hashing** | Mengacak password agar tak bisa dibaca balik. | Password disimpan teracak di database. |
| **Session** | "Kartu identitas sementara" setelah login. | Cookie sesi saat Anda menjelajah. |
| **Rate limiting** | Membatasi jumlah percobaan. | Login gagal berulang → diblokir sementara (429). |
| **Validasi input** | Menyaring data yang dikirim pengguna. | Panjang teks, jumlah uang, tipe file. |
| **XSS** | Menyisipkan script jahat lewat teks. | Teks pengguna "dinetralkan" saat ditampilkan. |
| **CSRF** | Situs jahat diam-diam menekan tombol atas nama Anda. | Setiap form punya token rahasia. |
| **SQL Injection** | Menyusupkan perintah jahat ke database. | Query database memakai ORM berparameter. |
| **IDOR** | Mengakses data orang lain hanya dengan mengubah angka di URL. | Aksi cek kepemilikan (Policy/Gate). |
| **PII** | Data pribadi yang sensitif. | KTM, email, nomor rekening. |

---

## 3. Model Peran & Hak Akses

| Peran | Kemampuan utama |
|---|---|
| **Mahasiswa** | Cari peluang, melamar, kirim hasil (dan revisi), dompet & tarik saldo, unggah KTM, atur portofolio. |
| **UMKM** | Buat/edit/batalkan proyek, kelola pelamar (terima/tolak), tinjau & bayar hasil, beri ulasan. |
| **Admin** | Dashboard, kelola pengguna (verifikasi KTM/suspend), kelola proyek, transaksi, penarikan, sengketa. |

**Cara penguncian akses (3 lapis):**
1. **Route middleware** `auth` + `role:*` — halaman hanya bisa dibuka peran yang sesuai (`routes/web.php`, alias `role` didaftarkan di `bootstrap/app.php`).
2. **Policy/Gate** — aturan izin per-aksi di `app/Policies/` (lihat §4).
3. **Cek kepemilikan** — hanya pemilik data yang boleh mengubah (mis. hanya pemilik proyek yang bisa menerima pelamar).

Akses lintas peran diuji dan menghasilkan **403 (ditolak)**.

---

## 4. Kontrol Keamanan yang SUDAH Ada

### 4.1 Akun & Login
| Kontrol | Penjelasan | Lokasi | Status |
|---|---|---|---|
| Password di-hash | Password tidak disimpan apa adanya. | `app/Models/User.php`, `AuthController` | ✅ |
| Sesi aman | ID sesi diperbarui saat login, dihapus saat logout. | `AuthController::login/logout` | ✅ |
| Anti brute-force | Login/daftar dibatasi `throttle:10,1`. | `routes/web.php` | ✅ |
| Akun suspend | Akun nonaktif tak bisa login & diblokir di middleware. | `RoleMiddleware`, `AuthController` | ✅ |
| Ganti password | Wajib memasukkan password lama. | `Student/Profile`, `Umkm/Profile` | ✅ |

### 4.2 Otorisasi (paling penting)
| Policy | Aksi yang dijaga |
|---|---|
| `ApplicationPolicy` | `apply`, `withdraw`, `respond` (terima/tolak pelamar), `view` |
| `ProjectPolicy` | `update`, `cancel`, `delete`, `refund` |
| `SubmissionPolicy` | `create`, `resubmit`, `view` |
| `WithdrawalPolicy` | `update` (admin setujui/tolak), `cancel` |
| `DisputePolicy` | `create`, `view`, `resolve`, `cancel` |
| `UserPolicy` | `suspend`, `activate`, `verifyKtm`, `revokeKtm` |
| `PortfolioPolicy` | `manage` (pemilik), `view` |

> Semua aksi sensitif memanggil `Gate::authorize(...)` **di server**, bukan hanya menyembunyikan tombol.

### 4.3 Perlindungan Data & Input
| Kontrol | Penjelasan | Status |
|---|---|---|
| Validasi input di server | Semua form divalidasi (panjang, format, tipe). | ✅ |
| Anti-XSS | Output Blade otomatis di-escape; tidak ada `{!! !!}`. | ✅ |
| Anti-CSRF | Token Laravel di setiap form/aksi Livewire. | ✅ |
| Anti-SQLi | ORM Eloquent berparameter. | ✅ |
| Skema URL aman | Validator `url` menolak `javascript:`/`data:` (diuji). | ✅ |
| Upload dibatasi | KTM & portofolio: `jpg/jpeg/png/pdf` + batas ukuran. | ✅ |
| KTM disimpan privat | Bukan di folder publik; akses lewat rute ber-otorisasi. | ✅ |

### 4.4 Integritas Keuangan
- Semua operasi uang (tarik saldo, setujui & bayar, refund, tolak penarikan) dijalankan **atomik** (`DB::transaction`) dan memakai **`lockForUpdate`** → mencegah **dobel-bayar / dobel-tarik** meski ditekan cepat/bersamaan.
- Ada **jejak audit transaksi** (`transactions`: payment / withdrawal / refund).

### 4.5 Konfigurasi & Operasional
| Kontrol | Nilai / Lokasi | Status |
|---|---|---|
| Debug mati | `APP_DEBUG=false` (`.env`) | ✅ |
| Kunci aplikasi | `APP_KEY` terisi | ✅ |
| `.env` tidak di-commit | ada di `.gitignore` | ✅ |
| User database khusus | `DB_USERNAME=kivu` (bukan root) | ✅ |
| Audit dependensi | `composer audit` & `npm audit` → 0 kerentanan | ✅ |

### 4.6 UX Keamanan
- **Modal konfirmasi** untuk aksi berbahaya (takedown proyek, refund, tolak/terima, tarik saldo, batalkan lamaran/penarikan/sengketa) — `resources/views/components/ui/confirm-modal.blade.php`.
- **Badge kepercayaan** mahasiswa (KTM/.ac.id) + profil publik yang menyembunyikan email.

---

## 5. Data Pribadi & Privasi

| Data | Sensitivitas | Penyimpanan | Siapa yang boleh lihat |
|---|---|---|---|
| Nama, email | Sedang | `users` | Pemilik, admin |
| KTM | **Tinggi** | disk privat `storage/app/private` | Pemilik + admin (`KtmController`) |
| Portofolio (file/URL) | Publik (disengaja) | disk publik | Publik |
| Bio, skill | Publik (disengaja) | `users` | Publik |
| Token pembayaran/penarikan | Sedang | `withdrawals`, `transactions` | Pemilik + admin |

Prinsip: **kumpulkan seperlunya**, simpan data sensitif secara privat, tampilkan data publik tanpa identitas pribadi.

---

## 6. Risiko & Kemungkinan yang Terjadi

| # | Risiko | Penjelasan awam | Dampak | Kemungkinan | Mitigasi |
|---|---|---|---|---|---|
| 1 | Email kampus dipalsukan | Belum ada verifikasi kepemilikan email; `.ac.id` langsung "terverifikasi". | Sedang | Sedang | Tambah verifikasi email (link). |
| 2 | Tanpa HTTPS | Data bisa disadap di jaringan. | Tinggi | (Demo lokal: rendah) | Wajib `https://` di produksi. |
| 3 | Header keamanan belum ada | Bisa terkena clickjacking/sniffing. | Rendah | Rendah | Middleware header keamanan. |
| 4 | Sesi tak dienkripsi | Jika DB bocor, isi sesi terbaca. | Sedang | Rendah | `SESSION_ENCRYPT=true`. |
| 5 | KTM bisa ter-cache | Bukti KTM tersimpan di cache browser/proxy. | Sedang | Rendah | Header `Cache-Control: private, no-store`. |
| 6 | Password demo lemah | `password` mudah ditebak. | Sedang | (Demo: sedang) | Ganti password demo lebih kuat. |
| 7 | Spam / abuse | Rate limit baru di login/daftar. | Rendah | Sedang | Perluas rate limit. |
| 8 | Enumerasi profil publik | ID berurutan mudah dijelajahi otomatis. | Rendah | Sedang | Pakai slug alamat profil. |

> **Tidak ditemukan** celah kritis (SQL Injection, RCE, IDOR terbuka, XSS tersimpan) pada kode saat ini.

---

## 7. Rekomendasi Lanjutan (Roadmap)

### 7.1 Demo Security Pack (ringan, direkomendasikan sebelum demo)
| Langkah | Manfaat | Effort |
|---|---|---|
| Middleware **header keamanan** (`X-Content-Type-Options: nosniff`, `X-Frame-Options: SAMEORIGIN`, `Referrer-Policy`, `Permissions-Policy`) | Menutup #3 | Kecil |
| `KtmController` → `Cache-Control: private, no-store` + `nosniff` | Menutup #5 | Kecil |
| `SESSION_ENCRYPT=true` | Menutup #4 | Kecil |
| (Opsional) ganti password akun demo | Menutup #6 | Kecil |

### 7.2 Menuju Produksi (prioritas)
| Prioritas | Item |
|---|---|
| P1 | HTTPS/TLS + `SESSION_SECURE_COOKIE=true`, verifikasi email, rotasi kredensial demo. |
| P2 | CSP, 2FA (opsional), perluasan rate limit, monitoring & logging aktivitas mencurigakan. |
| P3 | Backup terjadwal, least-privilege DB, slug profil, uji penetrasi. |

---

## 8. Checklist Keamanan

### 8.1 Sebelum Demo
- [x] Password ter-hash
- [x] Sesi regenerate/invalidate
- [x] Throttle login & daftar
- [x] Otorisasi per-role + Policy
- [x] Validasi input & escaping output
- [x] CSRF aktif
- [x] Upload divalidasi; KTM privat
- [x] `APP_DEBUG=false`, `APP_KEY` terisi
- [x] `.env` tidak ter-commit
- [x] Audit dependensi bersih
- [ ] Header keamanan (Demo Security Pack)
- [ ] KTM `Cache-Control: no-store`
- [ ] `SESSION_ENCRYPT=true`
- [ ] Password demo diperkuat

### 8.2 Sebelum Produksi
- [ ] HTTPS aktif + cookie `secure`
- [ ] Verifikasi email pengguna
- [ ] CSP & header keamanan lengkap
- [ ] Rate limit diperluas
- [ ] Monitoring & logging
- [ ] Backup & pemulihan
- [ ] Least-privilege user database
- [ ] Kredensial demo dihapus/di-rotasi

---

## 9. Prosedur Operasional Singkat

- **Pelaporan masalah keamanan:** catat tanggal, langkah reproduksi, dampak; simpan di issue privat (bukan publik).
- **Penanganan insiden:** (1) nonaktifkan akun terkait (suspend), (2) putar (rotate) kredensial (`APP_KEY`, password DB), (3) tinjau `storage/logs`, (4) perbaiki & uji.
- **Akses `.env`/server:** hanya maintainer tepercaya. Jangan pernah menaruh `.env` di repo atau membagikannya.
- **Ubah konfigurasi:** karena `config:cache` aktif, setiap perubahan `.env` perlu `php artisan config:clear`.

---

## 10. Lampiran

### 10.1 Peta File Keamanan
| Area | File |
|---|---|
| Middleware peran | `app/Http/Middleware/RoleMiddleware.php` |
| Registrasi middleware/alias | `bootstrap/app.php` |
| Autentikasi | `app/Http/Controllers/AuthController.php` |
| Akses KTM | `app/Http/Controllers/KtmController.php` |
| Policies | `app/Policies/*.php` |
| Komponen konfirmasi | `resources/views/components/ui/confirm-modal.blade.php` |
| Badge status/kepercayaan | `resources/views/components/ui/status-badge.blade.php`, `verified-badge.blade.php` |
| Rute | `routes/web.php` |
| Konfigurasi | `.env`, `config/session.php`, `config/filesystems.php` |

### 10.2 Perintah Audit
```bash
composer audit          # kerentanan paket PHP
npm audit               # kerentanan paket JS
php -l app/...          # cek sintaks
php artisan route:list  # tinjau daftar rute & middleware
```

### 10.3 Glosarium
Singkatan & istilah: **PII** (data pribadi), **XSS/SQLi/CSRF/IDOR** (lihat §2), **Rate limit**, **Hash**, **Session**, **Policy/Gate**.

### 10.4 Riwayat Perubahan
| Versi | Tanggal | Catatan |
|---|---|---|
| 1.0 | 14 Sep 2026 | Dokumen awal; audit kontrol, risiko, roadmap, checklist. |
