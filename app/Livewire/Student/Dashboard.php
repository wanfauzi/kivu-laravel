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

        $acceptedProjects = Application::where('student_id', $user->id)
            ->where('status', 'ACCEPTED')
            ->pluck('project_id');

        // Menunggu kirim hasil: diterima tapi belum ada submission, atau submission diminta revisi
        $awaitingSubmissionProjects = Project::whereIn('id', $acceptedProjects)
            ->where('status', 'IN_PROGRESS')
            ->where(function ($q) use ($user) {
                $q->whereDoesntHave('submission', function ($sub) use ($user) {
                    $sub->where('student_id', $user->id);
                })->orWhereHas('submission', function ($sub) use ($user) {
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

        return view('livewire.student.dashboard', [
            'balance' => $wallet->balance,
            'activeApplications' => $activeApplications,
            'awaitingSubmission' => $awaitingSubmissionProjects,
            'totalEarnings' => $totalEarnings,
            'latestApplications' => $latestApplications,
            'opportunities' => $opportunities,
        ])->layout('components.layouts.dashboard');
    }
}