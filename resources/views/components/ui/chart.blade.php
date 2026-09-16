@props(['type' => 'line', 'labels' => [], 'series' => [], 'height' => 240, 'compact' => true])

@php
    $payload = [
        'type' => $type,
        'labels' => array_values($labels),
        'series' => array_values($series),
        'compact' => (bool) $compact,
    ];
@endphp

<div {{ $attributes->merge(['class' => 'relative w-full']) }} style="height: {{ $height }}px;">
    <canvas data-chart="{{ json_encode($payload) }}"></canvas>
</div>
