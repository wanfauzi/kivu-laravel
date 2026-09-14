<div class="space-y-6">
    <div>
        <h1 class="text-title-sm font-bold text-gray-800">Riwayat Transaksi</h1>
        <p class="mt-1 text-sm text-gray-500">Semua transaksi pembayaran dan penarikan.</p>
    </div>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
        <div class="relative flex-1">
            <x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama mahasiswa atau proyek..." class="h-11 w-full rounded-lg border border-gray-300 bg-white pl-10 pr-4 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10">
        </div>
        <select wire:model.live="type" class="h-11 min-w-[160px] rounded-lg border border-gray-300 bg-white px-4 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10">
            <option value="">Semua Tipe</option>
            <option value="payment">Payment</option>
            <option value="withdrawal">Withdrawal</option>
            <option value="refund">Refund</option>
        </select>
        <select wire:model.live="status" class="h-11 min-w-[160px] rounded-lg border border-gray-300 bg-white px-4 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10">
            <option value="">Semua Status</option>
            <option value="RECORDED">Recorded</option>
            <option value="SUCCESS">Success</option>
            <option value="REJECTED">Rejected</option>
        </select>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
        <div class="custom-scrollbar max-w-full overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-5 py-3 text-left sm:px-6"><p class="text-theme-xs font-medium text-gray-500">Mahasiswa</p></th>
                        <th class="px-5 py-3 text-left sm:px-6"><p class="text-theme-xs font-medium text-gray-500">Proyek</p></th>
                        <th class="px-5 py-3 text-left sm:px-6"><p class="text-theme-xs font-medium text-gray-500">Jumlah</p></th>
                        <th class="px-5 py-3 text-left sm:px-6"><p class="text-theme-xs font-medium text-gray-500">Tipe</p></th>
                        <th class="px-5 py-3 text-left sm:px-6"><p class="text-theme-xs font-medium text-gray-500">Status</p></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $t)
                    <tr class="border-b border-gray-100 last:border-0">
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center gap-3">
                                <x-ui.avatar :name="$t->student->name ?? ''" size="sm" />
                                <p class="text-theme-sm font-medium text-gray-800">{{ $t->student->name }}</p>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <p class="text-theme-sm text-gray-500">{{ $t->project->title ?? '-' }}</p>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <p class="text-theme-sm font-bold text-gray-800">Rp {{ number_format($t->amount, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            @php
                                $typeMap = ['payment' => ['Payment', 'brand'], 'withdrawal' => ['Withdrawal', 'info'], 'refund' => ['Refund', 'warning']];
                                [$typeLabel, $tv] = $typeMap[$t->type] ?? [ucfirst($t->type), 'neutral'];
                            @endphp
                            <x-ui.pill :tone="$tv">{{ $typeLabel }}</x-ui.pill>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            @php $stv = match($t->status) { 'SUCCESS' => 'success', 'REJECTED' => 'error', default => 'warning' }; @endphp
                            <x-ui.pill :tone="$stv">{{ $t->status }}</x-ui.pill>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center sm:px-6">
                            <x-icon name="receipt-text" :size="48" stroke="1.25" class="mx-auto text-gray-300" />
                            <p class="mt-3 text-sm font-medium text-gray-500">Tidak ada transaksi ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-2">
        {{ $transactions->links() }}
    </div>
</div>