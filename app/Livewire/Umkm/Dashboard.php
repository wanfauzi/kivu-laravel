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

        $awaitingReviewProjects = Project::withCount(['submissions'])
            ->where('owner_id', $user->id)
            ->where('status', 'SUBMITTED')
            ->latest()
            ->take(5)
            ->get();

        $awaitingReviewWinnerIds = $awaitingReviewProjects->isNotEmpty()
            ? Application::whereIn('project_id', $awaitingReviewProjects->pluck('id'))
                ->where('status', 'ACCEPTED')
                ->pluck('project_id')
                ->all()
            : [];

        $monthly = Transaction::where('type', 'payment')
            ->where('status', 'SUCCESS')
            ->whereHas('project', fn ($q) => $q->where('owner_id', $user->id))
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->get(['amount', 'created_at'])
            ->groupBy(fn ($t) => $t->created_at->format('Y-m'))
            ->map(fn ($rows) => (int) $rows->sum('amount'));

        $spendLabels = [];
        $spendData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $spendLabels[] = $month->translatedFormat('M');
            $spendData[] = $monthly[$month->format('Y-m')] ?? 0;
        }

        $recommendedTalent = \App\Models\User::where('role', 'student')->where('status', 'active')->latest()->take(4)->get();
        $recommendedServices = \App\Models\Portfolio::with(['student:id,name', 'category'])
            ->where('is_service', true)
            ->whereHas('student', fn ($q) => $q->where('role', 'student')->where('status', 'active'))
            ->latest()->take(4)->get();

        return view('livewire.umkm.dashboard', [
            'activeProjects' => $activeProjects,
            'openProjects' => $openProjects,
            'pendingApplicants' => $pendingApplicants,
            'awaitingReview' => $awaitingReview,
            'totalPaid' => $totalPaid,
            'projects' => $projects,
            'pendingApplicantsList' => $pendingApplicantsList,
            'awaitingReviewProjects' => $awaitingReviewProjects,
            'awaitingReviewWinnerIds' => $awaitingReviewWinnerIds,
            'spendLabels' => $spendLabels,
            'spendData' => $spendData,
            'recommendedTalent' => $recommendedTalent,
            'recommendedServices' => $recommendedServices,
        ])->layout('components.layouts.dashboard');
    }
}
