<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Withdrawal;

class WithdrawalPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, Withdrawal $withdrawal): bool
    {
        return $user->role === 'admin';
    }

    public function approve(User $user, Withdrawal $withdrawal): bool
    {
        return $user->role === 'admin' && $withdrawal->status === 'pending';
    }

    public function reject(User $user, Withdrawal $withdrawal): bool
    {
        return $user->role === 'admin' && $withdrawal->status === 'pending';
    }
}
