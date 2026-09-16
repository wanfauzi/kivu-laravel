<div class="space-y-6">
    <x-ui.page-header title="Lamaran Saya" subtitle="Daftar proyek yang Anda lamar beserta statusnya." />

    <div class="space-y-4">
        @forelse ($applications as $app)
            @php
                $mySubmission = \App\Models\Submission::where('project_id', $app->project_id)
                    ->where('student_id', $app->student_id)
                    ->first();
                $projectDisputes = $disputes->get($app->project_id, collect());
                $myOpenDispute = $projectDisputes->firstWhere('reporter_id', auth()->id())?->status === 'OPEN';
                $canDispute = in_array($app->project->status, ['IN_PROGRESS', 'SUBMITTED', 'COMPLETED'], true) && ! $myOpenDispute;
            @endphp

            <div class="kivu-card flex flex-col gap-4 p-5 sm:flex-row sm:items-start sm:justify-between">
                <div class="min-w-0 space-y-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <h3 class="text-base font-semibold text-kivu-text">{{ $app->project->title }}</h3>
                        <x-ui.status-badge :value="$app->status" kind="application" :dot="true" />
                        @if ($mySubmission)
                            <x-ui.status-badge :value="$mySubmission->status" kind="submission" />
                        @endif
                    </div>

                    <p class="text-xs text-kivu-text-muted">
                        UMKM: {{ $app->project->owner->name }} &middot; Budget: Rp {{ number_format($app->project->budget, 0, ',', '.') }} &middot; {{ $app->created_at->translatedFormat('d F Y · H:i') }}
                    </p>

                    @if ($app->message)
                        <p class="rounded-kivu-sm bg-kivu-surface-muted px-3 py-2 text-xs italic text-kivu-text-secondary">"{{ $app->message }}"</p>
                    @endif

                    @if ($app->status === 'REJECTED' && $app->rejection_note)
                        <p class="rounded-kivu-sm bg-kivu-danger-soft px-3 py-2 text-xs text-kivu-danger">Alasan ditolak: {{ $app->rejection_note }}</p>
                    @endif

                    @foreach ($projectDisputes as $d)
                        <div class="flex flex-wrap items-center gap-2">
                            <x-ui.status-badge :value="$d->status" kind="dispute" />
                            @if ($d->reporter_id === auth()->id())
                                <span class="text-[11px] text-kivu-text-muted">(diajukan oleh Anda)</span>
                            @endif
                            @if ($d->reporter_id === auth()->id() && $d->status === 'OPEN')
                                <button wire:click="confirmCancelDispute({{ $d->id }})"
                                    class="kivu-focus rounded text-[11px] font-medium text-kivu-text-muted transition hover:text-kivu-danger">
                                    Batalkan
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="flex shrink-0 flex-wrap items-center gap-2 sm:flex-col sm:items-end">
                    @if (in_array($app->project->status, ['OPEN', 'SUBMITTED'], true)
                            && in_array($app->status, ['PENDING', 'ACCEPTED'], true)
                            && (! $mySubmission || $mySubmission->status === 'REVISION'))
                        <x-ui.button href="{{ route('student.submit-work', $app->project_id) }}" size="sm">
                            <x-icon name="upload" :size="14" /> {{ $mySubmission?->status === 'REVISION' ? 'Kirim Revisi' : 'Kirim Hasil' }}
                        </x-ui.button>
                    @endif

                    @if ($app->status === 'PENDING')
                        <x-ui.button wire:click="confirmWithdraw({{ $app->id }})" variant="secondary" size="sm">
                            <x-icon name="x" :size="14" /> Batalkan Lamaran
                        </x-ui.button>
                    @endif

                    <x-ui.button href="{{ route('student.inbox', ['project' => $app->project_id]) }}" variant="secondary" size="sm">
                        <x-icon name="message-square" :size="14" /> Pesan UMKM
                    </x-ui.button>

                    @if ($canDispute)
                        <x-ui.button wire:click="openDispute({{ $app->project_id }})" variant="secondary" size="sm">
                            <x-icon name="shield-alert" :size="14" /> Ajukan Sengketa
                        </x-ui.button>
                    @endif
                </div>
            </div>
        @empty
            <x-ui.empty-state icon="file-text" title="Belum ada lamaran" description="Jelajahi peluang proyek dan mulai lamar pekerjaan Anda.">
                <x-ui.button href="{{ route('student.opportunities') }}" size="sm">
                    Jelajahi Peluang <x-icon name="arrow-right" :size="16" />
                </x-ui.button>
            </x-ui.empty-state>
        @endforelse
    </div>

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
                    <label for="dispute-reason" class="mb-1.5 block text-sm font-medium text-kivu-text">Alasan</label>
                    <select id="dispute-reason" wire:model="reason" class="kivu-input px-3 py-2.5 text-sm">
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
                    <label for="dispute-description" class="mb-1.5 block text-sm font-medium text-kivu-text">Deskripsi</label>
                    <textarea id="dispute-description" wire:model="description" rows="4" placeholder="Jelaskan masalah yang Anda alami..."
                        class="kivu-input px-3 py-2.5 text-sm"></textarea>
                    @error('description')
                        <p class="mt-1.5 text-xs font-medium text-kivu-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </x-ui.confirm-modal>
    @endif

    @if ($confirmingWithdraw)
        <x-ui.confirm-modal
            title="Batalkan Lamaran"
            message="Lamaran ini akan dibatalkan. Anda dapat melamar ulang proyek ini nanti."
            confirm-label="Ya, Batalkan"
            confirm-method="withdrawApplication"
            cancel-method="cancelWithdraw"
            tone="danger"
            loading-target="withdrawApplication" />
    @endif

    @if ($confirmingCancelDispute)
        <x-ui.confirm-modal
            title="Batalkan Sengketa"
            message="Sengketa ini akan dibatalkan dan tidak akan diproses lebih lanjut."
            confirm-label="Ya, Batalkan"
            confirm-method="cancelDisputeItem"
            cancel-method="cancelCancelDispute"
            tone="danger"
            loading-target="cancelDisputeItem" />
    @endif
</div>
