@props(['user'])

@if($user && $user->isVerifiedStudent())
    <span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-full bg-brand-50 px-2 py-0.5 text-[11px] font-semibold text-brand-700']) }} title="Diverifikasi oleh KIVU sebagai mahasiswa">
        <x-icon name="check" :size="12" /> Mahasiswa Terverifikasi
    </span>
@endif