<div class="mx-auto max-w-2xl space-y-6">
    <x-ui.page-header title="Profil UMKM" subtitle="Kelola informasi akun dan bisnis Anda." />

    <div class="kivu-card p-6">
        <div class="flex items-center gap-4">
            <x-ui.avatar :name="auth()->user()->business_name ?: auth()->user()->name" size="xl" />
            <div class="min-w-0">
                <p class="truncate text-lg font-bold text-kivu-text">{{ auth()->user()->business_name ?: auth()->user()->name }}</p>
                <p class="truncate text-sm text-kivu-text-muted">{{ auth()->user()->email }}</p>
                @if (auth()->user()->business_name)
                    <p class="mt-0.5 text-xs text-kivu-text-muted">Penanggung jawab: {{ auth()->user()->name }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="kivu-card p-6">
        <h3 class="text-lg font-bold text-kivu-text">Ubah Profil</h3>

        <form wire:submit="updateProfile" class="mt-5 space-y-4">
            <div>
                <label for="umkm-business-name" class="mb-1.5 block text-sm font-medium text-kivu-text">Nama Usaha / Bisnis</label>
                <input id="umkm-business-name" type="text" wire:model="business_name" placeholder="Contoh: Warung Kopi Menangan"
                    class="kivu-input px-3.5 py-2.5 text-sm" />
                @error('business_name')
                    <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-kivu-text-muted">Nama ini tampil di kartu proyek dan profil publik.</p>
            </div>

            <div>
                <label for="umkm-name" class="mb-1.5 block text-sm font-medium text-kivu-text">Nama Penanggung Jawab</label>
                <input id="umkm-name" type="text" wire:model="name" class="kivu-input px-3.5 py-2.5 text-sm" />
                @error('name')
                    <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-4 border-t border-kivu-border pt-4 sm:grid-cols-3">
                <div>
                    <label for="umkm-current-password" class="mb-1.5 block text-sm font-medium text-kivu-text">Password Saat Ini</label>
                    <input id="umkm-current-password" type="password" wire:model="current_password" placeholder="Kosongkan bila tidak ganti"
                        class="kivu-input px-3.5 py-2.5 text-sm" />
                    @error('current_password')
                        <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="umkm-password" class="mb-1.5 block text-sm font-medium text-kivu-text">Password Baru</label>
                    <input id="umkm-password" type="password" wire:model="password" placeholder="Minimal 8 karakter"
                        class="kivu-input px-3.5 py-2.5 text-sm" />
                    @error('password')
                        <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="umkm-password-confirm" class="mb-1.5 block text-sm font-medium text-kivu-text">Konfirmasi Password</label>
                    <input id="umkm-password-confirm" type="password" wire:model="password_confirmation" class="kivu-input px-3.5 py-2.5 text-sm" />
                </div>
            </div>

            <x-ui.button type="submit" loading-target="updateProfile" loading-label="Menyimpan...">
                Simpan Perubahan
            </x-ui.button>
        </form>
    </div>
</div>
