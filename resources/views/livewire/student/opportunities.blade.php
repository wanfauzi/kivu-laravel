<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Peluang Proyek</h1>
            <p class="text-sm text-gray-500">Temukan proyek dari UMKM dan mulai berkarya.</p>
        </div>
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
            <div class="relative">
                <x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" :size="18" />
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari proyek..." class="h-11 w-full rounded-lg border border-gray-300 bg-white pl-10 pr-4 text-sm text-gray-800 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 sm:w-64">
            </div>
            <select wire:model.live="budgetFilter" class="h-11 rounded-lg border border-gray-300 bg-white px-3 pr-8 text-sm text-gray-800 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                <option value="">Semua Budget</option>
                <option value="low">&lt; Rp 500rb</option>
                <option value="medium">Rp 500rb - 2jt</option>
                <option value="high">&gt; Rp 2jt</option>
            </select>
            <select wire:model.live="sort" class="h-11 rounded-lg border border-gray-300 bg-white px-3 pr-8 text-sm text-gray-800 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                <option value="latest">Terbaru</option>
                <option value="budget_high">Budget Tertinggi</option>
                <option value="budget_low">Budget Terendah</option>
            </select>
            @if($search !== '' || $budgetFilter !== '' || $sort !== 'latest')
                <button wire:click="resetFilter" class="inline-flex h-11 items-center justify-center gap-1.5 rounded-lg border border-gray-300 bg-white px-4 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                    <x-icon name="x" :size="15" /> Reset
                </button>
            @endif
        </div>
    </div>

    <p class="text-sm text-gray-500">
        Menampilkan <span class="font-semibold text-gray-800">{{ $projects->total() }}</span> proyek
    </p>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
        @forelse($projects as $project)
            <x-domain.project-card :project="$project" />
        @empty
            <div class="col-span-full">
                <x-ui.empty-state icon="briefcase" title="Tidak ada proyek ditemukan" :description="$search !== '' ? 'Coba ubah kata kunci atau filter budget Anda.' : 'Belum ada proyek terbuka saat ini.'">
<select wire:model.live="sort" class="h-11 rounded-lg border border-gray-300 bg-white px-3 pr-8 text-sm text-gray-800 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                <option value="latest">Terbaru</option>
                <option value="budget_high">Budget Tertinggi</option>
                <option value="budget_low">Budget Terendah</option>
            </select>
            @if($search !== '' || $budgetFilter !== '')
                        <button wire:click="resetFilter" class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">Reset Filter</button>
                    @endif
                </x-ui.empty-state>
            </div>
        @endforelse
    </div>

    @if($projects->hasPages())
        <div class="mt-4">{{ $projects->links() }}</div>
    @endif
</div>