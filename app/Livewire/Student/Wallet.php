<?php

namespace App\Livewire\Student;

use App\Models\Transaction;
use App\Models\Wallet as WalletModel;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class Wallet extends Component
{
    use WithPagination;

    public $amount;

    public $bank_name;

    public $bank_account;

    public $note;

    public bool $confirming = false;

    public ?int $pendingCancelId = null;

    public bool $confirmingCancel = false;

    protected $rules = [
        'amount' => 'required|numeric|min:10000',
        'bank_name' => 'required|string|max:100',
        'bank_account' => 'required|string|max:50',
        'note' => 'nullable|string|max:255',
    ];

    public function confirmWithdraw()
    {
        $wallet = WalletModel::firstOrCreate(['student_id' => Auth::id()], ['balance' => 0]);

        if ($wallet->balance < 10000) {
            $this->addError('amount', 'Saldo tidak mencukupi. Saldo Anda Rp '.number_format($wallet->balance, 0, ',', '.').' minimal penarikan Rp 10.000.');

            return;
        }

        if ((int) $this->amount > $wallet->balance) {
            $this->addError('amount', 'Saldo tidak mencukupi. Saldo tersedia Rp '.number_format($wallet->balance, 0, ',', '.').'.');

            return;
        }

        $this->validate([
            'amount' => 'required|numeric|min:10000|max:'.$wallet->balance,
            'bank_name' => 'required|string|max:100',
            'bank_account' => 'required|string|max:50',
            'note' => 'nullable|string|max:255',
        ]);

        $this->confirming = true;
    }

    public function cancelWithdraw()
    {
        $this->confirming = false;
    }

    public function confirmCancel(int $id)
    {
        $wd = Withdrawal::findOrFail($id);
        Gate::authorize('cancel', $wd);
        $this->pendingCancelId = $id;
        $this->confirmingCancel = true;
    }

    public function cancelCancel()
    {
        $this->pendingCancelId = null;
        $this->confirmingCancel = false;
    }

    public function cancelWithdrawal()
    {
        $wd = Withdrawal::findOrFail($this->pendingCancelId);
        Gate::authorize('cancel', $wd);

        $ok = DB::transaction(function () use ($wd) {
            $locked = Withdrawal::whereKey($wd->id)->lockForUpdate()->first();
            if ($locked->status !== 'PENDING') {
                return false;
            }

            $locked->update(['status' => 'CANCELLED']);

            $wallet = WalletModel::where('student_id', $locked->student_id)->lockForUpdate()->firstOrCreate(['student_id' => $locked->student_id], ['balance' => 0]);
            $wallet->increment('balance', $locked->amount);

            Transaction::create([
                'student_id' => $locked->student_id,
                'withdrawal_id' => $locked->id,
                'amount' => $locked->amount,
                'type' => 'withdrawal',
                'status' => 'REJECTED',
            ]);

            return true;
        });

        $this->cancelCancel();

        if ($ok !== true) {
            session()->flash('error', 'Penarikan tidak dapat dibatalkan.');

            return;
        }

        session()->flash('success', 'Penarikan dibatalkan, saldo dikembalikan.');
    }

    public function withdraw()
    {
        $walletCheck = WalletModel::firstOrCreate(['student_id' => Auth::id()], ['balance' => 0]);

        $this->validate([
            'amount' => 'required|numeric|min:10000|max:'.$walletCheck->balance,
            'bank_name' => 'required|string|max:100',
            'bank_account' => 'required|string|max:50',
            'note' => 'nullable|string|max:255',
        ], [
            'amount.max' => 'Saldo tidak mencukupi. Saldo tersedia Rp '.number_format($walletCheck->balance, 0, ',', '.').'.',
        ]);

        $ok = DB::transaction(function () {
            $wallet = WalletModel::where('student_id', Auth::id())->lockForUpdate()->firstOrCreate(['student_id' => Auth::id()], ['balance' => 0]);

            if ($wallet->balance < $this->amount) {
                return false;
            }

            Withdrawal::create([
                'student_id' => Auth::id(),
                'amount' => $this->amount,
                'status' => 'PENDING',
                'bank_name' => $this->bank_name,
                'bank_account' => $this->bank_account,
                'note' => $this->note,
            ]);

            $wallet->decrement('balance', $this->amount);

            return true;
        });

        if ($ok !== true) {
            session()->flash('error', 'Saldo tidak mencukupi.');
            $this->cancelWithdraw();

            return;
        }

        $this->reset(['amount', 'bank_name', 'bank_account', 'note']);
        $this->cancelWithdraw();
        session()->flash('success', 'Permintaan penarikan berhasil dikirim.');
    }

    public function render()
    {
        $wallet = WalletModel::firstOrCreate(['student_id' => Auth::id()], ['balance' => 0]);
        $transactions = Transaction::with('project')
            ->where('student_id', Auth::id())
            ->latest()
            ->paginate(10);
        $withdrawals = Withdrawal::where('student_id', Auth::id())
            ->latest()
            ->get();

        $totalEarnings = Transaction::where('student_id', Auth::id())
            ->where('type', 'payment')
            ->where('status', 'SUCCESS')
            ->sum('amount')
            - Transaction::where('student_id', Auth::id())
                ->where('type', 'refund')
                ->where('status', 'SUCCESS')
                ->sum('amount');
        $totalWithdrawn = Withdrawal::where('student_id', Auth::id())
            ->where('status', 'APPROVED')
            ->sum('amount');

        return view('livewire.student.wallet', [
            'balance' => $wallet->balance,
            'transactions' => $transactions,
            'withdrawals' => $withdrawals,
            'totalEarnings' => $totalEarnings,
            'totalWithdrawn' => $totalWithdrawn,
        ])->layout('components.layouts.dashboard');
    }
}
