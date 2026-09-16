<?php

namespace App\Livewire\Umkm;

use App\Models\Application;
use App\Models\Project;
use App\Models\ProjectFund;
use App\Models\Review;
use App\Models\Submission;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Notifications\PaymentReleased;
use App\Notifications\RevisionRequested;
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
        $this->project = $project->load(['applications.student', 'owner', 'reviews', 'fund']);
        $this->hasReviewed = $this->project->reviews()->where('reviewer_id', Auth::id())->exists();
    }

    private function authorizeOwner(): void
    {
        if ($this->project->owner_id !== Auth::id()) {
            abort(403, 'Bukan pemilik proyek.');
        }
    }

    private function winnerApp(): ?Application
    {
        $cached = $this->project->applications->first(fn ($app) => $app->status === 'ACCEPTED');
        if ($cached) {
            return $cached;
        }
        return $this->project->winnerApp();
    }

    private function winnerSubmission(): ?Submission
    {
        $winner = $this->winnerApp();
        if (! $winner) {
            return null;
        }

        return Submission::with('student')
            ->where('project_id', $this->project->id)
            ->where('student_id', $winner->student_id)
            ->latest()
            ->first();
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

    public function releaseEscrow()
    {
        $this->authorizeOwner();
        $winner = $this->winnerApp();
        $submission = $winner ? $this->winnerSubmission() : null;

        if (! $winner || ! $submission) {
            session()->flash('error', 'Belum ada pemenang yang dipilih.');

            return;
        }

        if ($this->project->status === 'COMPLETED') {
            session()->flash('error', 'Proyek sudah selesai dan dana sudah dirilis.');

            return;
        }

        $winnerApp = $this->winnerApp();
        DB::transaction(function () use ($submission, $winnerApp) {
            $fund = ProjectFund::where('project_id', $this->project->id)
                ->where('status', 'LOCKED')
                ->lockForUpdate()
                ->first();

            if (! $fund) {
                return;
            }

            $project = Project::whereKey($this->project->id)->lockForUpdate()->first();
            if ($project->status === 'COMPLETED') {
                return;
            }

            $locked = Submission::lockForUpdate()->findOrFail($submission->id);
            $locked->update(['status' => 'APPROVED']);
            $project->update(['status' => 'COMPLETED']);

            $fund->update([
                'status' => 'RELEASED',
                'released_at' => now(),
            ]);

            $amount = min($this->project->agreedAmount($winnerApp), $fund->amount);

            Transaction::create([
                'project_id' => $this->project->id,
                'student_id' => $submission->student_id,
                'amount' => $amount,
                'type' => 'payment',
                'status' => 'SUCCESS',
                'payment_method' => 'qris',
                'payment_reference' => $fund->reference,
            ]);

            $wallet = Wallet::where('student_id', $submission->student_id)->lockForUpdate()->firstOrCreate(
                ['student_id' => $submission->student_id],
                ['balance' => 0]
            );
            $wallet->increment('balance', $amount);
        });

        $this->project->refresh();
        $this->showModal = false;

        if ($this->project->status !== 'COMPLETED') {
            session()->flash('error', 'Gagal merilis dana: dana escrow tidak ditemukan.');

            return;
        }

        $submission->student?->notify(new PaymentReleased($this->project, $this->project->agreedAmount($winnerApp)));

        session()->flash('success', 'Submission disetujui, dana escrow diteruskan ke dompet mahasiswa!');
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

        Review::create([
            'project_id' => $this->project->id,
            'reviewer_id' => Auth::id(),
            'reviewee_id' => $this->winnerApp()?->student_id,
            'rating' => $this->rating,
            'comment' => $this->comment,
        ]);

        $this->hasReviewed = true;
        session()->flash('success', 'Ulasan berhasil dikirim!');
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
        $winner = $this->winnerApp();
        $submission = $winner ? $this->winnerSubmission() : null;

        if (! $winner || ! $submission || $submission->status !== 'SUBMITTED') {
            session()->flash('error', 'Hanya hasil pemenang yang terkirim yang dapat direvisi.');

            return;
        }

        $this->validate([
            'revision_note' => 'required|string|max:1000',
        ]);

        DB::transaction(function () use ($submission) {
            $locked = Submission::lockForUpdate()->findOrFail($submission->id);
            $locked->update(['status' => 'REVISION', 'revision_note' => $this->revision_note]);
        });

        $this->project->refresh();
        $this->showRevisionModal = false;

        $submission->student?->notify(new RevisionRequested($this->project, $this->revision_note));

        session()->flash('success', 'Permintaan revisi dikirim, pemenang dapat mengirim ulang.');
    }

    public function render()
    {
        $winner = $this->winnerApp();
        $submission = $winner ? $this->winnerSubmission() : null;

        return view('livewire.umkm.review-submission', [
            'winner' => $winner,
            'submission' => $submission,
        ])->layout('components.layouts.dashboard');
    }
}