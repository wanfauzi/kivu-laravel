<?php

namespace App\Policies;

use App\Models\Dispute;
use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DisputePolicy
{
    use HandlesAuthorization;

    public function create(User $auth, Project $project): bool
    {
        if (!in_array($project->status, ['IN_PROGRESS', 'SUBMITTED', 'COMPLETED'], true)) {
            return false;
        }

        if (!$this->isParty($auth, $project)) {
            return false;
        }

        // 1 sengketa OPEN per pelapor per proyek
        return !Dispute::where('project_id', $project->id)
            ->where('reporter_id', $auth->id)
            ->where('status', 'OPEN')
            ->exists();
    }

    public function view(User $auth, Dispute $dispute): bool
    {
        return $auth->role === 'admin'
            || $auth->id === $dispute->reporter_id
            || $auth->id === $dispute->against_id;
    }

    public function resolve(User $auth, Dispute $dispute): bool
    {
        return $auth->role === 'admin' && $dispute->status === 'OPEN';
    }

    public function cancel(User $auth, Dispute $dispute): bool
    {
        return $auth->id === $dispute->reporter_id && $dispute->status === 'OPEN';
    }

    private function isParty(User $auth, Project $project): bool
    {
        if ($auth->role === 'umkm') {
            return $project->owner_id === $auth->id;
        }

        if ($auth->role === 'student') {
            $accepted = $project->applications()
                ->where('student_id', $auth->id)
                ->where('status', 'ACCEPTED')
                ->exists();

            $submitted = \App\Models\Submission::where('project_id', $project->id)
                ->where('student_id', $auth->id)
                ->exists();

            return $accepted || $submitted;
        }

        return false;
    }
}
