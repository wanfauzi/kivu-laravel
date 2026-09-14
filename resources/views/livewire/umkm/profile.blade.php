<div class="mx-auto max-w-2xl space-y-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Profil UMKM</h1>
        <p class="text-sm text-gray-500">Kelola informasi akun bisnis Anda.</p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <div class="flex items-center gap-4">
            <x-ui.avatar :name="auth()->user()->name" size="lg" />
            <div>
                <p class="text-lg font-bold text-gray-900">{{ auth()->user()->name }}</p>
                <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                @if(auth()->user()->business_name)
                    <p class="text-xs text-gray-400">{{ auth()->user()->business_name }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <h3 class="text-lg font-bold text-gray-900">Ubah Profil</h3>
        <form wire:submit.prevent="updateProfile" class="mt-4 space-y-4">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" wire:model="name" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                @error('name') <p class="mt-1 text-xs text-error-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">Password Saat Ini</label>
                <input type="password" wire:model="current_password" placeholder="Kosongkan bila tidak ganti password" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                @error('current_password') <p class="mt-1 text-xs text-error-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">Password Baru</label>
                <input type="password" wire:model="password" placeholder="Minimal 8 karakter" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                @error('password') <p class="mt-1 text-xs text-error-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                <input type="password" wire:model="password_confirmation" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
            </div>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="updateProfile">Simpan Perubahan</span>
                <span wire:loading wire:target="updateProfile">Menyimpan...</span>
            </button>
        </form>
    </div>
</div>
