@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'href' => null,
    'loadingTarget' => null,
    'loadingLabel' => 'Memproses...',
])

@php
    $variants = [
        'primary' => 'bg-kivu-yellow text-kivu-yellow-text hover:bg-kivu-yellow-hover',
        'secondary' => 'border border-kivu-primary bg-white text-kivu-primary hover:bg-kivu-primary-soft',
        'blue' => 'bg-kivu-primary text-white hover:bg-kivu-primary-hover',
        'soft' => 'bg-kivu-primary-soft text-kivu-primary hover:brightness-95',
        'ghost' => 'text-kivu-primary hover:bg-kivu-primary-soft',
        'danger' => 'bg-kivu-danger text-white hover:brightness-95',
        'success' => 'bg-kivu-success text-white hover:brightness-95',
    ];

    $sizes = [
        'sm' => 'h-9 gap-1.5 px-3 text-xs',
        'md' => 'h-11 gap-2 px-5 text-sm',
        'lg' => 'h-12 gap-2 px-6 text-sm',
    ];

    $base = 'kivu-focus inline-flex shrink-0 items-center justify-center rounded-[10px] font-semibold transition disabled:cursor-not-allowed disabled:opacity-50';

    $classes = trim($base . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']));

    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    @if ($href) href="{{ $href }}" @else type="{{ $type }}" @endif
    @if ($loadingTarget)
        wire:loading.attr="disabled"
        wire:target="{{ $loadingTarget }}"
    @endif
    {{ $attributes->merge(['class' => $classes]) }}>
    @if ($loadingTarget)
        <x-icon name="rotate-ccw" :size="16" class="animate-spin" wire:loading wire:target="{{ $loadingTarget }}" />
        <span wire:loading.remove wire:target="{{ $loadingTarget }}" class="inline-flex items-center gap-2">{{ $slot }}</span>
        <span wire:loading wire:target="{{ $loadingTarget }}">{{ $loadingLabel }}</span>
    @else
        {{ $slot }}
    @endif
</{{ $tag }}>
