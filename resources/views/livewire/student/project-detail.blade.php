@php
    $canApply = ! $hasApplied && $project->status === 'OPEN';
@endphp

<div class="space-y-6">
    <x-ui.page-header :breadcrumbs="[
        ['label' => 'Beranda', 'url' => url('/')],
        ['label' => 'Peluang', 'url' => route('student.opportunities')],
        ['label' => \Illuminate\Support\Str::limit($project->title, 32)],
    ]" :back="route('student.opportunities')" back-label="Kembali ke Peluang" :title="$project->title">
        <x-slot:actions>
            <x-ui.status-badge :value="$project->status" kind="project" :dot="true" />
        </x-slot:actions>
    </x-ui.page-header>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Main --}}
        <div class="space-y-6 lg:col-span-2">
            <div class="kivu-card p-6">
                <h2 class="text-sm font-semibold text-kivu-text-muted">Deskripsi Proyek</h2>
                <p class="mt-2 text-sm leading-relaxed whitespace-pre-line text-kivu-text-secondary">{{ $project->description }}</p>

                <dl class="mt-6 grid grid-cols-2 gap-4 border-t border-kivu-border pt-5 sm:grid-cols-4">
                    <div>
                        <dt class="text-xs text-kivu-text-muted">Budget</dt>
                        @if ($project->hasBudgetRange())
                            <dd class="mt-0.5 text-base font-bold text-kivu-primary">Rp {{ number_format($project->min_budget, 0, ',', '.') }} &ndash; {{ number_format($project->max_budget, 0, ',', '.') }}</dd>
                        @else
                            <dd class="mt-0.5 text-base font-bold text-kivu-primary">Rp {{ number_format($project->budget, 0, ',', '.') }}</dd>
                        @endif
                    </div>
                    <div>
                        <dt class="text-xs text-kivu-text-muted">Deadline</dt>
                        <dd class="mt-0.5 text-sm font-semibold text-kivu-text">
                            {{ $project->due_date ? $project->due_date->translatedFormat('d M Y') : 'Fleksibel' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-kivu-text-muted">Kategori</dt>
                        <dd class="mt-0.5 text-sm font-semibold text-kivu-text">{{ $project->category->name ?? 'Umum' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-kivu-text-muted">Dibuka</dt>
                        <dd class="mt-0.5 text-sm font-semibold text-kivu-text">{{ $project->created_at->translatedFormat('d M Y') }}</dd>
                    </div>
                </dl>

                @if ($project->skills->isNotEmpty())
                    <div class="mt-5 border-t border-kivu-border pt-5">
                        <p class="text-xs text-kivu-text-muted">Keahlian dibutuhkan</p>
                        <div class="mt-2 flex flex-wrap gap-1.5">
                            @foreach ($project->skills as $skill)
                                <span class="rounded-full bg-kivu-primary-soft px-2.5 py-1 text-xs font-medium text-kivu-primary">{{ $skill->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="kivu-card p-6">
                <h2 class="text-sm font-semibold text-kivu-text-muted">Alur Proyek</h2>
                <div class="mt-4">
                    <x-ui.timeline :steps="$timeline" />
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6 lg:col-span-1">
            <div class="kivu-card kivu-card-hover space-y-4 p-6 lg:sticky lg:top-24">
                <div>
                    <p class="text-xs text-kivu-text-muted">Budget Proyek</p>
                    @if ($project->hasBudgetRange())
                        <p class="text-2xl font-extrabold text-kivu-primary">Rp {{ number_format($project->min_budget, 0, ',', '.') }} &ndash; {{ number_format($project->max_budget, 0, ',', '.') }}</p>
                    @else
                        <p class="text-3xl font-extrabold text-kivu-primary">Rp {{ number_format($project->budget, 0, ',', '.') }}</p>
                    @endif
                </div>

                @if ($project->due_date)
                    <div class="flex items-center gap-2 text-sm text-kivu-text-secondary">
                        <x-icon name="calendar" :size="16" class="text-kivu-text-muted" />
                        Deadline {{ $project->due_date->diffForHumans() }}
                    </div>
                @endif

                <div class="border-t border-kivu-border pt-4">
                    @if ($hasApplied)
                        <div class="flex items-center gap-2 rounded-kivu-sm border border-kivu-success/25 bg-kivu-success-soft px-3.5 py-3 text-sm font-medium text-kivu-success">
                            <x-icon name="circle-check" :size="17" class="shrink-0" />
                            Anda sudah melamar proyek ini.
                        </div>
                        <x-ui.button href="{{ route('student.my-applications') }}" variant="secondary" class="mt-3 w-full">
                            Lihat Status Lamaran
                        </x-ui.button>
                        <x-ui.button href="{{ route('student.inbox', ['project' => $project->id]) }}" variant="secondary" class="mt-2 w-full">
                            <x-icon name="message-square" :size="16" /> Hubungi UMKM
                        </x-ui.button>
                    @elseif ($canApply)
                        <x-ui.button wire:click="openModal" size="lg" class="w-full">
                            <x-icon name="file-text" :size="18" /> Lamar Sekarang
                        </x-ui.button>
                        <x-ui.button href="{{ route('student.inbox', ['project' => $project->id]) }}" variant="secondary" class="mt-2 w-full">
                            <x-icon name="message-square" :size="16" /> Hubungi UMKM
                        </x-ui.button>
                    @else
                        <p class="rounded-kivu-sm bg-kivu-surface-muted px-3.5 py-3 text-sm text-kivu-text-muted">
                            Proyek ini sudah tidak tersedia untuk dilamar.
                        </p>
                        <x-ui.button href="{{ route('student.inbox', ['project' => $project->id]) }}" variant="secondary" class="mt-3 w-full">
                            <x-icon name="message-square" :size="16" /> Hubungi UMKM
                        </x-ui.button>
                    @endif
                </div>

                <p class="text-xs leading-relaxed text-kivu-text-muted">
                    Pastikan deskripsi dan budget sesuai sebelum melamar. Lamaran dapat dibatalkan selama masih menunggu.
                </p>
            </div>

            <div class="kivu-card p-6">
                <p class="text-xs text-kivu-text-muted">Diposting oleh</p>
                <div class="mt-3 flex items-center gap-3">
                    <x-ui.avatar :name="$project->owner->name" size="lg" />
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-kivu-text">{{ $project->owner->name }}</p>
                        <p class="text-xs text-kivu-text-muted">Mitra UMKM KIVU</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($showModal)
        <x-ui.confirm-modal
            title="Konfirmasi Lamaran"
            :message="'Anda akan melamar proyek: '.$project->title"
            confirm-label="Kirim Lamaran"
            confirm-method="apply"
            cancel-method="closeModal"
            tone="primary"
            loading-target="apply">
            <div class="space-y-4">
                <div>
                    <label for="apply-bid" class="mb-1.5 block text-sm font-medium text-kivu-text">Penawaran Harga (Rp) <span class="text-kivu-danger">*</span></label>
                    <input id="apply-bid" type="number" wire:model="bid_amount" min="{{ $project->hasBudgetRange() ? $project->min_budget : 10000 }}" max="{{ $project->hasBudgetRange() ? $project->max_budget : $project->budget }}" step="1000" required
                        placeholder="{{ $project->hasBudgetRange() ? number_format($project->min_budget, 0, ',', '.') : number_format($project->budget, 0, ',', '.') }}"
                        class="kivu-input px-3 py-2.5 text-sm" />
                    <p class="mt-1 text-xs text-kivu-text-muted">
                        @if ($project->hasBudgetRange())
                            Wajib dalam rentang UMKM: Rp {{ number_format($project->min_budget, 0, ',', '.') }} &ndash; {{ number_format($project->max_budget, 0, ',', '.') }}.
                        @else
                            Wajib &le; budget UMKM: Rp {{ number_format($project->budget, 0, ',', '.') }}.
                        @endif
                    </p>
                    @error('bid_amount')
                        <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="apply-file" class="mb-1.5 block text-sm font-medium text-kivu-text">Berkas Lamaran <span class="text-kivu-danger">*</span></label>
                    <input id="apply-file" type="file" wire:model="lamaranFile" required
                        class="kivu-input file:mr-3 file:rounded-kivu-sm file:border-0 file:bg-kivu-primary-soft file:px-3 file:py-2 file:text-sm file:font-semibold file:text-kivu-primary px-3 py-2 text-sm" />
                    @error('lamaranFile')
                        <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-kivu-text-muted">Wajib — maks 10 MB pdf, doc, docx, zip, png, jpg.</p>
                    <div wire:loading wire:target="lamaranFile" class="mt-2 flex items-center gap-2 text-xs text-kivu-text-muted">
                        <x-icon name="rotate-ccw" :size="14" class="animate-spin" /> Mengunggah...
                    </div>
                </div>

                <div>
                    <label for="apply-message" class="mb-1.5 block text-sm font-medium text-kivu-text">Pesan untuk UMKM (opsional)</label>
                    <textarea id="apply-message" wire:model="message" rows="3" placeholder="Ceritakan singkat pengalaman atau ketertarikan Anda..."
                        class="kivu-input px-3 py-2.5 text-sm"></textarea>
                    @error('message')
                        <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </x-ui.confirm-modal>
    @endif
</div>
