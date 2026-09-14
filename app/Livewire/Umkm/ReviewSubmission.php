<?php

namespace App\Livewire\Umkm;

use App\Models\Project;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ReviewSubmission extends Component
{
    public Project $project;
    public bool $showModal = false;
    public bool $showRevisionModal = false;
    public string $revision_note = '';
    public int $rating = 5;
    public string $comment = '';
    public bool $hasReviewed = false;

    public function mount(Project $project)
    {
        if ($project->owner_id !== Auth::id()) {
            abort(403, 'Bukan pemilik proyek.');
        }
        $this->project = $project->load(['submission.student', 'owner', 'reviews']);
        $this->hasReviewed = $this->project->reviews()->where('reviewer_id', Auth::id())->exists();
    }

    private function authorizeOwner(): void
    {
        if ($this->project->owner_id !== Auth::id()) {
            abort(403, 'Bukan pemilik proyek.');
        }
    }

    public function confirmApprove()
    {
        $this->authorizeOwner();
        $this->showModal = true;
    }

    public function cancelApprove()
    {
        $this->showModal = false;
    }

    public function openRevision()
    {
        $this->authorizeOwner();
        $this->showRevisionModal = true;
    }

    public function closeRevision()
    {
        $this->showRevisionModal = false;
        $this->revision_note = '';
    }

    public function requestRevision()
    {
        $this->authorizeOwner();
        $submission = $this->project->submission;

        if (!$submission || $submission->status !== 'SUBMITTED') {
            session()->flash('error', 'Hanya submission terkirim yang dapat direvisi.');
            return;
        }

        $this->validate([
            'revision_note' => 'required|string|max:1000',
        ]);

        DB::transaction(function () use ($submission) {
            $locked = \App\Models\Submission::lockForUpdate()->findOrFail($submission->id);
            $locked->update(['status' => 'REVISION', 'revision_note' => $this->revision_note]);
            $this->project->lockForUpdate()->update(['status' => 'IN_PROGRESS']);
        });

        $this->project->refresh();
        $this->showRevisionModal = false;
        session()->flash('success', 'Permintaan revisi dikirim, mahasiswa dapat mengirim ulang.');
    }

    public function approveAndPay()
    {
        $this->authorizeOwner();
        $submission = $this->project->submission;

        if (!$submission) {
            session()->flash('error', 'Belum ada submission.');
            return;
        }

        if ($submission->status === 'APPROVED') {
            session()->flash('error', 'Submission sudah disetujui.');
            return;
        }

        DB::transaction(function () use ($submission) {
            $locked = \App\Models\Submission::lockForUpdate()->findOrFail($submission->id);
            if ($locked->status === 'APPROVED') {
                return;
            }
            $locked->update(['status' => 'APPROVED']);
            $this->project->lockForUpdate()->update(['status' => 'COMPLETED']);

            \App\Models\Transaction::create([
                'project_id' => $this->project->id,
                'student_id' => $submission->student_id,
                'amount' => $this->project->budget,
                'type' => 'payment',
                'status' => 'SUCCESS',
            ]);

            $wallet = \App\Models\Wallet::where('student_id', $submission->student_id)->lockForUpdate()->firstOrCreate(
                ['student_id' => $submission->student_id],
                ['balance' => 0]
            );
            $wallet->increment('balance', $this->project->budget);
        });

        $this->project->refresh();
        $this->showModal = false;
        session()->flash('success', 'Submission disetujui dan dana dibayarkan!');
    }

    public function submitReview()
    {
        $this->authorizeOwner();
        if ($this->project->status !== 'COMPLETED') {
            session()->flash('error', 'Proyek harus selesai sebelum memberi ulasan.');
            return;
        }

        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        \App\Models\Review::create([
            'project_id' => $this->project->id,
            'reviewer_id' => Auth::id(),
            'reviewee_id' => $this->project->submission->student_id,
            'rating' => $this->rating,
            'comment' => $this->comment,
        ]);

        $this->hasReviewed = true;
        session()->flash('success', 'Ulasan berhasil dikirim!');
    }

    public function render()
    {
        return view('livewire.umkm.review-submission')
            ->layout('components.layouts.dashboard');
    }
}
