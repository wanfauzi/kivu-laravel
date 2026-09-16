@props(['items' => [], 'active' => null, 'method' => null, 'property' => null, 'variant' => 'pill'])

@php
    $base = 'kivu-focus inline-flex items-center gap-2 rounded-full px-3.5 py-2 text-sm font-semibold transition';
    $on = 'bg-kivu-primary text-white shadow-theme-xs';
    $off = 'text-kivu-text-secondary hover:bg-kivu-surface-muted hover:text-kivu-text';
@endphp

<nav {{ $attributes->merge(['class' => 'no-scrollbar flex items-center gap-1 overflow-x-auto']) }} role="tablist">
    @foreach ($items as $item)
        @php
            $value = $item['value'] ?? ($item['label'] ?? '');
            $isActive = (string) $active === (string) $value;
            $class = $base.' '.($isActive ? $on : $off);
        @endphp

        @if (! empty($item['route']))
            <a href="{{ $item['route'] }}" wire:navigate role="tab" aria-selected="{{ $isActive ? 'true' : 'false' }}" class="{{ $class }}">
                {{ $item['label'] ?? '' }}
                @if (! empty($item['badge']))
                    <span class="rounded-full bg-black/10 px-1.5 py-0.5 text-[10px] font-bold">{{ $item['badge'] }}</span>
                @endif
            </a>
        @else
            <button
                type="button"
                role="tab"
                aria-selected="{{ $isActive ? 'true' : 'false' }}"
                @if ($method) wire:click="{{ $method }}('{{ $value }}')" @endif
                class="{{ $class }}">
                {{ $item['label'] ?? '' }}
                @if (! empty($item['badge']))
                    <span class="rounded-full bg-black/10 px-1.5 py-0.5 text-[10px] font-bold">{{ $item['badge'] }}</span>
                @endif
            </button>
        @endif
    @endforeach
</nav>
