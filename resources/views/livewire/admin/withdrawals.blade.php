<div class="space-y-6">
    <div>
        <h1 class="text-title-sm font-bold text-gray-800">Kelola Penarikan Saldo</h1>
        <p class="mt-1 text-sm text-gray-500">Setujui atau tolak permintaan penarikan dari mahasiswa.</p>
    </div>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
        <div class="relative flex-1">
            <x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama mahasiswa..." class="h-11 w-full rounded-lg border border-gray-300 bg-white pl-10 pr-4 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10">
        </div>
        <select wire:model.live="status" class="h-11 min-w-[180px] rounded-lg border border-gray-300 bg-white px-4 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10">
            <option value="">Semua Status</option>
            <option value="PENDING">Pending</option>
            <option value="APPROVED">Approved</option>
            <option value="REJECTED">Rejected</option>
        </select>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
        <div class="custom-scrollbar max-w-full overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-5 py-3 text-left sm:px-6"><p class="text-theme-xs font-medium text-gray-500">Mahasiswa</p></th>
                        <th class="px-5 py-3 text-left sm:px-6"><p class="text-theme-xs font-medium text-gray-500">Bank / Akun</p></th>
                        <th class="px-5 py-3 text-left sm:px-6"><p class="text-theme-xs font-medium text-gray-500">Jumlah</p></th>
                        <th class="px-5 py-3 text-left sm:px-6"><p class="text-theme-xs font-medium text-gray-500">Status</p></th>
                        <th class="px-5 py-3 text-left sm:px-6"><p class="text-theme-xs font-medium text-gray-500">Aksi</p></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $wd)
                    <tr class="border-b border-gray-100 last:border-0">
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center gap-3">
                                <x-ui.avatar :name="$wd->student->name ?? ''" size="sm" />
                                <p class="text-theme-sm font-semibold text-gray-800">{{ $wd->student->name }}</p>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <p class="text-theme-sm text-gray-500">{{ $wd->bank_name }} - {{ $wd->bank_account }}</p>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <p class="text-theme-sm font-bold text-gray-800">Rp {{ number_format($wd->amount, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            @php
                                $v = match($wd->status) { 'APPROVED' => 'success', 'REJECTED' => 'error', default => 'warning' };
                            @endphp
                            <x-ui.pill :tone="$v">{{ $wd->status }}</x-ui.pill>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            @if($wd->status === 'PENDING')
                                <div class="flex gap-2">
                                    <button wire:click="confirmApprove({{ $wd->id }})" wire:loading.attr="disabled" wire:target="runAction" class="inline-flex items-center gap-1.5 rounded-lg bg-success-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-success-600 disabled:opacity-50">
                                        <x-icon name="check" :size="14" /> Setujui
                                    </button>
                                    <button wire:click="confirmReject({{ $wd->id }})" wire:loading.attr="disabled" wire:target="runAction" class="inline-flex items-center gap-1.5 rounded-lg bg-error-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-error-600 disabled:opacity-50">
                                        <x-icon name="x" :size="14" /> Tolak
                                    </button>
                                </div>
                            @else
                                <span class="text-theme-xs text-gray-400">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center sm:px-6">
                            <x-icon name="wallet" :size="48" stroke="1.25" class="mx-auto text-gray-300" />
                            <p class="mt-3 text-sm font-medium text-gray-500">Tidak ada permintaan penarikan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-2">
        {{ $withdrawals->links() }}
    </div>

    @if($confirming)
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
            loading-target="runAction"
        >
            <p class="rounded-lg bg-gray-50 px-3 py-2 text-xs text-gray-600">Hanya penarikan berstatus PENDING dapat diproses. Guard backend memastikan aksi idempoten.</p>
        </x-ui.confirm-modal>
    @endif
</div>