@props(['variant' => 'neutral'])
@php
    $map = [
        'success' => 'bg-[var(--kivu-success-soft)] text-[var(--kivu-success)] border-[var(--kivu-success)]/20',
        'warning' => 'bg-[#FFFBEB] text-[#D97706] border-[#D97706]/20',
        'danger' => 'bg-[var(--kivu-danger-soft)] text-[var(--kivu-danger)] border-[var(--kivu-danger)]/20',
        'info' => 'bg-[var(--kivu-info-soft)] text-[var(--kivu-info)] border-[var(--kivu-info)]/20',
        'neutral' => 'bg-[var(--kivu-surface-muted)] text-[var(--kivu-text-secondary)] border-[var(--kivu-border)]',
    ];
@endphp
<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-medium ' . ($map[$variant] ?? $map['neutral'])]) }}>{{ $slot }}</span>
