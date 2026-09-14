<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('images/kivu-logo-tab.png') }}">
    <title>{{ config('app.name', 'KIVU') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@auth
@if(auth()->user()->role === 'admin')
@php
    $adminPageTitle = match(true) {
        request()->routeIs('admin.users') => 'Kelola Pengguna',
        request()->routeIs('admin.projects') => 'Kelola Proyek',
        request()->routeIs('admin.transactions') => 'Riwayat Transaksi',
        request()->routeIs('admin.withdrawals') => 'Kelola Penarikan',
        request()->routeIs('admin.disputes') => 'Sengketa',
        default => 'Dashboard Admin',
    };
    $adminNav = [
        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard'],
        ['route' => 'admin.users', 'label' => 'Pengguna', 'icon' => 'users'],
        ['route' => 'admin.projects', 'label' => 'Proyek', 'icon' => 'folder-kanban'],
        ['route' => 'admin.transactions', 'label' => 'Transaksi', 'icon' => 'arrow-left-right'],
        ['route' => 'admin.withdrawals', 'label' => 'Penarikan', 'icon' => 'wallet'],
        ['route' => 'admin.disputes', 'label' => 'Sengketa', 'icon' => 'shield-alert'],
    ];
@endphp
<body class="font-outfit bg-gray-50 text-gray-800 antialiased">
    <div class="flex h-screen overflow-hidden">
        <aside id="admin-sidebar" class="sidebar fixed top-0 left-0 z-[9999] flex h-screen w-72.5 flex-col overflow-y-auto border-r border-gray-200 bg-white px-5 transition-all duration-300 -translate-x-full xl:static xl:translate-x-0 rtl:right-0 rtl:left-auto">
            <div class="sidebar-header flex items-center gap-2 pt-8 pb-7">
                <a href="{{ url('/') }}" class="logo">
                    <img src="{{ asset('images/kivu-logo.png') }}" alt="KIVU" class="h-9 w-auto">
                </a>
            </div>

            <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear">
                <nav>
                    <div>
                        <h3 class="mb-4 text-xs leading-5 text-gray-400 uppercase">MENU ADMIN</h3>
                        <ul class="mb-6 flex flex-col gap-1">
                            @foreach($adminNav as $item)
                            <li>
                                <a href="{{ route($item['route']) }}" class="menu-item group {{ request()->routeIs($item['route']) ? 'menu-item-active' : 'menu-item-inactive' }}">
                                    <x-icon :name="$item['icon']" :size="24" class="{{ request()->routeIs($item['route']) ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}" />
                                    <span class="menu-item-text">{{ $item['label'] }}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </nav>
            </div>

            <div class="mt-auto pb-6">
                <a href="{{ url('/') }}" class="flex items-center gap-3 rounded-xl border border-gray-100 bg-gray-50 p-4 transition hover:border-brand-100 hover:bg-brand-50">
                    <img src="{{ asset('images/kivu-logo-tab.png') }}" alt="KIVU" class="h-8 w-8 rounded-lg">
                    <span>
                        <span class="block text-sm font-semibold text-gray-800">Kembali ke beranda</span>
                        <span class="block text-xs text-gray-500">Lihat situs publik KIVU</span>
                    </span>
                </a>
            </div>
        </aside>

        <div class="relative flex flex-1 flex-col overflow-x-hidden overflow-y-auto">
            <div id="admin-overlay" class="fixed inset-0 z-50 hidden bg-black/40 xl:hidden"></div>

            <header class="sticky top-0 z-[60] flex w-full border-b border-gray-200 bg-white">
                <div class="flex grow flex-col items-center justify-between xl:flex-row xl:px-6">
                    <div class="flex w-full items-center justify-between gap-2 border-b border-gray-200 px-3 py-3 sm:gap-4 xl:justify-between xl:border-b-0 xl:px-0">
                        <div class="flex items-center gap-3">
                            <button id="admin-sidebar-toggle" type="button" class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition hover:bg-gray-50 xl:hidden" aria-label="Buka menu">
                                <x-icon name="menu" :size="24" />
                            </button>
                            <a href="{{ url('/') }}" class="xl:hidden">
                                <img src="{{ asset('images/kivu-logo.png') }}" alt="KIVU" class="h-8 w-auto">
                            </a>
                        </div>
                        <h1 class="hidden text-lg font-semibold text-gray-800 xl:block">{{ $adminPageTitle }}</h1>

                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="relative" data-dropdown="admin-nav-dropdown">
                                <button type="button" data-dropdown-trigger class="relative flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition hover:bg-gray-50" aria-label="Menu admin">
                                    <x-icon name="bell" :size="20" />
                                </button>
                                <div id="admin-nav-dropdown" data-dropdown-menu class="absolute right-0 z-[70] mt-2 w-56 hidden rounded-2xl border border-gray-200 bg-white p-2 shadow-theme-lg">
                                    <p class="px-3 pt-2 pb-1 text-xs font-semibold text-gray-400 uppercase">Menu Admin</p>
                                    @foreach($adminNav as $item)
                                        @continue($item['route'] === 'admin.dashboard')
                                        <a href="{{ route($item['route']) }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
                                            <x-icon :name="$item['icon']" :size="18" class="text-gray-400" />
                                            {{ $item['label'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <div class="relative" data-dropdown="admin-user-dropdown">
                                <button type="button" data-dropdown-trigger class="flex items-center gap-2.5 rounded-lg p-1.5 transition hover:bg-gray-50">
                                    <x-ui.avatar :name="auth()->user()->name" />
                                    <span class="hidden text-left sm:block">
                                        <span class="block max-w-36 truncate text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</span>
                                        <span class="block text-xs text-gray-500 capitalize">Admin</span>
                                    </span>
                                    <x-icon name="chevron-down" :size="16" class="hidden shrink-0 text-gray-400 sm:block" />
                                </button>
                                <div id="admin-user-dropdown" data-dropdown-menu class="absolute right-0 z-[70] mt-2 w-64 hidden rounded-2xl border border-gray-200 bg-white shadow-theme-lg">
                                    <div class="border-b border-gray-100 p-4">
                                        <p class="truncate text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                                        <p class="truncate text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                    </div>
                                    <div class="p-2">
                                        <a href="{{ url('/') }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
                                            <x-icon name="home" :size="18" class="text-gray-400" />
                                            Lihat beranda KIVU
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-medium text-error-600 transition hover:bg-error-50">
                                                <x-icon name="log-out" :size="18" />
                                                Keluar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main>
                <div class="mx-auto max-w-7xl p-4 pb-10 md:p-6">
                    @if (session('success'))
                        <div class="mb-5 flex items-center gap-3 rounded-xl border border-success-200 bg-success-50 px-4 py-3 text-sm font-medium text-success-700">
                            <x-icon name="circle-check" :size="18" class="shrink-0 text-success-600" />
                            {{ session('success') }}
                        </div>
                    @endif
                    {{ $slot }}
                    <p class="mt-8 text-center text-xs text-gray-400">© {{ date('Y') }} KIVU — Marketplace Micro-Freelance Mahasiswa &times; UMKM</p>
                </div>
            </main>
        </div>
    </div>
    <script>
        (function () {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('admin-overlay');
            const toggleBtn = document.getElementById('admin-sidebar-toggle');

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function () {
                    sidebar.classList.toggle('-translate-x-full');
                    overlay.classList.toggle('hidden');
                });
            }
            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }

            function closeDropDowns(except) {
                document.querySelectorAll('[data-dropdown-menu]').forEach(function (menu) {
                    if (menu !== except) menu.classList.add('hidden');
                });
            }

            document.querySelectorAll('[data-dropdown-trigger]').forEach(function (trigger) {
                trigger.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const box = trigger.closest('[data-dropdown]');
                    const menu = box ? box.querySelector('[data-dropdown-menu]') : null;
                    if (!menu) return;
                    const willShow = menu.classList.contains('hidden');
                    closeDropDowns(menu);
                    if (willShow) menu.classList.remove('hidden');
                });
            });

            document.addEventListener('click', function (e) {
                if (!e.target.closest('[data-dropdown]')) closeDropDowns();
            });
        })();
    </script>
</body>
@else
@php
    $isStudent = auth()->user()->role === 'student';
    $marketNav = $isStudent ? [
        ['route' => 'student.dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard'],
        ['route' => 'student.opportunities', 'label' => 'Peluang', 'icon' => 'briefcase'],
        ['route' => 'student.my-applications', 'label' => 'Lamaran', 'icon' => 'file-text'],
        ['route' => 'student.wallet', 'label' => 'Dompet', 'icon' => 'wallet'],
        ['route' => 'student.profile', 'label' => 'Profil', 'icon' => 'user-round'],
    ] : [
        ['route' => 'umkm.dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard'],
        ['route' => 'umkm.my-projects', 'label' => 'Proyek Saya', 'icon' => 'folder-kanban'],
        ['route' => 'umkm.profile', 'label' => 'Profil', 'icon' => 'user-round'],
    ];
    $marketRoleLabel = $isStudent ? 'Mahasiswa' : 'UMKM';
@endphp
<body class="flex min-h-screen flex-col bg-gray-50 font-outfit text-gray-800 antialiased">
    <header class="sticky top-0 z-50 border-b border-gray-200 bg-white/95 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 md:px-6">
            <div class="flex items-center gap-6">
                <a href="{{ url('/') }}" class="flex items-center">
                    <img src="{{ asset('images/kivu-logo.png') }}" alt="KIVU" class="h-8 w-auto">
                </a>
                <nav class="hidden items-center gap-1 md:flex">
                    @foreach($marketNav as $item)
                        <a href="{{ route($item['route']) }}" wire:navigate class="rounded-lg px-3 py-2 text-sm font-semibold transition {{ request()->routeIs($item['route']) ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                    @if(!$isStudent)
                        <a href="{{ route('umkm.create-project') }}" wire:navigate class="ml-2 inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3.5 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
                            <x-icon name="plus-circle" :size="16" /> Buat Proyek
                        </a>
                    @endif
                </nav>
            </div>
            <div class="flex items-center gap-2">
                <button id="market-mobile-toggle" type="button" class="flex h-10 w-10 items-center justify-center rounded-lg text-gray-600 transition hover:bg-gray-100 md:hidden" aria-label="Buka menu">
                    <x-icon name="menu" :size="22" />
                </button>
                <div class="relative hidden md:block" data-dropdown="market-user">
                    <button type="button" data-dropdown-trigger class="flex items-center gap-2.5 rounded-full p-1.5 transition hover:bg-gray-100">
                        <x-ui.avatar :name="auth()->user()->name" size="sm" />
                        <span class="text-left leading-tight">
                            <span class="block max-w-36 truncate text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</span>
                            <span class="block text-xs text-gray-500">{{ $marketRoleLabel }}</span>
                        </span>
                        <x-icon name="chevron-down" :size="16" class="text-gray-400" />
                    </button>
                    <div data-dropdown-menu class="absolute right-0 z-50 mt-2 w-64 hidden rounded-2xl border border-gray-200 bg-white p-2 shadow-theme-lg">
                        <div class="border-b border-gray-100 px-3 py-2">
                            <p class="truncate text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                            <p class="truncate text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="p-1.5">
                            <a href="{{ route($isStudent ? 'student.profile' : 'umkm.profile') }}" wire:navigate class="flex items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
                                <x-icon name="user-round" :size="18" class="text-gray-400" /> Profil Saya
                            </a>
                            <a href="{{ url('/') }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
                                <x-icon name="home" :size="18" class="text-gray-400" /> Beranda KIVU
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-medium text-error-600 transition hover:bg-error-50">
                                    <x-icon name="log-out" :size="18" /> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="market-mobile-menu" class="hidden border-t border-gray-200 bg-white px-4 pt-2 pb-4 md:hidden">
            <nav class="flex flex-col gap-1">
                @foreach($marketNav as $item)
                    <a href="{{ route($item['route']) }}" wire:navigate class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold {{ request()->routeIs($item['route']) ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <x-icon :name="$item['icon']" :size="20" class="text-gray-400" /> {{ $item['label'] }}
                    </a>
                @endforeach
                @if(!$isStudent)
                    <a href="{{ route('umkm.create-project') }}" wire:navigate class="mt-1 flex items-center gap-3 rounded-lg bg-blue-600 px-3 py-2.5 text-sm font-semibold text-white">
                        <x-icon name="plus-circle" :size="20" /> Buat Proyek
                    </a>
                @endif
                <div class="my-2 border-t border-gray-100"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-error-600 hover:bg-error-50">
                        <x-icon name="log-out" :size="20" /> Keluar
                    </button>
                </form>
            </nav>
        </div>
    </header>

    <main class="flex-1">
        <div class="mx-auto max-w-7xl px-4 py-6 md:px-6 md:py-8">
            @if(session('success'))
                <div class="mb-5 flex items-center gap-3 rounded-xl border border-success-200 bg-success-50 px-4 py-3 text-sm font-medium text-success-700">
                    <x-icon name="circle-check" :size="18" class="shrink-0 text-success-600" /> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-5 flex items-center gap-3 rounded-xl border border-error-200 bg-error-50 px-4 py-3 text-sm font-medium text-error-700">
                    <x-icon name="alert-circle" :size="18" class="shrink-0 text-error-600" /> {{ session('error') }}
                </div>
            @endif
            {{ $slot }}
        </div>
    </main>

    <footer class="border-t border-gray-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-6 text-center text-xs text-gray-400 md:px-6">
            © {{ date('Y') }} KIVU — Marketplace Micro-Freelance Mahasiswa &times; UMKM
        </div>
    </footer>

    <script>
        (function () {
            const mbtn = document.getElementById('market-mobile-toggle');
            const mmenu = document.getElementById('market-mobile-menu');
            if (mbtn && mmenu) {
                mbtn.addEventListener('click', function () {
                    mmenu.classList.toggle('hidden');
                });
            }
            function closeDropDowns(except) {
                document.querySelectorAll('[data-dropdown-menu]').forEach(function (m) {
                    if (m !== except) m.classList.add('hidden');
                });
            }
            document.querySelectorAll('[data-dropdown-trigger]').forEach(function (t) {
                t.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const box = t.closest('[data-dropdown]');
                    const menu = box ? box.querySelector('[data-dropdown-menu]') : null;
                    if (!menu) return;
                    const willShow = menu.classList.contains('hidden');
                    closeDropDowns(menu);
                    if (willShow) menu.classList.remove('hidden');
                });
            });
            document.addEventListener('click', function (e) {
                if (!e.target.closest('[data-dropdown]')) closeDropDowns();
            });
        })();
    </script>
</body>
@endif
@endauth
</html>
