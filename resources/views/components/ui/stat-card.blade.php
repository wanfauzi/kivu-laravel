@props(['icon' => 'chart', 'label' => '', 'value' => '', 'tone' => 'brand', 'hint' => null])

@php
    $tones = [
        'brand' => 'bg-blue-50 text-blue-600',
        'success' => 'bg-success-50 text-success-600',
        'blue' => 'bg-blue-50 text-blue-600',
        'warning' => 'bg-warning-50 text-warning-600',
        'danger' => 'bg-error-50 text-error-600',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-4 rounded-2xl border border-gray-200 bg-white p-5']) }}>
    <div class="{{ $tones[$tone] ?? $tones['brand'] }} flex h-12 w-12 shrink-0 items-center justify-center rounded-xl">
        <x-icon :name="$icon" :size="24" />
    </div>
    <div class="min-w-0">
        <p class="text-sm text-gray-500">{{ $label }}</p>
        <p class="truncate text-2xl font-bold text-gray-800">{{ $value }}</p>
        @if ($hint)
            <p class="text-xs text-gray-400">{{ $hint }}</p>
        @endif
    </div>
</div>