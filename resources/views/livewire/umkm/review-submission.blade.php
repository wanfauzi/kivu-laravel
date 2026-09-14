<div class="mx-auto max-w-3xl space-y-6">
    <a href="{{ route('umkm.my-projects') }}" wire:navigate class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 transition hover:text-blue-600">
        <x-icon name="arrow-left" :size="16" /> Kembali ke Proyek Saya
    </a>

    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Review Hasil Kerja</h1>
        <p class="mt-1 text-sm text-gray-500">{{ $project->title }}</p>
    </div>

    @if(!$project->submission)
        <x-ui.empty-state icon="upload" title="Hasil belum dikirim" description="Mahasiswa belum mengirim hasil untuk proyek ini. Silakan tunggu." />
    @else
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <div class="flex flex-col gap-3 border-b border-gray-100 p-6 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <x-ui.avatar :name="$project->submission->student->name" />
                    <div>
                        <p class="flex items-center gap-2 text-sm font-semibold text-gray-900">{{ $project->submission->student->name }} <x-ui.verified-badge :user="$project->submission->student" /></p>
                        <p class="text-xs text-gray-500">{{ $project->submission->created_at->translatedFormat('d F Y · H:i') }}</p>
                    </div>
                </div>
                <x-ui.status-badge :value="$project->submission->status" kind="submission" />
            </div>

            <div class="space-y-4 p-6">
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs text-gray-500">Budget Proyek</dt>
                        <dd class="text-lg font-bold text-blue-600">Rp {{ number_format($project->budget, 0, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">File</dt>
                        <dd class="text-sm font-medium text-gray-800">{{ $project->submission->file_path ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Link Hasil</dt>
                        <dd class="text-sm break-all text-blue-600">
                            @if($project->submission->link)
                                <a href="{{ $project->submission->link }}" target="_blank" rel="noopener" class="hover:underline">{{ $project->submission->link }}</a>
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs text-gray-500">Catatan</dt>
                        <dd class="text-sm text-gray-700">{{ $project->submission->note ?? '-' }}</dd>
                    </div>
                </dl>

                @if($project->submission->status !== 'APPROVED')
                    <div class="border-t border-gray-100 pt-5 space-y-3">
                        <div class="flex flex-wrap gap-3">
                            <button wire:click="confirmApprove" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 sm:w-auto">
                                <x-icon name="circle-check" :size="18" /> Setujui & Bayar
                            </button>
                            @if($project->submission->status === 'SUBMITTED')
                                <button wire:click="openRevision" class="inline-flex items-center justify-center gap-2 rounded-xl border border-amber-300 bg-white px-5 py-3 text-sm font-semibold text-amber-700 transition hover:bg-amber-50 sm:w-auto">
                                    <x-icon name="alert-circle" :size="18" /> Minta Revisi
                                </button>
                            @endif
                        </div>
                        <p class="text-xs text-gray-400">Menyetujui akan menandai proyek selesai dan mentransfer budget ke wallet mahasiswa.</p>
                    </div>
                    @if($showRevisionModal)
                        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" wire:click.self="closeRevision">
                            <div class="w-full max-w-md rounded-2xl border border-gray-200 bg-white p-6 shadow-xl">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-bold text-gray-900">Minta Revisi</h3>
                                    <button wire:click="closeRevision" type="button" class="rounded-lg p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600" aria-label="Tutup">
                                        <x-icon name="x" :size="20" />
                                    </button>
                                </div>
                                <div class="mt-4">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Catatan Revisi</label>
                                    <textarea wire:model="revision_note" rows="3" placeholder="Apa yang perlu diperbaiki?" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"></textarea>
                                    @error('revision_note') <p class="mt-1 text-xs text-error-600">{{ $message }}</p> @enderror
                                </div>
                                <div class="mt-5 flex justify-end gap-3">
                                    <button wire:click="closeRevision" type="button" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">Batal</button>
                                    <button wire:click="requestRevision" class="rounded-lg bg-amber-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-700" wire:loading.attr="disabled">Kirim Permintaan Revisi</button>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="rounded-xl border border-success-200 bg-success-50 px-4 py-3 text-sm font-medium text-success-700">
                        <x-icon name="circle-check" :size="16" class="mr-1 inline" /> Hasil disetujui dan dana telah diteruskan ke mahasiswa.
                    </div>

                    @if($project->status === 'COMPLETED')
                        <div class="border-t border-gray-100 pt-5">
                            <h3 class="text-base font-semibold text-gray-900">Beri Ulasan untuk Mahasiswa</h3>
                            @if($hasReviewed)
                                <div class="mt-3 rounded-lg bg-gray-50 p-4 text-sm font-medium text-gray-600">Terima kasih, ulasan Anda sudah terkirim.</div>
                            @else
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Rating (1–5)</label>
                                        <div class="flex gap-2">
                                            @foreach(range(1, 5) as $i)
                                                <button wire:click="$set('rating', {{ $i }})" type="button" class="flex h-11 w-11 items-center justify-center rounded-lg border transition {{ $rating >= $i ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300 bg-white text-gray-400 hover:border-blue-400' }}">
                                                    <x-icon name="star" :size="22" :filled="$rating >= $i" />
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Komentar</label>
                                        <textarea wire:model="comment" rows="3" placeholder="Bagaimana hasil kerjanya?" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"></textarea>
                                        @error('comment') <p class="mt-1 text-xs text-error-600">{{ $message }}</p> @enderror
                                    </div>
                                    <button wire:click="submitReview" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700" wire:loading.attr="disabled">
                                        <x-icon name="send" :size="16" /> Kirim Ulasan
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endif
                @endif
            </div>
        </div>
    @endif

    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" wire:click.self="cancelApprove">
            <div class="w-full max-w-md rounded-2xl border border-gray-200 bg-white p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Konfirmasi Pembayaran</h3>
                    <button wire:click="cancelApprove" type="button" class="rounded-lg p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600" aria-label="Tutup">
                        <x-icon name="x" :size="20" />
                    </button>
                </div>
                <p class="mt-2 text-sm text-gray-600">
                    Setujui hasil kerja dan bayar <span class="font-bold text-gray-900">Rp {{ number_format($project->budget, 0, ',', '.') }}</span> ke wallet mahasiswa?<br>
                    Proyek akan ditandai selesai dan dana tidak dapat dibatalkan.
                </p>
                <div class="mt-5 flex justify-end gap-3">
                    <button wire:click="cancelApprove" type="button" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">Batal</button>
                    <button wire:click="approveAndPay" class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="approveAndPay">Ya, Setujui & Bayar</span>
                        <span wire:loading wire:target="approveAndPay">Memproses...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>