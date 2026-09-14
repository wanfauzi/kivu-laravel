<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('images/kivu-logo-tab.png') }}">
    <title>{{ config('app.name', 'KIVU') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-outfit bg-white text-gray-800 antialiased">
    <div class="relative z-1 bg-white p-6 sm:p-0">
        <div class="relative flex h-screen w-full flex-col justify-center sm:p-0 lg:flex-row">
            <div class="flex w-full flex-1 flex-col lg:w-1/2">
                <div class="mx-auto w-full max-w-md pt-10">
                    <a href="{{ url('/') }}" class="inline-flex items-center text-sm text-gray-500 transition-colors hover:text-gray-700">
                        <x-icon name="arrow-left" :size="20" />
                        <span class="ms-1">Kembali ke beranda</span>
                    </a>
                </div>
                <div class="mx-auto flex w-full max-w-md flex-1 flex-col justify-center">
                    {{ $slot }}
                </div>
            </div>

            <div class="bg-brand-950 relative hidden h-full w-full items-center lg:grid lg:w-1/2">
                <svg class="pointer-events-none absolute inset-0 h-full w-full" width="100%" height="100%" viewBox="0 0 600 800" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                    <circle cx="560" cy="120" r="220" stroke="rgba(255,255,255,0.08)" stroke-width="1"/>
                    <circle cx="560" cy="120" r="150" stroke="rgba(255,255,255,0.06)" stroke-width="1"/>
                    <circle cx="30" cy="720" r="180" stroke="rgba(255,255,255,0.08)" stroke-width="1"/>
                    <circle cx="30" cy="720" r="110" stroke="rgba(255,255,255,0.06)" stroke-width="1"/>
                </svg>
                <div class="z-1 relative flex items-center justify-center">
                    <div class="flex max-w-xs flex-col items-center">
                        <a href="{{ url('/') }}" class="mb-6 block">
                            <img src="{{ asset('images/kivu-logo.png') }}" alt="KIVU" class="h-12 w-auto">
                        </a>
                        <p class="text-center text-sm text-gray-400 leading-relaxed">
                            Marketplace micro-freelance<br>
                            Mahasiswa &times; UMKM
                        </p>
                        <p class="mt-8 text-xs text-gray-500">© {{ date('Y') }} KIVU</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('[data-password-toggle]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const input = document.getElementById(btn.dataset.passwordToggle);
                if (!input) return;
                const hidden = input.type === 'password';
                input.type = hidden ? 'text' : 'password';
                const eye = btn.querySelector('[data-icon-eye]');
                const eyeOff = btn.querySelector('[data-icon-eye-off]');
                eye && eye.classList.toggle('hidden', !hidden);
                eyeOff && eyeOff.classList.toggle('hidden', hidden);
            });
        });
    </script>
</body>
</html>