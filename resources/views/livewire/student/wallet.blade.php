<div class="space-y-6">
    <x-ui.page-header title="Dompet Saya" subtitle="Kelola saldo, penarikan, dan riwayat transaksi Anda." />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            {{-- Balance --}}
            <div class="kivu-card p-6">
                <p class="text-sm text-kivu-text-secondary">Saldo Tersedia</p>
                <p class="mt-1 text-4xl font-extrabold text-kivu-text">Rp {{ number_format($balance, 0, ',', '.') }}</p>
                <div class="mt-4 flex items-center gap-2 text-xs text-kivu-text-muted">
                    <x-icon name="wallet" :size="15" class="text-kivu-primary" /> Dompet micro-freelance KIVU
                </div>
            </div>

            {{-- Metrics --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-ui.stat-card icon="banknote" tone="success" label="Total Pendapatan" :value="'Rp '.number_format($totalEarnings, 0, ',', '.')" hint="dari proyek selesai" />
                <x-ui.stat-card icon="arrow-up-from-line" tone="info" label="Total Ditarik" :value="'Rp '.number_format($totalWithdrawn, 0, ',', '.')" hint="penarikan disetujui" />
            </div>

            {{-- Transactions --}}
            <div class="space-y-4">
                <h3 class="text-lg font-bold text-kivu-text">Riwayat Transaksi</h3>

                <div class="space-y-3">
                    @forelse ($transactions as $trx)
                        @php
                            $isIn = $trx->direction() === 'in';
                            $tone = match (true) {
                                $trx->type === 'payment' => 'bg-kivu-success-soft text-kivu-success',
                                $trx->type === 'refund' => 'bg-kivu-warning-soft text-kivu-warning',
                                $isIn => 'bg-kivu-success-soft text-kivu-success',
                                default => 'bg-kivu-surface-muted text-kivu-text-muted',
                            };
                        @endphp
                        <div class="kivu-card flex items-center justify-between gap-4 p-4">
                            <div class="flex min-w-0 items-center gap-3.5">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $tone }}">
                                    <x-icon :name="$isIn ? 'arrow-down' : 'arrow-up'" :size="18" />
                                </span>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-kivu-text">{{ $trx->displayLabel() }}</p>
                                    <p class="text-xs text-kivu-text-muted">{{ $trx->created_at->translatedFormat('d F Y · H:i') }}</p>
                                </div>
                            </div>

                            <div class="shrink-0 text-right">
                                <p class="text-sm font-bold {{ $isIn ? 'text-kivu-success' : 'text-kivu-text-secondary' }}">
                                    {{ $isIn ? '+' : '−' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                                </p>
                                <x-ui.status-badge :value="$trx->status" kind="transaction" class="mt-1" />
                            </div>
                        </div>
                    @empty
                        <x-ui.empty-state icon="receipt-text" title="Belum ada transaksi"
                            description="Pembayaran dari proyek yang selesai akan muncul di sini." />
                    @endforelse
                </div>

                @if ($transactions->hasPages())
                    <div>{{ $transactions->links() }}</div>
                @endif
            </div>

            {{-- Withdrawals --}}
            <div class="space-y-4">
                <h3 class="text-lg font-bold text-kivu-text">Riwayat Penarikan</h3>

                <div class="space-y-3">
                    @forelse ($withdrawals as $wd)
                        <div class="kivu-card flex items-center justify-between gap-4 p-4">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-kivu-text">{{ $wd->bank_name }} &middot; {{ $wd->bank_account }}</p>
                                <p class="text-xs text-kivu-text-muted">{{ $wd->created_at->translatedFormat('d F Y · H:i') }}</p>
                            </div>

                            <div class="flex shrink-0 items-center gap-3">
                                <div class="text-right">
                                    <p class="text-sm font-bold text-kivu-text">Rp {{ number_format($wd->amount, 0, ',', '.') }}</p>
                                    <x-ui.status-badge :value="$wd->status" kind="withdrawal" class="mt-1" />
                                </div>

                                @if ($wd->status === 'PENDING')
                                    <x-ui.button wire:click="confirmCancel({{ $wd->id }})" variant="ghost" size="sm"
                                        class="h-8 w-8 px-0!" aria-label="Batalkan penarikan">
                                        <x-icon name="x" :size="14" />
                                    </x-ui.button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <x-ui.empty-state icon="arrow-up-from-line" title="Belum ada penarikan"
                            description="Ajukan penarikan setelah saldo Anda cukup." />
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Withdraw form --}}
        <div class="space-y-6">
            <div class="kivu-card p-6 lg:sticky lg:top-24">
                <h3 class="flex items-center gap-2 text-lg font-bold text-kivu-text">
                    <x-icon name="arrow-up-from-line" :size="20" class="text-kivu-primary" /> Tarik Saldo
                </h3>
                <p class="mt-1 text-xs text-kivu-text-muted">Minimal penarikan Rp 10.000</p>

                <form wire:submit="confirmWithdraw" class="mt-5 space-y-4">
                    <div>
                        <label for="wd-amount" class="mb-1.5 block text-sm font-medium text-kivu-text">Jumlah (Rp)</label>
                        <input id="wd-amount" type="number" wire:model="amount" placeholder="Contoh: 50000"
                            class="kivu-input px-3.5 py-2.5 text-sm" />
                        @error('amount')
                            <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                        @enderror
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach ([25000, 50000, 100000, 250000] as $preset)
                                <button type="button" wire:click="$set('amount', {{ $preset }})"
                                    class="kivu-focus rounded-full border border-kivu-border bg-kivu-surface px-3 py-1 text-xs font-medium text-kivu-text-secondary transition hover:border-kivu-primary hover:text-kivu-primary">
                                    {{ number_format($preset, 0, ',', '.') }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label for="wd-bank" class="mb-1.5 block text-sm font-medium text-kivu-text">Nama Bank / E-Wallet</label>
                        <input id="wd-bank" type="text" wire:model="bank_name" placeholder="BCA, Mandiri, GoPay..."
                            class="kivu-input px-3.5 py-2.5 text-sm" />
                        @error('bank_name')
                            <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="wd-account" class="mb-1.5 block text-sm font-medium text-kivu-text">Nomor Rekening / HP</label>
                        <input id="wd-account" type="text" wire:model="bank_account" placeholder="0812..."
                            class="kivu-input px-3.5 py-2.5 text-sm" />
                        @error('bank_account')
                            <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="wd-note" class="mb-1.5 block text-sm font-medium text-kivu-text">Catatan (opsional)</label>
                        <textarea id="wd-note" wire:model="note" rows="2" class="kivu-input px-3.5 py-2.5 text-sm"></textarea>
                    </div>

                    <x-ui.button type="submit" size="lg" loading-target="confirmWithdraw" class="w-full">
                        <x-icon name="arrow-up-from-line" :size="18" /> Tarik Sekarang
                    </x-ui.button>
                </form>
            </div>

            <div class="flex gap-2.5 rounded-kivu border border-kivu-info/25 bg-kivu-info-soft p-4">
                <x-icon name="info" :size="16" class="mt-0.5 shrink-0 text-kivu-info" />
                <p class="text-xs leading-relaxed text-kivu-info">
                    Penarikan diproses manual oleh admin dalam 1–3 hari kerja. Pastikan data rekening benar sebelum mengirim.
                </p>
            </div>
        </div>
    </div>

    @if ($confirming)
        <x-ui.confirm-modal
            title="Konfirmasi Tarik Saldo"
            message="Anda akan mengajukan penarikan saldo. Jumlah dan rekening tidak dapat diubah setelah dikirim."
            confirm-label="Tarik Saldo"
            confirm-method="withdraw"
            cancel-method="cancelWithdraw"
            tone="primary"
            loading-target="withdraw">
            <dl class="space-y-2 rounded-kivu-sm bg-kivu-surface-muted px-3.5 py-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-kivu-text-muted">Jumlah</dt>
                    <dd class="font-semibold text-kivu-text">Rp {{ number_format((int) $amount, 0, ',', '.') }}</dd>
                </div>
                <div class="flex justify-between gap-3">
                    <dt class="text-kivu-text-muted">Tujuan</dt>
                    <dd class="truncate font-semibold text-kivu-text">{{ $bank_name }} · {{ $bank_account }}</dd>
                </div>
            </dl>
        </x-ui.confirm-modal>
    @endif

    @if ($confirmingCancel)
        <x-ui.confirm-modal
            title="Batalkan Penarikan"
            message="Penarikan ini akan dibatalkan dan saldo dikembalikan ke dompet Anda."
            confirm-label="Ya, Batalkan"
            confirm-method="cancelWithdrawal"
            cancel-method="cancelCancel"
            tone="danger"
            loading-target="cancelWithdrawal" />
    @endif
</div>
