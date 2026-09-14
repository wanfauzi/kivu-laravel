<?php

namespace App\Livewire\Admin;

use App\Models\Application;
use App\Models\Dispute;
use App\Models\Project;
use App\Models\Submission;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class Disputes extends Component
{
    public ?int $actionDisputeId = null;
    public string $actionType = '';
    public bool $confirming = false;

    public function confirmAction(int $id, string $type)
    {
        $dispute = Dispute::findOrFail($id);
        Gate::authorize('resolve', $dispute);
        $this->actionDisputeId = $id;
        $this->actionType = $type;
        $this->confirming = true;
    }

    public function cancelAction()
    {
        $this->actionDisputeId = null;
        $this->actionType = '';
        $this->confirming = false;
    }

    public function runAction()
    {
        $result = DB::transaction(function () {
            $dispute = Dispute::lockForUpdate()->findOrFail($this->actionDisputeId);
            Gate::authorize('resolve', $dispute);

            if ($dispute->status !== 'OPEN') {
                return 'sudah diproses';
            }

            $project = Project::whereKey($dispute->project_id)->lockForUpdate()->first();

            if ($this->actionType === 'reject') {
                $dispute->update([
                    'status' => 'REJECTED',
                    'resolved_by' => Auth::id(),
                    'resolved_at' => now(),
                ]);
                return null;
            }

            $submission = Submission::where('project_id', $project->id)->lockForUpdate()->first();

            if ($this->actionType === 'release') {
                if (!$submission) {
                    return 'Proyek belum memiliki submission untuk dibayar.';
                }

                if ($submission->status === 'APPROVED' || $project->status === 'COMPLETED') {
                    return 'Proyek sudah dibayar. Gunakan Refund, bukan Release.';
                }

                $submission->update(['status' => 'APPROVED']);
                $project->update(['status' => 'COMPLETED']);

                Transaction::create([
                    'project_id' => $project->id,
                    'student_id' => $submission->student_id,
                    'amount' => $project->budget,
                    'type' => 'payment',
                    'status' => 'SUCCESS',
                ]);

                $wallet = Wallet::where('student_id', $submission->student_id)
                    ->lockForUpdate()
                    ->firstOrCreate(['student_id' => $submission->student_id], ['balance' => 0]);
                $wallet->increment('balance', $project->budget);

                $dispute->update([
                    'status' => 'RESOLVED',
                    'resolution' => 'release',
                    'resolved_by' => Auth::id(),
                    'resolved_at' => now(),
                ]);
                return null;
            }

            // refund
            $payment = Transaction::where('project_id', $project->id)
                ->where('type', 'payment')
                ->where('status', 'SUCCESS')
                ->first();

            if ($payment) {
                $wallet = Wallet::where('student_id', $payment->student_id)
                    ->lockForUpdate()
                    ->first();
                if (!$wallet || $wallet->balance < $payment->amount) {
                    return 'Saldo mahasiswa tidak mencukupi untuk refund.';
                }
                $wallet->decrement('balance', $payment->amount);
                Transaction::create([
                    'project_id' => $project->id,
                    'student_id' => $payment->student_id,
                    'amount' => $payment->amount,
                    'type' => 'refund',
                    'status' => 'SUCCESS',
                ]);
            }

            if ($submission) {
                $submission->delete();
            }

            Application::where('project_id', $project->id)
                ->where('status', 'ACCEPTED')
                ->update(['status' => 'PENDING']);

            $project->update(['status' => 'OPEN']);

            $dispute->update([
                'status' => 'RESOLVED',
                'resolution' => 'refund',
                'resolved_by' => Auth::id(),
                'resolved_at' => now(),
            ]);

            return null;
        });

        $this->cancelAction();

        if ($result !== null) {
            session()->flash('error', $result);
            return;
        }

        session()->flash('success', 'Sengketa diproses.');
    }

    public function render()
    {
        $disputes = Dispute::with(['project', 'reporter', 'against', 'resolver'])
            ->orderByRaw("FIELD(status, 'OPEN', 'RESOLVED', 'REJECTED')")
            ->latest()
            ->get();

        return view('livewire.admin.disputes', ['disputes' => $disputes])
            ->layout('components.layouts.dashboard');
    }
}
