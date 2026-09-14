<div class="space-y-6">
    <div>
        <h1 class="text-title-sm font-bold text-gray-800">Sengketa (Disputes)</h1>
        <p class="mt-1 text-sm text-gray-500">Tinjau sengketa yang diajukan mahasiswa atau UMKM, lalu putuskan refund atau release.</p>
    </div>

    <div class="space-y-4">
        @forelse($disputes as $d)
            @php
                $sv = match($d->status) { 'OPEN' => 'warning', 'RESOLVED' => 'success', default => 'error' };
                $sl = match($d->status) { 'OPEN' => 'Menunggu', 'RESOLVED' => 'Diputuskan', default => 'Ditolak' };
            @endphp
            <div class="rounded-2xl border border-gray-200 bg-white p-5">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div class="min-w-0 space-y-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="text-base font-semibold text-gray-800">{{ $d->project->title ?? 'Proyek dihapus' }}</h3>
                            <x-ui.pill :tone="$sv">{{ $sl }}</x-ui.pill>
                            @if($d->resolution)
                                <span class="inline-block rounded-full bg-gray-100 px-2.5 py-0.5 text-theme-xs font-medium text-gray-600 capitalize">{{ $d->resolution }}</span>
                            @endif
                        </div>
                        <p class="text-theme-sm font-medium text-gray-700">Alasan: {{ $d->reasonLabel() }}</p>
                        <p class="text-theme-sm text-gray-500">{{ $d->description }}</p>
                        <p class="text-theme-xs text-gray-400">
                            Pelapor: <span class="font-medium text-gray-600">{{ $d->reporter->name ?? '-' }}</span>
                            &middot; Terlapor: <span class="font-medium text-gray-600">{{ $d->against->name ?? '-' }}</span>
                            &middot; {{ $d->created_at->translatedFormat('d F Y · H:i') }}
                        </p>
                        @if($d->resolved_at)
                            <p class="text-theme-xs text-gray-400">Diputuskan oleh {{ $d->resolver->name ?? '-' }} pada {{ $d->resolved_at->translatedFormat('d F Y · H:i') }}</p>
                        @endif
                    </div>

                    @if($d->status === 'OPEN')
                        <div class="flex shrink-0 flex-wrap gap-2">
                            <button wire:click="confirmAction({{ $d->id }}, 'refund')" class="inline-flex items-center gap-1.5 rounded-lg border border-warning-300 px-3 py-1.5 text-theme-xs font-semibold text-warning-700 transition hover:bg-warning-50">
                                <x-icon name="rotate-ccw" :size="14" /> Refund
                            </button>
                            <button wire:click="confirmAction({{ $d->id }}, 'release')" class="inline-flex items-center gap-1.5 rounded-lg bg-success-500 px-3 py-1.5 text-theme-xs font-semibold text-white transition hover:bg-success-600">
                                <x-icon name="check" :size="14" /> Release
                            </button>
                            <button wire:click="confirmAction({{ $d->id }}, 'reject')" class="inline-flex items-center gap-1.5 rounded-lg border border-error-200 px-3 py-1.5 text-theme-xs font-medium text-error-600 transition hover:bg-error-50">
                                <x-icon name="x" :size="14" /> Tolak
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-6 py-12 text-center sm:px-12">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                    <x-icon name="shield-check" :size="32" />
                </div>
                <h3 class="mt-4 text-lg font-semibold text-gray-800">Tidak Ada Sengketa</h3>
                <p class="mx-auto mt-2 max-w-sm text-sm text-gray-500">Belum ada sengketa yang diajukan. Sengketa dari mahasiswa atau UMKM akan tampil di sini.</p>
            </div>
        @endforelse
    </div>

    @if($confirming)
        @php
            $isRefund = $actionType === 'refund';
            $isRelease = $actionType === 'release';
            $msg = $isRefund
                ? 'Sengketa diputuskan dengan REFUND: dana dikembalikan ke UMKM (bila sudah dibayar), submission dihapus, lamaran kembali menunggu, proyek dibuka kembali ke OPEN.'
                : ($isRelease
                    ? 'Sengketa diputuskan dengan RELEASE: submission disetujui dan dana dibayarkan ke mahasiswa, proyek selesai.'
                    : 'Sengketa akan ditolak tanpa mengubah kondisi proyek.');
            $lbl = $isRefund ? 'Refund' : ($isRelease ? 'Release' : 'Tolak');
            $tone = $isRefund ? 'danger' : ($isRelease ? 'success' : 'danger');
        @endphp
        <x-ui.confirm-modal
            title="Konfirmasi {{ $lbl }}"
            :message="$msg"
            :confirm-label="$lbl"
            confirm-method="runAction"
            cancel-method="cancelAction"
            :tone="$tone"
            loading-target="runAction"
        />
    @endif
</div>
