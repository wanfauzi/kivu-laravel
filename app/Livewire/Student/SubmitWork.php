<?php

namespace App\Livewire\Student;

use App\Models\Application;
use App\Models\Project;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class SubmitWork extends Component
{
    public Project $project;
    public string $file_path = '';
    public string $link = '';
    public string $note = '';
    public ?Submission $existingSubmission = null;
    public bool $isAccepted = false;
    public bool $confirming = false;

    public function mount(Project $project)
    {
        $this->project = $project;
        $app = Application::where('project_id', $project->id)
            ->where('student_id', Auth::id())
            ->first();

        $this->isAccepted = $app && $app->status === 'ACCEPTED';

        if (!$this->isAccepted) {
            abort(403, 'Hanya pelamar yang diterima dapat mengirim hasil.');
        }

        $this->existingSubmission = Submission::where('project_id', $project->id)
            ->where('student_id', Auth::id())
            ->first();
    }

    public function confirmSubmit()
    {
        $this->validate([
            'file_path' => 'nullable|string|max:255',
            'link' => 'nullable|url|max:255',
            'note' => 'nullable|string|max:1000',
        ]);

        if (!$this->isAccepted) {
            session()->flash('error', 'Tidak diizinkan mengirim hasil.');
            return;
        }

        if (empty($this->file_path) && empty($this->link)) {
            session()->flash('error', 'Isi minimal file_path atau link.');
            return;
        }

        if ($this->existingSubmission) {
            if ($this->existingSubmission->status !== 'REVISION') {
                session()->flash('error', 'Hasil sudah dikirim.');
                return;
            }
            if (!Gate::allows('resubmit', $this->existingSubmission)) {
                session()->flash('error', 'Tidak diizinkan mengirim revisi.');
                return;
            }
        } elseif (!Gate::allows('create', [Submission::class, $this->project])) {
            session()->flash('error', 'Hasil tidak dapat dikirim (proyek tidak aktif atau sudah dikirim).');
            return;
        }

        $this->confirming = true;
    }

    public function cancelSubmit()
    {
        $this->confirming = false;
    }

    public function submit()
    {
        $this->validate([
            'file_path' => 'nullable|string|max:255',
            'link' => 'nullable|url|max:255',
            'note' => 'nullable|string|max:1000',
        ]);

        if (!$this->isAccepted || empty($this->file_path) && empty($this->link)) {
            session()->flash('error', 'Data hasil belum lengkap.');
            $this->cancelSubmit();
            return;
        }

        $result = DB::transaction(function () {
            $project = Project::whereKey($this->project->id)->lockForUpdate()->first();
            $existing = Submission::where('project_id', $project->id)
                ->where('student_id', Auth::id())
                ->lockForUpdate()
                ->first();

            if ($existing) {
                if ($existing->status === 'REVISION') {
                    $existing->update([
                        'file_path' => $this->file_path,
                        'link' => $this->link,
                        'note' => $this->note,
                        'status' => 'SUBMITTED',
                    ]);
                    $project->update(['status' => 'SUBMITTED']);
                    return 'ok';
                }

                return 'exists';
            }

            if ($project->status !== 'IN_PROGRESS') {
                return 'invalid';
            }

            if (!Gate::allows('create', [Submission::class, $project])) {
                return 'invalid';
            }

            Submission::create([
                'project_id' => $project->id,
                'student_id' => Auth::id(),
                'file_path' => $this->file_path,
                'link' => $this->link,
                'note' => $this->note,
                'status' => 'SUBMITTED',
            ]);

            $project->update(['status' => 'SUBMITTED']);

            return 'ok';
        });

        $this->cancelSubmit();

        if ($result !== 'ok') {
            session()->flash('error', $result === 'exists'
                ? 'Hasil sudah dikirim.'
                : 'Proyek tidak dalam keadaan dapat dikirim.');
            return;
        }

        session()->flash('success', 'Hasil berhasil dikirim!');
        return redirect()->route('student.my-applications');
    }

    public function render()
    {
        return view('livewire.student.submit-work')
            ->layout('components.layouts.dashboard');
    }
}
