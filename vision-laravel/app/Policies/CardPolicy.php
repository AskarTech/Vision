<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Card;
use App\Models\Seller;

class CardPolicy
{
    public function viewAny(User $user): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'seller_manager') {
            return true;
        }

        return false;
    }

    public function view(User $user, Card $card): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'seller_manager' && $user->seller) {
            return $card->package->seller_id === $user->seller->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        if ($user->role === 'seller_manager' && $user->seller) {
            return true;
        }

        return $user->role === 'admin';
    }

    public function update(User $user, Card $card): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'seller_manager' && $user->seller) {
            return $card->package->seller_id === $user->seller->id;
        }

        return false;
    }

    public function delete(User $user, Card $card): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'seller_manager' && $user->seller) {
            return $card->package->seller_id === $user->seller->id;
        }

        return false;
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
