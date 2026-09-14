<?php

namespace App\Livewire\Student;

use App\Models\Application;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class ProjectDetail extends Component
{
    public Project $project;
    public string $message = '';
    public bool $showModal = false;
    public bool $hasApplied = false;

    public function mount(Project $project)
    {
        $this->project = $project->load('owner');
        $existing = Application::where('project_id', $project->id)
            ->where('student_id', Auth::id())->first();
        $this->hasApplied = $existing && $existing->status !== 'WITHDRAWN';
    }

    public function openModal()
    {
        if ($this->hasApplied) {
            session()->flash('error', 'Anda sudah melamar proyek ini.');
            return;
        }
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function apply()
    {
        $this->validate([
            'message' => 'nullable|string|max:1000',
        ]);

        if ($this->hasApplied) {
            session()->flash('error', 'Anda sudah melamar proyek ini.');
            $this->showModal = false;
            return;
        }

        if (!Gate::allows('apply', [Application::class, $this->project])) {
            session()->flash('error', 'Anda tidak dapat melamar proyek ini.');
            $this->showModal = false;
            return;
        }

        $existing = Application::where('project_id', $this->project->id)
            ->where('student_id', Auth::id())
            ->first();

        if ($existing && $existing->status === 'WITHDRAWN') {
            $existing->update(['status' => 'PENDING', 'message' => $this->message]);
        } else {
            Application::create([
                'project_id' => $this->project->id,
                'student_id' => Auth::id(),
                'status' => 'PENDING',
                'message' => $this->message,
            ]);
        }

        $this->hasApplied = true;
        $this->showModal = false;
        session()->flash('success', 'Lamaran berhasil dikirim!');
        $this->dispatch('toast', message: 'Lamaran berhasil dikirim!');
    }

    public function render()
    {
        return view('livewire.student.project-detail')
            ->layout('components.layouts.dashboard');
    }
}
