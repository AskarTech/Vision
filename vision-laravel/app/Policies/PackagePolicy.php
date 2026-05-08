<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Package;

class PackagePolicy
{
    public function view(User $user, Package $package): bool
    {
        return $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Package $package): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Package $package): bool
    {
        return $user->role === 'admin';
    }
}
