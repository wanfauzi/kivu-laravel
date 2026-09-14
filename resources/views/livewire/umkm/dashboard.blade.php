<div class="space-y-8">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Halo, {{ auth()->user()->name }}</h1>
            <p class="text-sm text-gray-500">Kelola proyek, pelamar, dan hasil kerja UMKM Anda.</p>
        </div>
        <a href="{{ route('umkm.create-project') }}" wire:navigate class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
            <x-icon name="plus-circle" :size="18" /> Buat Proyek
        </a>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-ui.stat-card :icon="'folder-kanban'" tone="blue" label="Proyek Berjalan" :value="$activeProjects" :hint="'$openProjects proyek terbuka'">
        </x-ui.stat-card>
        <x-ui.stat-card :icon="'users'" tone="warning" label="Pelamar Menunggu" :value="$pendingApplicants" hint="menunggu keputusan">
        </x-ui.stat-card>
        <x-ui.stat-card :icon="'circle-check'" tone="success" label="Menunggu Review" :value="$awaitingReview" hint="hasil kerja masuk">
        </x-ui.stat-card>
        <x-ui.stat-card :icon="'banknote'" label="Total Dibayarkan" :value="'Rp ' . number_format($totalPaid, 0, ',', '.')" hint="proyek selesai">
        </x-ui.stat-card>
    </div>

    @if($awaitingReviewProjects->isNotEmpty())
    <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5">
        <h3 class="flex items-center gap-2 text-base font-semibold text-blue-900">
            <x-icon name="alert-circle" :size="18" /> Menunggu review Anda
        </h3>
        <p class="mt-1 text-sm text-blue-700">Sebuah hasil kerja sudah dikirim mahasiswa. Tinjau dan setujui agar dana terbayar.</p>
        <div class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-2">
            @foreach($awaitingReviewProjects as $project)
            <div class="flex items-center justify-between gap-3 rounded-xl border border-blue-200 bg-white p-4">
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-gray-800">{{ $project->title }}</p>
                    <p class="text-xs text-gray-500">oleh {{ $project->submission?->student?->name ?? 'Mahasiswa' }}</p>
                </div>
                <a href="{{ route('umkm.review-submission', $project->id) }}" wire:navigate class="shrink-0 inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3.5 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
                    <x-icon name="circle-check" :size="16" /> Review
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        <div class="space-y-4 lg:col-span-2">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">Proyek Anda</h2>
                <a href="{{ route('umkm.my-projects') }}" wire:navigate class="text-sm font-semibold text-blue-600 hover:underline">Semua proyek &rarr;</a>
            </div>
            @forelse($projects as $project)
                <div class="rounded-2xl border border-gray-200 bg-white p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="line-clamp-1 text-sm font-semibold text-gray-800">{{ $project->title }}</p>
                            <p class="mt-0.5 text-xs text-gray-500">Budget: Rp {{ number_format($project->budget, 0, ',', '.') }}</p>
                        </div>
                        <x-ui.status-badge :value="$project->status" kind="project" />
                    </div>
                    <div class="mt-3 flex items-center gap-3">
                        <a href="{{ route('umkm.manage-applicants', $project->id) }}" wire:navigate class="text-xs font-semibold text-blue-600 hover:underline">Pelamar ({{ $project->applications_count }})</a>
                        <span class="text-gray-300">&middot;</span>
                        <a href="{{ route('umkm.my-projects') }}" wire:navigate class="text-xs font-semibold text-blue-600 hover:underline">Kelola</a>
                    </div>
                </div>
            @empty
                <x-ui.empty-state icon="folder-kanban" title="Belum ada proyek" description="Mulai dengan membuat proyek pertama Anda.">
                    <a href="{{ route('umkm.create-project') }}" wire:navigate class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                        <x-icon name="plus-circle" :size="16" /> Buat Proyek
                    </a>
                </x-ui.empty-state>
            @endforelse
        </div>

        <div class="space-y-6">
            <div>
                <h2 class="mb-3 text-lg font-bold text-gray-900">Pelamar menunggu</h2>
                @forelse($pendingApplicantsList as $app)
                    <div class="mb-3 rounded-xl border border-gray-200 bg-white p-3.5">
                        <p class="text-sm font-semibold text-gray-800">{{ $app->student->name }}</p>
                        <p class="line-clamp-1 text-xs text-gray-500">{{ $app->project->title }}</p>
                        <a href="{{ route('umkm.manage-applicants', $app->project_id) }}" wire:navigate class="mt-2 inline-block text-xs font-semibold text-blue-600 hover:underline">Lihat pelamar &rarr;</a>
                    </div>
                @empty
                    <div class="rounded-xl border border-gray-200 bg-white p-4 text-center text-sm text-gray-500">Tidak ada pelamar menunggu.</div>
                @endforelse
            </div>

            <a href="{{ route('umkm.my-projects') }}" wire:navigate class="flex items-center justify-center gap-2 rounded-2xl border border-dashed border-gray-300 bg-white px-4 py-5 text-sm font-semibold text-blue-600 transition hover:border-blue-400 hover:bg-blue-50">
                <x-icon name="search" :size="18" /> Kelola semua proyek & pelamar
            </a>
        </div>
    </div>
</div>