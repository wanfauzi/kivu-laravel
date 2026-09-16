<?php

namespace App\Livewire\Admin;

use App\Models\Dispute;
use App\Models\Project;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $paymentSum = Transaction::where('type', 'payment')->where('status', 'SUCCESS')->sum('amount');
        $refundSum = Transaction::where('type', 'refund')->where('status', 'SUCCESS')->sum('amount');

        $months = collect(range(5, 0))->map(fn ($i) => now()->subMonths($i));
        $since = now()->subMonths(5)->startOfMonth();

        $groupByMonth = function (string $type) use ($since) {
            return Transaction::where('type', $type)
                ->where('status', 'SUCCESS')
                ->where('created_at', '>=', $since)
                ->get(['amount', 'created_at'])
                ->groupBy(fn ($t) => $t->created_at->format('Y-m'))
                ->map(fn ($rows) => (int) $rows->sum('amount'));
        };

        $paymentByMonth = $groupByMonth('payment');
        $withdrawalByMonth = $groupByMonth('withdrawal');

        return view('livewire.admin.dashboard', [
            'totalUsers' => User::count(),
            'totalProjects' => Project::count(),
            'totalPaymentNet' => $paymentSum - $refundSum,
            'totalWithdrawal' => Transaction::where('type', 'withdrawal')->where('status', 'SUCCESS')->sum('amount'),
            'transactionCount' => Transaction::count(),
            'paymentCount' => Transaction::where('type', 'payment')->count(),
            'withdrawalCount' => Transaction::where('type', 'withdrawal')->count(),
            'refundCount' => Transaction::where('type', 'refund')->count(),
            'pendingWithdrawals' => Withdrawal::where('status', 'PENDING')->count(),
            'pendingKtm' => User::where('status', 'pending_ktm')->count(),
            'openDisputes' => Dispute::where('status', 'OPEN')->count(),
            'recentUsers' => User::latest()->limit(5)->get(),
            'chartLabels' => $months->map(fn ($m) => $m->translatedFormat('M'))->all(),
            'paymentSeries' => $months->map(fn ($m) => $paymentByMonth[$m->format('Y-m')] ?? 0)->all(),
            'withdrawalSeries' => $months->map(fn ($m) => $withdrawalByMonth[$m->format('Y-m')] ?? 0)->all(),
        ])->layout('components.layouts.dashboard');
    }
}
