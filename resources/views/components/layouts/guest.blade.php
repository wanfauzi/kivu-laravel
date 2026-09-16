<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('images/kivu-logo-tab.png') }}">
    <title>{{ $title ?? config('app.name', 'KIVU') }}</title>
    <meta name="robots" content="noindex, nofollow">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-kivu-bg font-sans text-kivu-text antialiased">
    <div class="grid min-h-screen lg:grid-cols-2">
        {{-- Brand panel --}}
        <aside class="relative hidden overflow-hidden bg-gradient-to-br from-kivu-primary via-kivu-primary to-kivu-primary-active p-12 text-white lg:flex lg:flex-col lg:justify-between">
            <div class="pointer-events-none absolute -top-24 -right-24 h-80 w-80 rounded-full bg-white/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-32 -left-24 h-80 w-80 rounded-full bg-white/10 blur-3xl"></div>

            <a href="{{ url('/') }}" class="relative flex items-center">
                <img src="{{ asset('images/kivu-logo-white.png') }}" alt="KIVU" class="h-8 w-auto">
            </a>

            <div class="relative max-w-md">
                <h2 class="text-3xl font-bold leading-tight">Bangun portofolio.<br>Bantu UMKM tumbuh.</h2>
                <p class="mt-4 text-sm leading-relaxed text-white/80">
                    Marketplace micro-freelance yang menghubungkan mahasiswa dengan UMKM. Posting, lamar, kerja, dibayar &mdash; semuanya transparan.
                </p>

                <ul class="mt-8 space-y-3 text-sm text-white/90">
                    <li class="flex items-center gap-3">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white/15"><x-icon name="check" :size="13" stroke="3" /></span>
                        Peluang terkurasi dengan budget transparan
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white/15"><x-icon name="shield-check" :size="13" /></span>
                        Pembayaran aman lewat wallet
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white/15"><x-icon name="star" :size="13" :filled="true" /></span>
                        Reputasi nyata dari ulasan UMKM
                    </li>
                </ul>
            </div>

            <p class="relative text-xs text-white/60">&copy; {{ date('Y') }} KIVU &mdash; Mahasiswa &times; UMKM</p>
        </aside>

        {{-- Form panel --}}
        <main class="flex flex-col px-5 py-8 sm:px-10">
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="flex items-center lg:hidden">
                    <img src="{{ asset('images/kivu-logo.png') }}" alt="KIVU" class="h-9 w-auto">
                </a>
            </div>

            <div class="flex flex-1 items-center justify-center py-8">
                <div class="w-full max-w-sm">
                    {{ $slot }}
                </div>
            </div>

            <p class="text-center text-xs text-kivu-text-muted">
                <a href="{{ url('/') }}" class="kivu-focus rounded transition hover:text-kivu-primary">&larr; Kembali ke beranda</a>
            </p>
        </main>
    </div>

    <x-ui.toast />
</body>
</html>
