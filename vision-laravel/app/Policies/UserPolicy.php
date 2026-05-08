<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function view(User $actor, User $target): bool
    {
        return $actor->role === 'admin';
    }

    public function update(User $actor, User $target): bool
    {
        return $actor->role === 'admin';
    }

    public function suspend(User $actor, User $target): bool
    {
        return $actor->role === 'admin' && $target->role === 'customer';
    }

    public function activate(User $actor, User $target): bool
    {
        return $actor->role === 'admin' && $target->role === 'customer';
    }
}
