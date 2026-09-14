<div class="bg-white">
    {{-- ============ TOP BAR ============ --}}
    <header class="sticky top-0 z-40 border-b border-gray-100 bg-white/80 backdrop-blur-md">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
            <a href="/" class="flex shrink-0 items-center">
                <img src="{{ asset('images/kivu-logo.png') }}" alt="KIVU" class="h-8 w-auto">
            </a>
            <nav class="hidden items-center gap-7 text-sm font-medium text-gray-600 md:flex">
                <a href="#peluang" class="transition hover:text-blue-600">Peluang</a>
                <a href="#talent" class="transition hover:text-blue-600">Talent</a>
                <a href="#fitur" class="transition hover:text-blue-600">Fitur</a>
                <a href="#cara-kerja" class="transition hover:text-blue-600">Cara Kerja</a>
            </nav>
            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full px-4 py-2 text-sm font-semibold text-gray-700 transition hover:text-blue-600">Masuk</a>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full bg-blue-600 px-5 py-2 text-sm font-semibold text-white shadow-sm shadow-blue-600/20 transition hover:bg-blue-700">Daftar</a>
            </div>
        </div>
    </header>

    {{-- ============ HERO ============ --}}
    <section class="relative overflow-hidden">
        {{-- background blobs --}}
        <div class="pointer-events-none absolute inset-0 -z-10">
            <div class="absolute -top-24 -right-24 h-[420px] w-[420px] rounded-full bg-gradient-to-br from-blue-300/40 to-sky-200/30 blur-3xl"></div>
            <div class="absolute top-40 -left-32 h-[380px] w-[380px] rounded-full bg-gradient-to-br from-indigo-200/40 to-blue-100/20 blur-3xl"></div>
            <div class="absolute bottom-0 left-1/3 h-[280px] w-[280px] rounded-full bg-gradient-to-br from-sky-100/60 to-blue-50 blur-3xl"></div>
        </div>

        <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 py-16 sm:px-6 sm:py-24 lg:grid-cols-2 lg:px-8">
            <div class="reveal">
                <p class="inline-flex items-center gap-2 rounded-full border border-blue-100 bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                    <span class="flex h-2 w-2 rounded-full bg-blue-500"></span>
                    Marketplace micro-freelance Mahasiswa × UMKM
                </p>
                <h1 class="mt-6 text-4xl font-extrabold leading-[1.1] tracking-tight text-gray-900 sm:text-5xl lg:text-6xl">
                    Bangun portofolio.<br>
                    <span class="bg-gradient-to-r from-blue-600 via-indigo-500 to-sky-500 bg-clip-text text-transparent">Bantu UMKM tumbuh.</span>
                </h1>
                <p class="mt-6 max-w-xl text-lg leading-relaxed text-gray-600">
                    KIVU menghubungkan mahasiswa berbakat dengan UMKM yang butuh bantuan nyata — desain, website, konten, dan tugas kreatif lainnya. Alur jelas: <span class="font-semibold text-gray-900">posting → lamar → kerja → dibayar</span>.
                </p>
                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a href="{{ route('register') }}" class="group inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 px-7 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-600/25 transition hover:shadow-blue-600/40">
                        Mulai Sekarang — Gratis
                        <x-icon name="arrow-right" :size="18" class="transition-transform group-hover:translate-x-0.5" />
                    </a>
                    <a href="#cara-kerja" class="inline-flex items-center justify-center rounded-full border border-gray-200 bg-white px-7 py-3.5 text-sm font-semibold text-gray-700 transition hover:border-blue-200 hover:text-blue-600">Lihat Cara Kerja</a>
                </div>
                <p class="mt-4 text-xs text-gray-400">Email kampus <span class="font-semibold text-gray-600">.ac.id</span> langsung aktif. Gmail perlu verifikasi KTM.</p>
            </div>

            <div class="reveal relative" style="transition-delay: 120ms">
                <div class="relative mx-auto max-w-md">
                    <div class="absolute -inset-3 -z-10 rounded-[2rem] bg-gradient-to-br from-blue-400/30 via-indigo-300/20 to-sky-300/30 blur-2xl"></div>

                    @forelse($projects->take(1) as $project)
                    <div class="animate-float rounded-3xl border border-white/60 bg-white/80 p-6 shadow-xl shadow-blue-900/5 backdrop-blur">
                        <div class="flex items-center justify-between gap-2">
                            <p class="line-clamp-1 text-base font-bold text-gray-900">{{ $project->title }}</p>
                            <x-ui.status-badge :value="$project->status" kind="project" />
                        </div>
                        <p class="mt-2 line-clamp-2 text-sm text-gray-500">{{ $project->description }}</p>
                        <div class="mt-5 flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-400">Budget</p>
                                <p class="text-xl font-extrabold text-blue-600">Rp {{ number_format($project->budget, 0, ',', '.') }}</p>
                            </div>
                            <span class="flex items-center gap-1.5 text-xs text-gray-500">
                                <x-ui.avatar :name="$project->owner->name ?? ''" size="xs" /> {{ $project->owner->name ?? 'UMKM' }}
                            </span>
                        </div>
                        <a href="{{ route('login') }}" class="mt-5 flex items-center justify-center gap-2 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 py-3 text-sm font-semibold text-white transition hover:opacity-95">
                            Lamar Proyek <x-icon name="arrow-right" :size="16" />
                        </a>
                    </div>
                    @empty
                    <div class="rounded-3xl border border-white/60 bg-white/80 p-8 text-center text-gray-400 shadow-xl backdrop-blur">
                        Belum ada proyek terbuka saat ini.
                    </div>
                    @endforelse

                    <div class="pointer-events-none absolute -top-6 -right-6 hidden rounded-2xl border border-gray-100 bg-white px-4 py-3 shadow-lg sm:block">
                        <div class="flex items-center gap-1.5 text-amber-500">
                            <x-icon name="star" :size="16" :filled="true" />
                            <span class="text-sm font-bold text-gray-900">4.9</span>
                        </div>
                        <p class="text-xs text-gray-400">Rating UMKM</p>
                    </div>
                    <div class="pointer-events-none absolute -bottom-5 -left-5 hidden rounded-2xl border border-gray-100 bg-white px-4 py-3 shadow-lg sm:block">
                        <div class="flex items-center gap-2 text-sm font-bold text-gray-900">
                            <x-icon name="wallet" :size="16" class="text-blue-600" /> Dibayar Aman
                        </div>
                        <p class="text-xs text-gray-400">Wallet otomatis</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ LOGO / TRUST STRIP ============ --}}
    <section class="border-y border-gray-100 bg-gray-50/60">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <p class="text-center text-xs font-semibold uppercase tracking-widest text-gray-400">Dipercaya mahasiswa & UMKM di Indonesia</p>
            <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
                <div class="rounded-2xl border border-gray-100 bg-white px-4 py-4 text-center">
                    <p class="text-2xl font-extrabold text-gray-900"><span data-count="{{ $openCount }}">0</span><span class="text-blue-600">+</span></p>
                    <p class="text-sm text-gray-500">Proyek Terbuka</p>
                </div>
                <div class="rounded-2xl border border-gray-100 bg-white px-4 py-4 text-center">
                    <p class="text-2xl font-extrabold text-gray-900"><span data-count="{{ $studentCount }}">0</span><span class="text-blue-600">+</span></p>
                    <p class="text-sm text-gray-500">Mahasiswa Aktif</p>
                </div>
                <div class="rounded-2xl border border-gray-100 bg-white px-4 py-4 text-center">
                    <p class="text-2xl font-extrabold text-gray-900"><span data-count="{{ $completedCount }}">0</span><span class="text-blue-600">+</span></p>
                    <p class="text-sm text-gray-500">Proyek Selesai</p>
                </div>
                <div class="rounded-2xl border border-gray-100 bg-white px-4 py-4 text-center">
                    <p class="text-2xl font-extrabold text-gray-900">100<span class="text-blue-600">%</span></p>
                    <p class="text-sm text-gray-500">Pembayaran Terjamin</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ PELUANG ============ --}}
    <section id="peluang" class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
        <div class="reveal mx-auto max-w-2xl text-center">
            <p class="text-sm font-semibold uppercase tracking-widest text-blue-600">Peluang Nyata</p>
            <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">Proyek dari UMKM terverifikasi</h2>
            <p class="mt-3 text-gray-500">Budget transparan, deskripsi jelas, siap untuk dikerjakan.</p>
            <div class="relative mx-auto mt-6 max-w-md">
                <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                    <x-icon name="search" :size="18" />
                </span>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari proyek..." class="h-12 w-full rounded-full border border-gray-200 bg-white pl-11 pr-4 text-sm text-gray-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
            </div>
        </div>

        <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($projects as $i => $project)
            <div class="reveal group flex flex-col rounded-3xl border border-gray-100 bg-white p-6 transition duration-300 hover:-translate-y-1 hover:border-blue-100 hover:shadow-xl hover:shadow-blue-500/5" style="transition-delay: {{ $i * 60 }}ms">
                <div class="flex items-start justify-between gap-3">
                    <h3 class="line-clamp-1 text-base font-bold text-gray-900">{{ $project->title }}</h3>
                    <x-ui.status-badge :value="$project->status" kind="project" />
                </div>
                <p class="mt-2 line-clamp-2 flex-1 text-sm text-gray-500">{{ $project->description }}</p>
                <div class="mt-5 flex items-center justify-between pt-4">
                    <div>
                        <p class="text-lg font-extrabold text-blue-600">Rp {{ number_format($project->budget, 0, ',', '.') }}</p>
                        <p class="flex items-center gap-1.5 text-xs text-gray-400">
                            <x-ui.avatar :name="$project->owner->name ?? ''" size="xs" /> {{ $project->owner->name ?? 'UMKM' }}
                        </p>
                    </div>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1 rounded-full border border-gray-200 px-4 py-2 text-xs font-semibold text-gray-700 transition group-hover:border-blue-600 group-hover:bg-blue-600 group-hover:text-white">
                        Detail <x-icon name="arrow-right" :size="14" />
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full rounded-3xl border border-dashed border-gray-200 bg-white px-6 py-16 text-center text-sm text-gray-400">
                Tidak ada proyek yang cocok. Coba kata kunci lain.
            </div>
            @endforelse
        </div>
    </section>

    {{-- ============ FITUR ============ --}}
    <section id="fitur" class="border-y border-gray-100 bg-gray-50/60">
        <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <div class="reveal mx-auto max-w-2xl text-center">
                <p class="text-sm font-semibold uppercase tracking-widest text-blue-600">Kenapa KIVU</p>
                <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">Semua yang kamu butuhkan untuk mulai berkarya</h2>
            </div>
            <div class="mt-12 grid gap-6 md:grid-cols-3">
                <div class="reveal rounded-3xl border border-gray-100 bg-white p-8 transition hover:shadow-xl hover:shadow-blue-500/5">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/25">
                        <x-icon name="briefcase" :size="26" />
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-gray-900">Peluang Terkurasi</h3>
                    <p class="mt-2 text-sm leading-relaxed text-gray-500">Proyek dari UMKM terverifikasi dengan budget transparan dan deskripsi jelas. Pilih sesuai skill-mu.</p>
                </div>
                <div class="reveal rounded-3xl border border-gray-100 bg-white p-8 transition hover:shadow-xl hover:shadow-blue-500/5" style="transition-delay: 80ms">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-lg shadow-emerald-500/25">
                        <x-icon name="wallet" :size="26" />
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-gray-900">Pembayaran Terjamin</h3>
                    <p class="mt-2 text-sm leading-relaxed text-gray-500">Submission disetujui → transaksi tercatat → saldo wallet bertambah otomatis. Tanpa ribet.</p>
                </div>
                <div class="reveal rounded-3xl border border-gray-100 bg-white p-8 transition hover:shadow-xl hover:shadow-blue-500/5" style="transition-delay: 160ms">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-lg shadow-amber-500/25">
                        <x-icon name="star" :size="26" :filled="true" />
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-gray-900">Portofolio & Reputasi</h3>
                    <p class="mt-2 text-sm leading-relaxed text-gray-500">Selesaikan proyek, kumpulkan review, dan bangun kredibilitas profesional sejak kuliah.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ TALENT ============ --}}
    <section id="talent" class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
        <div class="reveal mx-auto max-w-2xl text-center">
            <p class="text-sm font-semibold uppercase tracking-widest text-blue-600">Talent Terbaik</p>
            <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">Dipercaya lewat review nyata</h2>
            <p class="mt-3 text-gray-500">Mahasiswa aktif dengan reputasi terbaik di KIVU.</p>
        </div>
        <div class="mt-12 grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-4">
            @forelse($talent as $i => $student)
            <div class="reveal rounded-3xl border border-gray-100 bg-white p-6 text-center transition hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-500/5" style="transition-delay: {{ $i * 50 }}ms">
                <x-ui.avatar :name="$student->name" size="lg" class="mx-auto" />
                <p class="mt-3 flex items-center justify-center gap-1.5 truncate text-sm font-bold text-gray-900">{{ $student->name }}</p>
                <div class="mt-1 flex items-center justify-center">
                    <x-ui.verified-badge :user="$student" />
                </div>
                <p class="text-xs text-gray-400">Mahasiswa terverifikasi</p>
                <div class="mt-3 inline-flex items-center gap-1 rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-600">
                    <x-icon name="star" :size="14" :filled="true" /> {{ $student->review_count }} <span class="font-normal text-amber-500">review</span>
                </div>
                <a href="{{ route('talents.show', $student->id) }}" class="mt-4 inline-flex w-full items-center justify-center rounded-full border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:border-blue-600 hover:text-blue-600">Lihat Profil</a>
            </div>
            @empty
            <p class="col-span-full py-10 text-center text-sm text-gray-400">Belum ada mahasiswa terdaftar.</p>
            @endforelse
        </div>
    </section>

    {{-- ============ CARA KERJA ============ --}}
    <section id="cara-kerja" class="border-y border-gray-100 bg-gray-50/60">
        <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <div class="reveal mx-auto max-w-2xl text-center">
                <p class="text-sm font-semibold uppercase tracking-widest text-blue-600">Cara Kerja</p>
                <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">Empat langkah menuju transaksi selesai</h2>
            </div>
            <div class="mt-14 grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
                @foreach([
                    ['1', 'UMKM Posting', 'Buat proyek: judul, deskripsi, budget. Status terbuka.'],
                    ['2', 'Mahasiswa Lamar', 'Temukan peluang, lihat detail, kirim lamaran.'],
                    ['3', 'Kerja & Kirim', 'Diterima → kerjakan → submit hasil.'],
                    ['4', 'Setujui & Bayar', 'UMKM setujui → transaksi & wallet otomatis.'],
                ] as $i => $step)
                <div class="reveal relative text-center" style="transition-delay: {{ $i * 80 }}ms">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 text-xl font-extrabold text-white shadow-lg shadow-blue-600/25">{{ $step[0] }}</div>
                    <h3 class="mt-5 text-base font-bold text-gray-900">{{ $step[1] }}</h3>
                    <p class="mt-2 text-sm text-gray-500">{{ $step[2] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ PANEL MAHASISWA / UMKM ============ --}}
    <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
        <div class="grid gap-6 lg:grid-cols-2">
            <div class="reveal overflow-hidden rounded-3xl bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-700 p-8 text-white sm:p-10">
                <p class="text-xs font-semibold uppercase tracking-widest text-blue-200">Untuk Mahasiswa</p>
                <h3 class="mt-3 text-2xl font-extrabold">Cari penghasilan dari skill kamu</h3>
                <p class="mt-3 text-sm leading-relaxed text-blue-100">Jelajahi peluang, lamar proyek sesuai keahlian, dan dapatkan bayaran langsung ke wallet. Cocok untuk desain, coding, menulis, dan lainnya.</p>
                <ul class="mt-6 space-y-3 text-sm text-blue-50">
                    <li class="flex items-center gap-3"><span class="flex h-6 w-6 items-center justify-center rounded-full bg-white/15"><x-icon name="check" :size="14" /></span> Peluang OPEN yang terkurasi</li>
                    <li class="flex items-center gap-3"><span class="flex h-6 w-6 items-center justify-center rounded-full bg-white/15"><x-icon name="check" :size="14" /></span> Status lamaran yang jelas</li>
                    <li class="flex items-center gap-3"><span class="flex h-6 w-6 items-center justify-center rounded-full bg-white/15"><x-icon name="check" :size="14" /></span> Wallet & riwayat transaksi transparan</li>
                </ul>
                <a href="{{ route('register') }}" class="mt-8 inline-flex items-center justify-center rounded-full bg-white px-6 py-3 text-sm font-semibold text-blue-700 transition hover:bg-blue-50">Daftar sebagai Mahasiswa</a>
            </div>
            <div class="reveal overflow-hidden rounded-3xl bg-gray-900 p-8 text-white sm:p-10" style="transition-delay: 80ms">
                <p class="text-xs font-semibold uppercase tracking-widest text-blue-300">Untuk UMKM</p>
                <h3 class="mt-3 text-2xl font-extrabold">Temukan talent mahasiswa terbaik</h3>
                <p class="mt-3 text-sm leading-relaxed text-gray-300">Posting kebutuhanmu, kelola pelamar, review hasil, dan setujui pembayaran hanya saat puas. Proses cepat dan terkontrol.</p>
                <ul class="mt-6 space-y-3 text-sm text-gray-200">
                    <li class="flex items-center gap-3"><span class="flex h-6 w-6 items-center justify-center rounded-full bg-white/10"><x-icon name="check" :size="14" /></span> Buat proyek dalam hitungan menit</li>
                    <li class="flex items-center gap-3"><span class="flex h-6 w-6 items-center justify-center rounded-full bg-white/10"><x-icon name="check" :size="14" /></span> Kelola pelamar & submission terpusat</li>
                    <li class="flex items-center gap-3"><span class="flex h-6 w-6 items-center justify-center rounded-full bg-white/10"><x-icon name="check" :size="14" /></span> Bayar setelah hasil disetujui</li>
                </ul>
                <a href="{{ route('register') }}" class="mt-8 inline-flex items-center justify-center rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-500">Daftar sebagai UMKM</a>
            </div>
        </div>
    </section>

    {{-- ============ TESTIMONI ============ --}}
    <section class="border-y border-gray-100 bg-gray-50/60">
        <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <div class="reveal mx-auto max-w-2xl text-center">
                <p class="text-sm font-semibold uppercase tracking-widest text-blue-600">Testimoni</p>
                <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">Mereka sudah merasakan KIVU</h2>
            </div>
            <div class="mt-12 grid gap-6 md:grid-cols-3">
                @foreach([
                    ['Desain logo UMKM jadi profesional dalam 3 hari. Bayaran langsung masuk wallet. Sangat membantu saya bangun portofolio.', 'Rini', 'Mahasiswa Desain'],
                    ['Pelamar datang banyak dan berkualitas. Saya tinggal pilih, review hasil, lalu bayar. Prosesnya jauh lebih cepat dari cara lama.', 'Budi', 'Pemilik UMKM Kopi'],
                    ['Akhirnya ada tempat aman untuk kerjaan freelance semasa kuliah. Wallet & riwayat transaksinya transparan.', 'Sari', 'Mahasiswa IT'],
                ] as $i => $tv)
                <div class="reveal rounded-3xl border border-gray-100 bg-white p-7" style="transition-delay: {{ $i * 80 }}ms">
                    <div class="flex gap-1 text-amber-500">
                        @for($s = 0; $s < 5; $s++)<x-icon name="star" :size="16" :filled="true" />@endfor
                    </div>
                    <p class="mt-4 text-sm leading-relaxed text-gray-600">"{{ $tv[0] }}"</p>
                    <div class="mt-5 flex items-center gap-3 border-t border-gray-100 pt-4">
                        <x-ui.avatar :name="$tv[1]" size="sm" />
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $tv[1] }}</p>
                            <p class="text-xs text-gray-400">{{ $tv[2] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ CTA AKHIR ============ --}}
    <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
        <div class="reveal relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-blue-600 via-indigo-600 to-sky-600 px-6 py-16 text-center text-white sm:px-12 sm:py-20">
            <div class="pointer-events-none absolute -top-20 -right-20 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 -left-16 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
            <div class="relative mx-auto max-w-2xl">
                <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl">Siap mulai perjalanan KIVU?</h2>
                <p class="mt-3 text-blue-100">Bergabung sekarang — gratis untuk mahasiswa & UMKM.</p>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('register') }}" class="inline-flex items-center rounded-full bg-white px-8 py-4 text-sm font-semibold text-blue-700 shadow-lg transition hover:bg-blue-50">Buat Akun Sekarang</a>
                    <a href="{{ route('login') }}" class="inline-flex items-center rounded-full border border-white/30 bg-white/10 px-8 py-4 text-sm font-semibold text-white backdrop-blur transition hover:bg-white/20">Masuk</a>
                </div>
                <p class="mt-5 text-xs text-blue-200">Tanpa kartu kredit · Email kampus langsung aktif</p>
            </div>
        </div>
    </section>

    {{-- ============ FOOTER ============ --}}
    <footer class="border-t border-gray-100 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-5 px-4 py-10 sm:flex-row sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/kivu-logo.png') }}" alt="KIVU" class="h-7 w-auto">
                <span class="text-sm text-gray-400">© {{ date('Y') }} Marketplace Mahasiswa × UMKM</span>
            </div>
            <nav class="flex items-center gap-6 text-sm text-gray-500">
                <a href="#peluang" class="transition hover:text-blue-600">Peluang</a>
                <a href="#talent" class="transition hover:text-blue-600">Talent</a>
                <a href="#cara-kerja" class="transition hover:text-blue-600">Cara Kerja</a>
            </nav>
            <p class="text-xs text-gray-400">Dibuat dengan Laravel + Livewire + Tailwind CSS</p>
        </div>
    </footer>
</div>