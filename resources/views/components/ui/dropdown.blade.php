@props(['align' => 'left', 'width' => 'w-64'])

@php
    $panelPosition = $align === 'right' ? 'right-0' : 'left-0';
@endphp

<details data-kivu-dropdown {{ $attributes->merge(['class' => 'relative']) }}>
    <summary class="kivu-summary kivu-focus inline-flex list-none items-center gap-2 rounded-kivu-sm">
        {{ $trigger }}
        <x-icon name="chevron-down" :size="16" class="kivu-caret text-kivu-text-muted transition-transform duration-200" />
    </summary>

    <div class="kivu-card absolute {{ $panelPosition }} z-50 mt-2 {{ $width }} p-1.5 shadow-theme-lg">
        {{ $slot }}
    </div>
</details>
