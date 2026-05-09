<?php

namespace App\Actions\Auth;

use App\Models\User;

final class RedirectAfterLoginAction
{
    public function execute(User $user): string
    {
        return match ($user->role) {
            'admin' => route('admin.dashboard'),
            'seller_manager' => route('seller.dashboard'),
            default => route('customer.marketplace.index'),
        };
    }
}
