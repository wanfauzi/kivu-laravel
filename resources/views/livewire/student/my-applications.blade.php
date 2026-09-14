<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Lamaran Saya</h1>
        <p class="text-sm text-gray-500">Daftar proyek yang Anda lamar beserta statusnya.</p>
    </div>

    <div class="space-y-4">
        @forelse($applications as $app)
            @php
                $submission = $app->project->submission;
                $projectDisputes = $disputes->get($app->project_id, collect());
                $myOpenDispute = $projectDisputes->firstWhere('reporter_id', auth()->id())?->status === 'OPEN';
                $canDispute = in_array($app->project->status, ['IN_PROGRESS', 'SUBMITTED', 'COMPLETED'], true) && !$myOpenDispute;
            @endphp
            <div class="flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0 space-y-1.5">
                    <div class="flex flex-wrap items-center gap-2">
                        <h3 class="text-base font-semibold text-gray-900">{{ $app->project->title }}</h3>
                        @if($submission)
                            <x-ui.status-badge :value="$submission->status" kind="submission" />
                        @endif
                    </div>
                    <p class="text-xs text-gray-500">UMKM: {{ $app->project->owner->name }} &middot; Budget: Rp {{ number_format($app->project->budget, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400">{{ $app->created_at->translatedFormat('d F Y · H:i') }}</p>
                    @if($app->message)
                        <p class="rounded-lg bg-gray-50 px-3 py-2 text-xs italic text-gray-600">"{{ $app->message }}"</p>
                    @endif
                    @if($app->status === 'REJECTED' && $app->rejection_note)
                        <p class="rounded-lg bg-error-50 px-3 py-2 text-xs text-error-600">Alasan ditolak: {{ $app->rejection_note }}</p>
                    @endif
                    @foreach($projectDisputes as $d)
                        @php
                            $sStyle = $d->status === 'OPEN' ? 'bg-amber-50 text-amber-700' : ($d->status === 'CANCELLED' ? 'bg-gray-100 text-gray-500' : ($d->status === 'RESOLVED' ? 'bg-success-50 text-success-700' : 'bg-error-50 text-error-600'));
                            $sl = match($d->status) { 'OPEN' => 'Sengketa Menunggu', 'RESOLVED' => 'Sengketa Diputuskan', 'CANCELLED' => 'Sengketa Dibatalkan', default => 'Sengketa Ditolak' };
                        @endphp
                        <div class="flex items-center gap-2">
                            <p class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $sStyle }}">
                                {{ $sl }} @if($d->reporter_id === auth()->id()) (oleh Anda) @endif
                            </p>
                            @if($d->reporter_id === auth()->id() && $d->status === 'OPEN')
                                <button wire:click="confirmCancelDispute({{ $d->id }})" class="text-[11px] font-medium text-gray-400 hover:text-error-600">Batalkan</button>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="flex shrink-0 flex-col items-end gap-2">
                    <x-ui.status-badge :value="$app->status" kind="application" />
                    @if($app->status === 'PENDING')
                        <button wire:click="confirmWithdraw({{ $app->id }})" class="inline-flex items-center gap-1 rounded-lg border border-gray-300 px-3.5 py-2 text-xs font-medium text-gray-500 transition hover:bg-gray-50 hover:text-gray-700">
                            <x-icon name="x" :size="14" /> Batalkan Lamaran
                        </button>
                    @endif
                    @if($app->status === 'ACCEPTED' && (!$submission || $submission->status === 'REVISION'))
                        <a href="{{ route('student.submit-work', $app->project_id) }}" wire:navigate class="inline-flex items-center gap-1 rounded-lg bg-blue-600 px-3.5 py-2 text-xs font-semibold text-white transition hover:bg-blue-700">
                            <x-icon name="upload" :size="14" /> {{ $submission?->status === 'REVISION' ? 'Kirim Revisi' : 'Kirim Hasil' }}
                        </a>
                    @endif
                    @if($canDispute)
                        <button wire:click="openDispute({{ $app->project_id }})" class="inline-flex items-center gap-1 rounded-lg border border-amber-300 px-3.5 py-2 text-xs font-semibold text-amber-700 transition hover:bg-amber-50">
                            <x-icon name="shield-alert" :size="14" /> Ajukan Sengketa
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <x-ui.empty-state icon="file-text" title="Belum ada lamaran" description="Jelajahi peluang proyek dan mulai lamar pekerjaan Anda.">
                <a href="{{ route('student.opportunities') }}" wire:navigate class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                    Jelajahi Peluang <x-icon name="arrow-right" :size="16" />
                </a>
            </x-ui.empty-state>
        @endforelse
    </div>

    @if($confirmingDispute)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" wire:click.self="cancelDispute">
            <div class="w-full max-w-md rounded-2xl border border-gray-200 bg-white p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Ajukan Sengketa</h3>
                    <button wire:click="cancelDispute" type="button" class="rounded-lg p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600" aria-label="Tutup">
                        <x-icon name="x" :size="20" />
                    </button>
                </div>
                <div class="mt-4 space-y-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Alasan</label>
                        <select wire:model="reason" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                            <option value="">Pilih alasan...</option>
                            @foreach(\App\Models\Dispute::REASONS as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('reason') <p class="mt-1 text-xs text-error-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea wire:model="description" rows="4" placeholder="Jelaskan masalah yang Anda alami..." class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"></textarea>
                        @error('description') <p class="mt-1 text-xs text-error-600">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="mt-5 flex justify-end gap-3">
                    <button wire:click="cancelDispute" type="button" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">Batal</button>
                    <button wire:click="submitDispute" class="rounded-lg bg-amber-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-700" wire:loading.attr="disabled">Kirim Sengketa</button>
                </div>
            </div>
        </div>
    @endif

    @if($confirmingWithdraw)
        <x-ui.confirm-modal
            title="Batalkan Lamaran"
            message="Lamaran ini akan dibatalkan. Anda dapat melamar ulang proyek ini nanti."
            confirm-label="Ya, Batalkan"
            confirm-method="withdrawApplication"
            cancel-method="cancelWithdraw"
            tone="danger"
            loading-target="withdrawApplication"
        />
    @endif

    @if($confirmingCancelDispute)
        <x-ui.confirm-modal
            title="Batalkan Sengketa"
            message="Sengketa ini akan dibatalkan dan tidak akan diproses lebih lanjut."
            confirm-label="Ya, Batalkan"
            confirm-method="cancelDisputeItem"
            cancel-method="cancelCancelDispute"
            tone="danger"
            loading-target="cancelDisputeItem"
        />
    @endif
</div>
