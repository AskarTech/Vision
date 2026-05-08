<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WalletTransaction;

class AuditPolicy
{
    public function view(User $user, WalletTransaction $transaction): bool
    {
        return $user->role === 'admin';
    }

    public function index(User $user): bool
    {
        return $user->role === 'admin';
    }
}
