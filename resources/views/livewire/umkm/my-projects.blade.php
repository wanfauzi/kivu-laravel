<div class="space-y-6">
    <x-ui.page-header title="Proyek Saya" subtitle="Kelola proyek, pelamar, dan hasil kerja dari mahasiswa.">
        <x-slot:actions>
            <x-ui.button href="{{ route('umkm.create-project') }}">
                <x-icon name="plus-circle" :size="18" /> Buat Proyek
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <x-ui.tabs :items="$tabs" :active="$statusFilter" method="setStatus" />

    @if ($projects->isEmpty())
        <x-ui.empty-state icon="folder-kanban" title="Belum ada proyek"
            :description="$statusFilter !== '' ? 'Tidak ada proyek dengan status ini.' : 'Buat proyek pertama Anda dan mulai terhubung dengan mahasiswa berbakat.'">
            @if ($statusFilter === '')
                <x-ui.button href="{{ route('umkm.create-project') }}" size="sm">
                    <x-icon name="plus-circle" :size="16" /> Buat Proyek Pertama
                </x-ui.button>
            @endif
        </x-ui.empty-state>
    @else
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($projects as $proj)
                @php
                    $projectDisputes = $disputes->get($proj->id, collect());
                    $myOpenDispute = $projectDisputes->firstWhere('reporter_id', auth()->id())?->status === 'OPEN';
                    $canDispute = in_array($proj->status, ['IN_PROGRESS', 'SUBMITTED', 'COMPLETED'], true) && ! $myOpenDispute;
                    $hasWinner = $proj->applications->contains(fn ($a) => $a->status === 'ACCEPTED');
                    $reviewRoute = $hasWinner
                        ? route('umkm.review-submission', $proj->id)
                        : route('umkm.select-winner', $proj->id);
                    $primary = $proj->status === 'SUBMITTED'
                        ? $reviewRoute
                        : ($proj->status === 'DRAFT'
                            ? route('umkm.project-fund', $proj->id)
                            : route('umkm.manage-applicants', $proj->id));
                @endphp

                <div class="kivu-card kivu-card-hover relative flex flex-col p-5">
                    <div class="flex flex-wrap items-center gap-2">
                        <x-ui.status-badge :value="$proj->status" kind="project" :dot="true" />
                        @foreach ($projectDisputes as $d)
                            <x-ui.status-badge :value="$d->status" kind="dispute" />
                        @endforeach
                    </div>

                    <h3 class="mt-2.5 line-clamp-2 text-base font-semibold text-kivu-text">
                        <a href="{{ $primary }}" wire:navigate class="kivu-focus rounded transition after:absolute after:inset-0 after:content-[''] hover:text-kivu-primary">
                            {{ $proj->title }}
                        </a>
                    </h3>

                    <p class="mt-1.5 line-clamp-2 flex-1 text-sm leading-relaxed text-kivu-text-secondary">{{ $proj->description }}</p>

                    @if ($proj->skills->isNotEmpty())
                        <div class="mt-3 flex flex-wrap gap-1.5">
                            @foreach ($proj->skills->take(3) as $skill)
                                <span class="rounded-full bg-kivu-surface-muted px-2 py-0.5 text-[11px] font-medium text-kivu-text-secondary">{{ $skill->name }}</span>
                            @endforeach
                        </div>
                    @endif

                    @if ($proj->category)
                        <p class="mt-3 inline-flex items-center gap-1.5 text-xs text-kivu-text-muted">
                            <x-icon :name="$proj->category->icon ?: 'tag'" :size="13" /> {{ $proj->category->name }}
                        </p>
                    @endif

                    <div class="mt-4 flex items-end justify-between gap-3 border-t border-kivu-border pt-4">
                        <div>
                            <p class="text-xs text-kivu-text-muted">Budget</p>
                            <p class="text-lg font-bold text-kivu-primary">Rp {{ number_format($proj->budget, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-right">
                            @if ($proj->due_date)
                                <p class="inline-flex items-center gap-1 text-xs text-kivu-text-muted">
                                    <x-icon name="calendar" :size="13" /> {{ $proj->due_date->translatedFormat('d M') }}
                                </p>
                            @endif
                            <p class="mt-1 inline-flex items-center gap-1 text-xs text-kivu-text-muted">
                                <x-icon name="users" :size="13" /> {{ $proj->applications_count }} pelamar
                            </p>
                        </div>
                    </div>

                    <div class="relative z-10 mt-4 flex flex-wrap gap-2">
                        @if ($proj->status === 'SUBMITTED')
                            <x-ui.button href="{{ $reviewRoute }}" size="sm">
                                <x-icon name="circle-check" :size="14" /> {{ $hasWinner ? 'Tinjau Pemenang' : 'Pilih Pemenang ('.$proj->submissions_count.')' }}
                            </x-ui.button>
                        @elseif ($proj->status === 'DRAFT')
                            <x-ui.button href="{{ route('umkm.project-fund', $proj->id) }}" size="sm">
                                <x-icon name="qrcode" :size="14" /> Lanjut Pembayaran
                            </x-ui.button>
                        @else
                            <x-ui.button href="{{ route('umkm.manage-applicants', $proj->id) }}" size="sm" variant="secondary">
                                <x-icon name="users" :size="14" /> Pelamar ({{ $proj->applications_count }})
                            </x-ui.button>
                        @endif

                        @if ($proj->status === 'OPEN')
                            <x-ui.button href="{{ route('umkm.edit-project', $proj->id) }}" size="sm" variant="ghost">
                                <x-icon name="pencil" :size="14" /> Edit
                            </x-ui.button>
                        @endif

                        @if ($canDispute)
                            <x-ui.button wire:click="openDispute({{ $proj->id }})" size="sm" variant="ghost">
                                <x-icon name="shield-alert" :size="14" /> Sengketa
                            </x-ui.button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if ($confirmingDispute)
        <x-ui.confirm-modal
            title="Ajukan Sengketa"
            confirm-label="Kirim Sengketa"
            confirm-method="submitDispute"
            cancel-method="cancelDispute"
            tone="primary"
            loading-target="submitDispute">
            <div class="space-y-4">
                <div>
                    <label for="umkm-dispute-reason" class="mb-1.5 block text-sm font-medium text-kivu-text">Alasan</label>
                    <select id="umkm-dispute-reason" wire:model="reason" class="kivu-input px-3 py-2.5 text-sm">
                        <option value="">Pilih alasan...</option>
                        @foreach (\App\Models\Dispute::REASONS as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('reason')
                        <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="umkm-dispute-description" class="mb-1.5 block text-sm font-medium text-kivu-text">Deskripsi</label>
                    <textarea id="umkm-dispute-description" wire:model="description" rows="4" placeholder="Jelaskan masalah yang Anda alami..."
                        class="kivu-input px-3 py-2.5 text-sm"></textarea>
                    @error('description')
                        <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </x-ui.confirm-modal>
    @endif

    @if ($confirmingCancelDispute)
        <x-ui.confirm-modal
            title="Batalkan Sengketa"
            message="Sengketa ini akan dibatalkan dan tidak akan diproses lebih lanjut."
            confirm-label="Ya, Batalkan"
            confirm-method="cancelDisputeItem"
            cancel-method="closeCancelDisputeItem"
            tone="danger"
            loading-target="cancelDisputeItem" />
    @endif
</div>
