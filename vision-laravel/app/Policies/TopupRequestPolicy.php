<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TopupRequest;

class TopupRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, TopupRequest $topup): bool
    {
        return $user->role === 'admin';
    }

    public function approve(User $user, TopupRequest $topup): bool
    {
        return $user->role === 'admin' && $topup->status === 'pending';
    }

    public function reject(User $user, TopupRequest $topup): bool
    {
        return $user->role === 'admin' && $topup->status === 'pending';
    }
}
