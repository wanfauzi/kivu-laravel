@props(['name' => '', 'size' => 'md'])

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
        default => 'h-10 w-10 text-sm',
    };
@endphp

<span {{ $attributes->merge(['class' => "flex {$sizeClass} shrink-0 items-center justify-center rounded-full bg-brand-100 font-semibold text-brand-700"]) }}>
    @if ($initials !== '')
        {{ $initials }}
    @else
        <x-icon name="user-round" :size="16" />
    @endif
</span>
