<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function suspend(User $auth, User $target): bool
    {
        if ($auth->role !== 'admin') {
            return false;
        }

        return $target->role !== 'admin' && $target->status !== 'suspended';
    }

    public function activate(User $auth, User $target): bool
    {
        if ($auth->role !== 'admin') {
            return false;
        }

        return $target->role !== 'admin' && $target->status === 'suspended';
    }

    public function verifyKtm(User $auth, User $target): bool
    {
        if ($auth->role !== 'admin') {
            return false;
        }

        return $target->role === 'student'
            && $target->status === 'pending_ktm'
            && $target->ktm_path !== null
            && $target->ktm_path !== '';
    }

    public function revokeKtm(User $auth, User $target): bool
    {
        if ($auth->role !== 'admin') {
            return false;
        }

        return $target->role === 'student' && $target->student_verified_at !== null;
    }
}