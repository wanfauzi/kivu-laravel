<div class="mx-auto max-w-3xl space-y-6">
    <x-ui.page-header title="Profil Saya" subtitle="Kelola identitas akun, portofolio, dan verifikasi mahasiswa Anda." />

    {{-- Identity --}}
    <div class="kivu-card p-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
            <x-ui.avatar :name="$user->name" size="xl" />
            <div class="min-w-0">
                <p class="flex flex-wrap items-center gap-2 text-lg font-bold text-kivu-text">
                    {{ $user->name }}
                    <x-ui.verified-badge :user="$user" />
                </p>
                <p class="text-sm text-kivu-text-muted">{{ $user->email }}</p>
            </div>
        </div>

        <dl class="mt-5 grid grid-cols-1 gap-3 border-t border-kivu-border pt-5 sm:grid-cols-2">
            <div>
                <dt class="text-xs text-kivu-text-muted">Peran</dt>
                <dd class="text-sm font-medium text-kivu-text">Mahasiswa</dd>
            </div>
            <div>
                <dt class="text-xs text-kivu-text-muted">Status Akun</dt>
                <dd class="mt-0.5">
                    @if ($user->status === 'pending_ktm')
                        <x-ui.badge variant="warning">Menunggu Verifikasi</x-ui.badge>
                    @elseif ($user->status === 'suspended')
                        <x-ui.badge variant="danger">Ditangguhkan</x-ui.badge>
                    @else
                        <x-ui.badge variant="success">Aktif</x-ui.badge>
                    @endif
                </dd>
            </div>
        </dl>

        <div class="mt-5 grid grid-cols-2 gap-3 border-t border-kivu-border pt-5 sm:grid-cols-4">
            @foreach ([
                ['value' => $trust['completed_projects'], 'label' => 'Proyek Selesai'],
                ['value' => $trust['rating_avg'] ?? '-', 'label' => 'Rating'],
                ['value' => $trust['reviews_count'], 'label' => 'Ulasan'],
                ['value' => $trust['member_since']?->translatedFormat('M Y') ?? '-', 'label' => 'Bergabung'],
            ] as $stat)
                <div class="rounded-kivu-sm bg-kivu-surface-muted p-3 text-center">
                    <p class="text-lg font-bold text-kivu-text">{{ $stat['value'] }}</p>
                    <p class="text-xs text-kivu-text-muted">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Edit profile --}}
    <div class="kivu-card p-6">
        <h3 class="text-lg font-bold text-kivu-text">Ubah Profil</h3>

        <form wire:submit="updateProfile" class="mt-5 space-y-4">
            <div>
                <label for="profile-name" class="mb-1.5 block text-sm font-medium text-kivu-text">Nama</label>
                <input id="profile-name" type="text" wire:model="name" class="kivu-input px-3.5 py-2.5 text-sm" />
                @error('name')
                    <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="profile-bio" class="mb-1.5 block text-sm font-medium text-kivu-text">Bio Singkat</label>
                <textarea id="profile-bio" wire:model="bio" rows="3" placeholder="Ceritakan keahlian dan minat Anda..."
                    class="kivu-input px-3.5 py-2.5 text-sm"></textarea>
                @error('bio')
                    <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="profile-skills" class="mb-1.5 block text-sm font-medium text-kivu-text">Keahlian</label>
                <input id="profile-skills" type="text" wire:model="skillsInput" placeholder="Desain Grafis, Copywriting, Web Development"
                    class="kivu-input px-3.5 py-2.5 text-sm" />
                @error('skillsInput')
                    <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-kivu-text-muted">Pisahkan dengan koma. Maksimal 10 keahlian.</p>
            </div>

            <div class="grid grid-cols-1 gap-4 border-t border-kivu-border pt-4 sm:grid-cols-3">
                <div>
                    <label for="profile-current-password" class="mb-1.5 block text-sm font-medium text-kivu-text">Password Saat Ini</label>
                    <input id="profile-current-password" type="password" wire:model="current_password" placeholder="Kosongkan bila tidak ganti"
                        class="kivu-input px-3.5 py-2.5 text-sm" />
                    @error('current_password')
                        <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="profile-password" class="mb-1.5 block text-sm font-medium text-kivu-text">Password Baru</label>
                    <input id="profile-password" type="password" wire:model="password" placeholder="Minimal 8 karakter"
                        class="kivu-input px-3.5 py-2.5 text-sm" />
                    @error('password')
                        <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="profile-password-confirm" class="mb-1.5 block text-sm font-medium text-kivu-text">Konfirmasi Password</label>
                    <input id="profile-password-confirm" type="password" wire:model="password_confirmation" class="kivu-input px-3.5 py-2.5 text-sm" />
                </div>
            </div>

            <x-ui.button type="submit" loading-target="updateProfile" loading-label="Menyimpan...">
                Simpan Perubahan
            </x-ui.button>
        </form>
    </div>

    {{-- KTM --}}
    <div class="kivu-card p-6">
        <h3 class="flex items-center gap-2 text-lg font-bold text-kivu-text">
            <x-icon name="id-card" :size="20" class="text-kivu-primary" /> Verifikasi KTM
        </h3>

        @if ($user->isVerifiedStudent())
            <div class="mt-4 flex items-center gap-2 rounded-kivu-sm border border-kivu-success/25 bg-kivu-success-soft px-4 py-3 text-sm text-kivu-success">
                <x-icon name="circle-check" :size="18" class="shrink-0" /> Akun Anda telah terverifikasi sebagai mahasiswa.
            </div>
        @else
            <p class="mt-2 text-sm text-kivu-text-secondary">
                Unggah foto/scan Kartu Tanda Mahasiswa untuk diverifikasi admin. Format JPG, PNG, atau PDF (maks. 2 MB).
            </p>

            @if ($user->ktm_path)
                <div class="mt-4 flex items-center gap-3 rounded-kivu-sm border border-kivu-info/25 bg-kivu-info-soft px-4 py-3 text-sm text-kivu-info">
                    <x-icon name="upload" :size="18" class="shrink-0" />
                    <span>KTM sudah diunggah dan menunggu verifikasi admin.</span>
                    <a href="{{ route('ktm.show', $user->id) }}" target="_blank" class="kivu-focus ms-auto shrink-0 rounded font-semibold underline">Lihat</a>
                </div>
            @endif

            <form wire:submit="saveKtm" class="mt-4 space-y-4">
                <div>
                    <label for="ktm-file" class="mb-1.5 block text-sm font-medium text-kivu-text">File KTM</label>
                    <input id="ktm-file" type="file" wire:model="ktm" accept=".jpg,.jpeg,.png,.pdf"
                        class="kivu-input file:mr-3 file:rounded-kivu-sm file:border-0 file:bg-kivu-primary-soft file:px-3 file:py-2 file:text-sm file:font-semibold file:text-kivu-primary px-3 py-2 text-sm" />
                    @error('ktm')
                        <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="ktm" class="mt-2 flex items-center gap-2 text-xs text-kivu-text-muted">
                        <x-icon name="rotate-ccw" :size="14" class="animate-spin" /> Mengunggah...
                    </div>
                </div>

                <x-ui.button type="submit" loading-target="saveKtm" loading-label="Mengunggah...">
                    <x-icon name="upload" :size="18" /> Unggah KTM
                </x-ui.button>
            </form>
        @endif
    </div>

    {{-- Portfolio --}}
    <div class="kivu-card p-6">
        <div class="flex items-center justify-between gap-3">
            <h3 class="flex items-center gap-2 text-lg font-bold text-kivu-text">
                <x-icon name="briefcase" :size="20" class="text-kivu-primary" /> Portofolio
                <span class="text-xs font-medium text-kivu-text-muted">({{ $portfolios->count() }}/12)</span>
            </h3>
            <x-ui.button wire:click="openPortfolioModal" size="sm">
                <x-icon name="plus-circle" :size="14" /> Tambah
            </x-ui.button>
        </div>

        <p class="mt-1 text-xs text-kivu-text-muted">Tampilkan karya terbaik Anda agar UMKM lebih percaya.</p>

        <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
            @forelse ($portfolios as $item)
                <div class="rounded-kivu border border-kivu-border p-4 {{ $item->is_service ? 'bg-kivu-yellow-soft/40 border-kivu-yellow' : '' }}">
                    @if ($item->file_path && $item->isImage())
                        <img src="{{ Storage::disk('public')->url($item->file_path) }}" alt="{{ $item->title }}"
                            class="mb-3 h-32 w-full rounded-kivu-sm object-cover" loading="lazy">
                    @endif

                    <div class="flex items-start justify-between gap-2">
                        <p class="text-sm font-semibold text-kivu-text">{{ $item->title }}</p>
                        <div class="flex shrink-0 gap-1">
                            <button wire:click="editPortfolio({{ $item->id }})"
                                class="kivu-focus rounded p-1 text-kivu-text-muted transition hover:text-kivu-primary" aria-label="Edit portofolio">
                                <x-icon name="pencil" :size="14" />
                            </button>
                            <button wire:click="confirmDeletePortfolio({{ $item->id }})"
                                class="kivu-focus rounded p-1 text-kivu-text-muted transition hover:text-kivu-danger" aria-label="Hapus portofolio">
                                <x-icon name="trash" :size="14" />
                            </button>
                        </div>
                    </div>

                    @if ($item->is_service)
                        <div class="mt-1.5 flex flex-wrap gap-1.5">
                            <span class="kivu-focus inline-flex items-center gap-1 rounded-full bg-kivu-yellow px-2 py-0.5 text-xs font-semibold text-kivu-yellow-text">Jasa</span>
                            <span class="text-xs font-bold text-kivu-text">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                            @if ($item->delivery_days)
                                <span class="text-xs text-kivu-text-muted">{{ $item->delivery_days }} hari</span>
                            @endif
                            @if ($item->category)
                                <span class="text-xs text-kivu-text-muted">• {{ $item->category->name }}</span>
                            @endif
                        </div>
                    @endif

                    @if ($item->description)
                        <p class="mt-1 text-xs text-kivu-text-muted">{{ $item->description }}</p>
                    @endif

                    <div class="mt-2 flex flex-wrap gap-3">
                        @if ($item->url)
                            <a href="{{ $item->url }}" target="_blank" rel="noopener"
                                class="kivu-focus inline-flex items-center gap-1 rounded text-xs font-medium text-kivu-primary hover:underline">
                                <x-icon name="external-link" :size="12" /> Tautan
                            </a>
                        @endif
                        @if ($item->file_path && ! $item->isImage())
                            <a href="{{ Storage::disk('public')->url($item->file_path) }}" target="_blank"
                                class="kivu-focus inline-flex items-center gap-1 rounded text-xs font-medium text-kivu-primary hover:underline">
                                <x-icon name="file-text" :size="12" /> Lihat File
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <x-ui.empty-state icon="briefcase" title="Belum ada portofolio"
                        description="Tambahkan karya terbaik Anda untuk menarik perhatian UMKM." />
                </div>
            @endforelse
        </div>
    </div>

    {{-- Reviews --}}
    <div class="kivu-card p-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h3 class="flex items-center gap-2 text-lg font-bold text-kivu-text">
                <x-icon name="star" :size="20" :filled="true" class="text-kivu-warning" /> Ulasan dari UMKM
            </h3>
            @if ($avgRating)
                <x-ui.rating-stars :value="$avgRating" :count="$reviews->count()" :size="16" show-value />
            @endif
        </div>

        <div class="mt-4 space-y-3">
            @forelse ($reviews as $review)
                <div class="rounded-kivu border border-kivu-border bg-kivu-surface-muted/60 p-4">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-sm font-semibold text-kivu-text">{{ $review->reviewer->name ?? 'UMKM' }}</p>
                        <x-ui.rating-stars :value="$review->rating" :size="14" />
                    </div>
                    @if ($review->comment)
                        <p class="mt-1.5 text-sm leading-relaxed text-kivu-text-secondary">{{ $review->comment }}</p>
                    @endif
                    <p class="mt-1.5 text-xs text-kivu-text-muted">{{ $review->created_at->translatedFormat('d F Y') }}</p>
                </div>
            @empty
                <p class="text-sm text-kivu-text-muted">Belum ada ulasan. Selesaikan proyek untuk menerima ulasan dari UMKM.</p>
            @endforelse
        </div>
    </div>

    {{-- Info --}}
    <div class="flex gap-2.5 rounded-kivu border border-kivu-info/25 bg-kivu-info-soft p-4">
        <x-icon name="info" :size="16" class="mt-0.5 shrink-0 text-kivu-info" />
        <p class="text-xs leading-relaxed text-kivu-info">
            Mahasiswa dengan email <strong>.ac.id</strong> terverifikasi otomatis. Email lain harus mengunggah KTM agar lencana &quot;Mahasiswa Terverifikasi&quot; muncul.
        </p>
    </div>

    {{-- Portfolio modal --}}
    @if ($showPortfolioModal)
        <x-ui.confirm-modal
            :title="($editingPortfolioId ? 'Edit' : 'Tambah').' Portofolio'"
            confirm-label="Simpan"
            confirm-method="savePortfolio"
            cancel-method="closePortfolioModal"
            tone="primary"
            loading-target="savePortfolio">
            <div class="space-y-4">
                <div>
                    <label for="p-title" class="mb-1.5 block text-sm font-medium text-kivu-text">Judul</label>
                    <input id="p-title" type="text" wire:model="p_title" class="kivu-input px-3 py-2.5 text-sm" />
                    @error('p_title')
                        <p class="mt-1 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="p-description" class="mb-1.5 block text-sm font-medium text-kivu-text">Deskripsi</label>
                    <textarea id="p-description" wire:model="p_description" rows="2" class="kivu-input px-3 py-2.5 text-sm"></textarea>
                    @error('p_description')
                        <p class="mt-1 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="p-url" class="mb-1.5 block text-sm font-medium text-kivu-text">Tautan URL (opsional)</label>
                    <input id="p-url" type="url" wire:model="p_url" placeholder="https://..." class="kivu-input px-3 py-2.5 text-sm" />
                    @error('p_url')
                        <p class="mt-1 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="p-file" class="mb-1.5 block text-sm font-medium text-kivu-text">File (JPG/PNG/PDF, maks 4 MB)</label>
                    <input id="p-file" type="file" wire:model="p_file" accept=".jpg,.jpeg,.png,.pdf"
                        class="kivu-input file:mr-3 file:rounded-kivu-sm file:border-0 file:bg-kivu-primary-soft file:px-3 file:py-2 file:text-sm file:font-semibold file:text-kivu-primary px-3 py-2 text-sm" />
                    @error('p_file')
                        <p class="mt-1 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="p_file" class="mt-1 flex items-center gap-2 text-xs text-kivu-text-muted">
                        <x-icon name="rotate-ccw" :size="14" class="animate-spin" /> Mengunggah...
                    </div>
                </div>

                <label class="flex cursor-pointer items-center gap-2 rounded-kivu-sm border border-kivu-border bg-kivu-surface-muted p-3">
                    <input type="checkbox" wire:model.live="p_is_service" class="h-4 w-4 text-kivu-primary" />
                    <span class="text-sm font-medium text-kivu-text">Tawarkan sebagai Jasa (UMKM bisa memesan)</span>
                </label>

                @if ($p_is_service)
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-kivu-text">Harga (Rp)</label>
                            <input type="number" wire:model="p_price" class="kivu-input px-3 py-2 text-sm" placeholder="50000" />
                            @error('p_price')
                                <p class="mt-1 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-kivu-text">Estimasi (hari)</label>
                            <input type="number" wire:model="p_delivery_days" class="kivu-input px-3 py-2 text-sm" />
                            @error('p_delivery_days')
                                <p class="mt-1 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-kivu-text">Kategori Jasa</label>
                        <select wire:model="p_category_id" class="kivu-input px-3 py-2 text-sm">
                            <option value="">Pilih kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('p_category_id')
                            <p class="mt-1 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                        @enderror
                    </div>
                @endif
            </div>
        </x-ui.confirm-modal>
    @endif

    @if ($confirmingDeletePortfolio)
        <x-ui.confirm-modal
            title="Hapus Portofolio"
            message="Item portofolio ini akan dihapus permanen."
            confirm-label="Ya, Hapus"
            confirm-method="deletePortfolioItem"
            cancel-method="cancelDeletePortfolio"
            tone="danger"
            loading-target="deletePortfolioItem" />
    @endif
</div>
