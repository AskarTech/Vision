<?php

namespace App\Actions\Auth;

use App\Models\Network;
use App\Models\Seller;
use App\Models\SellerManager;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegisterSellerOnboardingAction
{
    /**
     * @param  array{
     *     business_name:string,
     *     business_phone:string,
     *     manager_name:string,
     *     manager_phone:string,
     *     password:string,
     *     network_name:string,
     *     network_provider_code?:string|null,
     *     wallet_display_label?:string|null
     * }  $data
     */
    public function execute(array $data): User
    {
        return DB::transaction(function () use ($data): User {
            $baseSlug = Str::slug($data['business_name']);
            $sellerSlug = $baseSlug !== '' ? $baseSlug : 'seller-'.Str::lower(Str::random(6));
            while (Seller::query()->where('slug', $sellerSlug)->exists()) {
                $sellerSlug = $baseSlug.'-'.Str::lower(Str::random(4));
            }

            $seller = Seller::query()->create([
                'name' => $data['business_name'],
                'slug' => $sellerSlug,
                'phone' => $data['business_phone'],
                'status' => 'active',
            ]);

            $user = User::query()->create([
                'name' => $data['manager_name'],
                'phone' => $data['manager_phone'],
                'password' => $data['password'],
                'role' => 'seller_manager',
                'seller_id' => $seller->id,
                'status' => 'active',
            ]);

            SellerManager::query()->create([
                'seller_id' => $seller->id,
                'user_id' => $user->id,
                'username' => $sellerSlug.'-manager',
                'status' => 'active',
            ]);

            Wallet::query()->create([
                'user_id' => $user->id,
                'status' => 'active',
            ]);

            $netBase = Str::slug($data['network_name']);
            $networkSlug = $netBase !== '' ? $netBase : 'network-'.Str::lower(Str::random(4));
            while (Network::query()->where('seller_id', $seller->id)->where('slug', $networkSlug)->exists()) {
                $networkSlug = $netBase.'-'.Str::lower(Str::random(4));
            }

            Network::query()->create([
                'seller_id' => $seller->id,
                'name' => $data['network_name'],
                'slug' => $networkSlug,
                'provider_code' => $data['network_provider_code'] ?? null,
                'status' => 'active',
                'meta' => array_filter([
                    'local_wallet_label' => $data['wallet_display_label'] ?? null,
                ]),
            ]);

            return $user;
        });
    }
}
