<div class="mx-auto max-w-2xl space-y-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Profil Saya</h1>
        <p class="text-sm text-gray-500">Kelola identitas akun dan verifikasi mahasiswa Anda.</p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <div class="flex items-center gap-4">
            <x-ui.avatar :name="$user->name" size="lg" />
            <div>
                <p class="flex items-center gap-2 text-lg font-bold text-gray-900">
                    {{ $user->name }}
                    <x-ui.verified-badge :user="$user" />
                </p>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
            </div>
        </div>
        <dl class="mt-5 grid grid-cols-1 gap-3 border-t border-gray-100 pt-5 sm:grid-cols-2">
            <div>
                <dt class="text-xs text-gray-400">Role</dt>
                <dd class="text-sm font-medium capitalize text-gray-800">Mahasiswa</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-400">Status Akun</dt>
                <dd class="text-sm font-medium text-gray-800">
                    @if($user->status === 'pending_ktm')
                        Menunggu Verifikasi
                    @elseif($user->status === 'suspended')
                        Ditangguhkan
                    @else
                        Aktif
                    @endif
                </dd>
            </div>
        </dl>
        <div class="mt-5 grid grid-cols-2 gap-3 border-t border-gray-100 pt-5 sm:grid-cols-4">
            <div class="rounded-xl bg-gray-50 p-3 text-center">
                <p class="text-lg font-bold text-gray-900">{{ $trust['completed_projects'] }}</p>
                <p class="text-xs text-gray-500">Proyek Selesai</p>
            </div>
            <div class="rounded-xl bg-gray-50 p-3 text-center">
                <p class="text-lg font-bold text-gray-900">{{ $trust['rating_avg'] ?? '-' }}</p>
                <p class="text-xs text-gray-500">Rating</p>
            </div>
            <div class="rounded-xl bg-gray-50 p-3 text-center">
                <p class="text-lg font-bold text-gray-900">{{ $trust['reviews_count'] }}</p>
                <p class="text-xs text-gray-500">Ulasan</p>
            </div>
            <div class="rounded-xl bg-gray-50 p-3 text-center">
                <p class="text-lg font-bold text-gray-900">{{ $trust['member_since']?->translatedFormat('M Y') }}</p>
                <p class="text-xs text-gray-500">Bergabung</p>
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
                <label class="mb-1.5 block text-sm font-medium text-gray-700">Bio Singkat</label>
                <textarea wire:model="bio" rows="3" placeholder="Ceritakan keahlian dan minat Anda..." class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"></textarea>
                @error('bio') <p class="mt-1 text-xs text-error-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700">Keahlian (pisahkan dengan koma)</label>
                <input type="text" wire:model="skillsInput" placeholder="Desain Grafis, Copywriting, Web Development" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                @error('skillsInput') <p class="mt-1 text-xs text-error-600">{{ $message }}</p> @enderror
                <p class="mt-1 text-xs text-gray-400">Maks 10 keahlian.</p>
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

    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <h3 class="flex items-center gap-2 text-lg font-bold text-gray-900">
            <x-icon name="id-card" :size="20" /> Verifikasi KTM
        </h3>

        @if($user->isVerifiedStudent())
            <div class="mt-4 flex items-center gap-2 rounded-xl border border-success-200 bg-success-50 px-4 py-3 text-sm text-success-700">
                <x-icon name="circle-check" :size="18" /> Akun Anda telah terverifikasi sebagai mahasiswa.
            </div>
        @else
            <p class="mt-2 text-sm text-gray-500">
                Unggah foto/scan Kartu Tanda Mahasiswa untuk diverifikasi admin. Format: <strong>JPG, PNG, atau PDF</strong> (maks. 2 MB).
            </p>

            @if($user->ktm_path)
                <div class="mt-4 flex items-center gap-3 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                    <x-icon name="upload" :size="18" />
                    <span>KTM telah diunggah dan menunggu verifikasi admin.</span>
                    <a href="{{ route('ktm.show', $user->id) }}" target="_blank" class="ms-auto shrink-0 font-semibold text-blue-700 underline">Lihat</a>
                </div>
            @endif

            <form wire:submit.prevent="saveKtm" class="mt-4 space-y-4">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">File KTM</label>
                    <input type="file" wire:model="ktm" accept=".jpg,.jpeg,.png,.pdf"
                        class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-white text-sm text-gray-600 file:mr-3 file:border-0 file:bg-blue-50 file:px-4 file:py-2.5 file:text-sm file:font-medium file:text-blue-700">
                    @error('ktm') <p class="mt-1 text-xs text-error-600">{{ $message }}</p> @enderror
                    <div wire:loading wire:target="ktm" class="mt-2 text-xs text-gray-400">Mengunggah...</div>
                </div>
                <button type="submit" wire:loading.attr="disabled" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                    <x-icon name="upload" :size="18" /> Unggah KTM
                </button>
            </form>
        @endif
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <div class="flex items-center justify-between">
            <h3 class="flex items-center gap-2 text-lg font-bold text-gray-900">
                <x-icon name="briefcase" :size="20" /> Portofolio
            </h3>
            <button wire:click="openPortfolioModal" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3.5 py-2 text-xs font-semibold text-white transition hover:bg-blue-700">
                <x-icon name="plus-circle" :size="14" /> Tambah
            </button>
        </div>
        <p class="mt-1 text-xs text-gray-500">Tampilkan karya terbaik Anda agar UMKM lebih percaya. Maksimal 12 item.</p>

        <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
            @forelse($portfolios as $item)
                <div class="rounded-xl border border-gray-200 p-4">
                    @if($item->file_path && $item->isImage())
                        <img src="{{ Storage::disk('public')->url($item->file_path) }}" alt="{{ $item->title }}" class="mb-3 h-32 w-full rounded-lg object-cover">
                    @endif
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-sm font-semibold text-gray-800">{{ $item->title }}</p>
                        <div class="flex shrink-0 gap-1">
                            <button wire:click="editPortfolio({{ $item->id }})" class="rounded p-1 text-gray-400 hover:text-blue-600" aria-label="Edit"><x-icon name="file-text" :size="14" /></button>
                            <button wire:click="deletePortfolio({{ $item->id }})" wire:confirm="Hapus item portofolio ini?" class="rounded p-1 text-gray-400 hover:text-error-600" aria-label="Hapus"><x-icon name="x" :size="14" /></button>
                        </div>
                    </div>
                    @if($item->description)<p class="mt-1 text-xs text-gray-500">{{ $item->description }}</p>@endif
                    @if($item->url)
                        <a href="{{ $item->url }}" target="_blank" rel="noopener" class="mt-2 inline-flex items-center gap-1 text-xs font-medium text-blue-600 hover:underline"><x-icon name="arrow-right" :size="12" /> Tautan</a>
                    @endif
                    @if($item->file_path && !$item->isImage())
                        <a href="{{ Storage::disk('public')->url($item->file_path) }}" target="_blank" class="mt-2 inline-flex items-center gap-1 text-xs font-medium text-blue-600 hover:underline"><x-icon name="file-text" :size="12" /> Lihat File</a>
                    @endif
                </div>
            @empty
                <p class="text-sm text-gray-500">Belum ada portofolio.</p>
            @endforelse
        </div>

        @if($showPortfolioModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" wire:click.self="closePortfolioModal">
                <div class="w-full max-w-md rounded-2xl border border-gray-200 bg-white p-6 shadow-xl">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900">{{ $editingPortfolioId ? 'Edit' : 'Tambah' }} Portofolio</h3>
                        <button wire:click="closePortfolioModal" type="button" class="rounded-lg p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600" aria-label="Tutup">
                            <x-icon name="x" :size="20" />
                        </button>
                    </div>
                    <div class="mt-4 space-y-4">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Judul</label>
                            <input type="text" wire:model="p_title" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                            @error('p_title') <p class="mt-1 text-xs text-error-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea wire:model="p_description" rows="2" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"></textarea>
                            @error('p_description') <p class="mt-1 text-xs text-error-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">Tautan URL (opsional)</label>
                            <input type="url" wire:model="p_url" placeholder="https://..." class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                            @error('p_url') <p class="mt-1 text-xs text-error-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">File (JPG/PNG/PDF, maks 4MB)</label>
                            <input type="file" wire:model="p_file" accept=".jpg,.jpeg,.png,.pdf" class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-white text-sm text-gray-600 file:mr-3 file:border-0 file:bg-blue-50 file:px-4 file:py-2.5 file:text-sm file:font-medium file:text-blue-700">
                            @error('p_file') <p class="mt-1 text-xs text-error-600">{{ $message }}</p> @enderror
                            <div wire:loading wire:target="p_file" class="mt-1 text-xs text-gray-400">Mengunggah...</div>
                        </div>
                    </div>
                    <div class="mt-5 flex justify-end gap-3">
                        <button wire:click="closePortfolioModal" type="button" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">Batal</button>
                        <button wire:click="savePortfolio" class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700" wire:loading.attr="disabled">Simpan</button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5">
        <h4 class="flex items-center gap-2 text-sm font-bold text-blue-900"><x-icon name="info" :size="16" /> Informasi</h4>
        <p class="mt-1 text-xs leading-relaxed text-blue-700">
            Mahasiswa dengan email <strong>.ac.id</strong> terverifikasi otomatis. Pengguna email lain harus mengunggah KTM sebelum dapat menampilkan status &quot;Mahasiswa Terverifikasi&quot;.
        </p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <div class="flex items-center justify-between">
            <h3 class="flex items-center gap-2 text-lg font-bold text-gray-900">
                <x-icon name="star" :size="20" :filled="true" /> Ulasan dari UMKM
            </h3>
            @if($avgRating)
                <div class="flex items-center gap-2">
                    <span class="text-2xl font-bold text-gray-900">{{ $avgRating }}</span>
                    <div class="flex text-amber-400">
                        @for($i = 1; $i <= 5; $i++)
                            <x-icon name="star" :size="16" :filled="$i <= round($avgRating)" class="{{ $i <= round($avgRating) ? '' : 'text-gray-300' }}" />
                        @endfor
                    </div>
                    <span class="text-xs text-gray-400">({{ $reviews->count() }} ulasan)</span>
                </div>
            @endif
        </div>

        <div class="mt-4 space-y-3">
            @forelse($reviews as $review)
                <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-gray-800">{{ $review->reviewer->name ?? 'UMKM' }}</p>
                        <div class="flex text-amber-400">
                            @for($i = 1; $i <= 5; $i++)
                                <x-icon name="star" :size="14" :filled="$i <= $review->rating" class="{{ $i <= $review->rating ? '' : 'text-gray-300' }}" />
                            @endfor
                        </div>
                    </div>
                    @if($review->comment)
                        <p class="mt-1 text-sm text-gray-600">{{ $review->comment }}</p>
                    @endif
                    <p class="mt-1 text-xs text-gray-400">{{ $review->created_at->translatedFormat('d F Y') }}</p>
                </div>
            @empty
                <p class="text-sm text-gray-500">Belum ada ulasan. Selesaikan proyek untuk menerima ulasan dari UMKM.</p>
            @endforelse
        </div>
    </div>
</div>