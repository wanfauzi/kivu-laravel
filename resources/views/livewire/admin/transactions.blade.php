@php
    $selectClass = 'h-11 rounded-kivu-sm border border-kivu-border bg-kivu-surface px-3 text-sm text-kivu-text transition focus:border-kivu-primary focus:outline-none focus:ring-2 focus:ring-kivu-primary/20';
@endphp

<div class="space-y-6">
    <x-ui.page-header title="Riwayat Transaksi" subtitle="Semua transaksi pembayaran dan penarikan." />

    <div class="kivu-card flex flex-col gap-3 p-4 sm:flex-row sm:items-center">
        <div class="relative flex-1">
            <x-icon name="search" :size="18" class="pointer-events-none absolute top-1/2 left-3.5 -translate-y-1/2 text-kivu-text-muted" />
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama mahasiswa atau proyek..."
                class="kivu-input h-11 pl-10 pr-4 text-sm" />
        </div>
        <select wire:model.live="type" class="{{ $selectClass }} min-w-36">
            <option value="">Semua Tipe</option>
            <option value="payment">Payment</option>
            <option value="withdrawal">Withdrawal</option>
            <option value="refund">Refund</option>
        </select>
        <select wire:model.live="status" class="{{ $selectClass }} min-w-36">
            <option value="">Semua Status</option>
            <option value="RECORDED">Tercatat</option>
            <option value="SUCCESS">Berhasil</option>
            <option value="REJECTED">Ditolak</option>
        </select>
    </div>

    <div class="kivu-card hidden overflow-hidden md:block">
        <div class="custom-scrollbar max-w-full overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-kivu-border">
                        <th class="px-5 py-3 text-left"><p class="kivu-section-title">Mahasiswa</p></th>
                        <th class="px-5 py-3 text-left"><p class="kivu-section-title">Proyek</p></th>
                        <th class="px-5 py-3 text-left"><p class="kivu-section-title">Jumlah</p></th>
                        <th class="px-5 py-3 text-left"><p class="kivu-section-title">Tipe</p></th>
                        <th class="px-5 py-3 text-left"><p class="kivu-section-title">Status</p></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $t)
                        <tr class="border-b border-kivu-border last:border-0">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2.5">
                                    <x-ui.avatar :name="$t->student->name ?? ''" size="sm" />
                                    <p class="text-sm font-medium text-kivu-text">{{ $t->student->name }}</p>
                                </div>
                            </td>
                            <td class="px-5 py-4"><p class="text-sm text-kivu-text-secondary">{{ $t->project->title ?? '-' }}</p></td>
                            <td class="px-5 py-4"><p class="text-sm font-bold text-kivu-text">Rp {{ number_format($t->amount, 0, ',', '.') }}</p></td>
                            <td class="px-5 py-4">
                                <x-ui.pill :tone="$t->type === 'payment' ? 'success' : ($t->type === 'withdrawal' ? 'info' : 'warning')" class="capitalize">{{ $t->type }}</x-ui.pill>
                            </td>
                            <td class="px-5 py-4"><x-ui.status-badge :value="$t->status" kind="transaction" /></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center">
                                <x-icon name="receipt-text" :size="48" stroke="1.25" class="mx-auto text-kivu-text-muted/40" />
                                <p class="mt-3 text-sm font-medium text-kivu-text-muted">Tidak ada transaksi ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-3 md:hidden">
        @forelse ($transactions as $t)
            <div class="kivu-card p-4">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-sm font-semibold text-kivu-text">{{ $t->student->name }}</p>
                    <p class="font-bold text-kivu-text">Rp {{ number_format($t->amount, 0, ',', '.') }}</p>
                </div>
                <p class="mt-1 text-xs text-kivu-text-muted">{{ $t->project->title ?? '-' }}</p>
                <div class="mt-2 flex items-center gap-2">
                    <x-ui.pill :tone="$t->type === 'payment' ? 'success' : ($t->type === 'withdrawal' ? 'info' : 'warning')" class="capitalize">{{ $t->type }}</x-ui.pill>
                    <x-ui.status-badge :value="$t->status" kind="transaction" />
                </div>
                <p class="mt-1 text-xs text-kivu-text-muted">{{ $t->created_at->translatedFormat('d F Y · H:i') }}</p>
            </div>
        @empty
            <x-ui.empty-state icon="receipt-text" title="Tidak ada transaksi ditemukan" description="Coba ubah kata kunci atau filter." />
        @endforelse
    </div>

    <div>
        {{ $transactions->links() }}
    </div>
</div>