<?php

namespace App\Livewire\Admin;

use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class Withdrawals extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';

    public ?int $pendingWdId = null;
    public string $pendingType = '';
    public bool $confirming = false;

    protected $queryString = ['search', 'status'];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function confirmApprove(int $id)
    {
        $wd = Withdrawal::findOrFail($id);
        Gate::authorize('update', $wd);
        $this->pendingWdId = $id;
        $this->pendingType = 'approve';
        $this->confirming = true;
    }

    public function confirmReject(int $id)
    {
        $wd = Withdrawal::findOrFail($id);
        Gate::authorize('update', $wd);
        $this->pendingWdId = $id;
        $this->pendingType = 'reject';
        $this->confirming = true;
    }

    public function cancelAction()
    {
        $this->pendingWdId = null;
        $this->pendingType = '';
        $this->confirming = false;
    }

    public function runAction()
    {
        $via = fn ($id) => function () use ($id) {
            $wd = Withdrawal::lockForUpdate()->findOrFail($id);
            Gate::authorize('update', $wd);
            return $wd;
        };

        DB::transaction(function () use ($via) {
            $wd = $this->pendingType === 'approve' || $this->pendingType === 'reject'
                ? $via($this->pendingWdId)()
                : null;

            if ($this->pendingType === 'approve') {
                $wd->update(['status' => 'APPROVED']);
                Transaction::create([
                    'student_id' => $wd->student_id,
                    'withdrawal_id' => $wd->id,
                    'amount' => $wd->amount,
                    'type' => 'withdrawal',
                    'status' => 'SUCCESS',
                ]);
                session()->flash('success', 'Penarikan disetujui.');
            } elseif ($this->pendingType === 'reject') {
                $wd->update(['status' => 'REJECTED']);
                $wallet = \App\Models\Wallet::where('student_id', $wd->student_id)->lockForUpdate()->first() ?? \App\Models\Wallet::firstOrCreate(['student_id' => $wd->student_id], ['balance' => 0]);
                $wallet->increment('balance', $wd->amount);
                Transaction::create([
                    'student_id' => $wd->student_id,
                    'withdrawal_id' => $wd->id,
                    'amount' => $wd->amount,
                    'type' => 'withdrawal',
                    'status' => 'REJECTED',
                ]);
                session()->flash('success', 'Penarikan ditolak dan saldo dikembalikan.');
            }
        });

        $this->cancelAction();
    }

    public function render()
    {
        $query = Withdrawal::with('student');

        if ($this->search !== '') {
            $query->whereHas('student', function ($s) {
                $s->where('name', 'like', '%' . $this->search . '%');
            });
        }

        if (in_array($this->status, ['PENDING', 'APPROVED', 'REJECTED'], true)) {
            $query->where('status', $this->status);
        }

        return view('livewire.admin.withdrawals', [
            'withdrawals' => $query->latest()->paginate(10),
        ])->layout('components.layouts.dashboard');
    }
}