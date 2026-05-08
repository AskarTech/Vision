<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CardOrder;

class CardOrderPolicy
{
    public function view(User $user, CardOrder $order): bool
    {
        return $user->role === 'admin' || $user->id === $order->user_id;
    }

    public function cancel(User $user, CardOrder $order): bool
    {
        return $user->role === 'admin';
    }

    public function refund(User $user, CardOrder $order): bool
    {
        return $user->role === 'admin';
    }
}
