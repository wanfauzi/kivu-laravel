@php
    $selectClass = 'h-11 rounded-kivu-sm border border-kivu-border bg-kivu-surface px-3 text-sm text-kivu-text transition focus:border-kivu-primary focus:outline-none focus:ring-2 focus:ring-kivu-primary/20';
@endphp

<div class="space-y-6">
    <x-ui.page-header title="Kelola Proyek" subtitle="Pantau semua proyek yang berjalan di platform." />

    <div class="kivu-card flex flex-col gap-3 p-4 sm:flex-row sm:items-center">
        <div class="relative flex-1">
            <x-icon name="search" :size="18" class="pointer-events-none absolute top-1/2 left-3.5 -translate-y-1/2 text-kivu-text-muted" />
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari judul proyek..."
                class="kivu-input h-11 pl-10 pr-4 text-sm" />
        </div>
        <select wire:model.live="status" class="{{ $selectClass }} min-w-44">
            <option value="">Semua Status</option>
            <option value="OPEN">Terbuka</option>
            <option value="IN_PROGRESS">Dikerjakan</option>
            <option value="SUBMITTED">Menunggu Review</option>
            <option value="COMPLETED">Selesai</option>
            <option value="CANCELLED">Dibatalkan</option>
        </select>
    </div>

    {{-- Desktop table --}}
    <div class="kivu-card hidden overflow-hidden md:block">
        <div class="custom-scrollbar max-w-full overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-kivu-border">
                        <th class="px-5 py-3 text-left"><p class="kivu-section-title">Judul</p></th>
                        <th class="px-5 py-3 text-left"><p class="kivu-section-title">Pemilik</p></th>
                        <th class="px-5 py-3 text-left"><p class="kivu-section-title">Budget</p></th>
                        <th class="px-5 py-3 text-left"><p class="kivu-section-title">Status</p></th>
                        <th class="px-5 py-3 text-left"><p class="kivu-section-title">Aksi</p></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($projects as $p)
                        <tr class="border-b border-kivu-border last:border-0">
                            <td class="px-5 py-4"><p class="text-sm font-medium text-kivu-text">{{ $p->title }}</p></td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2.5">
                                    <x-ui.avatar :name="$p->owner->name ?? ''" size="sm" />
                                    <p class="text-sm text-kivu-text-secondary">{{ $p->owner->name }}</p>
                                </div>
                            </td>
                            <td class="px-5 py-4"><p class="text-sm font-semibold text-kivu-primary">Rp {{ number_format($p->budget, 0, ',', '.') }}</p></td>
                            <td class="px-5 py-4"><x-ui.status-badge :value="$p->status" kind="project" /></td>
                            <td class="px-5 py-4">
                                <x-ui.button wire:click="confirmRemove({{ $p->id }})" variant="secondary" size="sm" class="text-kivu-danger">
                                    <x-icon name="trash" :size="14" /> Takedown
                                </x-ui.button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center">
                                <x-icon name="folder-open" :size="48" stroke="1.25" class="mx-auto text-kivu-text-muted/40" />
                                <p class="mt-3 text-sm font-medium text-kivu-text-muted">Tidak ada proyek ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile cards --}}
    <div class="space-y-3 md:hidden">
        @forelse ($projects as $p)
            <div class="kivu-card p-4">
                <div class="flex items-start justify-between gap-3">
                    <p class="min-w-0 text-sm font-semibold text-kivu-text">{{ $p->title }}</p>
                    <x-ui.status-badge :value="$p->status" kind="project" />
                </div>
                <p class="mt-1 text-xs text-kivu-text-muted">oleh {{ $p->owner->name }}</p>
                <div class="mt-3 flex items-center justify-between border-t border-kivu-border pt-3">
                    <p class="font-bold text-kivu-primary">Rp {{ number_format($p->budget, 0, ',', '.') }}</p>
                    <x-ui.button wire:click="confirmRemove({{ $p->id }})" variant="secondary" size="sm" class="text-kivu-danger">
                        <x-icon name="trash" :size="14" /> Takedown
                    </x-ui.button>
                </div>
            </div>
        @empty
            <x-ui.empty-state icon="folder-open" title="Tidak ada proyek ditemukan" description="Coba ubah kata kunci atau filter status." />
        @endforelse
    </div>

    <div>
        {{ $projects->links() }}
    </div>

    @if ($confirmingRemove)
        <x-ui.confirm-modal
            title="Takedown Proyek"
            message="Proyek ini akan dihapus dari platform beserta lamaran, pengiriman, transaksi, dan ulasan terkait. Tindakan ini tidak dapat dibatalkan."
            confirm-label="Hapus Proyek"
            confirm-method="remove"
            cancel-method="cancelRemove"
            tone="danger"
            loading-target="remove" />
    @endif
</div>