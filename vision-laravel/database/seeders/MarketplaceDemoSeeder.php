<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\Card;
use App\Models\CardOrder;
use App\Models\CardOrderItem;
use App\Models\Network;
use App\Models\Package;
use App\Models\PaymentGateway;
use App\Models\Seller;
use App\Models\SellerManager;
use App\Models\TopupRequest;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withdrawal;
use App\Services\Wallet\WalletService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MarketplaceDemoSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $admin = User::query()->firstOrCreate(
                ['email' => 'admin@yemenwifi.com'],
                [
                    'name' => 'Platform Admin',
                    'phone' => '+967700000001',
                    'password' => Hash::make('password'),
                    'role' => 'admin',
                    'status' => 'active',
                ]
            );

            $gateways = collect([
                ['code' => 'floosak', 'name' => 'Floosak', 'provider_type' => 'mobile_wallet', 'is_active' => true],
                ['code' => 'jawali', 'name' => 'Jawali', 'provider_type' => 'mobile_wallet', 'is_active' => true],
                ['code' => 'bank_transfer', 'name' => 'Bank Transfer', 'provider_type' => 'bank', 'is_active' => true],
            ])->map(fn (array $gateway) => PaymentGateway::query()->updateOrCreate(['code' => $gateway['code']], $gateway));

            $sellers = Seller::factory()->count(6)->create();

            $sellerManagers = $sellers->map(function (Seller $seller): User {
                $manager = User::factory()->create([
                    'name' => "Manager {$seller->name}",
                    'role' => 'seller_manager',
                    'seller_id' => $seller->id,
                ]);

                SellerManager::query()->create([
                    'seller_id' => $seller->id,
                    'user_id' => $manager->id,
                    'username' => Str::slug($seller->name) . '-manager',
                    'status' => 'active',
                    'last_login_at' => now()->subDays(rand(0, 4)),
                ]);

                $managerWallet = Wallet::query()->firstOrCreate(
                    ['user_id' => $manager->id],
                    ['status' => 'active', 'last_activity_at' => now()]
                );

                app(WalletService::class)->credit(
                    wallet: $managerWallet,
                    amount: (float) rand(10000, 150000),
                    description: 'Demo seed opening balance',
                    channel: 'manual_admin',
                    referenceType: null,
                    referenceId: null,
                    externalReference: 'demo_seed_opening:user:'.$manager->id,
                );

                return $manager;
            });

            $sellers->each(function (Seller $seller): void {
                Bank::query()->create([
                    'seller_id' => $seller->id,
                    'name' => 'Yemen International Bank',
                    'account_number' => fake()->numerify('##########'),
                    'account_owner' => $seller->name,
                    'status' => 'active',
                ]);
            });

            $customers = User::factory()->count(40)->create(['role' => 'customer']);
            $customers->each(function (User $customer): void {
                $wallet = Wallet::query()->firstOrCreate(
                    ['user_id' => $customer->id],
                    ['status' => 'active', 'last_activity_at' => now()->subDays(rand(0, 30))]
                );

                app(WalletService::class)->credit(
                    wallet: $wallet,
                    amount: (float) rand(500, 25000),
                    description: 'Demo seed opening balance',
                    channel: 'manual_admin',
                    referenceType: null,
                    referenceId: null,
                    externalReference: 'demo_seed_opening:user:'.$customer->id,
                );
            });

            $networks = collect();
            $packages = collect();

            foreach ($sellers as $seller) {
                $sellerNetworks = Network::factory()->count(rand(2, 4))->create(['seller_id' => $seller->id]);
                $networks = $networks->merge($sellerNetworks);

                foreach ($sellerNetworks as $network) {
                    $networkPackages = Package::factory()->count(rand(4, 8))->create([
                        'seller_id' => $seller->id,
                        'network_id' => $network->id,
                        'status' => 'active',
                    ]);

                    $packages = $packages->merge($networkPackages);
                }
            }

            foreach ($packages as $package) {
                for ($i = 0; $i < rand(30, 80); $i++) {
                    Card::query()->create([
                        'seller_id' => $package->seller_id,
                        'network_id' => $package->network_id,
                        'package_id' => $package->id,
                        'code' => strtoupper(Str::random(12)),
                        'serial_number' => strtoupper(Str::random(10)),
                        'price' => $package->price,
                        'status' => fake()->randomElement(['active', 'active', 'active', 'sold', 'reserved', 'reported']),
                        'uploaded_at' => now()->subDays(rand(1, 120)),
                        'meta' => ['batch' => fake()->numerify('B-###')],
                    ]);
                }
            }

            $paidOrders = collect();
            foreach ($customers as $customer) {
                $customerOrderCount = rand(2, 8);
                for ($i = 0; $i < $customerOrderCount; $i++) {
                    $package = $packages->random();
                    $card = Card::query()
                        ->where('package_id', $package->id)
                        ->where('status', 'active')
                        ->inRandomOrder()
                        ->first();

                    if (! $card) {
                        continue;
                    }

                    $amount = (float) $package->price;
                    $wallet = $customer->wallet;
                    $walletService = app(WalletService::class);

                    $orderStatus = fake()->randomElement(['paid', 'paid', 'paid', 'cancelled']);

                    $order = CardOrder::query()->create([
                        'user_id' => $customer->id,
                        'wallet_transaction_id' => null,
                        'total_amount' => $amount,
                        'payment_channel' => 'platform_wallet',
                        'status' => $orderStatus,
                        'external_reference' => 'ORD-' . strtoupper(Str::random(10)),
                        'paid_at' => now()->subDays(rand(0, 20)),
                    ]);

                    $walletTx = $walletService->debit(
                        wallet: $wallet,
                        amount: $amount,
                        description: 'شراء باقة من المتجر',
                        referenceType: CardOrder::class,
                        referenceId: $order->id,
                        externalReference: sprintf('demo_wallet_debit:card_order:%d', $order->id),
                    );

                    $order->update(['wallet_transaction_id' => $walletTx->id]);

                    CardOrderItem::query()->create([
                        'card_order_id' => $order->id,
                        'package_id' => $package->id,
                        'card_id' => $card->id,
                        'package_name' => $package->name,
                        'card_code' => $card->code,
                        'unit_price' => $amount,
                        'quantity' => 1,
                        'line_total' => $amount,
                    ]);

                    $card->update([
                        'sold_to_user_id' => $customer->id,
                        'status' => $order->status === 'cancelled' ? 'refunded' : 'sold',
                        'sold_at' => now()->subDays(rand(0, 20)),
                    ]);

                    $paidOrders->push($order);
                }
            }

            foreach ($customers->random(min(20, $customers->count())) as $customer) {
                TopupRequest::query()->create([
                    'user_id' => $customer->id,
                    'wallet_id' => $customer->wallet->id,
                    'payment_gateway_id' => $gateways->random()->id,
                    'method' => fake()->randomElement(['bank_transfer', 'mobile_wallet']),
                    'amount' => rand(1000, 20000),
                    'reference_code' => 'TP-' . strtoupper(Str::random(8)),
                    'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
                    'reviewed_by' => $admin->id,
                    'reviewed_at' => now()->subDays(rand(0, 10)),
                ]);
            }

            foreach ($sellerManagers as $manager) {
                if (! $manager->seller_id) {
                    continue;
                }

                Withdrawal::query()->create([
                    'seller_id' => $manager->seller_id,
                    'requested_by' => $manager->id,
                    'amount' => rand(5000, 60000),
                    'status' => fake()->randomElement(['pending', 'approved', 'rejected', 'paid']),
                    'bank_name' => 'Yemen International Bank',
                    'receiver_name' => $manager->name,
                    'account_number' => fake()->numerify('##########'),
                    'reviewed_by' => $admin->id,
                    'reviewed_at' => now()->subDays(rand(0, 15)),
                ]);
            }
        });
    }
}
