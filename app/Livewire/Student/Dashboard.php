<?php

namespace App\Livewire\Student;

use App\Models\Application;
use App\Models\Project;
use App\Models\Submission;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user();

        $wallet = Wallet::firstOrCreate(['student_id' => $user->id], ['balance' => 0]);

        $activeApplications = Application::where('student_id', $user->id)
            ->whereIn('status', ['PENDING', 'ACCEPTED'])
            ->count();

        $submittableProjectIds = Application::where('student_id', $user->id)
            ->whereIn('status', ['PENDING', 'ACCEPTED'])
            ->pluck('project_id');

        // Menunggu kirim hasil: proyek terbuka/menunggu review, app aktif, belum ada submission (non-revisi)
        $awaitingSubmissionProjects = Project::whereIn('id', $submittableProjectIds)
            ->whereIn('status', ['OPEN', 'SUBMITTED'])
            ->where(function ($q) use ($user) {
                $q->whereDoesntHave('submissions', function ($sub) use ($user) {
                    $sub->where('student_id', $user->id);
                })->orWhereHas('submissions', function ($sub) use ($user) {
                    $sub->where('student_id', $user->id)->where('status', 'REVISION');
                });
            })
            ->get();

        $earned = Transaction::where('student_id', $user->id)
            ->where('type', 'payment')
            ->where('status', 'SUCCESS')
            ->sum('amount');
        $refunded = Transaction::where('student_id', $user->id)
            ->where('type', 'refund')
            ->where('status', 'SUCCESS')
            ->sum('amount');
        $totalEarnings = $earned - $refunded;

        $latestApplications = Application::with(['project.owner'])
            ->where('student_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $opportunities = Project::where('status', 'OPEN')->latest()->take(6)->get();

        $monthly = Transaction::where('student_id', $user->id)
            ->where('type', 'payment')
            ->where('status', 'SUCCESS')
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->get(['amount', 'created_at'])
            ->groupBy(fn ($t) => $t->created_at->format('Y-m'))
            ->map(fn ($rows) => (int) $rows->sum('amount'));

        $earningsLabels = [];
        $earningsData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $earningsLabels[] = $month->translatedFormat('M');
            $earningsData[] = $monthly[$month->format('Y-m')] ?? 0;
        }

        return view('livewire.student.dashboard', [
            'balance' => $wallet->balance,
            'activeApplications' => $activeApplications,
            'awaitingSubmission' => $awaitingSubmissionProjects,
            'totalEarnings' => $totalEarnings,
            'latestApplications' => $latestApplications,
            'opportunities' => $opportunities,
            'earningsLabels' => $earningsLabels,
            'earningsData' => $earningsData,
        ])->layout('components.layouts.dashboard');
    }
}
