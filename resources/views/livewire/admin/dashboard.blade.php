<div class="space-y-6">
    <x-ui.page-header title="Dashboard Admin" subtitle="Ringkasan aktivitas platform KIVU." />

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-ui.stat-card icon="users" label="Total Pengguna" :value="$totalUsers" />
        <x-ui.stat-card icon="folder-kanban" label="Total Proyek" :value="$totalProjects" />
        <x-ui.stat-card icon="banknote" tone="success" label="Pembayaran Bersih" :value="'Rp '.number_format($totalPaymentNet, 0, ',', '.')" :hint="$paymentCount.' pembayaran · '.$refundCount.' refund'" />
        <x-ui.stat-card icon="wallet" tone="info" label="Total Penarikan" :value="'Rp '.number_format($totalWithdrawal, 0, ',', '.')" :hint="$withdrawalCount.' penarikan'" />
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <x-ui.stat-card icon="clock" tone="warning" label="Penarikan Pending" :value="$pendingWithdrawals" href="{{ route('admin.withdrawals') }}" />
        <x-ui.stat-card icon="id-card" tone="primary" label="Menunggu Verifikasi KTM" :value="$pendingKtm" hint="perlu verifikasi admin" href="{{ route('admin.users') }}" />
        <x-ui.stat-card icon="shield-alert" tone="danger" label="Sengketa Aktif" :value="$openDisputes" hint="menunggu keputusan" href="{{ route('admin.disputes') }}" />
    </div>

    <div class="kivu-card p-5 md:p-6">
        <h3 class="text-lg font-semibold text-kivu-text">Arus Dana 6 Bulan</h3>
        <p class="mt-1 text-sm text-kivu-text-secondary">Perbandingan pembayaran ke mahasiswa dan penarikan dana.</p>
        <div class="mt-4">
            <x-ui.chart
                type="line"
                :labels="$chartLabels"
                :series="[
                    ['label' => 'Pembayaran', 'data' => $paymentSeries, 'color' => null, 'fill' => true],
                    ['label' => 'Penarikan', 'data' => $withdrawalSeries, 'color' => null],
                ]"
                :height="240" />
        </div>
    </div>

    {{-- Recent users --}}
    <div class="kivu-card overflow-hidden">
        <div class="flex items-center justify-between border-b border-kivu-border px-5 py-4">
            <h3 class="text-lg font-semibold text-kivu-text">Pengguna Terbaru</h3>
            <x-ui.button href="{{ route('admin.users') }}" variant="secondary" size="sm">Lihat Semua</x-ui.button>
        </div>

        <div class="custom-scrollbar max-w-full overflow-x-auto">
            <table class="hidden w-full md:table">
                <tbody>
                    @forelse ($recentUsers as $user)
                        <tr class="border-b border-kivu-border last:border-0">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <x-ui.avatar :name="$user->name" size="sm" />
                                    <span class="text-sm font-medium text-kivu-text">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3"><p class="text-sm text-kivu-text-muted">{{ $user->email }}</p></td>
                            <td class="px-5 py-3">
                                <x-ui.pill :tone="$user->role === 'admin' ? 'info' : ($user->role === 'umkm' ? 'success' : 'primary')" class="capitalize">{{ $user->role }}</x-ui.pill>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-10 text-center text-sm text-kivu-text-muted">Belum ada pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="space-y-2 p-4 md:hidden">
                @forelse ($recentUsers as $user)
                    <div class="flex items-center gap-3 rounded-kivu-sm border border-kivu-border p-3">
                        <x-ui.avatar :name="$user->name" size="sm" />
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-kivu-text">{{ $user->name }}</p>
                            <p class="truncate text-xs text-kivu-text-muted">{{ $user->email }}</p>
                        </div>
                        <x-ui.pill :tone="$user->role === 'admin' ? 'info' : ($user->role === 'umkm' ? 'success' : 'primary')" class="capitalize">{{ $user->role }}</x-ui.pill>
                    </div>
                @empty
                    <p class="py-10 text-center text-sm text-kivu-text-muted">Belum ada pengguna.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>