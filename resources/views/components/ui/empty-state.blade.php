@props(['icon' => 'inbox', 'title' => '', 'description' => ''])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center rounded-2xl border border-dashed border-gray-200 bg-white px-6 py-14 text-center']) }}>
    <div class="flex h-14 w-14 items-center justify-center rounded-full border border-gray-200 bg-gray-50 text-gray-400">
        <x-icon :name="$icon" :size="26" stroke="1.5" />
    </div>
    @if ($title)
        <h3 class="mt-4 text-base font-semibold text-gray-800">{{ $title }}</h3>
    @endif
    @if ($description)
        <p class="mt-1 max-w-sm text-sm text-gray-500">{{ $description }}</p>
    @endif
    @if (trim($slot))
        <div class="mt-5">
            {{ $slot }}
        </div>
    @endif
</div>