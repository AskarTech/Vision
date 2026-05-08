<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Package;

class PackagePolicy
{
    public function view(User $user, Package $package): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->role === 'seller_manager'
            && $user->seller_id !== null
            && (int) $user->seller_id === (int) $package->seller_id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin'
            || ($user->role === 'seller_manager' && $user->seller_id !== null);
    }

    public function update(User $user, Package $package): bool
    {
        return $this->view($user, $package);
    }

    public function delete(User $user, Package $package): bool
    {
        return $this->view($user, $package);
    }
}
