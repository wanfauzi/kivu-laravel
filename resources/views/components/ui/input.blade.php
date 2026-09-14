@props(['label' => null, 'error' => null, 'id' => null])
<div class="space-y-1.5">
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-[var(--kivu-text-primary)]">{{ $label }}</label>
    @endif
    <input id="{{ $id }}" {{ $attributes->merge(['class' => 'w-full rounded-lg border border-[var(--kivu-border)] bg-white px-3 py-2.5 text-sm outline-none focus:border-[var(--kivu-primary)] focus:ring-2 focus:ring-[var(--kivu-primary-soft)]']) }}>
    @if($error)
        <p class="text-xs text-[var(--kivu-danger)]">{{ $error }}</p>
    @endif
</div>
