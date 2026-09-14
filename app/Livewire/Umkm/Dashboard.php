<?php

namespace App\Livewire\Umkm;

use App\Models\Application;
use App\Models\Project;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user();

        $activeProjects = Project::where('owner_id', $user->id)
            ->where('status', 'IN_PROGRESS')
            ->count();

        $openProjects = Project::where('owner_id', $user->id)
            ->where('status', 'OPEN')
            ->count();

        $pendingApplicants = Application::whereHas('project', function ($q) use ($user) {
            $q->where('owner_id', $user->id);
        })->where('status', 'PENDING')->count();

        $awaitingReview = Project::where('owner_id', $user->id)
            ->where('status', 'SUBMITTED')
            ->count();

        $totalPaid = Project::where('owner_id', $user->id)
            ->where('status', 'COMPLETED')
            ->sum('budget');

        $projects = Project::withCount('applications')
            ->where('owner_id', $user->id)
            ->latest()
            ->take(6)
            ->get();

        $pendingApplicantsList = Application::with(['student', 'project'])
            ->where('status', 'PENDING')
            ->whereHas('project', fn ($q) => $q->where('owner_id', $user->id))
            ->latest()
            ->take(5)
            ->get();

        $awaitingReviewProjects = Project::with('submission.student')
            ->where('owner_id', $user->id)
            ->where('status', 'SUBMITTED')
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.umkm.dashboard', [
            'activeProjects' => $activeProjects,
            'openProjects' => $openProjects,
            'pendingApplicants' => $pendingApplicants,
            'awaitingReview' => $awaitingReview,
            'totalPaid' => $totalPaid,
            'projects' => $projects,
            'pendingApplicantsList' => $pendingApplicantsList,
            'awaitingReviewProjects' => $awaitingReviewProjects,
        ])->layout('components.layouts.dashboard');
    }
}