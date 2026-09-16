<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApplicationPolicy
{
    use HandlesAuthorization;

    public function apply(User $user, Project $project): bool
    {
        if ($user->role !== 'student') {
            return false;
        }

        if ($project->status !== 'OPEN' || $project->owner_id === $user->id) {
            return false;
        }

        $existing = Application::where('project_id', $project->id)
            ->where('student_id', $user->id)
            ->first();

        if (!$existing) {
            return true;
        }

        return $existing->status === 'WITHDRAWN';
    }

    public function withdraw(User $user, Application $application): bool
    {
        return $user->role === 'student'
            && $user->id === $application->student_id
            && $application->status === 'PENDING';
    }

    public function respond(User $user, Application $application): bool
    {
        return $user->role === 'umkm'
            && $application->status === 'PENDING'
            && $application->project->owner_id === $user->id;
    }

    public function view(User $user, Application $application): bool
    {
        return $user->role === 'admin'
            || $user->id === $application->student_id
            || $application->project->owner_id === $user->id;
    }
}
