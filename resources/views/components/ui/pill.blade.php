@props(['tone' => 'neutral', 'dot' => false, 'size' => 'sm'])

@php
    $tones = [
        'success' => 'bg-kivu-success-soft text-kivu-success',
        'warning' => 'bg-kivu-warning-soft text-kivu-warning',
        'error' => 'bg-kivu-danger-soft text-kivu-danger',
        'danger' => 'bg-kivu-danger-soft text-kivu-danger',
        'primary' => 'bg-kivu-primary-soft text-kivu-primary',
        'brand' => 'bg-kivu-primary-soft text-kivu-primary',
        'blue' => 'bg-kivu-info-soft text-kivu-info',
        'info' => 'bg-kivu-info-soft text-kivu-info',
        'neutral' => 'bg-kivu-surface-muted text-kivu-text-secondary',
    ];

    $dots = [
        'success' => 'bg-kivu-success',
        'warning' => 'bg-kivu-warning',
        'error' => 'bg-kivu-danger',
        'danger' => 'bg-kivu-danger',
        'primary' => 'bg-kivu-primary',
        'brand' => 'bg-kivu-primary',
        'blue' => 'bg-kivu-info',
        'info' => 'bg-kivu-info',
        'neutral' => 'bg-kivu-text-muted',
    ];

    $sizes = [
        'xs' => 'px-2 py-0.5 text-[10px] gap-1',
        'sm' => 'px-2.5 py-0.5 text-[11px] gap-1',
        'md' => 'px-3 py-1 text-xs gap-1.5',
    ];

    $toneClass = $tones[$tone] ?? $tones['neutral'];
    $dotClass = $dots[$tone] ?? $dots['neutral'];
    $sizeClass = $sizes[$size] ?? $sizes['sm'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full font-semibold {$sizeClass} {$toneClass}"]) }}>
    @if ($dot)
        <span class="h-1.5 w-1.5 shrink-0 rounded-full {{ $dotClass }}"></span>
    @endif
    {{ $slot }}
</span>
