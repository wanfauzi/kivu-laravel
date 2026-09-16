@props(['variant' => 'neutral', 'dot' => false, 'size' => 'md'])

@php
    $map = [
        'primary' => 'bg-kivu-primary-soft text-kivu-primary',
        'success' => 'bg-kivu-success-soft text-kivu-success',
        'warning' => 'bg-kivu-warning-soft text-kivu-warning',
        'danger' => 'bg-kivu-danger-soft text-kivu-danger',
        'info' => 'bg-kivu-info-soft text-kivu-info',
        'neutral' => 'bg-kivu-surface-muted text-kivu-text-secondary',
    ];

    $dotMap = [
        'primary' => 'bg-kivu-primary',
        'success' => 'bg-kivu-success',
        'warning' => 'bg-kivu-warning',
        'danger' => 'bg-kivu-danger',
        'info' => 'bg-kivu-info',
        'neutral' => 'bg-kivu-text-muted',
    ];

    $sizes = [
        'sm' => 'px-2 py-0.5 text-[11px] gap-1',
        'md' => 'px-2.5 py-1 text-xs gap-1.5',
    ];

    $tone = $map[$variant] ?? $map['neutral'];
    $dotTone = $dotMap[$variant] ?? $dotMap['neutral'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full font-semibold {$sizeClass} {$tone}"]) }}>
    @if ($dot)
        <span class="h-1.5 w-1.5 shrink-0 rounded-full {{ $dotTone }}"></span>
    @endif
    {{ $slot }}
</span>
