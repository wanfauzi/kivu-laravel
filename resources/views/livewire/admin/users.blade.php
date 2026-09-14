<div class="space-y-6">
    <div>
        <h1 class="text-title-sm font-bold text-gray-800">Kelola Pengguna</h1>
        <p class="mt-1 text-sm text-gray-500">Lihat dan kelola semua pengguna platform.</p>
    </div>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
        <div class="relative flex-1">
            <x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau email..." class="h-11 w-full rounded-lg border border-gray-300 bg-white pl-10 pr-4 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10">
        </div>
        <select wire:model.live="role" class="h-11 min-w-[160px] rounded-lg border border-gray-300 bg-white px-4 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10">
            <option value="">Semua Role</option>
            <option value="student">Student</option>
            <option value="umkm">UMKM</option>
            <option value="admin">Admin</option>
        </select>
        <select wire:model.live="status" class="h-11 min-w-[180px] rounded-lg border border-gray-300 bg-white px-4 text-sm text-gray-800 outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10">
            <option value="">Semua Status</option>
            <option value="active">Aktif</option>
            <option value="pending_ktm">Pending KTM</option>
            <option value="suspended">Suspended</option>
        </select>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
        <div class="custom-scrollbar max-w-full overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-5 py-3 text-left sm:px-6"><p class="text-theme-xs font-medium text-gray-500">Pengguna</p></th>
                        <th class="px-5 py-3 text-left sm:px-6"><p class="text-theme-xs font-medium text-gray-500">Role</p></th>
                        <th class="px-5 py-3 text-left sm:px-6"><p class="text-theme-xs font-medium text-gray-500">Status</p></th>
                        <th class="px-5 py-3 text-left sm:px-6"><p class="text-theme-xs font-medium text-gray-500">Aksi</p></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                    <tr class="border-b border-gray-100 last:border-0">
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center gap-3">
                                <x-ui.avatar :name="$u->name" />
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="text-theme-sm truncate font-medium text-gray-800">{{ $u->name }}</span>
                                        <x-ui.verified-badge :user="$u" class="shrink-0" />
                                    </div>
                                    <span class="text-theme-xs block truncate text-gray-500">{{ $u->email }}</span>
                                    @if($u->ktm_path && $u->role === 'student')
                                        <button wire:click="viewKtm({{ $u->id }})" class="text-theme-xs mt-0.5 inline-flex items-center gap-1 font-semibold text-brand-600 hover:text-brand-700">
                                            <x-icon name="upload" :size="12" /> Lihat Bukti KTM
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            @php $rv = $u->role === 'admin' ? 'info' : ($u->role === 'umkm' ? 'success' : 'brand'); @endphp
                            <x-ui.pill :tone="$rv" class="capitalize">{{ $u->role }}</x-ui.pill>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            @php
                                $sv = match($u->status) { 'active' => 'success', 'suspended' => 'error', default => 'warning' };
                                $sl = match($u->status) { 'active' => 'Aktif', 'suspended' => 'Suspended', default => 'Pending KTM' };
                            @endphp
                            <x-ui.pill :tone="$sv">{{ $sl }}</x-ui.pill>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            @if($u->role !== 'admin')
                                <div class="flex flex-wrap items-center gap-2">
                                    @if($u->status === 'pending_ktm')
                                        @if($u->ktm_path)
                                            <button wire:click="confirmAction({{ $u->id }}, 'verifyKtm')" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-3 py-1.5 text-theme-xs font-semibold text-white transition hover:bg-brand-600">
                                                <x-icon name="check" :size="14" /> Verifikasi KTM
                                            </button>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-lg bg-gray-100 px-3 py-1.5 text-theme-xs font-medium text-gray-400" title="Mahasiswa belum mengunggah KTM">
                                                <x-icon name="clock" :size="14" /> Belum Upload KTM
                                            </span>
                                        @endif
                                        <button wire:click="confirmAction({{ $u->id }}, 'suspend')" class="inline-flex items-center gap-1.5 rounded-lg border border-error-200 px-3 py-1.5 text-theme-xs font-medium text-error-600 transition hover:bg-error-50">
                                            <x-icon name="x" :size="14" /> Suspend
                                        </button>
                                    @elseif($u->status === 'active')
                                        @if($u->isVerifiedStudent())
                                            <button wire:click="confirmAction({{ $u->id }}, 'revokeKtm')" class="inline-flex items-center gap-1.5 rounded-lg border border-warning-200 px-3 py-1.5 text-theme-xs font-medium text-warning-600 transition hover:bg-warning-50">
                                                <x-icon name="rotate-ccw" :size="14" /> Batalkan Verifikasi
                                            </button>
                                        @endif
                                        <button wire:click="confirmAction({{ $u->id }}, 'suspend')" class="inline-flex items-center gap-1.5 rounded-lg border border-error-200 px-3 py-1.5 text-theme-xs font-medium text-error-600 transition hover:bg-error-50">
                                            <x-icon name="x" :size="14" /> Suspend
                                        </button>
                                    @elseif($u->status === 'suspended')
                                        <button wire:click="confirmAction({{ $u->id }}, 'activate')" class="inline-flex items-center gap-1.5 rounded-lg bg-success-500 px-3 py-1.5 text-theme-xs font-semibold text-white transition hover:bg-success-600">
                                            <x-icon name="check" :size="14" /> Aktifkan
                                        </button>
                                    @endif
                                </div>
                            @else
                                <span class="text-theme-xs text-gray-400">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-12 text-center sm:px-6">
                            <x-icon name="users" :size="48" stroke="1.25" class="mx-auto text-gray-300" />
                            <p class="mt-3 text-sm font-medium text-gray-500">Tidak ada pengguna ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-2">
        {{ $users->links() }}
    </div>

    @if($confirming)
        @php
            $msg = match($pendingAction) {
                'verifyKtm' => 'KTM pengguna ini akan diverifikasi dan statusnya menjadi aktif.',
                'revokeKtm' => 'Verifikasi KTM akan dibatalkan. Pengguna kembali berstatus menunggu verifikasi dan lencana terverifikasi dilepas.',
                'activate' => 'Pengguna akan diaktifkan kembali dan dapat mengakses platform.',
                default => 'Pengguna akan di-suspend. Akun tidak dapat login sampai diaktifkan kembali. Tindakan ini dapat dibatalkan.',
            };
            $lbl = match($pendingAction) {
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
            loading-target="runAction"
        >
            <p class="rounded-lg bg-gray-50 px-3 py-2 text-xs text-gray-600">Aksi ini terekam oleh policy backend dan tidak dapat dijalankan untuk akun admin.</p>
        </x-ui.confirm-modal>
    @endif

    @if($viewingKtmId)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" wire:click.self="closeViewKtm">
            <div class="w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Bukti KTM</h3>
                    <button wire:click="closeViewKtm" type="button" class="rounded-lg p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600" aria-label="Tutup">
                        <x-icon name="x" :size="20" />
                    </button>
                </div>
                <div class="mt-4 flex aspect-video items-center justify-center overflow-hidden rounded-xl border border-gray-200 bg-gray-50">
                    <img src="{{ route('ktm.show', $viewingKtmId) }}" alt="Bukti KTM" class="h-full w-full object-contain">
                </div>
                <div class="mt-4 flex justify-end">
                    <a href="{{ route('ktm.show', $viewingKtmId) }}" target="_blank" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">Buka di Tab Baru</a>
                </div>
            </div>
        </div>
    @endif
</div>