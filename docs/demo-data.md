# Data Demo KIVU

Dokumen ini menjelaskan **data demo** yang di-seed oleh `DemoSeeder`, agar semua bagian CRUD dan alur per role dapat diuji tanpa membuat data manual.

## Cara memuat / reset

```bash
php artisan demo:reset          # konfirmasi dulu
php artisan demo:reset --force  # tanpa konfirmasi
```

Perintah ini menjalankan `migrate:fresh --seed` (menghapus semua data lalu memuat data demo), membuat file dummy KTM & portofolio, dan menampilkan daftar akun.

> Pastikan `php artisan storage:link` sudah pernah dijalankan agar file portofolio tampil.

## Akun Demo (10) — password semua: `password`

| Role | Email | Nama | Status | Verifikasi |
| --- | --- | --- | --- | --- |
| Admin | `admin@kivu.id` | Administrator KIVU | active | — |
| UMKM | `umkm@kivu.id` | Warung Kopi Menangan | active | — |
| UMKM | `batik@kivu.id` | Batik Heritage | active | — |
| UMKM | `keripik@kivu.id` | Keripik Singkong Sari | active | — |
| Mahasiswa | `talent@kivu.id` | Budi Santoso | active | ✅ Lencana terverifikasi |
| Mahasiswa | `sinta@univ.ac.id` | Sinta Dewi | active | ✅ Verifikasi email kampus |
| Mahasiswa | `adi@univ.ac.id` | Adi Wijaya | active | ✅ Verifikasi email kampus |
| Mahasiswa | `dewi@gmail.com` | Dewi Lestari | active | ✅ KTM diverifikasi admin |
| Mahasiswa | `riko@gmail.com` | Riko Pratama | **pending_ktm** | ⏳ Sudah unggah KTM, menunggu |
| Mahasiswa | `fitri@gmail.com` | Fitri Saputra | **pending_ktm** | ⏳ Belum unggah KTM |

## Ringkasan Data

### Proyek (9) — semua status terwakili

| ID | Judul | Pemilik | Budget | Status |
| --- | --- | --- | --- | --- |
| 1 | Desain Logo Warung Kopi Menangan | umkm | 500.000 | OPEN |
| 2 | Landing Page Batik Heritage | batik | 1.500.000 | OPEN |
| 3 | Artikel Kuliner Nusantara | umkm | 300.000 | IN_PROGRESS (submission REVISION) |
| 4 | Foto Produk Keripik Singkong | keripik | 600.000 | IN_PROGRESS (belum submit) |
| 5 | Video Promosi Keripik Singkong | keripik | 800.000 | SUBMITTED |
| 6 | Branding Toko Batik | batik | 1.000.000 | COMPLETED |
| 7 | Desain Feed Instagram | umkm | 400.000 | COMPLETED |
| 8 | Redesign Kemasan (Dibatalkan) | keripik | 250.000 | CANCELLED |
| 9 | Logo Toko Roti (Refund) | umkm | 350.000 | OPEN (dibuka kembali via refund) |

### Lamaran (12)
- **PENDING:** Budi & Riko (proyek 1), Adi (proyek 2), Budi (proyek 9).
- **ACCEPTED:** Sinta (3), Fitri (4), Dewi (5), Budi (6), Adi (7).
- **REJECTED** (+catatan): Sinta (5), Riko (8).
- **WITHDRAWN:** Budi (3) — contoh lamar ulang.

### Submission (4)
- Proyek 3: **REVISION** (catatan revisi) — uji kirim ulang.
- Proyek 5: **SUBMITTED** — uji Setujui & Bayar / Minta Revisi.
- Proyek 6 & 7: **APPROVED** — histori selesai.

### Dompet & Penarikan
| Mahasiswa | Saldo | Penarikan |
| --- | --- | --- |
| Budi | 500.000 | APPROVED 300.000; PENDING 200.000 |
| Adi | 400.000 | CANCELLED 100.000; REJECTED 50.000 |
| Lainnya | 0 | — |

### Transaksi (6)
payment SUCCESS (×3, termasuk proyek refund), refund SUCCESS (×1), withdrawal SUCCESS (×1), withdrawal REJECTED (×1).

### Ulasan (2)
- Batik → Budi (proyek 6): ⭐5 "Hasil rapi, komunikatif, tepat waktu."
- UMKM → Adi (proyek 7): ⭐4 "Bagus, ada sedikit revisi."

### Sengketa (4)
| ID | Proyek | Pelapor → Terlapor | Status | Resolusi |
| --- | --- | --- | --- | --- |
| 1 | 5 | Keripik → Dewi | OPEN | — |
| 2 | 9 | UMKM → Budi | RESOLVED | refund |
| 3 | 7 | Adi → UMKM | RESOLVED | release |
| 4 | 4 | Fitri → Keripik | CANCELLED | — |

### Portofolio (8)
2 item tiap mahasiswa (Budi, Sinta, Adi, Dewi) — kombinasi **file gambar** (dummy) + **URL**. Semua mahasiswa punya **bio** dan **keahlian**.

### File dummy
- KTM privat: `storage/app/private/ktm/{id}/ktm.jpg` (Dewi & Riko).
- Portofolio publik: `storage/app/public/portfolio/{id}/demo-*.jpg`.

## Panduan Uji Cepat per Role

**Mahasiswa**
- `talent@kivu.id` → Peluang (pilih proyek OPEN) → Lamar; Lamaran Saya (lihat PENDING/ACCEPTED/WITHDRAWN, batalkan lamaran, kirim hasil/revisi); Dompet (saldo, tarik saldo, batalkan penarikan); Profil (bio/skill/portofolio, KTM, ulasan).
- `sinta@univ.ac.id` → proyek 3 status **REVISION** → "Kirim Revisi".
- `dewi@gmail.com` → proyek 5 siap review; profil terverifikasi + portofolio bergambar.
- `riko@gmail.com` → banner **pending KTM** (tetap bisa melamar).

**UMKM**
- `umkm@kivu.id` → Proyek Saya (OPEN/IN_PROGRESS/COMPLETED/CANCELLED), Edits & Batalkan (proyek 1/2), Pelamar (terima/tolak), Sengketa.
- `keripik@kivu.id` → proyek 5 (SUBMITTED) → **Setujui & Bayar** atau **Minta Revisi**; proyek 4 (Pelamar Fitri).
- `batik@kivu.id` → proyek 6 (COMPLETED) → beri ulasan; proyek 2 OPEN.

**Admin**
- Dashboard (metrik), Pengguna (proyek: verifikasi/batalkan KTM Dewi; suspend), Proyek (takedown: coba proyek COMPLETED → diblokir), Transaksi (refund & REJECTED), Penarikan (setujui/tolak), Sengketa (proses OPEN).

> Skenario lengkap langkah-demi-langkah ada di `docs/scenario-pengujian.md` (bila tersedia).
