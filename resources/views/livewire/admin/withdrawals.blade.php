@php
    $selectClass = 'h-11 rounded-kivu-sm border border-kivu-border bg-kivu-surface px-3 text-sm text-kivu-text transition focus:border-kivu-primary focus:outline-none focus:ring-2 focus:ring-kivu-primary/20';
@endphp

<div class="space-y-6">
    <x-ui.page-header title="Kelola Penarikan Saldo" subtitle="Setujui atau tolak permintaan penarikan dari mahasiswa." />

    <div class="kivu-card flex flex-col gap-3 p-4 sm:flex-row sm:items-center">
        <div class="relative flex-1">
            <x-icon name="search" :size="18" class="pointer-events-none absolute top-1/2 left-3.5 -translate-y-1/2 text-kivu-text-muted" />
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama mahasiswa..."
                class="kivu-input h-11 pl-10 pr-4 text-sm" />
        </div>
        <select wire:model.live="status" class="{{ $selectClass }} min-w-44">
            <option value="">Semua Status</option>
            <option value="PENDING">Menunggu</option>
            <option value="APPROVED">Disetujui</option>
            <option value="REJECTED">Ditolak</option>
            <option value="CANCELLED">Dibatalkan</option>
        </select>
    </div>

    <div class="kivu-card hidden overflow-hidden md:block">
        <div class="custom-scrollbar max-w-full overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-kivu-border">
                        <th class="px-5 py-3 text-left"><p class="kivu-section-title">Mahasiswa</p></th>
                        <th class="px-5 py-3 text-left"><p class="kivu-section-title">Bank / Akun</p></th>
                        <th class="px-5 py-3 text-left"><p class="kivu-section-title">Jumlah</p></th>
                        <th class="px-5 py-3 text-left"><p class="kivu-section-title">Status</p></th>
                        <th class="px-5 py-3 text-left"><p class="kivu-section-title">Aksi</p></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($withdrawals as $wd)
                        <tr class="border-b border-kivu-border last:border-0">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2.5">
                                    <x-ui.avatar :name="$wd->student->name ?? ''" size="sm" />
                                    <p class="text-sm font-semibold text-kivu-text">{{ $wd->student->name }}</p>
                                </div>
                            </td>
                            <td class="px-5 py-4"><p class="text-sm text-kivu-text-secondary">{{ $wd->bank_name }} &middot; {{ $wd->bank_account }}</p></td>
                            <td class="px-5 py-4"><p class="text-sm font-bold text-kivu-text">Rp {{ number_format($wd->amount, 0, ',', '.') }}</p></td>
                            <td class="px-5 py-4"><x-ui.status-badge :value="$wd->status" kind="withdrawal" /></td>
                            <td class="px-5 py-4">
                                @if ($wd->status === 'PENDING')
                                    <div class="flex gap-2">
                                        <x-ui.button wire:click="confirmApprove({{ $wd->id }})" variant="success" size="sm">
                                            <x-icon name="check" :size="14" /> Setujui
                                        </x-ui.button>
                                        <x-ui.button wire:click="confirmReject({{ $wd->id }})" variant="danger" size="sm">
                                            <x-icon name="x" :size="14" /> Tolak
                                        </x-ui.button>
                                    </div>
                                @else
                                    <span class="text-xs text-kivu-text-muted">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center">
                                <x-icon name="wallet" :size="48" stroke="1.25" class="mx-auto text-kivu-text-muted/40" />
                                <p class="mt-3 text-sm font-medium text-kivu-text-muted">Tidak ada permintaan penarikan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-3 md:hidden">
        @forelse ($withdrawals as $wd)
            <div class="kivu-card p-4">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-sm font-semibold text-kivu-text">{{ $wd->student->name }}</p>
                    <p class="font-bold text-kivu-text">Rp {{ number_format($wd->amount, 0, ',', '.') }}</p>
                </div>
                <p class="mt-1 text-xs text-kivu-text-muted">{{ $wd->bank_name }} &middot; {{ $wd->bank_account }}</p>
                <div class="mt-2 flex items-center justify-between gap-2 border-t border-kivu-border pt-3">
                    <x-ui.status-badge :value="$wd->status" kind="withdrawal" />
                    @if ($wd->status === 'PENDING')
                        <div class="flex gap-2">
                            <x-ui.button wire:click="confirmApprove({{ $wd->id }})" variant="success" size="sm">Setujui</x-ui.button>
                            <x-ui.button wire:click="confirmReject({{ $wd->id }})" variant="danger" size="sm">Tolak</x-ui.button>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <x-ui.empty-state icon="wallet" title="Tidak ada permintaan penarikan" description="Permintaan penarikan dari mahasiswa akan tampil di sini." />
        @endforelse
    </div>

    <div>
        {{ $withdrawals->links() }}
    </div>

    @if ($confirming)
        @php
            $isApprove = $pendingType === 'approve';
            $msg = $isApprove
                ? 'Penarikan akan disetujui dan dicatat sebagai transaksi berhasil. Dana telah dikurangi dari saldo mahasiswa.'
                : 'Penarikan akan ditolak. Saldo akan dikembalikan ke wallet mahasiswa.';
        @endphp
        <x-ui.confirm-modal
            :title="$isApprove ? 'Konfirmasi Setujui Penarikan' : 'Konfirmasi Tolak Penarikan'"
            :message="$msg"
            :confirm-label="$isApprove ? 'Setujui' : 'Tolak'"
            confirm-method="runAction"
            cancel-method="cancelAction"
            :tone="$isApprove ? 'success' : 'danger'"
            loading-target="runAction" />
    @endif
</div>