<div class="space-y-8">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Dompet Saya</h1>
        <p class="text-sm text-gray-500">Kelola saldo dan riwayat transaksi Anda.</p>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 to-blue-700 p-6 text-white">
                <div class="pointer-events-none absolute -right-8 -bottom-8 h-36 w-36 rounded-full bg-white/10"></div>
                <div class="pointer-events-none absolute -top-6 right-16 h-20 w-20 rounded-full bg-white/5"></div>
                <div class="relative">
                    <p class="text-sm font-medium text-blue-100">Saldo Tersedia</p>
                    <p class="mt-1 text-4xl font-bold">Rp {{ number_format($balance, 0, ',', '.') }}</p>
                    <div class="mt-4 flex items-center gap-2 text-xs text-blue-100">
                        <x-icon name="wallet" :size="16" /> Dompet micro-freelance KIVU
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="rounded-2xl border border-gray-200 bg-white p-4">
                    <p class="text-xs text-gray-500">Total Pendapatan</p>
                    <p class="mt-1 text-lg font-bold text-success-600">Rp {{ number_format($totalEarnings, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-4">
                    <p class="text-xs text-gray-500">Total Ditarik</p>
                    <p class="mt-1 text-lg font-bold text-gray-800">Rp {{ number_format($totalWithdrawn, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="text-lg font-bold text-gray-900">Riwayat Transaksi</h3>
                <div class="space-y-3">
                    @forelse($transactions as $trx)
                        @php
                            $isPayment = $trx->type === 'payment';
                            $isRefund = $trx->type === 'refund';
                            $itemClass = $isPayment ? 'bg-success-50 text-success-600' : ($isRefund ? 'bg-warning-50 text-warning-600' : 'bg-error-50 text-error-600');
                            $itemIcon = $isPayment ? 'arrow-down' : 'arrow-up';
                            $itemLabel = $isPayment ? ($trx->project->title ?? 'Pembayaran Proyek') : ($isRefund ? 'Pengembalian Dana' : 'Penarikan Saldo');
                            $amountClass = $isPayment ? 'text-success-600' : ($isRefund ? 'text-warning-600' : 'text-error-600');
                        @endphp
                        <div class="flex items-center justify-between rounded-2xl border border-gray-200 bg-white p-4">
                            <div class="flex items-center gap-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full {{ $itemClass }}">
                                    <x-icon :name="$itemIcon" :size="20" />
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $itemLabel }}</p>
                                    <p class="text-xs text-gray-500">{{ $trx->created_at->translatedFormat('d F Y · H:i') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold {{ $amountClass }}">{{ $isPayment ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}</p>
                                <x-ui.status-badge :value="$trx->status" kind="transaction" class="mt-1" />
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center">
                            <x-icon name="receipt-text" :size="32" stroke="1.25" class="mx-auto text-gray-300" />
                            <p class="mt-3 text-sm text-gray-500">Belum ada riwayat transaksi. Pembayaran akan muncul di sini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="text-lg font-bold text-gray-900">Riwayat Penarikan</h3>
                <div class="space-y-3">
                    @forelse($withdrawals as $wd)
                        <div class="flex items-center justify-between rounded-2xl border border-gray-200 bg-white p-4">
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $wd->bank_name }} - {{ $wd->bank_account }}</p>
                                <p class="text-xs text-gray-500">{{ $wd->created_at->translatedFormat('d F Y · H:i') }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="text-right">
                                    <p class="text-sm font-bold text-gray-800">Rp {{ number_format($wd->amount, 0, ',', '.') }}</p>
                                    <x-ui.status-badge :value="$wd->status" kind="withdrawal" class="mt-1" />
                                </div>
                                @if($wd->status === 'PENDING')
                                    <button wire:click="confirmCancel({{ $wd->id }})" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-300 text-gray-400 transition hover:border-error-200 hover:text-error-600" aria-label="Batalkan penarikan">
                                        <x-icon name="x" :size="14" />
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center text-sm text-gray-500">Belum ada penarikan.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white p-6">
                <h3 class="flex items-center gap-2 text-lg font-bold text-gray-900">
                    <x-icon name="arrow-up-from-line" :size="20" /> Tarik Saldo
                </h3>
                <p class="mt-1 text-xs text-gray-500">Minimal penarikan Rp 10.000</p>

                <form wire:submit.prevent="confirmWithdraw" class="mt-4 space-y-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Jumlah (Rp)</label>
                        <input type="number" wire:model="amount" placeholder="Contoh: 50000" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                        @error('amount') <p class="mt-1 text-xs text-error-600">{{ $message }}</p> @enderror
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach([25000, 50000, 100000, 250000] as $preset)
                                <button type="button" wire:click="$set('amount', {{ $preset }})" class="rounded-full border border-gray-300 bg-white px-3 py-1 text-xs font-medium text-gray-600 transition hover:border-blue-500 hover:text-blue-600">{{ number_format($preset, 0, ',', '.') }}</button>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Nama Bank / E-Wallet</label>
                        <input type="text" wire:model="bank_name" placeholder="BCA, Mandiri, GoPay..." class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                        @error('bank_name') <p class="mt-1 text-xs text-error-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Nomor Rekening / HP</label>
                        <input type="text" wire:model="bank_account" placeholder="0812..." class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                        @error('bank_account') <p class="mt-1 text-xs text-error-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                        <textarea wire:model="note" rows="2" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"></textarea>
                    </div>

                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700" wire:loading.attr="disabled" wire:target="confirmWithdraw">
                        <span wire:loading.remove wire:target="confirmWithdraw"><x-icon name="arrow-up-from-line" :size="18" /> Tarik Sekarang</span>
                        <span wire:loading wire:target="confirmWithdraw">Memeriksa...</span>
                    </button>
                </form>
            </div>

            <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5">
                <h4 class="flex items-center gap-2 text-sm font-bold text-blue-900"><x-icon name="info" :size="16" /> Informasi</h4>
                <p class="mt-1 text-xs leading-relaxed text-blue-700">Penarikan diproses manual oleh admin dalam 1–3 hari kerja. Pastikan data rekening benar sebelum mengirim.</p>
            </div>
        </div>
    </div>

    @if($confirming)
        <x-ui.confirm-modal
            title="Konfirmasi Tarik Saldo"
            message="Anda akan mengajukan penarikan saldo. Jumlah dan rekening tidak dapat diubah setelah dikirim."
            confirm-label="Tarik Saldo"
            confirm-method="withdraw"
            cancel-method="cancelWithdraw"
            tone="brand"
            loading-target="withdraw"
        >
            <dl class="space-y-2 rounded-lg bg-gray-50 px-3 py-3 text-sm">
                <div class="flex justify-between"><dt class="text-gray-500">Jumlah</dt><dd class="font-semibold text-gray-800">Rp {{ number_format($amount, 0, ',', '.') }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Tujuan</dt><dd class="font-semibold text-gray-800">{{ $bank_name }} · {{ $bank_account }}</dd></div>
            </dl>
        </x-ui.confirm-modal>
    @endif

    @if($confirmingCancel)
        <x-ui.confirm-modal
            title="Batalkan Penarikan"
            message="Penarikan ini akan dibatalkan dan saldo dikembalikan."
            confirm-label="Ya, Batalkan"
            confirm-method="cancelWithdrawal"
            cancel-method="cancelCancel"
            tone="danger"
            loading-target="cancelWithdrawal"
        />
    @endif
</div>