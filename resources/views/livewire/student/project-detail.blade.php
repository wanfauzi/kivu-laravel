<div class="mx-auto max-w-3xl space-y-6">
    <a href="{{ route('student.opportunities') }}" wire:navigate class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 transition hover:text-blue-600">
        <x-icon name="arrow-left" :size="16" /> Kembali ke Peluang
    </a>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
        <div class="flex flex-col gap-3 border-b border-gray-100 p-6 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <x-ui.avatar :name="$project->owner->name" size="lg" />
                <div>
                    <p class="text-sm font-semibold text-gray-900">oleh {{ $project->owner->name }}</p>
                    <p class="text-xs text-gray-500">Mitra UMKM KIVU</p>
                </div>
            </div>
            <x-ui.status-badge :value="$project->status" kind="project" />
        </div>

        <div class="space-y-4 p-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ $project->title }}</h1>
            <p class="text-sm leading-relaxed text-gray-600">{{ $project->description }}</p>

            <div class="flex flex-wrap gap-6 pt-4">
                <div>
                    <p class="text-xs text-gray-500">Budget</p>
                    <p class="text-xl font-bold text-blue-600">Rp {{ number_format($project->budget, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Dibuka</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $project->created_at->translatedFormat('d F Y') }}</p>
                </div>
            </div>

            <div class="pt-2">
                @if($hasApplied)
                    <div class="rounded-xl border border-success-200 bg-success-50 px-4 py-3 text-sm font-medium text-success-700">
                        <x-icon name="circle-check" :size="16" class="mr-1 inline" /> Anda sudah melamar proyek ini.
                    </div>
                @elseif($project->status === 'OPEN')
                    <button wire:click="openModal" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 sm:w-auto">
                        <x-icon name="file-text" :size="18" /> Lamar Sekarang
                    </button>
                @else
                    <p class="text-sm text-gray-500">Proyek ini sudah tidak tersedia untuk dilamar.</p>
                @endif
            </div>
        </div>
    </div>

    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" wire:click.self="closeModal">
            <div class="w-full max-w-md rounded-2xl border border-gray-200 bg-white p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Konfirmasi Lamaran</h3>
                    <button wire:click="closeModal" type="button" class="rounded-lg p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600" aria-label="Tutup">
                        <x-icon name="x" :size="20" />
                    </button>
                </div>
                <p class="mt-2 text-sm text-gray-500">Anda akan melamar proyek <span class="font-semibold text-gray-800">"{{ $project->title }}"</span>.</p>
                <div class="mt-4">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Pesan untuk UMKM (opsional)</label>
                    <textarea wire:model="message" rows="3" placeholder="Ceritakan singkat pengalaman atau ketertarikan Anda..." class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"></textarea>
                </div>
                <div class="mt-5 flex justify-end gap-3">
                    <button wire:click="closeModal" type="button" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">Batal</button>
                    <button wire:click="apply" class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700"
                            wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="apply">Kirim Lamaran</span>
                        <span wire:loading wire:target="apply">Mengirim...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>