<?php

namespace App\Livewire\Umkm;

use App\Models\Application;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class EditProject extends Component
{
    public Project $project;
    public string $title = '';
    public string $description = '';
    public int $budget = 0;
    public bool $confirmingCancel = false;

    public function mount(Project $project)
    {
        Gate::authorize('update', $project);
        $this->project = $project;
        $this->title = $project->title;
        $this->description = $project->description;
        $this->budget = $project->budget;
    }

    public function update()
    {
        Gate::authorize('update', $this->project);

        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'budget' => 'required|numeric|min:100000',
        ]);

        $this->project->update([
            'title' => $this->title,
            'description' => $this->description,
            'budget' => $this->budget,
        ]);

        session()->flash('success', 'Proyek berhasil diperbarui.');
    }

    public function confirmCancel()
    {
        Gate::authorize('cancel', $this->project);
        $this->confirmingCancel = true;
    }

    public function closeCancel()
    {
        $this->confirmingCancel = false;
    }

    public function cancelProject()
    {
        Gate::authorize('cancel', $this->project);

        DB::transaction(function () {
            $project = Project::whereKey($this->project->id)->lockForUpdate()->first();

            if ($project->status !== 'OPEN') {
                return;
            }

            Application::where('project_id', $project->id)
                ->where('status', 'PENDING')
                ->update(['status' => 'REJECTED', 'rejection_note' => 'Proyek dibatalkan oleh UMKM.']);

            $project->update(['status' => 'CANCELLED']);
        });

        session()->flash('success', 'Proyek dibatalkan.');
        return redirect()->route('umkm.my-projects');
    }

    public function render()
    {
        return view('livewire.umkm.edit-project')
            ->layout('components.layouts.dashboard');
    }
}
