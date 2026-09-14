@props(['padding' => 'p-6'])
<div {{ $attributes->merge(['class' => "bg-white border border-[var(--kivu-border)] rounded-xl $padding"]) }}>
    {{ $slot }}
</div>
