<div class="mx-auto max-w-xl space-y-6">
    <x-ui.page-header :breadcrumbs="[
        ['label' => 'Proyek Saya', 'url' => route('umkm.my-projects')],
        ['label' => 'Pendanaan Proyek'],
    ]" :back="route('umkm.my-projects')" back-label="Kembali" title="Pendanaan Escrow"
        :subtitle="$project->title">
    </x-ui.page-header>

    <div class="kivu-card p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-kivu-border pb-4">
            <div>
                <span class="text-xs text-kivu-text-muted">Total Anggaran Proyek</span>
                <p class="text-2xl font-bold text-kivu-primary">Rp {{ number_format($project->budget, 0, ',', '.') }}</p>
            </div>
            <x-ui.badge variant="warning">Menunggu Pembayaran</x-ui.badge>
        </div>

        <p class="text-sm text-kivu-text-secondary leading-relaxed">
            Untuk menerbitkan proyek ini dan menjamin keamanan transaksi (Escrow), silakan lakukan pembayaran deposit anggaran sebesar anggaran proyek. Dana akan ditahan sistem hingga proyek selesai dikerjakan mahasiswa.
        </p>

        <div class="pt-4">
            <x-ui.button wire:click="initiateFunding" size="lg" class="w-full justify-center">
                <x-icon name="qrcode" :size="18" /> Bayar Deposit via QRIS
            </x-ui.button>
        </div>
    </div>

    @if ($showPaymentModal && $paymentSimulation)
        <div class="fixed inset-0 z-50 flex items-end justify-center bg-black/50 p-4 backdrop-blur-[2px] sm:items-center" wire:click.self="closeFunding">
            <div role="dialog" aria-modal="true" class="kivu-card w-full max-w-md p-6 shadow-theme-xl">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-base font-bold text-kivu-text">QRIS Pendanaan Escrow</h3>
                        <p class="mt-1 text-xs text-kivu-text-muted">Scan untuk mendepositokan anggaran proyek.</p>
                    </div>
                    <button type="button" wire:click="closeFunding" class="kivu-focus -mr-1 -mt-1 rounded-lg p-1.5 text-kivu-text-muted transition hover:bg-kivu-surface-muted hover:text-kivu-text">
                        <x-icon name="x" :size="18" />
                    </button>
                </div>

                <div class="mt-5 flex flex-col items-center">
                    @if ($qrDataUrl)
                        <img src="{{ $qrDataUrl }}" alt="QRIS {{ $paymentSimulation->reference }}" class="h-64 w-64 rounded-kivu-sm border border-kivu-border bg-white object-contain p-2" />
                    @else
                        <div class="flex h-64 w-64 items-center justify-center rounded-kivu-sm border border-kivu-border bg-kivu-surface-muted text-sm text-kivu-text-muted">
                            Memuat QRIS…
                        </div>
                    @endif
                    <p class="mt-3 font-mono text-xs font-semibold tracking-wider text-kivu-text">{{ $paymentSimulation->reference }}</p>
                    <p class="mt-1 text-sm font-bold text-kivu-primary">Rp {{ number_format($paymentSimulation->amount, 0, ',', '.') }}</p>
                </div>

                <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end sm:gap-3">
                    <button type="button" wire:click="closeFunding" class="kivu-focus inline-flex h-10 items-center justify-center rounded-kivu-sm border border-kivu-border bg-kivu-surface px-4 text-sm font-semibold text-kivu-text transition hover:bg-kivu-surface-muted">
                        Batal
                    </button>
                    <button type="button" wire:click="simulateFundingPaid" wire:loading.attr="disabled" class="kivu-focus inline-flex h-10 items-center justify-center gap-2 rounded-kivu-sm bg-kivu-success px-4 text-sm font-semibold text-white transition hover:brightness-95">
                        Saya Sudah Membayar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
