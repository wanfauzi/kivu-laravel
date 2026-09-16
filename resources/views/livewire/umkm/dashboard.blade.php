<div class="space-y-6">
    <x-ui.page-header :title="'Halo, '.auth()->user()->name" subtitle="Kelola proyek, pelamar, dan hasil kerja mahasiswa.">
        <x-slot:actions>
            <x-ui.button href="{{ route('umkm.create-project') }}">
                <x-icon name="plus-circle" :size="18" /> Buat Proyek
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-ui.stat-card icon="folder-kanban" label="Proyek Berjalan" :value="$activeProjects" :hint="$openProjects.' proyek terbuka'" href="{{ route('umkm.my-projects') }}" />
        <x-ui.stat-card icon="users" tone="warning" label="Pelamar Menunggu" :value="$pendingApplicants" hint="menunggu keputusan" />
        <x-ui.stat-card icon="circle-check" tone="success" label="Menunggu Review" :value="$awaitingReview" hint="hasil kerja masuk" />
        <x-ui.stat-card icon="banknote" tone="info" label="Total Dibayarkan" :value="'Rp '.number_format($totalPaid, 0, ',', '.')" hint="proyek selesai" />
    </div>

    @if ($awaitingReviewProjects->isNotEmpty())
        <div class="kivu-card border-kivu-primary/30 bg-kivu-primary-soft/50 p-5">
            <h3 class="flex items-center gap-2 text-base font-semibold text-kivu-text">
                <x-icon name="alert-circle" :size="18" class="text-kivu-primary" /> Menunggu review Anda
            </h3>
            <p class="mt-1 text-sm text-kivu-text-secondary">Hasil kerja sudah dikirim mahasiswa. Tinjau dan setujui agar dana terbayar.</p>

            <div class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-2">
                @foreach ($awaitingReviewProjects as $project)
                    <div class="flex items-center justify-between gap-3 rounded-kivu-sm border border-kivu-border bg-kivu-surface p-4">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-kivu-text">{{ $project->title }}</p>
                            <p class="text-xs text-kivu-text-muted">{{ $project->submissions_count }} hasil masuk</p>
                        </div>
                        <x-ui.button href="{{ in_array($project->id, $awaitingReviewWinnerIds, true) ? route('umkm.review-submission', $project->id) : route('umkm.select-winner', $project->id) }}" size="sm" class="shrink-0">
                            <x-icon name="circle-check" :size="16" /> {{ in_array($project->id, $awaitingReviewWinnerIds, true) ? 'Tinjau' : 'Pilih' }}
                        </x-ui.button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="kivu-card p-5">
        <h2 class="text-lg font-bold text-kivu-text">Pengeluaran 6 Bulan</h2>
        <p class="text-sm text-kivu-text-secondary">Total pembayaran yang Anda lepaskan ke mahasiswa.</p>
        <div class="mt-4">
            <x-ui.chart type="line" :labels="$spendLabels" :series="[['label' => 'Pengeluaran', 'data' => $spendData, 'fill' => true]]" :height="220" />
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-4 lg:col-span-2">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-kivu-text">Proyek Anda</h2>
                <a href="{{ route('umkm.my-projects') }}" wire:navigate class="kivu-focus rounded text-sm font-semibold text-kivu-primary hover:underline">Semua proyek &rarr;</a>
            </div>

            @forelse ($projects as $project)
                <div class="kivu-card p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="line-clamp-1 text-sm font-semibold text-kivu-text">{{ $project->title }}</p>
                            <p class="mt-0.5 text-xs text-kivu-text-muted">Budget: Rp {{ number_format($project->budget, 0, ',', '.') }}</p>
                        </div>
                        <x-ui.status-badge :value="$project->status" kind="project" />
                    </div>
                    <div class="mt-3 flex items-center gap-3 text-xs">
                        <a href="{{ route('umkm.manage-applicants', $project->id) }}" wire:navigate class="kivu-focus rounded font-semibold text-kivu-primary hover:underline">
                            Pelamar ({{ $project->applications_count }})
                        </a>
                        <span class="text-kivu-text-muted">&middot;</span>
                        <a href="{{ route('umkm.my-projects') }}" wire:navigate class="kivu-focus rounded font-semibold text-kivu-primary hover:underline">Kelola</a>
                    </div>
                </div>
            @empty
                <x-ui.empty-state icon="folder-kanban" title="Belum ada proyek" description="Mulai dengan membuat proyek pertama Anda.">
                    <x-ui.button href="{{ route('umkm.create-project') }}" size="sm">
                        <x-icon name="plus-circle" :size="16" /> Buat Proyek
                    </x-ui.button>
                </x-ui.empty-state>
            @endforelse
        </div>

        <div class="space-y-6">
            <div>
                <h2 class="mb-3 text-lg font-bold text-kivu-text">Pelamar menunggu</h2>

                @forelse ($pendingApplicantsList as $app)
                    <div class="kivu-card mb-3 p-3.5">
                        <p class="text-sm font-semibold text-kivu-text">{{ $app->student->name }}</p>
                        <p class="line-clamp-1 text-xs text-kivu-text-muted">{{ $app->project->title }}</p>
                        <a href="{{ route('umkm.manage-applicants', $app->project_id) }}" wire:navigate
                            class="kivu-focus mt-2 inline-block rounded text-xs font-semibold text-kivu-primary hover:underline">Lihat pelamar &rarr;</a>
                    </div>
                @empty
                    <div class="kivu-card p-4 text-center text-sm text-kivu-text-muted">Tidak ada pelamar menunggu.</div>
                @endforelse
            </div>

            <a href="{{ route('umkm.my-projects') }}" wire:navigate
                class="flex items-center justify-center gap-2 rounded-kivu border border-dashed border-kivu-border bg-kivu-surface px-4 py-5 text-sm font-semibold text-kivu-primary transition hover:border-kivu-primary/50 hover:bg-kivu-primary-soft">
                <x-icon name="search" :size="18" /> Kelola semua proyek &amp; pelamar
            </a>
        </div>
    </div>

    <div class="kivu-card p-5">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-kivu-text">Rekomendasi Talenta & Jasa</h2>
                <p class="text-sm text-kivu-text-secondary">Mahasiswa teraktif + karya jasa unggulan — langsung hubungi.</p>
            </div>
            <a href="{{ url('/') }}#talent" class="kivu-focus rounded text-sm font-semibold text-kivu-primary hover:underline">Lihat semua talenta &rarr;</a>
        </div>

        <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
            @forelse ($recommendedServices as $pf)
                <a href="{{ route('talents.show', $pf->student->id) }}" target="_blank" class="kivu-card p-3 group hover:border-kivu-primary/40">
                    @if ($pf->file_path && $pf->isImage())
                        <img src="{{ Storage::disk('public')->url($pf->file_path) }}" alt="{{ $pf->title }}" class="mb-2 h-20 w-full rounded-kivu-sm object-cover" loading="lazy">
                    @else
                        <div class="mb-2 h-20 w-full rounded-kivu-sm bg-kivu-surface-muted"></div>
                    @endif
                    <p class="line-clamp-2 text-xs font-semibold text-kivu-text group-hover:text-kivu-primary">{{ $pf->title }}</p>
                    <p class="mt-0.5 text-xs text-kivu-text-muted">Rp {{ number_format($pf->price, 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-kivu-primary font-semibold">Pesan →</p>
                </a>
            @empty
                <p class="col-span-full text-sm text-kivu-text-muted">Belum ada jasa tersedia. Mahasiswa bisa aktifkan di profil mereka.</p>
            @endforelse
        </div>

        <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
            @foreach ($recommendedTalent as $t)
                <a href="{{ route('talents.show', $t->id) }}" target="_blank" class="kivu-card p-3 text-center group hover:border-kivu-primary/40">
                    <x-ui.avatar :name="$t->name" size="lg" class="mx-auto" />
                    <p class="mt-2 text-sm font-semibold text-kivu-text group-hover:text-kivu-primary line-clamp-1">{{ $t->name }}</p>
                    <p class="text-xs text-kivu-text-muted line-clamp-1">{{ $t->skills ? implode(', ', array_slice($t->skills, 0, 2)) : '—' }}</p>
                </a>
            @endforeach
        </div>
    </div>
</div>
