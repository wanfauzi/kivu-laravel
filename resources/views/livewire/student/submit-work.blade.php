<div class="mx-auto max-w-2xl space-y-6">
    <a href="{{ route('student.my-applications') }}" wire:navigate class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 transition hover:text-blue-600">
        <x-icon name="arrow-left" :size="16" /> Kembali
    </a>

    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Kirim Hasil Kerja</h1>
        <p class="mt-1 text-sm text-gray-500">{{ $project->title }}</p>
    </div>

    @if($existingSubmission && $existingSubmission->status !== 'REVISION')
        <div class="rounded-2xl border border-success-200 bg-success-50 p-5">
            <p class="flex items-center gap-2 text-sm font-semibold text-success-700">
                <x-icon name="circle-check" :size="18" /> Hasil sudah dikirim
            </p>
            <div class="mt-2 space-y-1 text-xs text-success-700">
                <p>Status: <x-ui.status-badge :value="$existingSubmission->status" kind="submission" /></p>
                @if($existingSubmission->file_path)<p>File: {{ $existingSubmission->file_path }}</p>@endif
                @if($existingSubmission->link)<p>Link: {{ $existingSubmission->link }}</p>@endif
                @if($existingSubmission->note)<p>Catatan: {{ $existingSubmission->note }}</p>@endif
            </div>
        </div>
    @elseif($isAccepted)
        @if($existingSubmission?->status === 'REVISION')
            <div class="rounded-2xl border border-warning-200 bg-warning-50 p-4">
                <p class="flex items-center gap-2 text-sm font-semibold text-warning-700">
                    <x-icon name="alert-circle" :size="18" /> UMKM meminta revisi
                </p>
                <p class="mt-1 text-sm text-warning-700">{{ $existingSubmission->revision_note }}</p>
            </div>
        @endif
        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <div class="space-y-5">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Lokasi / Path File</label>
                    <input type="text" wire:model="file_path" placeholder="contoh: files/hasil.zip" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                    @error('file_path') <p class="mt-1 text-xs text-error-600">{{ $message }}</p> @enderror
                    <p class="mt-1 text-xs text-gray-400">Isi path hasil di server, atau gunakan kolom link di bawah.</p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Link Hasil</label>
                    <input type="url" wire:model="link" placeholder="https://..." class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                    @error('link') <p class="mt-1 text-xs text-error-600">{{ $message }}</p> @enderror
                    <p class="mt-1 text-xs text-gray-400">Link GitHub, Google Drive, atau tempat hasil Anda.</p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Catatan untuk UMKM</label>
                    <textarea wire:model="note" rows="3" placeholder="Jelaskan apa yang Anda kerjakan..." class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"></textarea>
                    @error('note') <p class="mt-1 text-xs text-error-600">{{ $message }}</p> @enderror
                </div>

                <button wire:click="confirmSubmit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 sm:w-auto"
                        wire:loading.attr="disabled" wire:target="confirmSubmit">
                    <span wire:loading.remove wire:target="confirmSubmit"><x-icon name="upload" :size="18" /> Kirim Hasil</span>
                    <span wire:loading wire:target="confirmSubmit">Memeriksa...</span>
                </button>
            </div>
        </div>
    @endif

    @if($confirming)
        <x-ui.confirm-modal
            title="Konfirmasi Kirim Hasil"
            message="Hasil kerja akan dikirim ke UMKM dan status menjadi terkunci (tidak dapat diubah). Lanjutkan?"
            confirm-label="Kirim Sekarang"
            confirm-method="submit"
            cancel-method="cancelSubmit"
            tone="brand"
            loading-target="submit"
        />
    @endif
</div>