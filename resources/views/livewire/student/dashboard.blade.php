<div class="space-y-6">
    <x-ui.page-header :title="'Halo, '.auth()->user()->name" subtitle="Temukan proyek dan kelola perjalanan micro-freelance Anda.">
        <x-slot:actions>
            <x-ui.verified-badge :user="auth()->user()" />
        </x-slot:actions>
    </x-ui.page-header>

    @if (auth()->user()->status === 'pending_ktm')
        <div class="flex flex-col gap-2 rounded-kivu border border-kivu-warning/25 bg-kivu-warning-soft p-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="flex items-center gap-2 text-sm text-kivu-warning">
                <x-icon name="alert-circle" :size="18" class="shrink-0" />
                Verifikasi KTM untuk menampilkan lencana &quot;Mahasiswa Terverifikasi&quot; dan meningkatkan kepercayaan UMKM. Anda tetap dapat melamar.
            </p>
            <x-ui.button href="{{ route('student.profile') }}" size="sm" class="shrink-0">
                <x-icon name="id-card" :size="14" /> Verifikasi KTM
            </x-ui.button>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-ui.stat-card icon="wallet" label="Saldo Tersedia" :value="'Rp '.number_format($balance, 0, ',', '.')"
            :hint="auth()->user()->status === 'active' ? null : 'Menunggu verifikasi KTM'" href="{{ route('student.wallet') }}" />
        <x-ui.stat-card icon="banknote" tone="success" label="Total Pendapatan" :value="'Rp '.number_format($totalEarnings, 0, ',', '.')" hint="dari proyek selesai" />
        <x-ui.stat-card icon="file-text" tone="info" label="Lamaran Aktif" :value="$activeApplications" hint="menunggu / diterima" href="{{ route('student.my-applications') }}" />
        <x-ui.stat-card icon="circle-check" tone="warning" label="Menunggu Kirim Hasil" :value="$awaitingSubmission->count()" hint="proyek sudah diterima" />
    </div>

    @if ($awaitingSubmission->isNotEmpty())
        <div class="kivu-card border-kivu-primary/30 bg-kivu-primary-soft/50 p-5">
            <h3 class="flex items-center gap-2 text-base font-semibold text-kivu-text">
                <x-icon name="alert-circle" :size="18" class="text-kivu-primary" /> Butuh aksi Anda
            </h3>
            <p class="mt-1 text-sm text-kivu-text-secondary">Proyek berikut sudah diterima. Kirim hasil kerja Anda sekarang.</p>

            <div class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-2">
                @foreach ($awaitingSubmission as $project)
                    <div class="flex items-center justify-between gap-3 rounded-kivu-sm border border-kivu-border bg-kivu-surface p-4">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-kivu-text">{{ $project->title }}</p>
                            <p class="text-xs text-kivu-text-muted">Budget: Rp {{ number_format($project->budget, 0, ',', '.') }}</p>
                        </div>
                        <x-ui.button href="{{ route('student.submit-work', $project->id) }}" size="sm" class="shrink-0">
                            <x-icon name="upload" :size="14" /> Kirim Hasil
                        </x-ui.button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="kivu-card p-5">
        <h2 class="text-lg font-bold text-kivu-text">Pendapatan 6 Bulan</h2>
        <p class="text-sm text-kivu-text-secondary">Total pembayaran yang masuk ke dompet Anda.</p>
        <div class="mt-4">
            <x-ui.chart type="line" :labels="$earningsLabels" :series="[['label' => 'Pendapatan', 'data' => $earningsData, 'fill' => true]]" :height="220" />
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-4 lg:col-span-2">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-kivu-text">Peluang untuk Anda</h2>
                <a href="{{ route('student.opportunities') }}" wire:navigate class="kivu-focus rounded text-sm font-semibold text-kivu-primary hover:underline">Semua Peluang &rarr;</a>
            </div>

            @forelse ($opportunities as $project)
                <x-domain.project-card :project="$project" />
            @empty
                <x-ui.empty-state icon="briefcase" title="Belum ada proyek terbuka" description="UMKM belum membuka proyek baru. Cek kembali nanti ya." />
            @endforelse
        </div>

        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-kivu-text">Lamaran terbaru</h2>
                <a href="{{ route('student.my-applications') }}" wire:navigate class="kivu-focus rounded text-sm font-semibold text-kivu-primary hover:underline">Lihat semua</a>
            </div>

            @forelse ($latestApplications as $app)
                <div class="kivu-card p-4">
                    <div class="flex items-start justify-between gap-2">
                        <p class="line-clamp-1 text-sm font-semibold text-kivu-text">{{ $app->project->title }}</p>
                        <x-ui.status-badge :value="$app->status" kind="application" />
                    </div>
                    <p class="mt-1 text-xs text-kivu-text-muted">{{ $app->project->owner->name }} &middot; {{ $app->created_at->diffForHumans() }}</p>
                    @php
                        $proj = $app->project;
                        $mySub = \App\Models\Submission::where('project_id', $proj->id)
                            ->where('student_id', $app->student_id)->first();
                    @endphp
                    @if (in_array($proj->status, ['OPEN', 'SUBMITTED'], true)
                        && in_array($app->status, ['PENDING', 'ACCEPTED'], true)
                        && (! $mySub || $mySub->status === 'REVISION'))
                        <a href="{{ route('student.submit-work', $app->project_id) }}" wire:navigate
                            class="kivu-focus mt-2 inline-block rounded text-xs font-semibold text-kivu-primary hover:underline">Kirim Hasil &rarr;</a>
                    @endif
                </div>
            @empty
                <div class="kivu-card p-5 text-center">
                    <p class="text-sm text-kivu-text-muted">Belum ada lamaran.</p>
                    <a href="{{ route('student.opportunities') }}" wire:navigate class="kivu-focus mt-2 inline-block rounded text-sm font-semibold text-kivu-primary hover:underline">Jelajahi peluang &rarr;</a>
                </div>
            @endforelse

            <div class="kivu-card p-5">
                <h3 class="text-sm font-semibold text-kivu-text">Dompet &amp; Penarikan</h3>
                <p class="mt-1 text-xs text-kivu-text-muted">Tarik saldo kapan saja setelah proyek Anda selesai.</p>
                <x-ui.button href="{{ route('student.wallet') }}" variant="secondary" class="mt-3 w-full">
                    <x-icon name="wallet" :size="16" /> Buka Dompet
                </x-ui.button>
            </div>
        </div>
    </div>
</div>
