@php
    $selectClass = 'h-11 rounded-kivu-sm border border-kivu-border bg-kivu-surface px-3 text-sm text-kivu-text transition focus:border-kivu-primary focus:outline-none focus:ring-2 focus:ring-kivu-primary/20';
@endphp

<div class="space-y-6">
    <x-ui.page-header title="Peluang Proyek" subtitle="Temukan proyek dari UMKM dan mulai berkarya." />

    {{-- Filter toolbar --}}
    <div class="kivu-card sticky top-16 z-30 space-y-4 p-4">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
            <div class="relative flex-1">
                <x-icon name="search" :size="18" class="pointer-events-none absolute top-1/2 left-3.5 -translate-y-1/2 text-kivu-text-muted" />
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari proyek..."
                    class="kivu-input h-11 pl-10 pr-4 text-sm" />
            </div>

            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:flex lg:items-center">
                <select wire:model.live="budgetFilter" class="{{ $selectClass }} lg:w-44" aria-label="Filter budget">
                    <option value="">Semua Budget</option>
                    <option value="low">&lt; Rp 500rb</option>
                    <option value="medium">Rp 500rb - 2jt</option>
                    <option value="high">&gt; Rp 2jt</option>
                </select>

                <select wire:model.live="deadlineFilter" class="{{ $selectClass }} lg:w-44" aria-label="Filter deadline">
                    <option value="">Semua Deadline</option>
                    <option value="soon">≤ 7 hari</option>
                    <option value="month">≤ 30 hari</option>
                    <option value="flexible">Tanpa deadline</option>
                </select>

                <select wire:model.live="sort" class="{{ $selectClass }} lg:w-44" aria-label="Urutkan">
                    <option value="latest">Terbaru</option>
                    <option value="budget_high">Budget Tertinggi</option>
                    <option value="budget_low">Budget Terendah</option>
                    <option value="deadline">Deadline Terdekat</option>
                </select>
            </div>

            @if ($hasFilters)
                <x-ui.button wire:click="resetFilter" variant="secondary" class="h-11">
                    <x-icon name="x" :size="15" /> Reset
                </x-ui.button>
            @endif
        </div>

        {{-- Category chips --}}
        <div class="no-scrollbar flex gap-2 overflow-x-auto pb-0.5">
            <button type="button" wire:click="resetFilter"
                class="kivu-focus shrink-0 rounded-full px-3.5 py-1.5 text-xs font-semibold transition {{ $category === '' && $skill === '' ? 'bg-kivu-primary text-white' : 'border border-kivu-border bg-kivu-surface text-kivu-text-secondary hover:bg-kivu-surface-muted' }}">
                Semua
            </button>
            @foreach ($categories as $cat)
                <button type="button" wire:click="selectCategory('{{ $cat->slug }}')"
                    class="kivu-focus inline-flex shrink-0 items-center gap-1.5 rounded-full px-3.5 py-1.5 text-xs font-semibold transition {{ $category === $cat->slug ? 'bg-kivu-primary text-white' : 'border border-kivu-border bg-kivu-surface text-kivu-text-secondary hover:bg-kivu-surface-muted' }}">
                    <x-icon :name="$cat->icon ?: 'tag'" :size="13" />
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>

        {{-- Skill chips --}}
        @if ($skills->isNotEmpty())
            <div class="no-scrollbar flex gap-2 overflow-x-auto border-t border-kivu-border pt-3">
                <span class="shrink-0 self-center text-xs font-semibold text-kivu-text-muted">Keahlian:</span>
                @foreach ($skills as $s)
                    <button type="button" wire:click="selectSkill('{{ $s->slug }}')"
                        class="kivu-focus shrink-0 rounded-full px-3 py-1 text-xs font-medium transition {{ $skill === $s->slug ? 'bg-kivu-primary text-white' : 'bg-kivu-surface-muted text-kivu-text-secondary hover:text-kivu-text' }}">
                        {{ $s->name }}
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    <p class="text-sm text-kivu-text-muted">
        Menampilkan <span class="font-semibold text-kivu-text">{{ $projects->total() }}</span> proyek
    </p>

    <div class="relative">
        {{-- Skeleton saat memuat --}}
        <div wire:loading.delay
            class="absolute inset-0 z-10 grid grid-cols-1 gap-5 bg-kivu-bg/70 md:grid-cols-2 lg:grid-cols-3">
            @for ($i = 0; $i < 3; $i++)
                <x-ui.skeleton type="card" />
            @endfor
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($projects as $project)
                <x-domain.project-card :project="$project" />
            @empty
                <div class="col-span-full">
                    <x-ui.empty-state icon="briefcase" title="Tidak ada proyek ditemukan"
                        description="Coba ubah kata kunci atau kurangi filter yang aktif.">
                        @if ($hasFilters)
                            <x-ui.button wire:click="resetFilter" variant="secondary" size="sm">Reset filter</x-ui.button>
                        @endif
                    </x-ui.empty-state>
                </div>
            @endforelse
        </div>
    </div>

    @if ($projects->hasPages())
        <div class="pt-2">
            {{ $projects->links() }}
        </div>
    @endif
</div>
