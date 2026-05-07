<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->whereDoesntHave('wallet')->each(function (User $user) {
            Wallet::query()->create([
                'user_id' => $user->id,
                'balance' => 0,
                'points_balance' => 0,
                'status' => 'active',
                'last_activity_at' => null,
            ]);
        });
    }
}
