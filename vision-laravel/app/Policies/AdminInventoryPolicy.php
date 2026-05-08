<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Card;

class AdminInventoryPolicy
{
    public function view(User $user, Card $card): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Card $card): bool
    {
        return $user->role === 'admin';
    }

    public function updateBulk(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function export(User $user): bool
    {
        return $user->role === 'admin';
    }
}
