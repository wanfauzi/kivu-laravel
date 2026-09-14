<?php

namespace App\Livewire\Public;

use App\Models\Project;
use App\Models\Review;
use App\Models\User;
use Livewire\Component;

class Landing extends Component
{
    public string $search = '';

    public function render()
    {
        $projectsQuery = Project::with('owner:id,name')->where('status', 'OPEN');

        if (trim($this->search) !== '') {
            $projectsQuery->where(fn ($q) => $q
                ->where('title', 'like', '%' . $this->search . '%')
                ->orWhere('description', 'like', '%' . $this->search . '%'));
        }

        $projects = $projectsQuery->latest()->take(6)->get();

        $studentCount = User::where('role', 'student')->where('status', 'active')->count();
        $openCount = Project::where('status', 'OPEN')->count();
        $completedCount = Project::where('status', 'COMPLETED')->count();

        $talent = User::where('role', 'student')->where('status', 'active')->latest()->take(12)->get();
        $reviewCounts = Review::whereIn('reviewee_id', $talent->pluck('id'))
            ->selectRaw('reviewee_id, count(*) as total')
            ->groupBy('reviewee_id')
            ->pluck('total', 'reviewee_id');
        $talent->each(fn ($u) => $u->setAttribute('review_count', $reviewCounts[$u->id] ?? 0));
        $talent = $talent->sortByDesc('review_count')->values()->take(8);

        return view('livewire.public.landing', compact(
            'projects',
            'studentCount',
            'openCount',
            'completedCount',
            'talent'
        ))->layout('layouts.public');
    }
}