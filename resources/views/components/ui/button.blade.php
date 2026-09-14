@props(['variant' => 'primary', 'type' => 'submit'])

@php
    $base = "inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-semibold transition focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed";
    $variants = [
        'primary' => "bg-[var(--kivu-primary)] text-white hover:bg-[var(--kivu-primary-hover)] focus:ring-[var(--kivu-primary)]",
        'secondary' => "bg-white border border-[var(--kivu-border)] text-[var(--kivu-text-primary)] hover:bg-[var(--kivu-surface-muted)]",
        'danger' => "bg-[var(--kivu-danger)] text-white hover:opacity-90 focus:ring-[var(--kivu-danger)]",
        'ghost' => "bg-transparent text-[var(--kivu-text-secondary)] hover:bg-[var(--kivu-surface-muted)]"
    ];
    $classes = "$base " . ($variants[$variant] ?? $variants['primary']);
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>