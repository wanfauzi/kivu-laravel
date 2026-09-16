<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('images/kivu-logo-tab.png') }}">
    <title>{{ $title ?? config('app.name', 'KIVU') }} &middot; Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php
    $adminNav = [
        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard'],
        ['route' => 'admin.users', 'label' => 'Pengguna', 'icon' => 'users'],
        ['route' => 'admin.projects', 'label' => 'Proyek', 'icon' => 'folder-kanban'],
        ['route' => 'admin.transactions', 'label' => 'Transaksi', 'icon' => 'arrow-left-right'],
        ['route' => 'admin.withdrawals', 'label' => 'Penarikan', 'icon' => 'wallet'],
        ['route' => 'admin.disputes', 'label' => 'Sengketa', 'icon' => 'shield-alert'],
    ];
@endphp
<body class="bg-kivu-bg font-sans text-kivu-text antialiased">
    <div class="flex h-screen overflow-hidden">
        <aside id="admin-sidebar"
            class="sidebar fixed top-0 left-0 z-[9999] flex h-screen w-72.5 -translate-x-full flex-col overflow-y-auto border-r border-kivu-border bg-kivu-surface px-5 transition-all duration-300 xl:static xl:translate-x-0 rtl:right-0 rtl:left-auto">
            <div class="sidebar-header flex items-center gap-2 pt-8 pb-7">
                <a href="{{ url('/') }}" class="logo">
                    <img src="{{ asset('images/kivu-logo.png') }}" alt="KIVU" class="h-9 w-auto">
                </a>
            </div>

            <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear">
                <nav aria-label="Navigasi admin">
                    <h3 class="kivu-section-title mb-4">Menu Admin</h3>
                    <ul class="mb-6 flex flex-col gap-1">
                        @foreach ($adminNav as $item)
                            <li>
                                <a href="{{ route($item['route']) }}"
                                    @if (request()->routeIs($item['route'])) aria-current="page" @endif
                                    class="menu-item kivu-focus group {{ request()->routeIs($item['route']) ? 'menu-item-active' : 'menu-item-inactive' }}">
                                    <x-icon :name="$item['icon']" :size="22" class="{{ request()->routeIs($item['route']) ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}" />
                                    <span class="menu-item-text">{{ $item['label'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            </div>

            <div class="mt-auto pb-6">
                <a href="{{ url('/') }}" class="flex items-center gap-3 rounded-kivu border border-kivu-border bg-kivu-surface-muted p-4 transition hover:border-kivu-primary/40">
                    <img src="{{ asset('images/kivu-logo-tab.png') }}" alt="KIVU" class="h-8 w-8 rounded-lg">
                    <span>
                        <span class="block text-sm font-semibold text-kivu-text">Kembali ke beranda</span>
                        <span class="block text-xs text-kivu-text-muted">Lihat situs publik KIVU</span>
                    </span>
                </a>
            </div>
        </aside>

        <div class="relative flex flex-1 flex-col overflow-x-hidden overflow-y-auto">
            <div id="admin-overlay" class="fixed inset-0 z-50 hidden bg-black/40 xl:hidden"></div>

            <header class="sticky top-0 z-[60] flex w-full border-b border-kivu-border bg-kivu-surface">
                <div class="flex w-full items-center justify-between gap-3 px-3 py-3 xl:px-6">
                    <div class="flex items-center gap-3">
                        <button id="admin-sidebar-toggle" type="button"
                            class="kivu-focus flex h-10 w-10 items-center justify-center rounded-kivu-sm border border-kivu-border text-kivu-text-secondary transition hover:bg-kivu-surface-muted xl:hidden"
                            aria-label="Buka menu">
                            <x-icon name="menu" :size="22" />
                        </button>
                        <a href="{{ url('/') }}" class="xl:hidden">
                            <img src="{{ asset('images/kivu-logo.png') }}" alt="KIVU" class="h-8 w-auto">
                        </a>
                    </div>

                    <div class="flex items-center gap-2">
                        <livewire:shared.notification-bell />
        
                        <x-ui.dropdown align="right" width="w-64">
                            <x-slot:trigger>
                                <button type="button" class="kivu-focus flex items-center gap-2.5 rounded-full p-1 transition hover:bg-kivu-surface-muted">
                                    <x-ui.avatar :name="auth()->user()->name" size="sm" />
                                    <span class="hidden text-left leading-tight sm:block">
                                        <span class="block max-w-32 truncate text-sm font-semibold text-kivu-text">{{ auth()->user()->name }}</span>
                                        <span class="block text-xs text-kivu-text-muted">Admin</span>
                                    </span>
                                    <x-icon name="chevron-down" :size="16" class="hidden text-kivu-text-muted sm:block" />
                                </button>
                            </x-slot:trigger>

                            <div class="border-b border-kivu-border px-3 py-2">
                                <p class="truncate text-sm font-semibold text-kivu-text">{{ auth()->user()->name }}</p>
                                <p class="truncate text-xs text-kivu-text-muted">{{ auth()->user()->email }}</p>
                            </div>
                            <div class="pt-1.5">
                                <a href="{{ url('/') }}" class="kivu-focus flex items-center gap-2.5 rounded-kivu-sm px-3 py-2.5 text-sm font-medium text-kivu-text-secondary transition hover:bg-kivu-surface-muted hover:text-kivu-text">
                                    <x-icon name="home" :size="18" /> Lihat beranda KIVU
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
            </header>

            <main>
                <div class="mx-auto max-w-7xl p-4 pb-10 md:p-6">
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

                    <p class="mt-8 text-center text-xs text-kivu-text-muted">
                        &copy; {{ date('Y') }} KIVU &mdash; Marketplace Micro-Freelance Mahasiswa &times; UMKM
                    </p>
                </div>
            </main>
        </div>
    </div>

    <x-ui.toast />

    <script>
        (function () {
            var sidebar = document.getElementById('admin-sidebar');
            var overlay = document.getElementById('admin-overlay');
            var toggleBtn = document.getElementById('admin-sidebar-toggle');

            if (!sidebar || !overlay || !toggleBtn) return;

            toggleBtn.addEventListener('click', function () {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            });

            overlay.addEventListener('click', function () {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        })();
    </script>
</body>
</html>
