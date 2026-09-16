<?php

namespace App\Livewire\Public;

use App\Models\Category;
use App\Models\Project;
use App\Models\Review;
use App\Models\User;
use Livewire\Component;

class Landing extends Component
{
    public string $search = '';

    public string $category = '';

    public string $budget = '';

    public string $deadline = '';

    public string $sort = 'terbaru';

    public string $skill = '';

    public function selectCategory(string $slug): void
    {
        $this->category = $this->category === $slug ? '' : $slug;
    }

    public function selectSkill(string $skill): void
    {
        $this->skill = $this->skill === $skill ? '' : $skill;
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->category = '';
        $this->budget = '';
        $this->deadline = '';
        $this->sort = 'terbaru';
        $this->skill = '';
    }

    public function updatedBudget(): void
    {
        $allowed = ['', 'lt500', '500-1500', 'gt1500'];
        if (! in_array($this->budget, $allowed, true)) $this->budget = '';
    }

    public function updatedDeadline(): void
    {
        $allowed = ['', 'lt3', 'lt7', 'gt7'];
        if (! in_array($this->deadline, $allowed, true)) $this->deadline = '';
    }

    public function updatedSort(): void
    {
        $allowed = ['terbaru', 'termurah', 'termahal', 'deadline'];
        if (! in_array($this->sort, $allowed, true)) $this->sort = 'terbaru';
    }

    public function render()
    {
        $projectsQuery = Project::with(['owner:id,name', 'category', 'skills'])
            ->where('status', 'OPEN');

        if (trim($this->search) !== '') {
            $search = trim($this->search);
            $projectsQuery->where(fn ($q) => $q
                ->where('title', 'like', '%'.$search.'%')
                ->orWhere('description', 'like', '%'.$search.'%'));
        }

        if ($this->category !== '') {
            $projectsQuery->whereHas('category', fn ($q) => $q->where('slug', $this->category));
        }

        if ($this->skill !== '') {
            $projectsQuery->whereHas('skills', fn ($q) => $q->where('slug', $this->skill)->orWhere('name', $this->skill));
        }

        if ($this->budget === 'lt500') {
            $projectsQuery->where('budget', '<', 500000);
        } elseif ($this->budget === '500-1500') {
            $projectsQuery->whereBetween('budget', [500000, 1500000]);
        } elseif ($this->budget === 'gt1500') {
            $projectsQuery->where('budget', '>', 1500000);
        }

        if ($this->deadline === 'lt3') {
            $projectsQuery->where('due_date', '<=', now()->addDays(3));
        } elseif ($this->deadline === 'lt7') {
            $projectsQuery->where('due_date', '<=', now()->addDays(7));
        } elseif ($this->deadline === 'gt7') {
            $projectsQuery->where('due_date', '>', now()->addDays(7));
        }

        $sort = $this->sort;
        if ($sort === 'termurah') {
            $projectsQuery->orderBy('budget', 'asc');
        } elseif ($sort === 'termahal') {
            $projectsQuery->orderBy('budget', 'desc');
        } elseif ($sort === 'deadline') {
            $projectsQuery->orderBy('due_date', 'asc');
        } else {
            $projectsQuery->latest();
        }

        $projects = $projectsQuery->take(6)->get();

        $categories = Category::query()
            ->withCount(['projects as open_projects_count' => fn ($q) => $q->where('status', 'OPEN')])
            ->orderBy('sort_order')
            ->get();

        $studentCount = User::where('role', 'student')->where('status', 'active')->count();
        $openCount = Project::where('status', 'OPEN')->count();
        $completedCount = Project::where('status', 'COMPLETED')->count();

        $skills = \App\Models\Skill::orderBy('name')->take(8)->get(['slug', 'name']);

        $talent = User::where('role', 'student')->where('status', 'active')->with('portfolios')->latest()->take(12)->get();
        $reviewCounts = Review::whereIn('reviewee_id', $talent->pluck('id'))
            ->selectRaw('reviewee_id, count(*) as total')
            ->groupBy('reviewee_id')
            ->pluck('total', 'reviewee_id');
        $talent->each(fn ($u) => $u->setAttribute('review_count', $reviewCounts[$u->id] ?? 0));
        $talent = $talent->sortByDesc('review_count')->values()->take(8);

        $featuredQuery = \App\Models\Portfolio::with(['student:id,name,skills,status', 'category'])
            ->where('is_service', true)
            ->whereHas('student', fn ($q) => $q->where('role', 'student')->where('status', 'active'));
        if (trim($this->search) !== '') {
            $search = trim($this->search);
            $featuredQuery->where(fn ($q) => $q->where('title', 'like', '%'.$search.'%')->orWhere('description', 'like', '%'.$search.'%'));
        }
        if ($this->category !== '') {
            $featuredQuery->whereHas('category', fn ($q) => $q->where('slug', $this->category));
        }
        $featuredPortfolios = $featuredQuery->latest()->take(6)->get();

        $isFallback = false;
        if ($featuredPortfolios->isEmpty() && trim($this->search) === '' && $this->category === '') {
            $featuredPortfolios = \App\Models\Portfolio::with(['student:id,name,skills,status', 'category'])
                ->whereHas('student', fn ($q) => $q->where('role', 'student')->where('status', 'active'))
                ->latest()
                ->take(6)
                ->get();
            $isFallback = true;
        }

        return view('livewire.public.landing', [
            'featured' => Project::with(['owner:id,name', 'category'])->where('status', 'OPEN')->latest()->first(),
            'projects' => $projects,
            'categories' => $categories,
            'skills' => $skills,
            'studentCount' => $studentCount,
            'openCount' => $openCount,
            'completedCount' => $completedCount,
            'talent' => $talent,
            'featuredPortfolios' => $featuredPortfolios,
            'isFallback' => $isFallback,
        ])->layout('layouts.public', [
            'title' => 'KIVU — Marketplace Micro-Freelance Mahasiswa × UMKM',
            'description' => 'KIVU menghubungkan mahasiswa berbakat dengan UMKM yang butuh bantuan nyata — desain, website, konten, foto produk, dan tugas kreatif lainnya. Posting, lamar, kerja, dibayar.',
        ]);
    }
}
