@props(['title' => '', 'closeMethod' => null, 'size' => 'max-w-lg'])

<div class="fixed inset-0 z-50 flex items-end justify-center bg-black/50 p-4 backdrop-blur-[2px] sm:items-center"
    @if ($closeMethod) wire:click.self="{{ $closeMethod }}" @endif>
    <div role="dialog" aria-modal="true" aria-labelledby="kivu-modal-title" tabindex="-1"
        @if ($closeMethod) wire:keydown.escape="{{ $closeMethod }}" @endif
        {{ $attributes->merge(['class' => "kivu-card max-h-[90vh] w-full {$size} overflow-y-auto p-6 shadow-theme-xl"]) }}>
        <div class="flex items-start justify-between gap-4">
            <h3 id="kivu-modal-title" class="text-base font-bold text-kivu-text">{{ $title }}</h3>
            @if ($closeMethod)
                <button type="button" wire:click="{{ $closeMethod }}"
                    class="kivu-focus -mt-1 -mr-1 rounded-lg p-1.5 text-kivu-text-muted transition hover:bg-kivu-surface-muted hover:text-kivu-text"
                    aria-label="Tutup">
                    <x-icon name="x" :size="18" />
                </button>
            @endif
        </div>

        <div class="mt-4">
            {{ $slot }}
        </div>
    </div>
</div>
