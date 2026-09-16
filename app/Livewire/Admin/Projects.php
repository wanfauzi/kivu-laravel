<?php

namespace App\Livewire\Admin;

use App\Models\Project;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class Projects extends Component
{
    use WithPagination;

    public string $search = '';

    public string $status = '';

    public ?int $projectToModerate = null;

    public bool $confirmingRemove = false;

    protected $queryString = ['search', 'status'];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function confirmRemove(int $id)
    {
        $project = Project::findOrFail($id);
        Gate::authorize('delete', $project);
        $this->projectToModerate = $id;
        $this->confirmingRemove = true;
    }

    public function cancelRemove()
    {
        $this->projectToModerate = null;
        $this->confirmingRemove = false;
    }

    public function remove()
    {
        $project = Project::findOrFail($this->projectToModerate);
        Gate::authorize('delete', $project);

        if ($project->status === 'COMPLETED') {
            $this->cancelRemove();
            session()->flash('error', 'Proyek selesai tidak dapat ditakedown. Gunakan Refund di menu Sengketa.');

            return;
        }

        $project->delete();
        $this->cancelRemove();
        session()->flash('success', 'Proyek telah dihapus dari platform.');
    }

    public function render()
    {
        $query = Project::with('owner');

        if ($this->search !== '') {
            $query->where('title', 'like', '%'.$this->search.'%');
        }

        if (in_array($this->status, ['OPEN', 'IN_PROGRESS', 'SUBMITTED', 'COMPLETED', 'CANCELLED'], true)) {
            $query->where('status', $this->status);
        }

        return view('livewire.admin.projects', [
            'projects' => $query->latest()->paginate(10),
        ])->layout('components.layouts.dashboard');
    }
}
