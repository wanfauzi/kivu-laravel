<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Auth\Access\HandlesAuthorization;

class WithdrawalPolicy
{
    use HandlesAuthorization;

    public function update(User $auth, Withdrawal $withdrawal): bool
    {
        return $auth->role === 'admin' && $withdrawal->status === 'PENDING';
    }

    public function cancel(User $auth, Withdrawal $withdrawal): bool
    {
        return $auth->id === $withdrawal->student_id && $withdrawal->status === 'PENDING';
    }
}