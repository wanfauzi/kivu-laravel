@props(['value' => 0, 'count' => null, 'size' => 15, 'max' => 5, 'showValue' => false, 'label' => null])

@php
    $rating = (float) $value;
    $rounded = (int) round($rating);
    $rounded = max(0, min($max, $rounded));
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5']) }} @if ($label) aria-label="{{ $label }}" @endif>
    <span class="inline-flex items-center gap-0.5 text-kivu-warning" role="img" aria-label="{{ number_format($rating, 1) }} dari {{ $max }}">
        @for ($i = 1; $i <= $max; $i++)
            <x-icon name="star" :size="$size" :filled="$i <= $rounded" class="{{ $i <= $rounded ? '' : 'text-kivu-text-muted/40' }}" />
        @endfor
    </span>

    @if ($showValue)
        <span class="text-sm font-semibold text-kivu-text">{{ number_format($rating, 1) }}</span>
    @endif

    @if ($count !== null)
        <span class="text-xs text-kivu-text-muted">({{ $count }})</span>
    @endif
</span>
