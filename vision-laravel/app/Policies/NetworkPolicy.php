<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Network;

class NetworkPolicy
{
    public function view(User $user, Network $network): bool
    {
        return $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Network $network): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Network $network): bool
    {
        return $user->role === 'admin';
    }
}
