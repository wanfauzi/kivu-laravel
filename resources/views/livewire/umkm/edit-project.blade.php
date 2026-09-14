<div class="mx-auto max-w-2xl space-y-6">
    <a href="{{ route('umkm.my-projects') }}" wire:navigate class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 transition hover:text-blue-600">
        <x-icon name="arrow-left" :size="16" /> Kembali ke Proyek Saya
    </a>

    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Edit Proyek</h1>
        <p class="mt-1 text-sm text-gray-500">Perbarui detail proyek selama masih berstatus terbuka.</p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <form wire:submit.prevent="update" class="space-y-5">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">Judul Proyek</label>
                <input type="text" wire:model="title" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                @error('title') <p class="mt-1 text-xs text-error-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea wire:model="description" rows="5" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"></textarea>
                @error('description') <p class="mt-1 text-xs text-error-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">Budget (Rp)</label>
                <input type="number" wire:model="budget" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                @error('budget') <p class="mt-1 text-xs text-error-600">{{ $message }}</p> @enderror
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="update">Simpan Perubahan</span>
                    <span wire:loading wire:target="update">Menyimpan...</span>
                </button>
                <button type="button" wire:click="confirmCancel" class="inline-flex items-center justify-center gap-2 rounded-xl border border-error-200 px-5 py-3 text-sm font-semibold text-error-600 transition hover:bg-error-50">
                    <x-icon name="x" :size="16" /> Batalkan Proyek
                </button>
            </div>
        </form>
    </div>

    @if($confirmingCancel)
        <x-ui.confirm-modal
            title="Batalkan Proyek"
            message="Proyek akan dibatalkan dan seluruh lamaran yang masih menunggu akan ditolak. Tindakan ini tidak dapat dibatalkan."
            confirm-label="Ya, Batalkan"
            confirm-method="cancelProject"
            cancel-method="closeCancel"
            tone="danger"
            loading-target="cancelProject"
        />
    @endif
</div>
