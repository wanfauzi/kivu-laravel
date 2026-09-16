@php
    $tones = [
        'success' => 'bg-kivu-success-soft text-kivu-success',
        'warning' => 'bg-kivu-warning-soft text-kivu-warning',
        'danger' => 'bg-kivu-danger-soft text-kivu-danger',
        'info' => 'bg-kivu-info-soft text-kivu-info',
    ];
@endphp

<div class="relative" wire:poll.30s>
    <button type="button" wire:click="toggle"
        class="kivu-focus relative inline-flex h-10 w-10 items-center justify-center rounded-kivu-sm border border-kivu-border bg-kivu-surface text-kivu-text-secondary transition hover:bg-kivu-surface-muted hover:text-kivu-text"
        aria-label="Notifikasi">
        <x-icon name="bell" :size="18" />
        @if ($unreadCount > 0)
            <span class="absolute -top-1 -right-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-kivu-danger px-1 text-[10px] font-bold text-white">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    @if ($open)
        <div class="fixed inset-0 z-40" wire:click="toggle" aria-hidden="true"></div>

        <div class="kivu-card absolute right-0 z-50 mt-2 w-80 overflow-hidden shadow-theme-lg">
            <div class="flex items-center justify-between border-b border-kivu-border px-4 py-3">
                <p class="text-sm font-semibold text-kivu-text">Notifikasi</p>
                @if ($unreadCount > 0)
                    <button type="button" wire:click="markAllAsRead"
                        class="kivu-focus rounded text-xs font-semibold text-kivu-primary transition hover:text-kivu-primary-hover">
                        Tandai semua dibaca
                    </button>
                @endif
            </div>

            <div class="max-h-96 overflow-y-auto">
                @forelse ($notifications as $notification)
                    @php
                        $icon = $notification->data['icon'] ?? 'bell';
                        $tone = $notification->data['tone'] ?? 'info';
                    @endphp
                    <button type="button" wire:click="openNotification('{{ $notification->id }}')"
                        class="kivu-focus flex w-full items-start gap-3 border-b border-kivu-border px-4 py-3 text-left transition last:border-b-0 hover:bg-kivu-surface-muted {{ $notification->read_at ? '' : 'bg-kivu-primary-soft/40' }}">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full {{ $tones[$tone] ?? $tones['info'] }}">
                            <x-icon :name="$icon" :size="16" />
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-sm leading-snug text-kivu-text">{{ $notification->data['message'] ?? '' }}</span>
                            <span class="mt-0.5 block text-xs text-kivu-text-muted">{{ $notification->created_at->diffForHumans() }}</span>
                        </span>
                        @unless ($notification->read_at)
                            <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-kivu-primary"></span>
                        @endunless
                    </button>
                @empty
                    <div class="px-4 py-10 text-center">
                        <x-icon name="bell" :size="28" class="mx-auto text-kivu-text-muted/50" />
                        <p class="mt-2 text-sm text-kivu-text-muted">Belum ada notifikasi.</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endif
</div>
