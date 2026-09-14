<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('images/kivu-logo-tab.png') }}">
    <title>{{ config('app.name', 'KIVU') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[var(--kivu-background)] text-[var(--kivu-text-primary)] font-sans antialiased min-h-screen flex flex-col">
    <main class="flex-1 flex flex-col items-center justify-center px-4 py-10">
        <a href="{{ url('/') }}" class="flex items-center mb-6">
            <img src="{{ asset('images/kivu-logo.png') }}" alt="KIVU" class="h-10 w-auto">
        </a>
        <div class="w-full max-w-md bg-white border border-[var(--kivu-border)] rounded-2xl p-6 sm:p-8 shadow-sm">
            {{ $slot }}
        </div>
        <p class="mt-6 text-sm text-[var(--kivu-text-muted)] text-center">
            © {{ date('Y') }} KIVU — Marketplace Micro-Freelance Mahasiswa × UMKM
        </p>
    </main>
</body>
</html>
