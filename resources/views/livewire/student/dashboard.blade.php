<div class="space-y-8">
    <div class="flex flex-col gap-1">
        <h1 class="flex items-center gap-2 text-2xl font-bold tracking-tight text-gray-900">Halo, {{ auth()->user()->name }} 👋 <x-ui.verified-badge :user="auth()->user()" /></h1>
        <p class="text-sm text-gray-500">Temukan proyek dan kelola perjalanan micro-freelance Anda.</p>
    </div>

    @if(auth()->user()->status === 'pending_ktm')
        <div class="flex flex-col gap-2 rounded-2xl border border-amber-200 bg-amber-50 p-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="flex items-center gap-2 text-sm text-amber-800">
                <x-icon name="alert-circle" :size="18" class="shrink-0" />
                Verifikasi KTM Anda untuk menampilkan lencana &quot;Mahasiswa Terverifikasi&quot; dan meningkatkan kepercayaan UMKM. Anda tetap dapat melamar.
            </p>
            <a href="{{ route('student.profile') }}" wire:navigate class="inline-flex shrink-0 items-center gap-1.5 rounded-lg bg-amber-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-amber-700">
                <x-icon name="id-card" :size="14" /> Verifikasi KTM
            </a>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-ui.stat-card :icon="'wallet'" label="Saldo Tersedia" :value="'Rp ' . number_format($balance, 0, ',', '.')" :hint="auth()->user()->status === 'active' ? '' : 'Menunggu verifikasi KTM'">
        </x-ui.stat-card>
        <x-ui.stat-card :icon="'banknote'" tone="success" label="Total Pendapatan" :value="'Rp ' . number_format($totalEarnings, 0, ',', '.')" hint="dari proyek selesai">
        </x-ui.stat-card>
        <x-ui.stat-card :icon="'file-text'" tone="blue" label="Lamaran Aktif" :value="$activeApplications" hint="menunggu / diterima">
        </x-ui.stat-card>
        <x-ui.stat-card :icon="'circle-check'" tone="warning" label="Menunggu Kirim Hasil" :value="$awaitingSubmission->count()" hint="proyek yang sudah diterima">
        </x-ui.stat-card>
    </div>

    @if($awaitingSubmission->isNotEmpty())
    <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5">
        <h3 class="flex items-center gap-2 text-base font-semibold text-blue-900">
            <x-icon name="alert-circle" :size="18" /> Butuh aksi Anda
        </h3>
        <p class="mt-1 text-sm text-blue-700">Proyek yang Anda lamar sudah diterima. Kirim hasil kerja Anda sekarang.</p>
        <div class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-2">
            @foreach($awaitingSubmission as $project)
            <div class="flex items-center justify-between gap-3 rounded-xl border border-blue-200 bg-white p-4">
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-gray-800">{{ $project->title }}</p>
                    <p class="text-xs text-gray-500">Budget: Rp {{ number_format($project->budget, 0, ',', '.') }}</p>
                </div>
                <a href="{{ route('student.submit-work', $project->id) }}" wire:navigate class="shrink-0 inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3.5 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
                    <x-icon name="upload" :size="16" /> Kirim Hasil
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        <div class="space-y-4 lg:col-span-2">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">Peluang untuk Anda</h2>
                <a href="{{ route('student.opportunities') }}" wire:navigate class="text-sm font-semibold text-blue-600 hover:underline">Semua Peluang &rarr;</a>
            </div>
            @forelse($opportunities as $project)
                <x-domain.project-card :project="$project" />
            @empty
                <x-ui.empty-state icon="briefcase" title="Belum ada proyek terbuka" description="UMKM belum membuka proyek baru. Cek kembali nanti ya." />
            @endforelse
        </div>

        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">Lamaran terbaru</h2>
                <a href="{{ route('student.my-applications') }}" wire:navigate class="text-sm font-semibold text-blue-600 hover:underline">Lihat semua</a>
            </div>
            @forelse($latestApplications as $app)
                <div class="rounded-2xl border border-gray-200 bg-white p-4">
                    <div class="flex items-start justify-between gap-2">
                        <p class="line-clamp-1 text-sm font-semibold text-gray-800">{{ $app->project->title }}</p>
                        <x-ui.status-badge :value="$app->status" kind="application" />
                    </div>
                    <p class="mt-1 text-xs text-gray-500">{{ $app->project->owner->name }} &middot; {{ $app->created_at->diffForHumans() }}</p>
                    @if($app->status === 'ACCEPTED')
                        <a href="{{ route('student.submit-work', $app->project_id) }}" wire:navigate class="mt-2 inline-block text-xs font-semibold text-blue-600 hover:underline">Kirim Hasil &rarr;</a>
                    @endif
                </div>
            @empty
                <div class="rounded-2xl border border-gray-200 bg-white p-5 text-center">
                    <p class="text-sm text-gray-500">Belum ada lamaran.</p>
                    <a href="{{ route('student.opportunities') }}" wire:navigate class="mt-2 inline-block text-sm font-semibold text-blue-600 hover:underline">Jelajahi peluang &rarr;</a>
                </div>
            @endforelse

            <div class="rounded-2xl border border-gray-200 bg-white p-5">
                <h3 class="text-sm font-semibold text-gray-800">Dompet & Penarikan</h3>
                <p class="mt-1 text-xs text-gray-500">Tarik saldo kapan saja setelah proyek Anda selesai.</p>
                <a href="{{ route('student.wallet') }}" wire:navigate class="mt-3 inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                    <x-icon name="wallet" :size="16" /> Buka Dompet
                </a>
            </div>
        </div>
    </div>
</div>