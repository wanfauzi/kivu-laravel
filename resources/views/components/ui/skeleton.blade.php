@props(['type' => 'text', 'lines' => 3])

@if ($type === 'circle')
    <span {{ $attributes->merge(['class' => 'block animate-pulse rounded-full bg-kivu-surface-muted']) }} style="width: 2.5rem; height: 2.5rem;"></span>
@elseif ($type === 'card')
    <div {{ $attributes->merge(['class' => 'kivu-card space-y-3 p-5']) }}>
        <div class="h-4 w-1/3 animate-pulse rounded bg-kivu-surface-muted"></div>
        <div class="h-3 w-full animate-pulse rounded bg-kivu-surface-muted"></div>
        <div class="h-3 w-2/3 animate-pulse rounded bg-kivu-surface-muted"></div>
    </div>
@else
    <div {{ $attributes->merge(['class' => 'space-y-2.5']) }}>
        @for ($i = 0; $i < $lines; $i++)
            <div class="h-3 animate-pulse rounded bg-kivu-surface-muted" style="width: {{ $i === $lines - 1 ? '60%' : '100%' }};"></div>
        @endfor
    </div>
@endif
