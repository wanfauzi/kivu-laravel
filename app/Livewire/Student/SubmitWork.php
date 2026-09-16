<?php

namespace App\Livewire\Student;

use App\Models\Application;
use App\Models\Project;
use App\Models\Submission;
use App\Notifications\WorkSubmitted;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class SubmitWork extends Component
{
    use WithFileUploads;

    public Project $project;

    public $file;

    public string $link = '';

    public string $note = '';

    public ?Submission $existingSubmission = null;

    public bool $canSubmit = false;

    public bool $confirming = false;

    public function mount(Project $project)
    {
        $this->project = $project;
        $app = Application::where('project_id', $project->id)
            ->where('student_id', Auth::id())
            ->first();

        $this->canSubmit = $app
            && $project->status === 'OPEN'
            && in_array($app->status, ['PENDING', 'ACCEPTED'], true);

        $this->existingSubmission = Submission::where('project_id', $project->id)
            ->where('student_id', Auth::id())
            ->first();
    }

    private function rules(): array
    {
        return [
            'file' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,zip,mp4,mov,doc,docx,ppt,pptx,txt',
            'link' => 'nullable|url|max:255',
            'note' => 'nullable|string|max:1000',
        ];
    }

    public function confirmSubmit()
    {
        $this->validate($this->rules());

        if (! $this->canSubmit) {
            session()->flash('error', 'Kamu tidak dapat mengirim hasil untuk proyek ini.');

            return;
        }

        if (empty($this->file) && blank($this->link)) {
            $this->addError('link', 'Unggah file atau isi tautan hasil kerja.');

            return;
        }

        if ($this->existingSubmission) {
            if ($this->existingSubmission->status !== 'REVISION') {
                session()->flash('error', 'Hasil sudah dikirim.');

                return;
            }
            if (! Gate::allows('resubmit', $this->existingSubmission)) {
                session()->flash('error', 'Tidak diizinkan mengirim revisi.');

                return;
            }
        } elseif (! Gate::allows('create', [Submission::class, $this->project])) {
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
        $key = 'submit-work:'.Auth::id();

        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);
            session()->flash('error', "Terlalu sering mengirim hasil. Coba lagi dalam {$seconds} detik.");
            $this->cancelSubmit();

            return;
        }

        RateLimiter::hit($key, 60);

        $this->validate($this->rules());

        if (! $this->canSubmit || (empty($this->file) && blank($this->link))) {
            session()->flash('error', 'Data hasil belum lengkap.');
            $this->cancelSubmit();

            return;
        }

        $newFilePath = null;

        if ($this->file) {
            $newFilePath = $this->file->store('submissions/'.Auth::id(), 'local');
        }

        $result = DB::transaction(function () use ($newFilePath) {
            $project = Project::whereKey($this->project->id)->lockForUpdate()->first();
            $existing = Submission::where('project_id', $project->id)
                ->where('student_id', Auth::id())
                ->lockForUpdate()
                ->first();

            if ($existing) {
                if ($existing->status !== 'REVISION') {
                    return 'exists';
                }

                if ($newFilePath && $existing->file_path) {
                    Storage::disk('local')->delete($existing->file_path);
                }

                $existing->update([
                    'file_path' => $newFilePath ?? $existing->file_path,
                    'link' => $this->link !== '' ? $this->link : null,
                    'note' => $this->note !== '' ? $this->note : null,
                    'status' => 'SUBMITTED',
                ]);

                return 'ok';
            }

            if ($project->status !== 'OPEN') {
                return 'invalid';
            }

            if (! Gate::allows('create', [Submission::class, $project])) {
                return 'invalid';
            }

            Submission::create([
                'project_id' => $project->id,
                'student_id' => Auth::id(),
                'file_path' => $newFilePath,
                'link' => $this->link !== '' ? $this->link : null,
                'note' => $this->note !== '' ? $this->note : null,
                'status' => 'SUBMITTED',
            ]);

            return 'ok';
        });

        $this->cancelSubmit();

        if ($result !== 'ok') {
            session()->flash('error', $result === 'exists'
                ? 'Hasil sudah dikirim.'
                : 'Proyek tidak dalam keadaan dapat dikirim.');

            return;
        }

        $this->project->owner?->notify(new WorkSubmitted($this->project, Auth::user()));

        session()->flash('success', 'Hasil berhasil dikirim!');

        return redirect()->route('student.my-applications');
    }

    public function render()
    {
        return view('livewire.student.submit-work')
            ->layout('components.layouts.dashboard');
    }
}
