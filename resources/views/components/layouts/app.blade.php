<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('images/kivu-logo-tab.png') }}">
    <title>{{ $title ?? config('app.name', 'KIVU') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php
    $isStudent = auth()->user()->role === 'student';
    $nav = $isStudent ? [
        ['route' => 'student.dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard'],
        ['route' => 'student.opportunities', 'label' => 'Peluang', 'icon' => 'briefcase'],
        ['route' => 'student.my-applications', 'label' => 'Lamaran', 'icon' => 'file-text'],
        ['route' => 'student.inbox', 'label' => 'Pesan', 'icon' => 'message-square'],
        ['route' => 'student.wallet', 'label' => 'Dompet', 'icon' => 'wallet'],
        ['route' => 'student.profile', 'label' => 'Profil', 'icon' => 'user-round'],
    ] : [
        ['route' => 'umkm.dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard'],
        ['route' => 'umkm.my-projects', 'label' => 'Proyek Saya', 'icon' => 'folder-kanban'],
        ['route' => 'umkm.inbox', 'label' => 'Pesan', 'icon' => 'message-square'],
        ['route' => 'umkm.profile', 'label' => 'Profil', 'icon' => 'user-round'],
    ];
    $roleLabel = $isStudent ? 'Mahasiswa' : 'UMKM';
    $profileRoute = $isStudent ? 'student.profile' : 'umkm.profile';
@endphp
<body class="flex min-h-screen flex-col bg-kivu-bg font-sans text-kivu-text antialiased">
    <header class="sticky top-0 z-50 border-b border-kivu-border bg-kivu-surface/95 backdrop-blur">
        <div class="mx-auto flex h-[72px] max-w-[1200px] items-center justify-between gap-4 px-4 md:px-6">
            <div class="flex items-center gap-6">
                <a href="{{ url('/') }}" class="kivu-focus flex items-center rounded-kivu-sm">
                    <img src="{{ asset('images/kivu-logo.png') }}" alt="KIVU" class="h-8 w-auto">
                </a>

                <nav class="hidden items-center gap-1 md:flex" aria-label="Navigasi utama">
                    @foreach ($nav as $item)
                        <a href="{{ route($item['route']) }}" wire:navigate
                            @if (request()->routeIs($item['route'])) aria-current="page" @endif
                            class="kivu-focus inline-flex items-center gap-2 rounded-kivu-sm px-3 py-2 text-sm font-semibold transition {{ request()->routeIs($item['route']) ? 'text-kivu-primary' : 'text-kivu-text-secondary hover:text-kivu-primary' }}">
                            <x-icon :name="$item['icon']" :size="17" />
                            {{ $item['label'] }}
                        </a>
                    @endforeach

                    @unless ($isStudent)
                        <a href="{{ route('umkm.create-project') }}" wire:navigate
                            class="kivu-focus ml-1 inline-flex items-center gap-1.5 rounded-full bg-kivu-primary px-4 py-2 text-sm font-semibold text-white transition hover:bg-kivu-primary-hover">
                            <x-icon name="plus-circle" :size="16" /> Buat Proyek
                        </a>
                    @endunless
                </nav>
            </div>

            <div class="flex items-center gap-2">
                <livewire:shared.notification-bell />

                <details class="relative md:hidden">
                    <summary class="kivu-summary kivu-focus inline-flex h-10 w-10 items-center justify-center rounded-kivu-sm border border-kivu-border bg-kivu-surface text-kivu-text-secondary">
                        <x-icon name="menu" :size="20" />
                    </summary>
                    <div class="kivu-card absolute right-0 z-50 mt-2 w-64 p-1.5 shadow-theme-lg">
                        <nav class="flex flex-col gap-0.5" aria-label="Navigasi mobile">
                            @foreach ($nav as $item)
                                <a href="{{ route($item['route']) }}" wire:navigate
                                    class="kivu-focus flex items-center gap-3 rounded-kivu-sm px-3 py-2.5 text-sm font-semibold {{ request()->routeIs($item['route']) ? 'bg-kivu-primary-soft text-kivu-primary' : 'text-kivu-text-secondary hover:bg-kivu-surface-muted' }}">
                                    <x-icon :name="$item['icon']" :size="20" /> {{ $item['label'] }}
                                </a>
                            @endforeach
                            @unless ($isStudent)
                                <a href="{{ route('umkm.create-project') }}" wire:navigate
                                    class="kivu-focus mt-1 flex items-center gap-3 rounded-kivu-sm bg-kivu-primary px-3 py-2.5 text-sm font-semibold text-white">
                                    <x-icon name="plus-circle" :size="20" /> Buat Proyek
                                </a>
                            @endunless
                            <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t border-kivu-border pt-1">
                                @csrf
                                <button type="submit" class="kivu-focus flex w-full items-center gap-3 rounded-kivu-sm px-3 py-2.5 text-sm font-semibold text-kivu-danger transition hover:bg-kivu-danger-soft">
                                    <x-icon name="log-out" :size="20" /> Keluar
                                </button>
                            </form>
                        </nav>
                    </div>
                </details>

                <div class="hidden md:block">
                    <x-ui.dropdown align="right">
                        <x-slot:trigger>
                            <button type="button" class="kivu-focus flex items-center gap-2.5 rounded-full p-1 transition hover:bg-kivu-surface-muted">
                                <x-ui.avatar :name="auth()->user()->name" size="sm" />
                                <span class="hidden text-left leading-tight lg:block">
                                    <span class="block max-w-36 truncate text-sm font-semibold text-kivu-text">{{ auth()->user()->name }}</span>
                                    <span class="block text-xs text-kivu-text-muted">{{ $roleLabel }}</span>
                                </span>
                                <x-icon name="chevron-down" :size="16" class="hidden text-kivu-text-muted lg:block" />
                            </button>
                        </x-slot:trigger>

                        <div class="border-b border-kivu-border px-3 py-2">
                            <p class="truncate text-sm font-semibold text-kivu-text">{{ auth()->user()->name }}</p>
                            <p class="truncate text-xs text-kivu-text-muted">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="pt-1.5">
                            <a href="{{ route($profileRoute) }}" wire:navigate class="kivu-focus flex items-center gap-2.5 rounded-kivu-sm px-3 py-2.5 text-sm font-medium text-kivu-text-secondary transition hover:bg-kivu-surface-muted hover:text-kivu-text">
                                <x-icon name="user-round" :size="18" /> Profil Saya
                            </a>
                            <a href="{{ url('/') }}" class="kivu-focus flex items-center gap-2.5 rounded-kivu-sm px-3 py-2.5 text-sm font-medium text-kivu-text-secondary transition hover:bg-kivu-surface-muted hover:text-kivu-text">
                                <x-icon name="home" :size="18" /> Beranda KIVU
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="kivu-focus flex w-full items-center gap-2.5 rounded-kivu-sm px-3 py-2.5 text-sm font-medium text-kivu-danger transition hover:bg-kivu-danger-soft">
                                    <x-icon name="log-out" :size="18" /> Keluar
                                </button>
                            </form>
                        </div>
                    </x-ui.dropdown>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-1">
        <div class="mx-auto max-w-7xl px-4 py-6 md:px-6 md:py-8">
            @if (session('success'))
                <div class="mb-5 flex items-center gap-3 rounded-kivu border border-kivu-success/25 bg-kivu-success-soft px-4 py-3 text-sm font-medium text-kivu-success">
                    <x-icon name="circle-check" :size="18" class="shrink-0" /> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-5 flex items-center gap-3 rounded-kivu border border-kivu-danger/25 bg-kivu-danger-soft px-4 py-3 text-sm font-medium text-kivu-danger">
                    <x-icon name="alert-circle" :size="18" class="shrink-0" /> {{ session('error') }}
                </div>
            @endif

            {{ $slot }}
        </div>
    </main>

    <footer class="border-t border-kivu-border bg-kivu-surface">
        <div class="mx-auto max-w-7xl px-4 py-6 text-center text-xs text-kivu-text-muted md:px-6">
            &copy; {{ date('Y') }} KIVU &mdash; Marketplace Micro-Freelance Mahasiswa &times; UMKM
        </div>
    </footer>

    <x-ui.toast />
</body>
</html>
