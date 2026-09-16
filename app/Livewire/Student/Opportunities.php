<?php

namespace App\Livewire\Student;

use App\Models\Category;
use App\Models\Project;
use App\Models\Skill;
use Livewire\Component;
use Livewire\WithPagination;

class Opportunities extends Component
{
    use WithPagination;

    public string $search = '';

    public string $budgetFilter = '';

    public string $category = '';

    public string $skill = '';

    public string $deadlineFilter = '';

    public string $sort = 'latest';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingBudgetFilter(): void
    {
        $this->resetPage();
    }

    public function updatingDeadlineFilter(): void
    {
        $this->resetPage();
    }

    public function updatingSort(): void
    {
        $this->resetPage();
    }

    public function selectCategory(string $slug): void
    {
        $this->category = $this->category === $slug ? '' : $slug;
        $this->resetPage();
    }

    public function selectSkill(string $slug): void
    {
        $this->skill = $this->skill === $slug ? '' : $slug;
        $this->resetPage();
    }

    public function resetFilter(): void
    {
        $this->search = '';
        $this->budgetFilter = '';
        $this->category = '';
        $this->skill = '';
        $this->deadlineFilter = '';
        $this->sort = 'latest';
        $this->resetPage();
    }

    public function render()
    {
        // OFFLINE-FEATURE-30%: Implementasi AI Recommendation Engine (Vector Search/Similarity) untuk matching skill
        $query = Project::with(['owner:id,name', 'category', 'skills'])
            ->where('status', 'OPEN');

        match ($this->sort) {
            'budget_high' => $query->orderByDesc('budget'),
            'budget_low' => $query->orderBy('budget'),
            'deadline' => $query->orderByRaw('due_date IS NULL')->orderBy('due_date'),
            default => $query->latest(),
        };

        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->budgetFilter !== '') {
            if ($this->budgetFilter === 'low') {
                $query->where('budget', '<', 500000);
            } elseif ($this->budgetFilter === 'medium') {
                $query->whereBetween('budget', [500000, 2000000]);
            } elseif ($this->budgetFilter === 'high') {
                $query->where('budget', '>', 2000000);
            }
        }

        if ($this->category !== '') {
            $query->whereHas('category', fn ($q) => $q->where('slug', $this->category));
        }

        if ($this->skill !== '') {
            $query->whereHas('skills', fn ($q) => $q->where('slug', $this->skill));
        }

        if ($this->deadlineFilter === 'soon') {
            $query->whereNotNull('due_date')->whereBetween('due_date', [now()->toDateString(), now()->addDays(7)->toDateString()]);
        } elseif ($this->deadlineFilter === 'month') {
            $query->whereNotNull('due_date')->whereBetween('due_date', [now()->toDateString(), now()->addDays(30)->toDateString()]);
        } elseif ($this->deadlineFilter === 'flexible') {
            $query->whereNull('due_date');
        }

        $categories = Category::orderBy('sort_order')->get();

        $skills = Skill::query()
            ->withCount(['projects as open_count' => fn ($q) => $q->where('status', 'OPEN')])
            ->get()
            ->filter(fn ($s) => $s->open_count > 0)
            ->sortByDesc('open_count')
            ->take(12)
            ->values();

        return view('livewire.student.opportunities', [
            'projects' => $query->paginate(9),
            'categories' => $categories,
            'skills' => $skills,
            'hasFilters' => $this->search !== ''
                || $this->budgetFilter !== ''
                || $this->category !== ''
                || $this->skill !== ''
                || $this->deadlineFilter !== ''
                || $this->sort !== 'latest',
        ])->layout('components.layouts.dashboard');
    }
}
