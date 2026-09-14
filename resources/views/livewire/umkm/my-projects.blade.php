<div class="space-y-8">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Proyek Saya</h1>
            <p class="text-sm text-gray-500">Kelola proyek yang telah Anda buat.</p>
        </div>
        <a href="{{ route('umkm.create-project') }}" wire:navigate class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
            <x-icon name="plus-circle" :size="18" /> Buat Proyek
        </a>
    </div>

    @foreach(['OPEN', 'IN_PROGRESS', 'SUBMITTED', 'COMPLETED', 'CANCELLED'] as $statusGroup)
        @if(isset($projects[$statusGroup]) && $projects[$statusGroup]->count() > 0)
            <div class="space-y-3">
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <x-ui.status-badge :value="$statusGroup" kind="project" />
                        <span class="text-sm font-semibold text-gray-500">({{ $projects[$statusGroup]->count() }})</span>
                    </h2>
                </div>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($projects[$statusGroup] as $proj)
                        @php
                            $projectDisputes = $disputes->get($proj->id, collect());
                            $myOpenDispute = $projectDisputes->firstWhere('reporter_id', auth()->id())?->status === 'OPEN';
                            $canDispute = in_array($proj->status, ['IN_PROGRESS', 'SUBMITTED', 'COMPLETED'], true) && !$myOpenDispute;
                        @endphp
                        <div class="flex flex-col rounded-2xl border border-gray-200 bg-white p-5">
                            <h3 class="line-clamp-2 text-base font-semibold text-gray-900">{{ $proj->title }}</h3>
                            <p class="mt-1 line-clamp-2 text-sm text-gray-600">{{ $proj->description }}</p>
                            @foreach($projectDisputes as $d)
                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                    @php
                                        $dStyle = $d->status === 'OPEN' ? 'bg-amber-50 text-amber-700' : ($d->status === 'CANCELLED' ? 'bg-gray-100 text-gray-500' : ($d->status === 'RESOLVED' ? 'bg-success-50 text-success-700' : 'bg-error-50 text-error-600'));
                                        $dLabel = match($d->status) { 'OPEN' => 'Sengketa Menunggu', 'RESOLVED' => 'Sengketa Diputuskan', 'CANCELLED' => 'Sengketa Dibatalkan', default => 'Sengketa Ditolak' };
                                    @endphp
                                    <p class="inline-flex w-fit items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $dStyle }}">
                                        {{ $dLabel }} @if($d->reporter_id === auth()->id()) (oleh Anda) @endif
                                    </p>
                                    @if($d->reporter_id === auth()->id() && $d->status === 'OPEN')
                                        <button wire:click="confirmCancelDisputeItem({{ $d->id }})" class="text-[11px] font-medium text-gray-400 hover:text-error-600">Batalkan</button>
                                    @endif
                                </div>
                            @endforeach
                            <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-3">
                                <span class="text-lg font-bold text-blue-600">Rp {{ number_format($proj->budget, 0, ',', '.') }}</span>
                                <x-ui.status-badge :value="$proj->status" kind="project" />
                            </div>
                            <div class="mt-4 flex flex-wrap gap-2">
                                <a href="{{ route('umkm.manage-applicants', $proj->id) }}" wire:navigate class="inline-flex items-center gap-1 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 transition hover:bg-gray-50">
                                    <x-icon name="users" :size="14" /> Pelamar ({{ $proj->applications_count }})
                                </a>
                                @if($statusGroup === 'SUBMITTED')
                                    <a href="{{ route('umkm.review-submission', $proj->id) }}" wire:navigate class="inline-flex items-center gap-1 rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-700">
                                        <x-icon name="circle-check" :size="14" /> Review Hasil
                                    </a>
                                @endif
                                @if($canDispute)
                                    <button wire:click="openDispute({{ $proj->id }})" class="inline-flex items-center gap-1 rounded-lg border border-amber-300 px-3 py-1.5 text-xs font-semibold text-amber-700 transition hover:bg-amber-50">
                                        <x-icon name="shield-alert" :size="14" /> Ajukan Sengketa
                                    </button>
                                @endif
                                @if($proj->status === 'OPEN')
                                    <a href="{{ route('umkm.edit-project', $proj->id) }}" wire:navigate class="inline-flex items-center gap-1 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 transition hover:bg-gray-50">
                                        <x-icon name="file-text" :size="14" /> Edit
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach

    @if($projects->isEmpty())
        <x-ui.empty-state icon="folder-kanban" title="Belum ada proyek" description="Buat proyek pertama Anda dan mulai terhubung dengan mahasiswa berbakat.">
            <a href="{{ route('umkm.create-project') }}" wire:navigate class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                <x-icon name="plus-circle" :size="16" /> Buat Proyek Pertama
            </a>
        </x-ui.empty-state>
    @endif

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

    @if($confirmingCancelDispute)
        <x-ui.confirm-modal
            title="Batalkan Sengketa"
            message="Sengketa ini akan dibatalkan dan tidak akan diproses lebih lanjut."
            confirm-label="Ya, Batalkan"
            confirm-method="cancelDisputeItem"
            cancel-method="closeCancelDisputeItem"
            tone="danger"
            loading-target="cancelDisputeItem"
        />
    @endif
</div>