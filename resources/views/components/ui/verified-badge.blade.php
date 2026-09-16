@props(['user' => null, 'compact' => false])

@if ($user && $user->isVerifiedStudent())
    <span
        {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-full bg-kivu-primary-soft font-semibold text-kivu-primary ' . ($compact ? 'p-1' : 'px-2 py-0.5 text-[11px]')]) }}
        title="Diverifikasi oleh KIVU sebagai mahasiswa">
        <x-icon name="shield-check" :size="$compact ? 14 : 12" />
        @unless ($compact)
            Mahasiswa Terverifikasi
        @endunless
    </span>
@endif
