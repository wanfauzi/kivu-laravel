<?php

namespace App\Livewire\Umkm;

use App\Models\Application;
use App\Models\Project;
use App\Models\Review;
use App\Models\User;
use App\Services\StudentTrust;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class ManageApplicants extends Component
{
    public Project $project;
    public bool $showModal = false;
    public bool $showRejectModal = false;
    public bool $showProfileModal = false;
    public ?int $selectedApplicantId = null;
    public ?Application $selectedApplicant = null;
    public ?User $viewingStudent = null;
    public string $rejection_note = '';

    public function mount(Project $project)
    {
        if ($project->owner_id !== Auth::id()) {
            abort(403, 'Bukan pemilik proyek.');
        }
        $this->project = $project;
    }

    public function viewApplicantProfile(int $applicationId)
    {
        $app = Application::with('student')->findOrFail($applicationId);
        if ($app->project_id !== $this->project->id) {
            abort(403);
        }
        $this->viewingStudent = $app->student;
        $this->showProfileModal = true;
    }

    public function closeApplicantProfile()
    {
        $this->showProfileModal = false;
        $this->viewingStudent = null;
    }

    public function confirmAccept(int $applicationId)
    {
        $app = Application::findOrFail($applicationId);
        Gate::authorize('respond', $app);
        $this->selectedApplicantId = $applicationId;
        $this->selectedApplicant = $app->load('student');
        $this->showModal = true;
    }

    public function cancelAccept()
    {
        $this->showModal = false;
        $this->selectedApplicantId = null;
        $this->selectedApplicant = null;
    }

    public function confirmReject(int $applicationId)
    {
        $app = Application::findOrFail($applicationId);
        Gate::authorize('respond', $app);
        $this->selectedApplicantId = $applicationId;
        $this->selectedApplicant = $app->load('student');
        $this->rejection_note = '';
        $this->showRejectModal = true;
    }

    public function cancelReject()
    {
        $this->showRejectModal = false;
        $this->selectedApplicantId = null;
        $this->selectedApplicant = null;
        $this->rejection_note = '';
    }

    public function accept()
    {
        $app = Application::findOrFail($this->selectedApplicantId);
        Gate::authorize('respond', $app);

        $result = DB::transaction(function () use ($app) {
            $project = Project::whereKey($this->project->id)->lockForUpdate()->first();

            if ($project->status !== 'OPEN') {
                return 'Proyek tidak lagi menerima penerimaan pelamar.';
            }

            $locked = Application::lockForUpdate()->findOrFail($app->id);
            if ($locked->status !== 'PENDING') {
                return 'Lamaran sudah diproses.';
            }

            $locked->update(['status' => 'ACCEPTED']);
            $project->update(['status' => 'IN_PROGRESS']);

            return null;
        });

        $this->cancelAccept();

        if ($result !== null) {
            session()->flash('error', $result);
            return;
        }

        session()->flash('success', 'Pelamar berhasil diterima!');
    }

    public function reject()
    {
        $app = Application::findOrFail($this->selectedApplicantId);
        Gate::authorize('respond', $app);

        $this->validate([
            'rejection_note' => 'nullable|string|max:1000',
        ]);

        $result = DB::transaction(function () use ($app) {
            $locked = Application::lockForUpdate()->findOrFail($app->id);
            if ($locked->status !== 'PENDING') {
                return 'Lamaran sudah diproses.';
            }

            $locked->update([
                'status' => 'REJECTED',
                'rejection_note' => $this->rejection_note,
            ]);

            return null;
        });

        if ($result !== null) {
            session()->flash('error', $result);
        } else {
            session()->flash('success', 'Pelamar ditolak.');
        }

        $this->cancelReject();
    }

    public function render()
    {
        $applicants = Application::with('student')
            ->where('project_id', $this->project->id)
            ->latest()
            ->get();

        $studentIds = $applicants->pluck('student_id')->unique();
        $ratings = Review::whereIn('reviewee_id', $studentIds)
            ->selectRaw('reviewee_id, AVG(rating) as avg_rating, COUNT(*) as total')
            ->groupBy('reviewee_id')
            ->get()
            ->keyBy('reviewee_id');

        return view('livewire.umkm.manage-applicants', [
            'applicants' => $applicants,
            'ratings' => $ratings,
            'projectOpen' => $this->project->status === 'OPEN',
            'viewingPortfolios' => $this->viewingStudent ? $this->viewingStudent->portfolios()->get() : collect(),
            'viewingReviews' => $this->viewingStudent ? $this->viewingStudent->reviewsReceived()->with('reviewer')->take(5)->get() : collect(),
            'viewingTrust' => $this->viewingStudent ? StudentTrust::summary($this->viewingStudent) : null,
        ])->layout('components.layouts.dashboard');
    }
}
