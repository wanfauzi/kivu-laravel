@if ($u->role !== 'admin')
    <div class="flex flex-wrap items-center gap-2">
        @if ($u->status === 'pending_ktm')
            @if ($u->ktm_path)
                <x-ui.button wire:click="confirmAction({{ $u->id }}, 'verifyKtm')" size="sm">
                    <x-icon name="check" :size="14" /> Verifikasi KTM
                </x-ui.button>
            @else
                <span class="inline-flex items-center gap-1.5 rounded-kivu-sm bg-kivu-surface-muted px-3 py-1.5 text-xs font-medium text-kivu-text-muted" title="Mahasiswa belum mengunggah KTM">
                    <x-icon name="clock" :size="14" /> Belum Upload KTM
                </span>
            @endif
            <x-ui.button wire:click="confirmAction({{ $u->id }}, 'suspend')" variant="secondary" size="sm">
                <x-icon name="x" :size="14" /> Suspend
            </x-ui.button>
        @elseif ($u->status === 'active')
            @if ($u->isVerifiedStudent())
                <x-ui.button wire:click="confirmAction({{ $u->id }}, 'revokeKtm')" variant="secondary" size="sm">
                    <x-icon name="rotate-ccw" :size="14" /> Batalkan Verifikasi
                </x-ui.button>
            @endif
            <x-ui.button wire:click="confirmAction({{ $u->id }}, 'suspend')" variant="secondary" size="sm">
                <x-icon name="x" :size="14" /> Suspend
            </x-ui.button>
        @elseif ($u->status === 'suspended')
            <x-ui.button wire:click="confirmAction({{ $u->id }}, 'activate')" variant="success" size="sm">
                <x-icon name="check" :size="14" /> Aktifkan
            </x-ui.button>
        @endif
    </div>
@else
    <span class="text-xs text-kivu-text-muted">—</span>
@endif