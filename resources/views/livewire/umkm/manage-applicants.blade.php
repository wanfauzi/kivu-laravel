<div class="space-y-6">
    <a href="{{ route('umkm.my-projects') }}" wire:navigate class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 transition hover:text-blue-600">
        <x-icon name="arrow-left" :size="16" /> Kembali ke Proyek Saya
    </a>

    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ $project->title }}</h1>
            <p class="mt-1 flex items-center gap-2 text-sm text-gray-500">
                Kelola pelamar untuk proyek ini.
                <span>Status: <x-ui.status-badge :value="$project->status" kind="project" /></span>
            </p>
        </div>
    </div>

    <div class="space-y-4">
        @forelse($applicants as $app)
            @php $r = $ratings->get($app->student_id); @endphp
            <div class="flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-start gap-4">
                    <x-ui.avatar :name="$app->student->name" />
                    <div class="space-y-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="font-semibold text-gray-900">{{ $app->student->name }}</p>
                            <x-ui.verified-badge :user="$app->student" />
                            <x-ui.status-badge :value="$app->status" kind="application" />
                            @if($r && $r->total > 0)
                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2 py-0.5 text-[11px] font-semibold text-amber-700">
                                    <x-icon name="star" :size="12" :filled="true" /> {{ number_format($r->avg_rating, 1) }} ({{ $r->total }})
                                </span>
                            @else
                                <span class="text-[11px] text-gray-400">Belum ada ulasan</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500">{{ $app->student->email }}</p>
                        @if($app->message)
                            <p class="mt-1 max-w-md rounded-lg bg-gray-50 px-3 py-2 text-xs italic text-gray-600">"{{ $app->message }}"</p>
                        @endif
                        @if($app->status === 'REJECTED' && $app->rejection_note)
                            <p class="mt-1 max-w-md rounded-lg bg-error-50 px-3 py-2 text-xs text-error-600">Alasan ditolak: {{ $app->rejection_note }}</p>
                        @endif
                        <p class="text-xs text-gray-400">{{ $app->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @if($app->status === 'PENDING')
                    <div class="flex shrink-0 gap-2">
                        <button wire:click="viewApplicantProfile({{ $app->id }})" class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-gray-300 px-4 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50">
                            <x-icon name="user-round" :size="15" /> Lihat Profil
                        </button>
                        <button wire:click="confirmAccept({{ $app->id }})" @disabled(!$projectOpen) class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-40">
                            <x-icon name="check" :size="15" /> Terima
                        </button>
                        <button wire:click="confirmReject({{ $app->id }})" class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-error-200 px-4 py-2 text-xs font-semibold text-error-600 transition hover:bg-error-50">
                            <x-icon name="x" :size="15" /> Tolak
                        </button>
                    </div>
                @else
                    <button wire:click="viewApplicantProfile({{ $app->id }})" class="inline-flex shrink-0 items-center justify-center gap-1.5 rounded-lg border border-gray-300 px-4 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50">
                        <x-icon name="user-round" :size="15" /> Lihat Profil
                    </button>
                @endif
            </div>
        @empty
            <x-ui.empty-state icon="users" title="Belum ada pelamar" description="Proyek ini belum memiliki mahasiswa yang melamar. Coba sebarkan proyek Anda." />
        @endforelse
    </div>

    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" wire:click.self="cancelAccept">
            <div class="w-full max-w-md rounded-2xl border border-gray-200 bg-white p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Konfirmasi Terima Pelamar</h3>
                    <button wire:click="cancelAccept" type="button" class="rounded-lg p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600" aria-label="Tutup">
                        <x-icon name="x" :size="20" />
                    </button>
                </div>
                <p class="mt-2 text-sm text-gray-600">
                    Terima <span class="font-semibold text-gray-900">{{ $selectedApplicant?->student->name }}</span> untuk proyek "{{ $project->title }}"?<br>
                    Status proyek akan berubah menjadi <span class="font-medium">Dikerjakan</span>.
                </p>
                <div class="mt-5 flex justify-end gap-3">
                    <button wire:click="cancelAccept" type="button" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">Batal</button>
                    <button wire:click="accept" class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700" wire:loading.attr="disabled">Ya, Terima</button>
                </div>
            </div>
        </div>
    @endif

    @if($showRejectModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" wire:click.self="cancelReject">
            <div class="w-full max-w-md rounded-2xl border border-gray-200 bg-white p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Tolak Pelamar</h3>
                    <button wire:click="cancelReject" type="button" class="rounded-lg p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600" aria-label="Tutup">
                        <x-icon name="x" :size="20" />
                    </button>
                </div>
                <p class="mt-2 text-sm text-gray-600">Tolak lamaran <span class="font-semibold text-gray-900">{{ $selectedApplicant?->student->name }}</span>?</p>
                <div class="mt-4">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Catatan alasan (opsional)</label>
                    <textarea wire:model="rejection_note" rows="3" placeholder="Contoh: portofolio belum sesuai kebutuhan" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"></textarea>
                    @error('rejection_note') <p class="mt-1 text-xs text-error-600">{{ $message }}</p> @enderror
                </div>
                <div class="mt-5 flex justify-end gap-3">
                    <button wire:click="cancelReject" type="button" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">Batal</button>
                    <button wire:click="reject" class="rounded-lg bg-error-500 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-error-600" wire:loading.attr="disabled">Ya, Tolak</button>
                </div>
            </div>
        </div>
    @endif

    @if($showProfileModal && $viewingStudent)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" wire:click.self="closeApplicantProfile">
            <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl border border-gray-200 bg-white p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Profil Pelamar</h3>
                    <button wire:click="closeApplicantProfile" type="button" class="rounded-lg p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600" aria-label="Tutup">
                        <x-icon name="x" :size="20" />
                    </button>
                </div>

                <div class="mt-4 flex items-center gap-4">
                    <x-ui.avatar :name="$viewingStudent->name" size="lg" />
                    <div>
                        <p class="flex items-center gap-2 text-lg font-bold text-gray-900">
                            {{ $viewingStudent->name }}
                            <x-ui.verified-badge :user="$viewingStudent" />
                        </p>
                        <p class="text-xs text-gray-500">Bergabung {{ $viewingTrust['member_since']?->translatedFormat('F Y') }}</p>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-3 gap-2">
                    <div class="rounded-xl bg-gray-50 p-3 text-center">
                        <p class="text-lg font-bold text-gray-900">{{ $viewingTrust['completed_projects'] }}</p>
                        <p class="text-[11px] text-gray-500">Proyek Selesai</p>
                    </div>
                    <div class="rounded-xl bg-gray-50 p-3 text-center">
                        <p class="flex items-center justify-center gap-1 text-lg font-bold text-gray-900"><x-icon name="star" :size="14" :filled="true" /> {{ $viewingTrust['rating_avg'] ?? '-' }}</p>
                        <p class="text-[11px] text-gray-500">{{ $viewingTrust['reviews_count'] }} Ulasan</p>
                    </div>
                    <div class="rounded-xl bg-gray-50 p-3 text-center">
                        <p class="text-lg font-bold {{ $viewingTrust['verified'] ? 'text-blue-600' : 'text-gray-400' }}">{{ $viewingTrust['verified'] ? 'Ya' : 'Belum' }}</p>
                        <p class="text-[11px] text-gray-500">Terverifikasi</p>
                    </div>
                </div>

                @if($viewingStudent->bio)
                    <div class="mt-4">
                        <p class="text-sm font-semibold text-gray-800">Tentang</p>
                        <p class="mt-1 text-sm text-gray-600">{{ $viewingStudent->bio }}</p>
                    </div>
                @endif

                @if(!empty($viewingStudent->skills))
                    <div class="mt-4">
                        <p class="text-sm font-semibold text-gray-800">Keahlian</p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach($viewingStudent->skills as $skill)
                                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($viewingPortfolios->isNotEmpty())
                    <div class="mt-4">
                        <p class="text-sm font-semibold text-gray-800">Portofolio</p>
                        <div class="mt-2 grid grid-cols-2 gap-3">
                            @foreach($viewingPortfolios as $item)
                                <div class="rounded-xl border border-gray-200 p-3">
                                    @if($item->file_path && $item->isImage())
                                        <img src="{{ Storage::disk('public')->url($item->file_path) }}" alt="{{ $item->title }}" class="mb-2 h-24 w-full rounded-lg object-cover">
                                    @endif
                                    <p class="text-xs font-semibold text-gray-800">{{ $item->title }}</p>
                                    @if($item->url)
                                        <a href="{{ $item->url }}" target="_blank" rel="noopener" class="mt-1 inline-flex items-center gap-1 text-[11px] font-medium text-blue-600 hover:underline"><x-icon name="arrow-right" :size="11" /> Tautan</a>
                                    @endif
                                    @if($item->file_path && !$item->isImage())
                                        <a href="{{ Storage::disk('public')->url($item->file_path) }}" target="_blank" class="mt-1 inline-flex items-center gap-1 text-[11px] font-medium text-blue-600 hover:underline"><x-icon name="file-text" :size="11" /> File</a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($viewingReviews->isNotEmpty())
                    <div class="mt-4">
                        <p class="text-sm font-semibold text-gray-800">Ulasan Terbaru</p>
                        <div class="mt-2 space-y-2">
                            @foreach($viewingReviews as $review)
                                <div class="rounded-xl bg-gray-50 p-3">
                                    <div class="flex items-center justify-between">
                                        <p class="text-xs font-semibold text-gray-700">{{ $review->reviewer->name ?? 'UMKM' }}</p>
                                        <div class="flex text-amber-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <x-icon name="star" :size="12" :filled="$i <= $review->rating" class="{{ $i <= $review->rating ? '' : 'text-gray-300' }}" />
                                            @endfor
                                        </div>
                                    </div>
                                    @if($review->comment)<p class="mt-1 text-xs text-gray-600">{{ $review->comment }}</p>@endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mt-5 flex justify-end">
                    <a href="{{ route('talents.show', $viewingStudent->id) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                        Lihat Profil Lengkap <x-icon name="arrow-right" :size="14" />
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>