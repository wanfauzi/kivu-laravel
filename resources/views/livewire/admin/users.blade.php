@php
    $selectClass = 'h-11 rounded-kivu-sm border border-kivu-border bg-kivu-surface px-3 text-sm text-kivu-text transition focus:border-kivu-primary focus:outline-none focus:ring-2 focus:ring-kivu-primary/20';
@endphp

<div class="space-y-6">
    <x-ui.page-header title="Kelola Pengguna" subtitle="Lihat dan kelola semua pengguna platform." />

    <div class="kivu-card flex flex-col gap-3 p-4 sm:flex-row sm:items-center">
        <div class="relative flex-1">
            <x-icon name="search" :size="18" class="pointer-events-none absolute top-1/2 left-3.5 -translate-y-1/2 text-kivu-text-muted" />
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau email..."
                class="kivu-input h-11 pl-10 pr-4 text-sm" />
        </div>
        <select wire:model.live="role" class="{{ $selectClass }} min-w-36">
            <option value="">Semua Role</option>
            <option value="student">Student</option>
            <option value="umkm">UMKM</option>
            <option value="admin">Admin</option>
        </select>
        <select wire:model.live="status" class="{{ $selectClass }} min-w-40">
            <option value="">Semua Status</option>
            <option value="active">Aktif</option>
            <option value="pending_ktm">Pending KTM</option>
            <option value="suspended">Suspended</option>
        </select>
    </div>

    {{-- Desktop table --}}
    <div class="kivu-card hidden overflow-hidden md:block">
        <div class="custom-scrollbar max-w-full overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-kivu-border">
                        <th class="px-5 py-3 text-left"><p class="kivu-section-title">Pengguna</p></th>
                        <th class="px-5 py-3 text-left"><p class="kivu-section-title">Role</p></th>
                        <th class="px-5 py-3 text-left"><p class="kivu-section-title">Status</p></th>
                        <th class="px-5 py-3 text-left"><p class="kivu-section-title">Aksi</p></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $u)
                        <tr class="border-b border-kivu-border last:border-0">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <x-ui.avatar :name="$u->name" size="sm" />
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="truncate text-sm font-medium text-kivu-text">{{ $u->name }}</span>
                                            <x-ui.verified-badge :user="$u" compact />
                                        </div>
                                        <span class="block truncate text-xs text-kivu-text-muted">{{ $u->email }}</span>
                                        @if ($u->ktm_path && $u->role === 'student')
                                            <button wire:click="viewKtm({{ $u->id }})"
                                                class="kivu-focus mt-0.5 inline-flex items-center gap-1 rounded text-xs font-semibold text-kivu-primary hover:underline">
                                                <x-icon name="upload" :size="12" /> Lihat Bukti KTM
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <x-ui.pill :tone="$u->role === 'admin' ? 'info' : ($u->role === 'umkm' ? 'success' : 'primary')" class="capitalize">{{ $u->role }}</x-ui.pill>
                            </td>
                            <td class="px-5 py-4">
                                @php $sl = match ($u->status) { 'active' => 'Aktif', 'suspended' => 'Suspended', default => 'Pending KTM' }; @endphp
                                <x-ui.pill :tone="$u->status === 'active' ? 'success' : ($u->status === 'suspended' ? 'error' : 'warning')">{{ $sl }}</x-ui.pill>
                            </td>
                            <td class="px-5 py-4">
                                @include('livewire.admin.partials.user-actions', ['u' => $u])
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center">
                                <x-icon name="users" :size="48" stroke="1.25" class="mx-auto text-kivu-text-muted/40" />
                                <p class="mt-3 text-sm font-medium text-kivu-text-muted">Tidak ada pengguna ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile cards --}}
    <div class="space-y-3 md:hidden">
        @forelse ($users as $u)
            <div class="kivu-card p-4">
                <div class="flex items-center gap-3">
                    <x-ui.avatar :name="$u->name" />
                    <div class="min-w-0 flex-1">
                        <p class="flex items-center gap-1.5 truncate text-sm font-semibold text-kivu-text">
                            {{ $u->name }} <x-ui.verified-badge :user="$u" compact />
                        </p>
                        <p class="truncate text-xs text-kivu-text-muted">{{ $u->email }}</p>
                        @if ($u->ktm_path && $u->role === 'student')
                            <button wire:click="viewKtm({{ $u->id }})"
                                class="kivu-focus mt-0.5 inline-flex items-center gap-1 rounded text-xs font-semibold text-kivu-primary hover:underline">
                                <x-icon name="upload" :size="12" /> Lihat Bukti KTM
                            </button>
                        @endif
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <x-ui.pill :tone="$u->role === 'admin' ? 'info' : ($u->role === 'umkm' ? 'success' : 'primary')" class="capitalize">{{ $u->role }}</x-ui.pill>
                        <x-ui.pill :tone="$u->status === 'active' ? 'success' : ($u->status === 'suspended' ? 'error' : 'warning')">{{ ucfirst(str_replace('_', ' ', $u->status)) }}</x-ui.pill>
                    </div>
                </div>
                <div class="mt-3 flex flex-wrap gap-2 border-t border-kivu-border pt-3">
                    @include('livewire.admin.partials.user-actions', ['u' => $u])
                </div>
            </div>
        @empty
            <x-ui.empty-state icon="users" title="Tidak ada pengguna ditemukan" description="Coba ubah kata kunci atau filter." />
        @endforelse
    </div>

    <div>
        {{ $users->links() }}
    </div>

    @if ($confirming)
        @php
            $msg = match ($pendingAction) {
                'verifyKtm' => 'KTM pengguna ini akan diverifikasi dan statusnya menjadi aktif.',
                'revokeKtm' => 'Verifikasi KTM akan dibatalkan. Pengguna kembali berstatus menunggu verifikasi dan lencana terverifikasi dilepas.',
                'activate' => 'Pengguna akan diaktifkan kembali dan dapat mengakses platform.',
                default => 'Pengguna akan di-suspend. Akun tidak dapat login sampai diaktifkan kembali.',
            };
            $lbl = match ($pendingAction) {
                'verifyKtm' => 'Verifikasi',
                'revokeKtm' => 'Batalkan Verifikasi',
                'activate' => 'Aktifkan',
                default => 'Suspend',
            };
            $tone = in_array($pendingAction, ['suspend', 'revokeKtm'], true) ? 'danger' : 'success';
        @endphp
        <x-ui.confirm-modal
            title="Konfirmasi {{ $lbl }}"
            :message="$msg"
            :confirm-label="$lbl"
            confirm-method="runAction"
            cancel-method="cancelAction"
            :tone="$tone"
            loading-target="runAction" />
    @endif

    @if ($viewingKtmId)
        <x-ui.modal title="Bukti KTM" close-method="closeViewKtm" size="max-w-xl">
            @php
                $ktmUser = \App\Models\User::find($viewingKtmId);
                $isPdf = $ktmUser && str_ends_with(strtolower((string) $ktmUser->ktm_path), '.pdf');
            @endphp
            <div class="flex aspect-video items-center justify-center overflow-hidden rounded-kivu-sm border border-kivu-border bg-kivu-surface-muted">
                @if ($isPdf)
                    <div class="flex flex-col items-center gap-2 p-6 text-center">
                        <x-icon name="file-text" :size="40" class="text-kivu-primary" />
                        <p class="text-sm text-kivu-text-muted">File KTM berupa PDF.</p>
                        <x-ui.button href="{{ route('ktm.show', $viewingKtmId) }}" target="_blank" size="sm">
                            Buka PDF <x-icon name="external-link" :size="14" />
                        </x-ui.button>
                    </div>
                @else
                    <img src="{{ route('ktm.show', $viewingKtmId) }}" alt="Bukti KTM" class="h-full w-full object-contain">
                @endif
            </div>
            <div class="mt-4 flex justify-end">
                <x-ui.button href="{{ route('ktm.show', $viewingKtmId) }}" target="_blank" variant="secondary" size="sm">
                    Buka di Tab Baru <x-icon name="external-link" :size="14" />
                </x-ui.button>
            </div>
        </x-ui.modal>
    @endif
</div>