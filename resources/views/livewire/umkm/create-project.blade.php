<div class="mx-auto max-w-xl space-y-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Buat Proyek Baru</h1>
        <p class="mt-1 text-sm text-gray-500">Luncurkan proyek untuk diambil mahasiswa. Budget minimal Rp 100.000.</p>
    </div>

    <form wire:submit="store" class="space-y-5 rounded-2xl border border-gray-200 bg-white p-6">
        <div>
            <label for="title" class="mb-1.5 block text-sm font-medium text-gray-700">Judul Proyek <span class="text-error-500">*</span></label>
            <input id="title" type="text" wire:model="title" placeholder="Contoh: Desain logo dan branding UMKM" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
            @error('title') <p class="mt-1 text-xs text-error-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="description" class="mb-1.5 block text-sm font-medium text-gray-700">Deskripsi <span class="text-error-500">*</span></label>
            <textarea id="description" wire:model="description" rows="5" placeholder="Jelaskan kebutuhan, ruang lingkup, dan hasil yang diharapkan..." class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"></textarea>
            @error('description') <p class="mt-1 text-xs text-error-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="budget" class="mb-1.5 block text-sm font-medium text-gray-700">Budget (Rp) <span class="text-error-500">*</span></label>
            <input id="budget" type="number" wire:model="budget" min="100000" step="5000" placeholder="Contoh: 500000" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
            @error('budget') <p class="mt-1 text-xs text-error-600">{{ $message }}</p> @enderror
            <div class="mt-2 flex flex-wrap gap-2">
                @foreach([250000, 500000, 1000000, 2500000] as $suggested)
                    <button type="button" wire:click="$set('budget', {{ $suggested }})" class="rounded-full border border-gray-300 bg-white px-3 py-1 text-xs font-medium text-gray-600 transition hover:border-blue-500 hover:text-blue-600">Rp {{ number_format($suggested, 0, ',', '.') }}</button>
                @endforeach
            </div>
        </div>

        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="store"><x-icon name="plus-circle" :size="18" /> Buat Proyek</span>
            <span wire:loading wire:target="store">Menyimpan...</span>
        </button>
    </form>
</div>