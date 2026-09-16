<?php

namespace App\Livewire\Umkm;

use App\Models\Application;
use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectFund;
use App\Models\Skill;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class EditProject extends Component
{
    public Project $project;

    public string $title = '';

    public string $description = '';

    public int $budget = 0;

    public ?int $min_budget = null;

    public ?int $max_budget = null;

    public string $category_id = '';

    public array $skillIds = [];

    public string $new_skill = '';

    public string $due_date = '';

    public bool $confirmingCancel = false;

    public function mount(Project $project)
    {
        Gate::authorize('update', $project);

        $this->project = $project;
        $this->title = $project->title;
        $this->description = $project->description;
        $this->budget = $project->budget;
        $this->min_budget = $project->min_budget ?? $project->budget;
        $this->max_budget = $project->max_budget ?? $project->budget;
        $this->category_id = $project->category_id ? (string) $project->category_id : '';
        $this->skillIds = $project->skills()->pluck('skills.id')->map(fn ($id) => (string) $id)->all();
        $this->due_date = $project->due_date?->format('Y-m-d') ?? '';
    }

    public function setBudgetRange(int $min, int $max)
    {
        $this->min_budget = $min;
        $this->max_budget = $max;
        $this->budget = $max;
    }

    public function addSkill(): void
    {
        $this->validate(['new_skill' => 'required|string|min:2|max:50']);

        if (count($this->skillIds) >= 8) {
            $this->addError('new_skill', 'Maksimal 8 keahlian.');
            return;
        }

        $skill = Skill::findOrCreateByName($this->new_skill);

        if (! in_array((string) $skill->id, $this->skillIds, true)) {
            $this->skillIds[] = (string) $skill->id;
        }

        $this->reset('new_skill');
        $this->resetValidation('new_skill');
    }

    public function update()
    {
        Gate::authorize('update', $this->project);

        $this->validate([
            'title' => 'required|string|min:10|max:255',
            'description' => 'required|string|min:50|max:5000',
            'min_budget' => 'required|integer|min:0',
            'max_budget' => 'required|integer|gte:min_budget|max:100000000',
            'category_id' => 'required|exists:categories,id',
            'skillIds' => 'array|max:8',
            'skillIds.*' => 'exists:skills,id',
            'due_date' => 'nullable|date',
        ]);

        $ok = $this->min_budget === ($this->project->min_budget ?? $this->project->budget)
            && $this->max_budget === ($this->project->max_budget ?? $this->project->budget);

        if (! $ok) {
            $funded = ProjectFund::where('project_id', $this->project->id)
                ->where('status', 'LOCKED')
                ->exists();

            if ($funded && $this->project->status !== 'DRAFT') {
                $this->addError('budget', 'Anggaran tidak dapat diubah setelah escrow terkunci.');
                return;
            }
        }

        $this->project->update([
            'title' => $this->title,
            'description' => $this->description,
            'budget' => $this->max_budget,
            'min_budget' => $this->min_budget,
            'max_budget' => $this->max_budget,
            'category_id' => $this->category_id,
            'due_date' => $this->due_date !== '' ? $this->due_date : null,
        ]);

        $this->project->skills()->sync($this->skillIds);

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

            ProjectFund::where('project_id', $project->id)
                ->where('status', 'LOCKED')
                ->lockForUpdate()
                ->update([
                    'status' => 'REFUNDED',
                    'refunded_at' => now(),
                ]);

            $project->update(['status' => 'CANCELLED']);
        });

        session()->flash('success', 'Proyek dibatalkan, escrow dikembalikan ke UMKM.');

        return redirect()->route('umkm.my-projects');
    }

    public function render()
    {
        return view('livewire.umkm.edit-project', [
            'categories' => Category::orderBy('sort_order')->get(),
            'skills' => Skill::orderBy('name')->get(),
        ])->layout('components.layouts.dashboard');
    }
}
