<?php

namespace App\Livewire\Umkm;

use App\Models\Category;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateProject extends Component
{
    public string $title = '';

    public string $description = '';

    public int $budget = 0;

    public ?int $min_budget = null;

    public ?int $max_budget = null;

    public string $category_id = '';

    public array $skillIds = [];

    public string $new_skill = '';

    public string $due_date = '';

    public function setBudgetRange(int $min, int $max)
    {
        $this->min_budget = $min;
        $this->max_budget = $max;
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

    public function store()
    {
        $this->validate([
            'title' => 'required|string|min:10|max:255',
            'description' => 'required|string|min:50|max:5000',
            'min_budget' => 'required|integer|min:0',
            'max_budget' => 'required|integer|gte:min_budget|max:100000000',
            'category_id' => 'required|exists:categories,id',
            'skillIds' => 'array|max:8',
            'skillIds.*' => 'exists:skills,id',
            'due_date' => 'nullable|date|after_or_equal:today',
        ]);

        $project = Project::create([
            'owner_id' => Auth::id(),
            'category_id' => $this->category_id,
            'title' => $this->title,
            'description' => $this->description,
            'budget' => $this->max_budget,
            'min_budget' => $this->min_budget,
            'max_budget' => $this->max_budget,
            'due_date' => $this->due_date !== '' ? $this->due_date : null,
            'status' => 'DRAFT',
        ]);

        $project->skills()->sync($this->skillIds);

        session()->flash('success', 'Proyek berhasil dibuat! Silakan lakukan pembayaran untuk mempublikasikan proyek.');

        return redirect()->route('umkm.project-fund', $project->id);
    }

    public function render()
    {
        return view('livewire.umkm.create-project', [
            'categories' => Category::orderBy('sort_order')->get(),
            'skills' => Skill::orderBy('name')->get(),
        ])->layout('components.layouts.dashboard');
    }
}
