@props(['categories' => collect(), 'skills' => collect()])

<div class="space-y-5">
    <div>
        <label for="project-title" class="mb-1.5 block text-sm font-medium text-kivu-text">Judul Proyek<span class="text-kivu-danger">*</span></label>
        <input id="project-title" type="text" wire:model="title" placeholder="Contoh: Desain Logo Warung Kopi"
            class="kivu-input px-3.5 py-2.5 text-sm" />
        @error('title')
            <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="project-description" class="mb-1.5 block text-sm font-medium text-kivu-text">Deskripsi<span class="text-kivu-danger">*</span></label>
        <textarea id="project-description" wire:model="description" rows="5"
            placeholder="Jelaskan kebutuhan, output yang diharapkan, dan referensi bila ada..."
            class="kivu-input px-3.5 py-2.5 text-sm"></textarea>
        @error('description')
            <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
        @enderror
    </div>

    <div class="space-y-4">
        <div>
            <span class="mb-1.5 block text-sm font-medium text-kivu-text">Rentang Budget (Rp)<span class="text-kivu-danger">*</span></span>
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-4">
                @foreach ([[100000, 300000, 'Ringan', 'Rp 100rb - 300rb'], [300000, 750000, 'Menengah', 'Rp 300rb - 750rb'], [750000, 1500000, 'Kompleks', 'Rp 750rb - 1,5jt'], [1500000, 3000000, 'Bespoke', 'di atas Rp 1,5jt']] as [$bMin, $bMax, $label, $hint])
                    <button type="button" wire:click="setBudgetRange({{ $bMin }}, {{ $bMax }})"
                        class="kivu-focus rounded-kivu-sm border border-kivu-border bg-kivu-surface px-3 py-2 text-left text-sm transition hover:border-kivu-primary/50 hover:bg-kivu-primary-soft">
                        <span class="block font-semibold text-kivu-text">{{ $label }}</span>
                        <span class="block text-xs text-kivu-text-muted">{{ $hint }}</span>
                    </button>
                @endforeach
            </div>
            <p class="mt-1.5 text-xs text-kivu-text-muted">
                Pilih perkiraan dari quick-pick di atas, atau isi rentang minimal &amp; maksimal secara manual. Talenta dapat mengajukan penawaran harga di dalam rentang ini.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
                <label for="project-min-budget" class="mb-1.5 block text-sm font-medium text-kivu-text">Minimal (Rp)<span class="text-kivu-danger">*</span></label>
                <input id="project-min-budget" type="number" wire:model.live="min_budget" step="any" placeholder="50000"
                    class="kivu-input px-3.5 py-2.5 text-sm" />
                @error('min_budget')
                    <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-kivu-text-muted">Bebas menyesuaikan kebutuhan proyek Anda.</p>
            </div>

            <div>
                <label for="project-max-budget" class="mb-1.5 block text-sm font-medium text-kivu-text">Maksimal (Rp)<span class="text-kivu-danger">*</span></label>
                <input id="project-max-budget" type="number" wire:model.live="max_budget" step="any" placeholder="200000"
                    class="kivu-input px-3.5 py-2.5 text-sm" />
                @error('max_budget')
                    <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="project-category" class="mb-1.5 block text-sm font-medium text-kivu-text">Kategori<span class="text-kivu-danger">*</span></label>
                <select id="project-category" wire:model="category_id" class="kivu-input px-3 py-2.5 text-sm">
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
                <label for="project-due" class="mb-1.5 block text-sm font-medium text-kivu-text">Deadline</label>
                <input id="project-due" type="date" wire:model="due_date" class="kivu-input px-3.5 py-2.5 text-sm" />
                @error('due_date')
                    <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-kivu-text-muted">Opsional</p>
            </div>
        </div>
    </div>

    <div>
        <span class="mb-1.5 block text-sm font-medium text-kivu-text">Keahlian Dibutuhkan</span>
        <div class="flex gap-2">
            <input type="text" wire:model="new_skill" wire:keydown.enter="addSkill" placeholder="Tambah keahlian lain..."
                class="kivu-input min-w-0 flex-1 px-3.5 py-2.5 text-sm" />
            <x-ui.button wire:click="addSkill" size="sm" class="shrink-0">
                <x-icon name="plus" :size="16" /> Tambah
            </x-ui.button>
        </div>
        @error('new_skill')
            <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
        @enderror
        <div class="mt-3 grid max-h-56 grid-cols-1 gap-1.5 overflow-y-auto rounded-kivu-sm border border-kivu-border p-3 sm:grid-cols-3 sm:gap-2">
            @foreach ($skills as $skill)
                <label class="kivu-focus flex cursor-pointer items-center gap-2 rounded-kivu-sm px-2 py-2 text-sm transition hover:bg-kivu-surface-muted">
                    <input type="checkbox" wire:model="skillIds" value="{{ $skill->id }}"
                        class="h-4 w-4 shrink-0 rounded border-kivu-border text-kivu-primary focus:ring-2 focus:ring-kivu-primary/30" />
                    <span class="break-words text-kivu-text-secondary">{{ $skill->name }}</span>
                </label>
            @endforeach
        </div>
        @error('skillIds')
            <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
        @enderror
        @error('skillIds.*')
            <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
        @enderror
        <p class="mt-1 text-xs text-kivu-text-muted">Pilih atau tambahkan keahlian, maksimal 8.</p>
    </div>
</div>
