<?php

namespace App\Livewire\Admin;

use App\Models\Application;
use App\Models\Dispute;
use App\Models\Project;
use App\Models\ProjectFund;
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

            $winnerApp = Application::where('project_id', $project->id)
                ->where('status', 'ACCEPTED')
                ->lockForUpdate()
                ->first();

            $winnerSubmission = $winnerApp
                ? Submission::where('project_id', $project->id)
                    ->where('student_id', $winnerApp->student_id)
                    ->lockForUpdate()
                    ->first()
                : Submission::where('project_id', $project->id)->lockForUpdate()->first();

            if ($this->actionType === 'release') {
                if (! $winnerSubmission) {
                    return 'Proyek belum memiliki hasil pemenang untuk dibayar.';
                }

                if ($winnerSubmission->status === 'APPROVED' || $project->status === 'COMPLETED') {
                    return 'Proyek sudah dibayar. Gunakan Refund, bukan Release.';
                }

                $fund = ProjectFund::where('project_id', $project->id)
                    ->where('status', 'LOCKED')
                    ->lockForUpdate()
                    ->first();

                if (! $fund) {
                    return 'Dana escrow belum terkunci untuk proyek ini.';
                }

                $winnerSubmission->update(['status' => 'APPROVED']);
                $project->update(['status' => 'COMPLETED']);

                $amount = min($project->agreedAmount($winnerApp), $fund->amount);

                $fund->update([
                    'status' => 'RELEASED',
                    'released_at' => now(),
                ]);

                Transaction::create([
                    'project_id' => $project->id,
                    'student_id' => $winnerSubmission->student_id,
                    'amount' => $amount,
                    'type' => 'payment',
                    'status' => 'SUCCESS',
                    'payment_method' => 'qris',
                    'payment_reference' => $fund->reference,
                ]);

                $wallet = Wallet::where('student_id', $winnerSubmission->student_id)
                    ->lockForUpdate()
                    ->firstOrCreate(['student_id' => $winnerSubmission->student_id], ['balance' => 0]);
                $wallet->increment('balance', $amount);

                $dispute->update([
                    'status' => 'RESOLVED',
                    'resolution' => 'release',
                    'resolved_by' => Auth::id(),
                    'resolved_at' => now(),
                ]);

                return null;
            }

            // refund: escrow kembali ke UMKM, semua hasil dihapus, lamaran kembali menunggu
            ProjectFund::where('project_id', $project->id)
                ->where('status', 'LOCKED')
                ->lockForUpdate()
                ->update([
                    'status' => 'REFUNDED',
                    'refunded_at' => now(),
                ]);

            Submission::where('project_id', $project->id)->delete();

            $project->applications()->update(['status' => 'PENDING']);

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
            ->orderByRaw("CASE status WHEN 'OPEN' THEN 0 WHEN 'RESOLVED' THEN 1 ELSE 2 END")
            ->latest()
            ->get();

        return view('livewire.admin.disputes', ['disputes' => $disputes])
            ->layout('components.layouts.dashboard');
    }
}
