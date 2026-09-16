@props(['name' => '', 'src' => null, 'size' => 'md', 'ring' => false])

@php
    $clean = trim((string) $name);
    $initials = collect(preg_split('/\s+/', $clean, -1, PREG_SPLIT_NO_EMPTY))
        ->take(2)
        ->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))
        ->join('');

    $sizeClass = match ($size) {
        'xs' => 'h-7 w-7 text-[11px]',
        'sm' => 'h-8 w-8 text-xs',
        'lg' => 'h-12 w-12 text-base',
        'xl' => 'h-16 w-16 text-lg',
        default => 'h-10 w-10 text-sm',
    };

    $ringClass = $ring ? 'ring-2 ring-kivu-surface' : '';
@endphp

@if ($src)
    <img src="{{ $src }}" alt="{{ $clean }}" {{ $attributes->merge(['class' => "{$sizeClass} {$ringClass} shrink-0 rounded-full object-cover"]) }}>
@else
    <span {{ $attributes->merge(['class' => "flex {$sizeClass} {$ringClass} shrink-0 items-center justify-center rounded-full bg-kivu-primary-soft font-semibold text-kivu-primary"]) }}>
        @if ($initials !== '')
            {{ $initials }}
        @else
            <x-icon name="user-round" :size="16" />
        @endif
    </span>
@endif
