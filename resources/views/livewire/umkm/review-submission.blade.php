@php
    $submission = $submission ?? null;

    if ($submission) {
        if ($submission->status === 'APPROVED') {
            $steps = [
                ['label' => 'Hasil dikirim', 'description' => 'Mahasiswa mengirim hasil kerja', 'state' => 'done'],
                ['label' => 'Dana escrow dilepas', 'description' => 'Escrow diteruskan ke dompet mahasiswa', 'state' => 'done'],
                ['label' => 'Proyek selesai', 'description' => 'Ulasan dapat diberikan', 'state' => 'done'],
            ];
        } elseif ($submission->status === 'REVISION') {
            $steps = [
                ['label' => 'Hasil dikirim', 'description' => 'Mahasiswa mengirim hasil kerja', 'state' => 'done'],
                ['label' => 'Perlu revisi', 'description' => $submission->revision_note, 'state' => 'warning'],
                ['label' => 'Menunggu kirim ulang', 'description' => 'Mahasiswa memperbaiki hasil', 'state' => 'current'],
            ];
        } else {
            $steps = [
                ['label' => 'Hasil dikirim', 'description' => 'Mahasiswa mengirim hasil kerja', 'state' => 'done'],
                ['label' => 'Menunggu review Anda', 'description' => 'Setujui atau minta revisi', 'state' => 'current'],
                ['label' => 'Pembayaran escrow', 'description' => 'Dana escrow diteruskan ke mahasiswa', 'state' => 'upcoming'],
            ];
        }
    }
@endphp

<div class="mx-auto max-w-3xl space-y-6">
    <x-ui.page-header :breadcrumbs="[
        ['label' => 'Proyek Saya', 'url' => route('umkm.my-projects')],
        ['label' => 'Review Hasil'],
    ]" :back="route('umkm.my-projects')" back-label="Kembali ke Proyek Saya" title="Review Hasil Kerja"
        :subtitle="$project->title">
        <x-slot:actions>
            <div class="flex items-center gap-2">
                <x-ui.button href="{{ route('umkm.inbox', ['project' => $project->id]) }}" variant="secondary" size="sm">
                    <x-icon name="message-square" :size="15" /> Pesan
                </x-ui.button>
                <x-ui.status-badge :value="$project->status" kind="project" :dot="true" />
            </div>
        </x-slot:actions>
    </x-ui.page-header>

    @if (! $submission)
        @if ($winner)
            <div class="kivu-card p-6">
                <h2 class="text-sm font-semibold text-kivu-text-muted">Kandidat Terpilih</h2>
                <div class="mt-4 flex items-center gap-3">
                    <x-ui.avatar :name="$winner->student->name" />
                    <div class="min-w-0">
                        <p class="flex flex-wrap items-center gap-2 text-sm font-semibold text-kivu-text">
                            {{ $winner->student->name }}
                            <x-ui.verified-badge :user="$winner->student" />
                        </p>
                        <p class="text-xs text-kivu-text-muted">Lamaran dipilih — menunggu hasil kerja</p>
                    </div>
                    <x-ui.status-badge :value="$winner->status" kind="application" class="ml-auto" />
                </div>
                @if ($winner->file_path)
                    <div class="mt-4">
                        <a href="{{ route('application-file', $winner) }}" target="_blank" rel="noopener"
                            class="kivu-focus inline-flex items-center gap-1 rounded text-sm font-medium text-kivu-primary hover:underline">
                            <x-icon name="file-text" :size="14" /> {{ basename($winner->file_path) }}
                        </a>
                    </div>
                @endif
                @if ($winner->bid_amount)
                    <p class="mt-2 text-sm text-kivu-text-secondary">Penawaran: <span class="font-semibold text-kivu-text">Rp {{ number_format($winner->bid_amount, 0, ',', '.') }}</span></p>
                @endif
                @if ($winner->message)
                    <p class="mt-2 rounded-kivu-sm bg-kivu-surface-muted px-3 py-2 text-xs italic text-kivu-text-secondary">"{{ $winner->message }}"</p>
                @endif
            </div>
            <x-ui.empty-state icon="upload" title="Menunggu hasil kerja"
                description="Kandidat sudah dipilih. Mahasiswa akan mengirim hasil kerja lewat menu Kirim Hasil." />
        @else
            <x-ui.empty-state icon="upload" title="Hasil belum dikirim"
                description="Mahasiswa belum mengirim hasil untuk proyek ini. Silakan tunggu." />
        @endif
    @else
        <div class="kivu-card p-6">
            <h2 class="text-sm font-semibold text-kivu-text-muted">Status Review</h2>
            <div class="mt-4">
                <x-ui.timeline :steps="$steps" />
            </div>
        </div>

        <div class="kivu-card p-6">
            <div class="flex flex-col gap-3 border-b border-kivu-border pb-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <x-ui.avatar :name="$submission->student?->name ?? $winner?->student->name ?? 'Mahasiswa'" />
                    <div class="min-w-0">
                        <p class="flex flex-wrap items-center gap-2 text-sm font-semibold text-kivu-text">
                            {{ $submission->student?->name ?? $winner?->student->name ?? 'Mahasiswa' }}
                            <x-ui.verified-badge :user="$submission->student ?? $winner?->student" />
                        </p>
                        <p class="text-xs text-kivu-text-muted">{{ $submission->created_at->translatedFormat('d F Y · H:i') }}</p>
                    </div>
                </div>
                <x-ui.status-badge :value="$submission->status" kind="submission" />
            </div>

            <dl class="mt-5 space-y-3 text-sm">
                <div class="flex items-start justify-between gap-3">
                    <dt class="text-kivu-text-muted">Budget Proyek</dt>
                    <dd class="font-bold text-kivu-primary">Rp {{ number_format($project->budget, 0, ',', '.') }}</dd>
                </div>

                @if ($submission->file_path)
                    <div class="flex items-start justify-between gap-3">
                        <dt class="text-kivu-text-muted">File</dt>
                        <dd>
                            <a href="{{ route('submission-file', $submission) }}" target="_blank" rel="noopener"
                                class="kivu-focus inline-flex items-center gap-1 rounded font-medium text-kivu-primary hover:underline">
                                <x-icon name="file-text" :size="14" /> {{ basename($submission->file_path) }}
                            </a>
                        </dd>
                    </div>
                @endif

                @if ($submission->link)
                    <div class="flex items-start justify-between gap-3">
                        <dt class="text-kivu-text-muted">Tautan</dt>
                        <dd class="min-w-0 text-right">
                            <a href="{{ $submission->link }}" target="_blank" rel="noopener"
                                class="kivu-focus inline-flex items-center gap-1 rounded font-medium text-kivu-primary hover:underline">
                                <x-icon name="external-link" :size="14" /> Lihat tautan
                            </a>
                        </dd>
                    </div>
                @endif

                @if ($submission->note)
                    <div class="border-t border-kivu-border pt-3">
                        <dt class="text-kivu-text-muted">Catatan</dt>
                        <dd class="mt-1 leading-relaxed text-kivu-text-secondary">{{ $submission->note }}</dd>
                    </div>
                @endif
            </dl>

            @if ($submission->status !== 'APPROVED')
                <div class="mt-6 flex flex-col gap-3 border-t border-kivu-border pt-5 sm:flex-row">
                    <x-ui.button wire:click="confirmApprove" size="lg">
                        <x-icon name="circle-check" :size="18" /> Setujui & Rilis Dana
                    </x-ui.button>

                    @if ($submission->status === 'SUBMITTED')
                        <x-ui.button wire:click="openRevision" variant="secondary" size="lg">
                            <x-icon name="rotate-ccw" :size="18" /> Minta Revisi
                        </x-ui.button>
                    @endif
                </div>
                <p class="mt-3 text-xs text-kivu-text-muted">Menyetujui akan menandai proyek selesai dan melepaskan dana escrow yang telah terkunci ke dompet mahasiswa.</p>
            @else
                <div class="mt-6 flex items-center gap-2 rounded-kivu-sm border border-kivu-success/25 bg-kivu-success-soft px-4 py-3 text-sm font-medium text-kivu-success">
                    <x-icon name="circle-check" :size="16" class="shrink-0" /> Hasil disetujui dan dana telah diteruskan ke mahasiswa.
                </div>

                @if ($project->status === 'COMPLETED')
                    <div class="mt-6 border-t border-kivu-border pt-5">
                        <h3 class="text-base font-semibold text-kivu-text">Beri Ulasan untuk Mahasiswa</h3>

                        @if ($hasReviewed)
                            <div class="mt-3 rounded-kivu-sm bg-kivu-surface-muted p-4 text-sm font-medium text-kivu-text-secondary">
                                Terima kasih, ulasan Anda sudah terkirim.
                            </div>
                        @else
                            <div class="mt-4 space-y-4">
                                <div>
                                    <span class="mb-1.5 block text-sm font-medium text-kivu-text">Rating</span>
                                    <div class="flex gap-1.5">
                                        @foreach (range(1, 5) as $i)
                                            <button wire:click="$set('rating', {{ $i }})" type="button"
                                                class="kivu-focus flex h-10 w-10 items-center justify-center rounded-kivu-sm border transition {{ $rating >= $i ? 'border-kivu-warning bg-kivu-warning-soft text-kivu-warning' : 'border-kivu-border bg-kivu-surface text-kivu-text-muted hover:border-kivu-warning/50' }}"
                                                aria-label="Beri {{ $i }} bintang">
                                                <x-icon name="star" :size="20" :filled="$rating >= $i" />
                                            </button>
                                        @endforeach
                                    </div>
                                    @error('rating')
                                        <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="review-comment" class="mb-1.5 block text-sm font-medium text-kivu-text">Komentar</label>
                                    <textarea id="review-comment" wire:model="comment" rows="3" placeholder="Bagaimana hasil kerjanya?"
                                        class="kivu-input px-3 py-2.5 text-sm"></textarea>
                                    @error('comment')
                                        <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <x-ui.button wire:click="submitReview" loading-target="submitReview" loading-label="Mengirim...">
                                    <x-icon name="send" :size="16" /> Kirim Ulasan
                                </x-ui.button>
                            </div>
                        @endif
                    </div>
                @endif
            @endif
        </div>
    @endif

    @if ($showModal)
        <x-ui.confirm-modal
            title="Rilis Dana Escrow"
            :message="'Setujui hasil kerja dan lepaskan escrow Rp '.number_format($project->budget, 0, ',', '.').' ke dompet mahasiswa? Proyek akan ditandai selesai dan dana tidak dapat dibatalkan.'"
            confirm-label="Ya, Setujui & Rilis Dana"
            confirm-method="releaseEscrow"
            cancel-method="cancelApprove"
            tone="primary"
            loading-target="releaseEscrow" />
    @endif

    @if ($showRevisionModal)
        <x-ui.confirm-modal
            title="Minta Revisi"
            confirm-label="Kirim Permintaan Revisi"
            confirm-method="requestRevision"
            cancel-method="closeRevision"
            tone="primary"
            loading-target="requestRevision">
            <div>
                <label for="revision-note" class="mb-1.5 block text-sm font-medium text-kivu-text">Catatan Revisi</label>
                <textarea id="revision-note" wire:model="revision_note" rows="3" placeholder="Apa yang perlu diperbaiki?"
                    class="kivu-input px-3 py-2.5 text-sm"></textarea>
                @error('revision_note')
                    <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                @enderror
            </div>
        </x-ui.confirm-modal>
    @endif
</div>
