<?php

namespace App\Livewire\Umkm;

use App\Models\Dispute;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class MyProjects extends Component
{
    public ?int $disputeProjectId = null;

    public string $reason = '';

    public string $description = '';

    public bool $confirmingDispute = false;

    public ?int $pendingCancelDisputeId = null;

    public bool $confirmingCancelDispute = false;

    public string $statusFilter = '';

    public function setStatus(string $status): void
    {
        $this->statusFilter = $this->statusFilter === $status ? '' : $status;
    }

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

        $accepted = $project->applications()->where('status', 'ACCEPTED')->first();

        try {
            DB::transaction(function () use ($project, $accepted) {
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
                    'against_id' => $accepted?->student_id ?? $project->owner_id,
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

    public function confirmCancelDisputeItem(int $disputeId)
    {
        $d = Dispute::findOrFail($disputeId);
        Gate::authorize('cancel', $d);
        $this->pendingCancelDisputeId = $disputeId;
        $this->confirmingCancelDispute = true;
    }

    public function closeCancelDisputeItem()
    {
        $this->pendingCancelDisputeId = null;
        $this->confirmingCancelDispute = false;
    }

    public function cancelDisputeItem()
    {
        $d = Dispute::findOrFail($this->pendingCancelDisputeId);
        Gate::authorize('cancel', $d);
        $d->update(['status' => 'CANCELLED']);
        $this->closeCancelDisputeItem();
        session()->flash('success', 'Sengketa dibatalkan.');
    }

    public function render()
    {
        $counts = Project::where('owner_id', Auth::id())
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $statuses = ['DRAFT', 'OPEN', 'IN_PROGRESS', 'SUBMITTED', 'COMPLETED', 'CANCELLED'];
        $labels = [
            'DRAFT' => 'Belum Dibayar',
            'OPEN' => 'Terbuka',
            'IN_PROGRESS' => 'Dikerjakan',
            'SUBMITTED' => 'Menunggu Review',
            'COMPLETED' => 'Selesai',
            'CANCELLED' => 'Dibatalkan',
        ];

        $tabs = [[
            'label' => 'Semua',
            'value' => '',
            'badge' => $counts->sum(),
        ]];

        foreach ($statuses as $status) {
            $tabs[] = [
                'label' => $labels[$status],
                'value' => $status,
                'badge' => $counts[$status] ?? 0,
            ];
        }

        $projects = Project::with(['category', 'skills'])
            ->where('owner_id', Auth::id())
            ->when($this->statusFilter !== '', fn ($q) => $q->where('status', $this->statusFilter))
            ->withCount('applications')
            ->withCount('submissions')
            ->with('applications')
            ->latest()
            ->get();

        $disputes = Dispute::with('against')
            ->whereIn('project_id', Project::where('owner_id', Auth::id())->pluck('id'))
            ->where(function ($q) {
                $q->where('reporter_id', Auth::id())
                    ->orWhere('against_id', Auth::id());
            })
            ->latest()
            ->get()
            ->groupBy('project_id');

        return view('livewire.umkm.my-projects', [
            'projects' => $projects,
            'disputes' => $disputes,
            'tabs' => $tabs,
        ])->layout('components.layouts.dashboard');
    }
}
