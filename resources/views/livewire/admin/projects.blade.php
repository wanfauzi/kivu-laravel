<div class="space-y-6">
    <div>
        <h1 class="text-title-sm font-bold text-gray-800">Kelola Proyek</h1>
        <p class="mt-1 text-sm text-gray-500">Pantau semua proyek yang berjalan di platform.</p>
    </div>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
        <div class="relative flex-1">
            <x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari judul proyek..." class="h-11 w-full rounded-lg border border-gray-300 bg-white pl-10 pr-4 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10">
        </div>
        <select wire:model.live="status" class="h-11 min-w-[180px] rounded-lg border border-gray-300 bg-white px-4 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10">
            <option value="">Semua Status</option>
            <option value="OPEN">Open</option>
            <option value="IN_PROGRESS">In Progress</option>
            <option value="SUBMITTED">Submitted</option>
            <option value="COMPLETED">Completed</option>
        </select>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
        <div class="custom-scrollbar max-w-full overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-5 py-3 text-left sm:px-6"><p class="text-theme-xs font-medium text-gray-500">Judul</p></th>
                        <th class="px-5 py-3 text-left sm:px-6"><p class="text-theme-xs font-medium text-gray-500">Pemilik</p></th>
                        <th class="px-5 py-3 text-left sm:px-6"><p class="text-theme-xs font-medium text-gray-500">Budget</p></th>
                        <th class="px-5 py-3 text-left sm:px-6"><p class="text-theme-xs font-medium text-gray-500">Status</p></th>
                        <th class="px-5 py-3 text-left sm:px-6"><p class="text-theme-xs font-medium text-gray-500">Aksi</p></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $p)
                    <tr class="border-b border-gray-100 last:border-0">
                        <td class="px-5 py-4 sm:px-6">
                            <p class="text-theme-sm font-medium text-gray-800">{{ $p->title }}</p>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center gap-3">
                                <x-ui.avatar :name="$p->owner->name ?? ''" size="sm" />
                                <p class="text-theme-sm text-gray-500">{{ $p->owner->name }}</p>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <p class="text-theme-sm font-semibold text-gray-800">Rp {{ number_format($p->budget, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            @php
                                $v = match($p->status) { 'OPEN' => 'success', 'IN_PROGRESS' => 'info', 'SUBMITTED' => 'warning', 'COMPLETED' => 'brand', default => 'neutral' };
                                $l = match($p->status) { 'OPEN' => 'Open', 'IN_PROGRESS' => 'In Progress', 'SUBMITTED' => 'Submitted', 'COMPLETED' => 'Completed', default => $p->status };
                            @endphp
                            <x-ui.pill :tone="$v">{{ $l }}</x-ui.pill>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <button type="button" wire:click="confirmRemove({{ $p->id }})"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-error-200 px-3 py-1.5 text-theme-xs font-medium text-error-600 transition hover:bg-error-50">
                                <x-icon name="trash" :size="14" />
                                Takedown
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center sm:px-6">
                            <x-icon name="folder-open" :size="48" stroke="1.25" class="mx-auto text-gray-300" />
                            <p class="mt-3 text-sm font-medium text-gray-500">Tidak ada proyek ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-2">
        {{ $projects->links() }}
    </div>

    @if($confirmingRemove)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" wire:click.self="cancelRemove">
            <div class="w-full max-w-md rounded-2xl border border-gray-200 bg-white p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Takedown Proyek</h3>
                    <button wire:click="cancelRemove" type="button" class="rounded-lg p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600" aria-label="Tutup">
                        <x-icon name="x" :size="20" />
                    </button>
                </div>
                <p class="mt-3 text-sm text-gray-500">Proyek ini akan dihapus dari platform beserta lamaran, pengiriman, transaksi, dan ulasan terkait. Tindakan ini tidak dapat dibatalkan.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" wire:click="cancelRemove" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">Batal</button>
                    <button type="button" wire:click="remove" class="rounded-lg bg-error-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-error-600">Hapus Proyek</button>
                </div>
            </div>
        </div>
    @endif
</div>