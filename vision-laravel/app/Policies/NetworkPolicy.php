<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Network;

class NetworkPolicy
{
    public function view(User $user, Network $network): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->role === 'seller_manager'
            && $user->seller_id !== null
            && (int) $user->seller_id === (int) $network->seller_id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin'
            || ($user->role === 'seller_manager' && $user->seller_id !== null);
    }

    public function update(User $user, Network $network): bool
    {
        return $this->view($user, $network);
    }

    public function delete(User $user, Network $network): bool
    {
        return $this->view($user, $network);
    }
}
