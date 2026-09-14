<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function delete(User $auth, Project $project): bool
    {
        return $auth->role === 'admin';
    }

    public function refund(User $auth, Project $project): bool
    {
        return $auth->role === 'admin';
    }

    public function update(User $auth, Project $project): bool
    {
        return $auth->role === 'umkm'
            && $project->owner_id === $auth->id
            && $project->status === 'OPEN';
    }

    public function cancel(User $auth, Project $project): bool
    {
        return $auth->role === 'umkm'
            && $project->owner_id === $auth->id
            && $project->status === 'OPEN';
    }
}