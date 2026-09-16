<div class="space-y-6">
    <x-ui.page-header :breadcrumbs="[
        ['label' => 'Proyek Saya', 'url' => route('umkm.my-projects')],
        ['label' => 'Pelamar'],
    ]" :back="route('umkm.my-projects')" back-label="Kembali ke Proyek Saya" :title="$project->title"
        subtitle="Tinjau reputasi pelamar. Semua pelamar dapat mengirim hasil; pilih pemenang dari hasil yang masuk.">
        <x-slot:actions>
            <x-ui.status-badge :value="$project->status" kind="project" :dot="true" />
        </x-slot:actions>
    </x-ui.page-header>

    @unless ($projectOpen)
        <div class="flex gap-2.5 rounded-kivu border border-kivu-warning/25 bg-kivu-warning-soft p-4">
            <x-icon name="alert-circle" :size="18" class="mt-0.5 shrink-0 text-kivu-warning" />
            <p class="text-sm leading-relaxed text-kivu-warning">
                Proyek ini sudah tidak berstatus terbuka, jadi tidak menerima lamaran baru.
            </p>
        </div>
    @endunless

    <div class="flex flex-wrap items-center gap-2">
        <span class="text-xs font-medium text-kivu-text-muted">Urutkan:</span>
        <select wire:model.live="sort" class="kivu-input w-auto px-2 py-1.5 text-xs">
            <option value="best">Terbaik (rekomendasi)</option>
            <option value="verified">Terverifikasi dulu</option>
            <option value="rating">Rating tertinggi</option>
            <option value="bid_asc">Penawaran termurah</option>
            <option value="newest">Terbaru</option>
        </select>
    </div>

    <div class="space-y-4">
        @forelse ($applicants as $app)
            @php $r = $ratings->get($app->student_id); @endphp

            <div class="kivu-card flex flex-col gap-4 p-5 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex min-w-0 items-start gap-4">
                    <x-ui.avatar :name="$app->student->name" />

                    <div class="min-w-0 space-y-1.5">
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="font-semibold text-kivu-text">{{ $app->student->name }}</p>
                            <x-ui.verified-badge :user="$app->student" compact />
                            <x-ui.status-badge :value="$app->status" kind="application" />
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            @if ($r && $r->total > 0)
                                <x-ui.rating-stars :value="$r->avg_rating" :count="$r->total" :size="14" show-value />
                            @else
                                <span class="text-[11px] text-kivu-text-muted">Belum ada ulasan</span>
                            @endif
                            <span class="text-[11px] text-kivu-text-muted">&middot; {{ $app->created_at->diffForHumans() }}</span>
                        </div>

                        @if ($app->bid_amount)
                            <div class="flex flex-wrap items-center gap-2 rounded-kivu-sm bg-kivu-danger-soft/50 px-3 py-2">
                                <span class="text-xs font-semibold text-kivu-text">Penawaran: Rp {{ number_format($app->bid_amount, 0, ',', '.') }}</span>
                                @if ($project->hasBudgetRange())
                                    @if ($app->bid_amount < $project->min_budget)
                                        <span class="rounded-full bg-kivu-warning-soft px-2 py-0.5 text-[10px] font-bold text-kivu-warning">di bawah rentang</span>
                                    @elseif ($app->bid_amount > $project->max_budget)
                                        <span class="rounded-full bg-kivu-warning-soft px-2 py-0.5 text-[10px] font-bold text-kivu-warning">di atas rentang</span>
                                    @else
                                        <span class="rounded-full bg-kivu-success-soft px-2 py-0.5 text-[10px] font-bold text-kivu-success">dalam rentang</span>
                                    @endif
                                @endif
                            </div>
                        @endif

                        @if ($app->file_path)
                            <a href="{{ route('application-file', $app) }}" target="_blank" rel="noopener"
                                class="kivu-focus inline-flex items-center gap-1 rounded text-xs font-medium text-kivu-primary hover:underline">
                                <x-icon name="file-text" :size="14" /> {{ basename($app->file_path) }}
                            </a>
                        @endif

                        @if ($app->message)
                            <p class="max-w-md rounded-kivu-sm bg-kivu-surface-muted px-3 py-2 text-xs italic text-kivu-text-secondary">"{{ $app->message }}"</p>
                        @endif

                        @if ($app->status === 'REJECTED' && $app->rejection_note)
                            <p class="max-w-md rounded-kivu-sm bg-kivu-danger-soft px-3 py-2 text-xs text-kivu-danger">Alasan ditolak: {{ $app->rejection_note }}</p>
                        @endif
                    </div>
                </div>

                <div class="flex shrink-0 flex-wrap gap-2">
                    <x-ui.button href="{{ route('umkm.inbox', ['project' => $project->id, 'peer' => $app->student_id]) }}" variant="secondary" size="sm">
                        <x-icon name="message-square" :size="15" /> Pesan
                    </x-ui.button>
                    <x-ui.button wire:click="viewApplicantProfile({{ $app->id }})" variant="secondary" size="sm">
                        <x-icon name="user-round" :size="15" /> Profil
                    </x-ui.button>

                    @if ($app->status === 'PENDING')
                        <x-ui.button wire:click="confirmAccept({{ $app->id }})" size="sm" :disabled="! $projectOpen"
                            :title="! $projectOpen ? 'Proyek sudah tidak berstatus terbuka' : null">
                            <x-icon name="check" :size="15" /> Pilih Kandidat
                        </x-ui.button>

                        <x-ui.button wire:click="confirmReject({{ $app->id }})" variant="secondary" size="sm">
                            <x-icon name="x" :size="15" /> Tolak
                        </x-ui.button>
                    @endif
                </div>
            </div>
        @empty
            <x-ui.empty-state icon="users" title="Belum ada pelamar"
                description="Proyek ini belum memiliki mahasiswa yang melamar. Sebarkan proyek Anda untuk menjangkau lebih banyak talent." />
        @endforelse
    </div>

    @if ($showModal)
        <x-ui.confirm-modal
            title="Konfirmasi Pilih Kandidat"
            :message="'Pilih '.($selectedApplicant?->student->name ?? 'pelamar').' sebagai kandidat untuk proyek ini?'"
            confirm-label="Ya, Pilih"
            confirm-method="accept"
            cancel-method="cancelAccept"
            tone="primary"
            loading-target="accept">
            @if ($selectedApplicant?->bid_amount)
                <div class="mt-3 rounded-kivu-sm border border-kivu-border bg-kivu-surface-muted px-3 py-2.5">
                    <p class="text-xs text-kivu-text-muted">Harga penawaran kandidat</p>
                    <p class="mt-0.5 text-lg font-bold text-kivu-primary">Rp {{ number_format($selectedApplicant->bid_amount, 0, ',', '.') }}</p>
                </div>
            @endif
            @if ($selectedApplicant?->file_path)
                <div class="mt-3">
                    <a href="{{ route('application-file', $selectedApplicant) }}" target="_blank" rel="noopener"
                        class="kivu-focus inline-flex items-center gap-1 rounded text-xs font-medium text-kivu-primary hover:underline">
                        <x-icon name="file-text" :size="14" /> {{ basename($selectedApplicant->file_path) }}
                    </a>
                </div>
            @endif
        </x-ui.confirm-modal>
    @endif

    @if ($showRejectModal)
        <x-ui.confirm-modal
            title="Tolak Pelamar"
            :message="'Tolak lamaran '.($selectedApplicant?->student->name ?? 'pelamar').'?'"
            confirm-label="Ya, Tolak"
            confirm-method="reject"
            cancel-method="cancelReject"
            tone="danger"
            loading-target="reject">
            <div>
                <label for="rejection-note" class="mb-1.5 block text-sm font-medium text-kivu-text">Catatan alasan (opsional)</label>
                <textarea id="rejection-note" wire:model="rejection_note" rows="3"
                    placeholder="Contoh: portofolio belum sesuai kebutuhan"
                    class="kivu-input px-3 py-2.5 text-sm"></textarea>
                @error('rejection_note')
                    <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                @enderror
            </div>
        </x-ui.confirm-modal>
    @endif

    @if ($showProfileModal && $viewingStudent)
        <x-ui.modal title="Profil Pelamar" close-method="closeApplicantProfile" size="max-w-lg">
            <div class="flex items-center gap-4">
                <x-ui.avatar :name="$viewingStudent->name" size="lg" />
                <div class="min-w-0">
                    <p class="flex flex-wrap items-center gap-2 text-lg font-bold text-kivu-text">
                        {{ $viewingStudent->name }}
                        <x-ui.verified-badge :user="$viewingStudent" />
                    </p>
                    <p class="text-xs text-kivu-text-muted">Bergabung {{ $viewingTrust['member_since']?->translatedFormat('F Y') ?? '-' }}</p>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-3 gap-2">
                <div class="rounded-kivu-sm bg-kivu-surface-muted p-3 text-center">
                    <p class="text-lg font-bold text-kivu-text">{{ $viewingTrust['completed_projects'] }}</p>
                    <p class="text-[11px] text-kivu-text-muted">Proyek Selesai</p>
                </div>
                <div class="rounded-kivu-sm bg-kivu-surface-muted p-3 text-center">
                    <p class="text-lg font-bold text-kivu-text">{{ $viewingTrust['rating_avg'] ?? '-' }}</p>
                    <p class="text-[11px] text-kivu-text-muted">{{ $viewingTrust['reviews_count'] }} Ulasan</p>
                </div>
                <div class="rounded-kivu-sm bg-kivu-surface-muted p-3 text-center">
                    <p class="text-lg font-bold {{ $viewingTrust['verified'] ? 'text-kivu-primary' : 'text-kivu-text-muted' }}">
                        {{ $viewingTrust['verified'] ? 'Ya' : 'Belum' }}
                    </p>
                    <p class="text-[11px] text-kivu-text-muted">Terverifikasi</p>
                </div>
            </div>

            @if ($viewingStudent->bio)
                <div class="mt-4">
                    <p class="text-sm font-semibold text-kivu-text">Tentang</p>
                    <p class="mt-1 text-sm leading-relaxed text-kivu-text-secondary">{{ $viewingStudent->bio }}</p>
                </div>
            @endif

            @if (! empty($viewingStudent->skills))
                <div class="mt-4">
                    <p class="text-sm font-semibold text-kivu-text">Keahlian</p>
                    <div class="mt-2 flex flex-wrap gap-1.5">
                        @foreach ($viewingStudent->skills as $skill)
                            <span class="rounded-full bg-kivu-primary-soft px-2.5 py-1 text-xs font-medium text-kivu-primary">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($viewingPortfolios->isNotEmpty())
                <div class="mt-4">
                    <p class="text-sm font-semibold text-kivu-text">Portofolio</p>
                    <div class="mt-2 grid grid-cols-2 gap-3">
                        @foreach ($viewingPortfolios as $item)
                            <div class="rounded-kivu border border-kivu-border p-3">
                                @if ($item->file_path && $item->isImage())
                                    <img src="{{ Storage::disk('public')->url($item->file_path) }}" alt="{{ $item->title }}"
                                        class="mb-2 h-24 w-full rounded-kivu-sm object-cover" loading="lazy">
                                @endif
                                <p class="text-xs font-semibold text-kivu-text">{{ $item->title }}</p>
                                @if ($item->url)
                                    <a href="{{ $item->url }}" target="_blank" rel="noopener"
                                        class="kivu-focus mt-1 inline-flex items-center gap-1 rounded text-[11px] font-medium text-kivu-primary hover:underline">
                                        <x-icon name="external-link" :size="11" /> Tautan
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($viewingReviews->isNotEmpty())
                <div class="mt-4">
                    <p class="text-sm font-semibold text-kivu-text">Ulasan Terbaru</p>
                    <div class="mt-2 space-y-2">
                        @foreach ($viewingReviews as $review)
                            <div class="rounded-kivu-sm bg-kivu-surface-muted p-3">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-xs font-semibold text-kivu-text">{{ $review->reviewer->name ?? 'UMKM' }}</p>
                                    <x-ui.rating-stars :value="$review->rating" :size="12" />
                                </div>
                                @if ($review->comment)
                                    <p class="mt-1 text-xs text-kivu-text-secondary">{{ $review->comment }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mt-5 flex justify-end">
                <x-ui.button href="{{ route('talents.show', $viewingStudent->id) }}" variant="secondary" size="sm">
                    Lihat Profil Lengkap <x-icon name="arrow-right" :size="14" />
                </x-ui.button>
            </div>
        </x-ui.modal>
    @endif
</div>
