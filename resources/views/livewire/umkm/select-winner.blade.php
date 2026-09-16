<div class="space-y-6">
    <x-ui.page-header :breadcrumbs="[
        ['label' => 'Proyek Saya', 'url' => route('umkm.my-projects')],
        ['label' => 'Pilih Pemenang'],
    ]" :back="route('umkm.my-projects')" back-label="Kembali ke Proyek Saya" :title="$project->title"
        subtitle="Tinjau semua hasil kerja yang masuk lalu pilih satu pemenang untuk dibayar.">
        <x-slot:actions>
            <x-ui.status-badge :value="$project->status" kind="project" :dot="true" />
        </x-slot:actions>
    </x-ui.page-header>

    <div class="flex gap-2.5 rounded-kivu border border-kivu-primary/25 bg-kivu-primary-soft p-4">
        <x-icon name="info" :size="18" class="mt-0.5 shrink-0 text-kivu-primary" />
        <p class="text-sm leading-relaxed text-kivu-text-secondary">
            Pilih satu mahasiswa sebagai pemenang. Lamaran lainnya otomatis ditolak. Setelah memilih, kamu bisa meminta revisi atau langsung merilis dana untuk pemenang.
        </p>
    </div>

    @forelse ($submissions as $submission)
        @php $r = $ratings->get($submission->student_id); @endphp
        <div class="kivu-card flex flex-col gap-4 p-5 sm:flex-row sm:items-start sm:justify-between">
            <div class="flex min-w-0 items-start gap-4">
                <x-ui.avatar :name="$submission->student->name" />

                <div class="min-w-0 space-y-1.5">
                    <div class="flex flex-wrap items-center gap-2">
                        <p class="font-semibold text-kivu-text">{{ $submission->student->name }}</p>
                        <x-ui.verified-badge :user="$submission->student" compact />
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        @if ($r && $r->total > 0)
                            <x-ui.rating-stars :value="$r->avg_rating" :count="$r->total" :size="14" show-value />
                        @else
                            <span class="text-[11px] text-kivu-text-muted">Belum ada ulasan</span>
                        @endif
                        <span class="text-[11px] text-kivu-text-muted">&middot; {{ $submission->created_at->diffForHumans() }}</span>
                        <x-ui.status-badge :value="$submission->status" kind="submission" />
                    </div>

                    @if ($bids->get($submission->student_id))
                        <p class="text-xs text-kivu-text-secondary">Penawaran: <span class="font-semibold text-kivu-text">Rp {{ number_format($bids->get($submission->student_id), 0, ',', '.') }}</span></p>
                    @endif

                    <div class="space-y-1.5 pt-1 text-sm">
                        @if ($submission->file_path)
                            <a href="{{ route('submission-file', $submission) }}" target="_blank" rel="noopener"
                                class="kivu-focus inline-flex items-center gap-1 rounded font-medium text-kivu-primary hover:underline">
                                <x-icon name="file-text" :size="14" /> {{ basename($submission->file_path) }}
                            </a>
                        @endif
                        @if ($submission->link)
                            <a href="{{ $submission->link }}" target="_blank" rel="noopener"
                                class="kivu-focus inline-flex items-center gap-1 rounded font-medium text-kivu-primary hover:underline">
                                <x-icon name="external-link" :size="14" /> Lihat tautan
                            </a>
                        @endif
                        @if ($submission->note)
                            <p class="rounded-kivu-sm bg-kivu-surface-muted px-3 py-2 text-xs italic text-kivu-text-secondary">"{{ $submission->note }}"</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex shrink-0 flex-wrap gap-2">
                <x-ui.button wire:click="confirmSelect({{ $submission->id }})" size="sm" :disabled="$project->winnerApp() !== null"
                    :title="$project->winnerApp() !== null ? 'Pemenang sudah dipilih' : null">
                    <x-icon name="trophy" :size="15" /> Pilih Menang
                </x-ui.button>
            </div>
        </div>
    @empty
        <x-ui.empty-state icon="file-text" title="Belum ada hasil masuk"
            description="Belum ada mahasiswa yang mengirim hasil kerja untuk proyek ini." />
    @endforelse

    @if ($showModal)
        <x-ui.confirm-modal
            title="Konfirmasi Pilih Pemenang"
            :message="'Pilih mahasiswa ini sebagai pemenang? Lamaran lainnya akan otomatis ditolak dan proyek menunggu rilis dana.'"
            confirm-label="Ya, Pilih"
            confirm-method="selectWinner"
            cancel-method="cancelSelect"
            tone="primary"
            loading-target="selectWinner" />
    @endif
</div>