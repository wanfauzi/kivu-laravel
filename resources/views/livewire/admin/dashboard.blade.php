<div class="space-y-6">
    <div>
        <h1 class="text-title-sm font-bold text-gray-800">Dashboard Admin</h1>
        <p class="mt-1 text-sm text-gray-500">Ringkasan aktivitas platform KIVU.</p>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 text-brand-500">
                <x-icon name="users" :size="24" />
            </div>
            <div class="mt-5 flex items-end justify-between">
                <div>
                    <span class="text-sm text-gray-500">Total Pengguna</span>
                    <h4 class="text-title-sm mt-2 font-bold text-gray-800">{{ $totalUsers }}</h4>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 text-brand-500">
                <x-icon name="folder-kanban" :size="24" />
            </div>
            <div class="mt-5 flex items-end justify-between">
                <div>
                    <span class="text-sm text-gray-500">Total Proyek</span>
                    <h4 class="text-title-sm mt-2 font-bold text-gray-800">{{ $totalProjects }}</h4>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-success-50 text-success-500">
                <x-icon name="banknote" :size="24" />
            </div>
            <div class="mt-5 flex items-end justify-between">
                <div>
                    <span class="text-sm text-gray-500">Pembayaran Bersih</span>
                    <h4 class="text-title-sm mt-2 font-bold text-gray-800">Rp {{ number_format($totalPaymentNet, 0, ',', '.') }}</h4>
                    <span class="text-theme-xs mt-1 block text-gray-400">{{ $paymentCount }} pembayaran &middot; {{ $refundCount }} refund</span>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-light-50 text-blue-light-500">
                <x-icon name="arrow-left-right" :size="24" />
            </div>
            <div class="mt-5 flex items-end justify-between">
                <div>
                    <span class="text-sm text-gray-500">Total Penarikan</span>
                    <h4 class="text-title-sm mt-2 font-bold text-gray-800">Rp {{ number_format($totalWithdrawal, 0, ',', '.') }}</h4>
                    <span class="text-theme-xs mt-1 block text-gray-400">{{ $withdrawalCount }} penarikan</span>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-warning-50 text-warning-500">
                <x-icon name="clock" :size="24" />
            </div>
            <div class="mt-5 flex items-end justify-between">
                <div>
                    <span class="text-sm text-gray-500">Penarikan Pending</span>
                    <h4 class="text-title-sm mt-2 font-bold text-warning-600">{{ $pendingWithdrawals }}</h4>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 text-brand-500">
                <x-icon name="id-card" :size="24" />
            </div>
            <div class="mt-5 flex items-end justify-between">
                <div>
                    <span class="text-sm text-gray-500">Menunggu Verifikasi KTM</span>
                    <h4 class="text-title-sm mt-2 font-bold text-brand-600">{{ $pendingKtm }}</h4>
                    <span class="text-theme-xs mt-1 block text-gray-400">Perlu verifikasi admin</span>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-error-50 text-error-500">
                <x-icon name="shield-alert" :size="24" />
            </div>
            <div class="mt-5 flex items-end justify-between">
                <div>
                    <span class="text-sm text-gray-500">Sengketa Aktif</span>
                    <h4 class="text-title-sm mt-2 font-bold text-error-600">{{ $openDisputes }}</h4>
                    <span class="text-theme-xs mt-1 block text-gray-400">Menunggu keputusan</span>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
        <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 sm:px-6">
            <h3 class="text-lg font-semibold text-gray-800">Pengguna Terbaru</h3>
            <a href="{{ route('admin.users') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800">Lihat Semua</a>
        </div>
        <div class="custom-scrollbar max-w-full overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-5 py-3 text-left sm:px-6"><p class="text-theme-xs font-medium text-gray-500">Pengguna</p></th>
                        <th class="px-5 py-3 text-left sm:px-6"><p class="text-theme-xs font-medium text-gray-500">Email</p></th>
                        <th class="px-5 py-3 text-left sm:px-6"><p class="text-theme-xs font-medium text-gray-500">Role</p></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentUsers as $user)
                    <tr class="border-b border-gray-100 last:border-0">
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center gap-3">
                                <x-ui.avatar :name="$user->name" size="sm" />
                                <span class="text-theme-sm font-medium text-gray-800">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <p class="text-theme-sm text-gray-500">{{ $user->email }}</p>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            @php $variant = $user->role === 'admin' ? 'info' : ($user->role === 'umkm' ? 'success' : 'brand'); @endphp
                            <x-ui.pill :tone="$variant" class="capitalize">{{ $user->role }}</x-ui.pill>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-5 py-12 text-center sm:px-6">
                            <x-icon name="users" :size="48" stroke="1.25" class="mx-auto text-gray-300" />
                            <p class="mt-3 text-sm font-medium text-gray-500">Belum ada pengguna.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>