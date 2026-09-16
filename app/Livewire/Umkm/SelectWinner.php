<?php

namespace App\Livewire\Umkm;

use App\Models\Application;
use App\Models\Project;
use App\Models\Review;
use App\Models\Submission;
use App\Notifications\ApplicationAccepted;
use App\Notifications\ApplicationRejected;
use App\Services\StudentTrust;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SelectWinner extends Component
{
    public Project $project;

    public bool $showModal = false;

    public ?int $selectedSubmissionId = null;

    public function mount(Project $project)
    {
        if ($project->owner_id !== Auth::id()) {
            abort(403, 'Bukan pemilik proyek.');
        }
        $this->project = $project;

        if ($project->winnerApp()) {
            $this->redirectRoute('umkm.review-submission', $project->id);
        }
    }

    public function confirmSelect(int $submissionId)
    {
        $this->authorizeOwner();
        $submission = Submission::findOrFail($submissionId);
        if ($submission->project_id !== $this->project->id) {
            abort(403, 'Submission bukan milik proyek ini.');
        }
        $this->selectedSubmissionId = $submissionId;
        $this->showModal = true;
    }

    public function cancelSelect()
    {
        $this->showModal = false;
        $this->selectedSubmissionId = null;
    }

    public function selectWinner()
    {
        $this->authorizeOwner();

        if ($this->project->winnerApp()) {
            session()->flash('error', 'Pemenang sudah dipilih untuk proyek ini.');
            $this->cancelSelect();

            return;
        }

        $result = DB::transaction(function () {
            $project = Project::whereKey($this->project->id)->lockForUpdate()->first();

            if (! in_array($project->status, ['OPEN', 'SUBMITTED'], true)) {
                return 'Proyek tidak lagi dalam keadaan dapat menerima pemenang.';
            }

            $submission = Submission::lockForUpdate()->find($this->selectedSubmissionId);
            if (! $submission || $submission->project_id !== $project->id) {
                return 'Submission tidak ditemukan.';
            }

            if ($submission->status === 'APPROVED') {
                return 'Submission ini sudah disetujui.';
            }

            $winnerApp = Application::where('project_id', $project->id)
                ->where('student_id', $submission->student_id)
                ->where('status', 'PENDING')
                ->lockForUpdate()
                ->first();

            if (! $winnerApp) {
                return 'Pelamar tidak dalam status dapat dipilih.';
            }

            $winnerApp->update(['status' => 'ACCEPTED']);

            Application::where('project_id', $project->id)
                ->where('status', 'PENDING')
                ->update([
                    'status' => 'REJECTED',
                    'rejection_note' => 'Kandidat lain dipilih sebagai pemenang.',
                ]);

            $submission->update(['status' => 'SUBMITTED']);

            return null;
        });

        $this->cancelSelect();

        if ($result !== null) {
            session()->flash('error', $result);

            return;
        }

        $this->project->refresh();

        $winnerApp = $this->project->winnerApp();
        $winnerApp?->student?->notify(new ApplicationAccepted($this->project->refresh()));

        $loserApps = Application::where('project_id', $this->project->id)
            ->where('status', 'REJECTED')
            ->where('student_id', '!=', $winnerApp?->student_id)
            ->get();

        foreach ($loserApps as $loser) {
            $loser->student?->notify(new ApplicationRejected($this->project, 'Kandidat lain dipilih sebagai pemenang.'));
        }

        session()->flash('success', 'Pemenang dipilih! Silakan tinjau atau rilis dana.');

        return redirect()->route('umkm.review-submission', $this->project->id);
    }

    public function authorizeOwner(): void
    {
        if ($this->project->owner_id !== Auth::id()) {
            abort(403, 'Bukan pemilik proyek.');
        }
    }

    public function render()
    {
        $submissions = Submission::with('student')
            ->where('project_id', $this->project->id)
            ->get();

        $studentIds = $submissions->pluck('student_id')->unique();
        $ratings = Review::whereIn('reviewee_id', $studentIds)
            ->selectRaw('reviewee_id, AVG(rating) as avg_rating, COUNT(*) as total')
            ->groupBy('reviewee_id')
            ->get()
            ->keyBy('reviewee_id');

        $bids = Application::where('project_id', $this->project->id)
            ->whereNotNull('bid_amount')
            ->pluck('bid_amount', 'student_id');

        return view('livewire.umkm.select-winner', [
            'submissions' => $submissions,
            'ratings' => $ratings,
            'bids' => $bids,
        ])->layout('components.layouts.dashboard');
    }
}