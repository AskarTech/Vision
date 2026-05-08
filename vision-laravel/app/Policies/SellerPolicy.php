<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Seller;

class SellerPolicy
{
    public function view(User $user, Seller $seller): bool
    {
        return $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Seller $seller): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->role === 'seller_manager'
            && $user->seller_id !== null
            && (int) $user->seller_id === (int) $seller->id;
    }

    public function delete(User $user, Seller $seller): bool
    {
        return $user->role === 'admin';
    }

    public function approve(User $user, Seller $seller): bool
    {
        return $user->role === 'admin';
    }

    public function suspend(User $user, Seller $seller): bool
    {
        return $user->role === 'admin';
    }
}
