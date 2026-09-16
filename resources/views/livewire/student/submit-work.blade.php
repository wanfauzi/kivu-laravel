@php
    $submission = $existingSubmission;
    $isRevision = $submission?->status === 'REVISION';
@endphp

<div class="mx-auto max-w-2xl space-y-6">
    <x-ui.page-header :breadcrumbs="[
        ['label' => 'Lamaran', 'url' => route('student.my-applications')],
        ['label' => 'Kirim Hasil'],
    ]" :back="route('student.my-applications')" back-label="Kembali ke Lamaran" title="Kirim Hasil Kerja"
        :subtitle="$project->title" />

    @if ($submission && ! $isRevision)
        <div class="kivu-card p-6">
            <div class="flex items-center gap-2">
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-kivu-success-soft text-kivu-success">
                    <x-icon name="circle-check" :size="18" />
                </span>
                <p class="text-base font-semibold text-kivu-text">Hasil sudah dikirim</p>
                <x-ui.status-badge :value="$submission->status" kind="submission" class="ml-auto" />
            </div>

            <dl class="mt-5 space-y-2 text-sm">
                @if ($submission->file_path)
                    <div class="flex items-center justify-between gap-3 border-b border-kivu-border pb-2">
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
                    <div class="flex items-center justify-between gap-3 border-b border-kivu-border pb-2">
                        <dt class="text-kivu-text-muted">Tautan</dt>
                        <dd class="truncate">
                            <a href="{{ $submission->link }}" target="_blank" rel="noopener"
                                class="kivu-focus inline-flex items-center gap-1 rounded font-medium text-kivu-primary hover:underline">
                                <x-icon name="external-link" :size="14" /> Lihat tautan
                            </a>
                        </dd>
                    </div>
                @endif
                @if ($submission->note)
                    <div class="pt-1">
                        <dt class="text-kivu-text-muted">Catatan</dt>
                        <dd class="mt-1 text-kivu-text-secondary">{{ $submission->note }}</dd>
                    </div>
                @endif
            </dl>
        </div>
    @elseif ($canSubmit)
        @if ($isRevision)
            <div class="flex gap-2.5 rounded-kivu border border-kivu-warning/25 bg-kivu-warning-soft p-4">
                <x-icon name="alert-circle" :size="18" class="mt-0.5 shrink-0 text-kivu-warning" />
                <div>
                    <p class="text-sm font-semibold text-kivu-warning">UMKM meminta revisi</p>
                    <p class="mt-1 text-sm leading-relaxed text-kivu-warning">{{ $submission->revision_note }}</p>
                </div>
            </div>
        @endif

        <div class="kivu-card p-6">
            <form wire:submit="confirmSubmit" class="space-y-5">
                <div>
                    <label for="submission-file" class="mb-1.5 block text-sm font-medium text-kivu-text">Unggah File Hasil</label>
                    <input id="submission-file" type="file" wire:model="file"
                        class="kivu-input file:mr-3 file:rounded-kivu-sm file:border-0 file:bg-kivu-primary-soft file:px-3 file:py-2 file:text-sm file:font-semibold file:text-kivu-primary px-3 py-2 text-sm" />
                    @error('file')
                        <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-kivu-text-muted">Maks 10 MB — jpg, png, pdf, zip, mp4, mov, doc, ppt.</p>

                    <div wire:loading wire:target="file" class="mt-2 flex items-center gap-2 text-xs text-kivu-text-muted">
                        <x-icon name="rotate-ccw" :size="14" class="animate-spin" /> Mengunggah...
                    </div>
                </div>

                <div>
                    <label for="submission-link" class="mb-1.5 block text-sm font-medium text-kivu-text">Atau Tautan Hasil</label>
                    <input id="submission-link" type="url" wire:model="link" placeholder="https://..."
                        class="kivu-input px-3.5 py-2.5 text-sm" />
                    @error('link')
                        <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-kivu-text-muted">Link GitHub, Google Drive, Figma, atau tempat hasil Anda.</p>
                </div>

                <div>
                    <label for="submission-note" class="mb-1.5 block text-sm font-medium text-kivu-text">Catatan untuk UMKM</label>
                    <textarea id="submission-note" wire:model="note" rows="3" placeholder="Jelaskan apa yang Anda kerjakan..."
                        class="kivu-input px-3.5 py-2.5 text-sm"></textarea>
                    @error('note')
                        <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                    @enderror
                </div>

                <x-ui.button type="submit" size="lg" loading-target="confirmSubmit" class="w-full sm:w-auto">
                    <x-icon name="upload" :size="18" /> {{ $isRevision ? 'Kirim Revisi' : 'Kirim Hasil' }}
                </x-ui.button>
            </form>
        </div>
    @else
        <x-ui.empty-state icon="file-text" title="Belum bisa mengirim hasil"
            description="Pastikan kamu sudah melamar, dan proyek masih terbuka untuk menerima hasil.">
            <x-ui.button href="{{ route('student.my-applications') }}" variant="secondary" size="sm">
                Lihat Lamaran Saya
            </x-ui.button>
        </x-ui.empty-state>
    @endif

    @if ($confirming)
        <x-ui.confirm-modal
            title="Konfirmasi Kirim Hasil"
            message="Hasil kerja akan dikirim ke UMKM dan status proyek menjadi menunggu review. Lanjutkan?"
            confirm-label="Kirim Sekarang"
            confirm-method="submit"
            cancel-method="cancelSubmit"
            tone="primary"
            loading-target="submit" />
    @endif
</div>
