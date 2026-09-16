<div class="space-y-6">
    <x-ui.page-header title="Sengketa" subtitle="Tinjau sengketa lalu putuskan refund atau release." />

    <div class="space-y-4">
        @forelse ($disputes as $d)
            <div class="kivu-card p-5">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div class="min-w-0 space-y-1.5">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="text-base font-semibold text-kivu-text">{{ $d->project->title ?? 'Proyek dihapus' }}</h3>
                            <x-ui.status-badge :value="$d->status" kind="dispute" />
                            @if ($d->resolution)
                                <x-ui.pill :tone="$d->resolution === 'refund' ? 'warning' : 'info'" class="capitalize">
                                    {{ $d->resolution === 'refund' ? 'Refund' : 'Release' }}
                                </x-ui.pill>
                            @endif
                        </div>

                        <p class="text-sm font-medium text-kivu-text-secondary">Alasan: {{ $d->reasonLabel() }}</p>
                        <p class="text-sm leading-relaxed text-kivu-text-secondary">{{ $d->description }}</p>

                        <p class="text-xs text-kivu-text-muted">
                            Pelapor: <span class="font-medium text-kivu-text">{{ $d->reporter->name ?? '-' }}</span>
                            &middot; Terlapor: <span class="font-medium text-kivu-text">{{ $d->against->name ?? '-' }}</span>
                            &middot; {{ $d->created_at->translatedFormat('d F Y · H:i') }}
                        </p>

                        @if ($d->resolved_at)
                            <p class="text-xs text-kivu-text-muted">
                                Diputuskan oleh {{ $d->resolver->name ?? '-' }} pada {{ $d->resolved_at->translatedFormat('d F Y · H:i') }}
                            </p>
                        @endif
                    </div>

                    @if ($d->status === 'OPEN')
                        <div class="flex shrink-0 flex-wrap gap-2">
                            <x-ui.button wire:click="confirmAction({{ $d->id }}, 'refund')" variant="secondary" size="sm" class="text-kivu-warning">
                                <x-icon name="rotate-ccw" :size="14" /> Refund
                            </x-ui.button>
                            <x-ui.button wire:click="confirmAction({{ $d->id }}, 'release')" variant="success" size="sm">
                                <x-icon name="check" :size="14" /> Release
                            </x-ui.button>
                            <x-ui.button wire:click="confirmAction({{ $d->id }}, 'reject')" variant="secondary" size="sm" class="text-kivu-danger">
                                <x-icon name="x" :size="14" /> Tolak
                            </x-ui.button>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <x-ui.empty-state icon="shield-check" title="Tidak Ada Sengketa"
                description="Belum ada sengketa yang diajukan. Sengketa dari mahasiswa atau UMKM akan tampil di sini." />
        @endforelse
    </div>

    @if ($confirming)
        @php
            $isRefund = $actionType === 'refund';
            $isRelease = $actionType === 'release';
            $msg = $isRefund
                ? 'Sengketa diputuskan dengan REFUND: dana dikembalikan ke UMKM (bila sudah dibayar), submission dihapus, lamaran kembali menunggu, proyek dibuka kembali ke OPEN.'
                : ($isRelease
                    ? 'Sengketa diputuskan dengan RELEASE: submission disetujui dan dana dibayarkan ke mahasiswa, proyek selesai.'
                    : 'Sengketa akan ditolak tanpa mengubah kondisi proyek.');
            $lbl = $isRefund ? 'Refund' : ($isRelease ? 'Release' : 'Tolak');
            $tone = $isRelease ? 'success' : 'danger';
        @endphp
        <x-ui.confirm-modal
            title="Konfirmasi {{ $lbl }}"
            :message="$msg"
            :confirm-label="$lbl"
            confirm-method="runAction"
            cancel-method="cancelAction"
            :tone="$tone"
            loading-target="runAction" />
    @endif
</div>