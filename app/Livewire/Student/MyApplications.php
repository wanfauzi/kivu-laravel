<?php

namespace App\Livewire\Student;

use App\Models\Application;
use App\Models\Dispute;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class MyApplications extends Component
{
    public ?int $disputeProjectId = null;

    public string $reason = '';

    public string $description = '';

    public bool $confirmingDispute = false;

    public ?int $pendingWithdrawId = null;

    public bool $confirmingWithdraw = false;

    public ?int $pendingCancelDisputeId = null;

    public bool $confirmingCancelDispute = false;

    public function openDispute(int $projectId)
    {
        $project = Project::findOrFail($projectId);
        Gate::authorize('create', [Dispute::class, $project]);

        $this->disputeProjectId = $projectId;
        $this->reason = '';
        $this->description = '';
        $this->confirmingDispute = true;
    }

    public function cancelDispute()
    {
        $this->confirmingDispute = false;
        $this->disputeProjectId = null;
    }

    public function submitDispute()
    {
        $this->validate([
            'reason' => 'required|in:'.implode(',', array_keys(Dispute::REASONS)),
            'description' => 'required|string|min:10|max:2000',
        ]);

        $project = Project::findOrFail($this->disputeProjectId);
        Gate::authorize('create', [Dispute::class, $project]);

        try {
            DB::transaction(function () use ($project) {
                $exists = Dispute::where('project_id', $project->id)
                    ->where('reporter_id', Auth::id())
                    ->where('status', 'OPEN')
                    ->lockForUpdate()
                    ->exists();

                if ($exists) {
                    throw new \RuntimeException('Sengketa sudah diajukan.');
                }

                Dispute::create([
                    'project_id' => $project->id,
                    'reporter_id' => Auth::id(),
                    'against_id' => $project->owner_id,
                    'reason' => $this->reason,
                    'description' => $this->description,
                    'status' => 'OPEN',
                ]);
            });
        } catch (\RuntimeException $e) {
            $this->cancelDispute();
            session()->flash('error', $e->getMessage());

            return;
        }

        $this->cancelDispute();
        session()->flash('success', 'Sengketa berhasil diajukan. Admin akan meninjau.');
    }

    public function confirmWithdraw(int $applicationId)
    {
        $app = Application::findOrFail($applicationId);
        Gate::authorize('withdraw', $app);
        $this->pendingWithdrawId = $applicationId;
        $this->confirmingWithdraw = true;
    }

    public function cancelWithdraw()
    {
        $this->pendingWithdrawId = null;
        $this->confirmingWithdraw = false;
    }

    public function withdrawApplication()
    {
        $app = Application::findOrFail($this->pendingWithdrawId);
        Gate::authorize('withdraw', $app);
        $app->update(['status' => 'WITHDRAWN']);
        $this->cancelWithdraw();
        session()->flash('success', 'Lamaran berhasil dibatalkan.');
    }

    public function confirmCancelDispute(int $disputeId)
    {
        $d = Dispute::findOrFail($disputeId);
        Gate::authorize('cancel', $d);
        $this->pendingCancelDisputeId = $disputeId;
        $this->confirmingCancelDispute = true;
    }

    public function cancelCancelDispute()
    {
        $this->pendingCancelDisputeId = null;
        $this->confirmingCancelDispute = false;
    }

    public function cancelDisputeItem()
    {
        $d = Dispute::findOrFail($this->pendingCancelDisputeId);
        Gate::authorize('cancel', $d);
        $d->update(['status' => 'CANCELLED']);
        $this->cancelCancelDispute();
        session()->flash('success', 'Sengketa dibatalkan.');
    }

    public function render()
    {
        $applications = Application::with(['project.owner', 'project.submissions'])
            ->where('student_id', Auth::id())
            ->latest()
            ->get();

        $disputes = Dispute::with('against')
            ->whereIn('project_id', $applications->pluck('project_id'))
            ->where(function ($q) {
                $q->where('reporter_id', Auth::id())
                    ->orWhere('against_id', Auth::id());
            })
            ->latest()
            ->get()
            ->groupBy('project_id');

        return view('livewire.student.my-applications', [
            'applications' => $applications,
            'disputes' => $disputes,
        ])->layout('components.layouts.dashboard');
    }
}
