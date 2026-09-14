<?php

namespace App\Policies;

use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PortfolioPolicy
{
    use HandlesAuthorization;

    public function manage(User $user, ?Portfolio $portfolio = null): bool
    {
        return $user->role === 'student';
    }

    public function view(User $user, Portfolio $portfolio): bool
    {
        return true;
    }
}
