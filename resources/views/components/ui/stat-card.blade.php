@props([
    'icon' => 'chart',
    'label' => '',
    'value' => '',
    'tone' => 'primary',
    'hint' => null,
    'trend' => null,
    'trendLabel' => null,
    'href' => null,
])

@php
    $tones = [
        'primary' => 'bg-kivu-primary-soft text-kivu-primary',
        'brand' => 'bg-kivu-primary-soft text-kivu-primary',
        'success' => 'bg-kivu-success-soft text-kivu-success',
        'warning' => 'bg-kivu-warning-soft text-kivu-warning',
        'danger' => 'bg-kivu-danger-soft text-kivu-danger',
        'info' => 'bg-kivu-info-soft text-kivu-info',
        'blue' => 'bg-kivu-info-soft text-kivu-info',
    ];

    $trendClass = $trend === 'down' ? 'text-kivu-danger' : 'text-kivu-success';
    $trendIcon = $trend === 'down' ? 'trending-down' : 'trending-up';

    $tag = $href ? 'a' : 'div';
    $hoverable = $href ? 'kivu-card-hover block' : '';
@endphp

<{{ $tag }}
    @if ($href) href="{{ $href }}" @endif
    {{ $attributes->merge(['class' => "kivu-card {$hoverable} flex items-start gap-4 p-5"]) }}>
    <div class="{{ $tones[$tone] ?? $tones['primary'] }} flex h-11 w-11 shrink-0 items-center justify-center rounded-kivu-sm">
        <x-icon :name="$icon" :size="22" />
    </div>

    <div class="min-w-0 flex-1">
        <p class="text-sm text-kivu-text-secondary">{{ $label }}</p>
        <p class="mt-0.5 truncate text-2xl font-bold text-kivu-text">{{ $value }}</p>

        @if ($hint)
            <p class="mt-0.5 text-xs text-kivu-text-muted">{{ $hint }}</p>
        @endif

        @if ($trend)
            <p class="mt-1.5 inline-flex items-center gap-1 text-xs font-semibold {{ $trendClass }}">
                <x-icon :name="$trendIcon" :size="14" />
                {{ $trendLabel }}
            </p>
        @endif
    </div>
</{{ $tag }}>
