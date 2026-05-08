<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class RegisterCustomerAction
{
    /**
     * @param  array{name:string, phone:string, email?:string|null, password:string}  $data
     */
    public function execute(array $data): User
    {
        return DB::transaction(function () use ($data): User {
            $user = User::query()->create([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'] ?? null,
                'password' => $data['password'],
                'role' => 'customer',
                'status' => 'active',
            ]);

            Wallet::query()->create([
                'user_id' => $user->id,
                'status' => 'active',
            ]);

            return $user;
        });
    }
}
