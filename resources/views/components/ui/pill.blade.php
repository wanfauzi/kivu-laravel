@props(['tone' => 'neutral'])

@php
    $classes = [
        'success' => 'bg-success-50 text-success-600',
        'warning' => 'bg-warning-50 text-warning-600',
        'error' => 'bg-error-50 text-error-600',
        'brand' => 'bg-brand-50 text-brand-600',
        'blue' => 'bg-blue-50 text-blue-600',
        'info' => 'bg-blue-light-50 text-blue-light-600',
        'neutral' => 'bg-gray-100 text-gray-600',
    ][$tone] ?? 'bg-gray-100 text-gray-600';
@endphp

<span {{ $attributes->merge(['class' => "inline-block rounded-full px-2.5 py-0.5 text-[11px] font-medium {$classes}"]) }}>
    {{ $slot }}
</span>
