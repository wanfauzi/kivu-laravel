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
        ])->layout('components.layouts.dashboard');
    }
}
