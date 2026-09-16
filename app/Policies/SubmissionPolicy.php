<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubmissionPolicy
{
    use HandlesAuthorization;

    public function create(User $user, Project $project): bool
    {
        if ($user->role !== 'student' || $project->status !== 'OPEN') {
            return false;
        }

        $hasActiveApplication = $project->applications()
            ->where('student_id', $user->id)
            ->whereIn('status', ['PENDING', 'ACCEPTED'])
            ->exists();

        if (! $hasActiveApplication) {
            return false;
        }

        return ! Submission::where('project_id', $project->id)
            ->where('student_id', $user->id)
            ->where('status', '!=', 'REVISION')
            ->exists();
    }

    public function resubmit(User $user, Submission $submission): bool
    {
        if ($user->role !== 'student' || $user->id !== $submission->student_id) {
            return false;
        }

        if ($submission->status !== 'REVISION') {
            return false;
        }

        return $submission->project->applications()
            ->where('student_id', $user->id)
            ->where('status', 'ACCEPTED')
            ->exists();
    }

    public function view(User $user, Submission $submission): bool
    {
        return $user->role === 'admin'
            || $user->id === $submission->student_id
            || $submission->project->owner_id === $user->id;
    }
}
