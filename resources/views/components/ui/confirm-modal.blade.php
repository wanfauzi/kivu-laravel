@props([
    'title',
    'message' => null,
    'confirmLabel' => 'Konfirmasi',
    'confirmMethod',
    'cancelMethod',
    'tone' => 'danger',
    'loadingTarget' => null,
])

@php
    $tones = [
        'danger' => 'bg-kivu-danger text-white hover:brightness-95',
        'error' => 'bg-kivu-danger text-white hover:brightness-95',
        'success' => 'bg-kivu-success text-white hover:brightness-95',
        'primary' => 'bg-kivu-primary text-white hover:bg-kivu-primary-hover',
        'brand' => 'bg-kivu-primary text-white hover:bg-kivu-primary-hover',
    ];
    $toneClass = $tones[$tone] ?? $tones['danger'];
@endphp

<div
    class="fixed inset-0 z-50 flex items-end justify-center bg-black/50 p-4 backdrop-blur-[2px] sm:items-center"
    wire:click.self="{{ $cancelMethod }}">
    <div
        role="dialog"
        aria-modal="true"
        aria-labelledby="kivu-modal-title"
        tabindex="-1"
        wire:keydown.escape="{{ $cancelMethod }}"
        {{ $attributes->merge(['class' => 'kivu-card w-full max-w-md p-6 shadow-theme-xl']) }}>
        <div class="flex items-start justify-between gap-4">
            <h3 id="kivu-modal-title" class="text-base font-bold text-kivu-text">{{ $title }}</h3>
            <button
                type="button"
                wire:click="{{ $cancelMethod }}"
                class="kivu-focus -mr-1 -mt-1 rounded-lg p-1.5 text-kivu-text-muted transition hover:bg-kivu-surface-muted hover:text-kivu-text"
                aria-label="Tutup">
                <x-icon name="x" :size="18" />
            </button>
        </div>

        @if ($message)
            <p class="mt-2.5 text-sm leading-relaxed text-kivu-text-secondary">{{ $message }}</p>
        @endif

        @if ($slot->isNotEmpty())
            <div class="mt-4">{{ $slot }}</div>
        @endif

        <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end sm:gap-3">
            <button
                type="button"
                wire:click="{{ $cancelMethod }}"
                class="kivu-focus inline-flex h-10 items-center justify-center rounded-kivu-sm border border-kivu-border bg-kivu-surface px-4 text-sm font-semibold text-kivu-text transition hover:bg-kivu-surface-muted">
                Batal
            </button>
            <button
                type="button"
                wire:click="{{ $confirmMethod }}"
                autofocus
                @if ($loadingTarget)
                    wire:loading.attr="disabled"
                    wire:target="{{ $loadingTarget }}"
                @endif
                class="kivu-focus inline-flex h-10 items-center justify-center gap-2 rounded-kivu-sm px-4 text-sm font-semibold transition disabled:cursor-not-allowed disabled:opacity-60 {{ $toneClass }}">
                @if ($loadingTarget)
                    <x-icon name="rotate-ccw" :size="16" class="animate-spin" wire:loading wire:target="{{ $loadingTarget }}" />
                    <span wire:loading.remove wire:target="{{ $loadingTarget }}">{{ $confirmLabel }}</span>
                    <span wire:loading wire:target="{{ $loadingTarget }}">Memproses...</span>
                @else
                    {{ $confirmLabel }}
                @endif
            </button>
        </div>
    </div>
</div>
