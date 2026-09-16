@props(['icon' => 'inbox', 'title' => '', 'description' => '', 'tone' => 'neutral'])

@php
    $tones = [
        'neutral' => 'bg-kivu-surface-muted text-kivu-text-muted',
        'primary' => 'bg-kivu-primary-soft text-kivu-primary',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center rounded-kivu border border-dashed border-kivu-border bg-kivu-surface px-6 py-14 text-center']) }}>
    <div class="flex h-14 w-14 items-center justify-center rounded-full {{ $tones[$tone] ?? $tones['neutral'] }}">
        <x-icon :name="$icon" :size="26" stroke="1.5" />
    </div>

    @if ($title)
        <h3 class="mt-4 text-base font-semibold text-kivu-text">{{ $title }}</h3>
    @endif

    @if ($description)
        <p class="mt-1 max-w-sm text-sm text-kivu-text-muted">{{ $description }}</p>
    @endif

    @if ($slot->isNotEmpty())
        <div class="mt-5">
            {{ $slot }}
        </div>
    @endif
</div>
