@props([
    'title',
    'message',
    'confirmLabel' => 'Konfirmasi',
    'confirmMethod',
    'cancelMethod',
    'tone' => 'danger',
    'loadingTarget' => null,
])

@php
    $toneClasses = [
        'danger' => 'bg-error-500 hover:bg-error-600',
        'success' => 'bg-success-500 hover:bg-success-600',
        'brand' => 'bg-brand-500 hover:bg-brand-600',
    ][$tone] ?? 'bg-brand-500 hover:bg-brand-600';
@endphp

<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" wire:click.self="{{ $cancelMethod }}">
    <div class="w-full max-w-md rounded-2xl border border-gray-200 bg-white p-6 shadow-xl" {{ $attributes }}>
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900">{{ $title }}</h3>
            <button type="button" wire:click="{{ $cancelMethod }}" class="rounded-lg p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600" aria-label="Tutup">
                <x-icon name="x" :size="20" />
            </button>
        </div>
        <p class="mt-3 text-sm text-gray-500">{{ $message }}</p>
        {{-- slot opsional untuk konten tambahan --}}
        @isset($slot)
            <div class="mt-3">{{ $slot }}</div>
        @endisset
        <div class="mt-6 flex justify-end gap-3">
            <button type="button" wire:click="{{ $cancelMethod }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">Batal</button>
            <button type="button"
                wire:click="{{ $confirmMethod }}"
                @if($loadingTarget) wire:loading.attr="disabled" wire:target="{{ $loadingTarget }}" @endif
                class="rounded-lg px-4 py-2 text-sm font-medium text-white transition {{ $toneClasses }}">
                <span {{ $loadingTarget ? "wire:loading.remove wire:target=" . $loadingTarget : '' }}>{{ $confirmLabel }}</span>
                @if($loadingTarget)
                    <span wire:loading wire:target="{{ $loadingTarget }}">Memproses...</span>
                @endif
            </button>
        </div>
    </div>
</div>