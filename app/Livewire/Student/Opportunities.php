<?php

namespace App\Livewire\Student;

use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;

class Opportunities extends Component
{
    use WithPagination;

    public string $search = '';
    public string $budgetFilter = '';
    public string $sort = 'latest';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingBudgetFilter()
    {
        $this->resetPage();
    }

    public function updatingSort()
    {
        $this->resetPage();
    }

    public function resetFilter(): void
    {
        $this->search = '';
        $this->budgetFilter = '';
        $this->sort = 'latest';
        $this->resetPage();
    }

    public function render()
    {
        $query = Project::query()->where('status', 'OPEN');

        match ($this->sort) {
            'budget_high' => $query->orderByDesc('budget'),
            'budget_low' => $query->orderBy('budget'),
            default => $query->latest(),
        };

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->budgetFilter)) {
            if ($this->budgetFilter === 'low') {
                $query->where('budget', '<', 500000);
            } elseif ($this->budgetFilter === 'medium') {
                $query->whereBetween('budget', [500000, 2000000]);
            } elseif ($this->budgetFilter === 'high') {
                $query->where('budget', '>', 2000000);
            }
        }

        return view('livewire.student.opportunities', [
            'projects' => $query->paginate(9)
        ])->layout('components.layouts.dashboard');
    }
}
