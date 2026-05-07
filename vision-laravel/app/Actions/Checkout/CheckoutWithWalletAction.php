<?php

namespace App\Actions\Checkout;

use App\Models\CardOrder;
use App\Models\Package;
use App\Models\User;
use App\Services\Cards\CardInventoryService;
use App\Services\Wallet\WalletService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CheckoutWithWalletAction
{
    public function __construct(
        private readonly CardInventoryService $cardInventoryService,
        private readonly WalletService $walletService
    ) {
    }

    /**
     * @param  array<int, array{package_id:int, quantity:int}>  $items
     */
    public function execute(User $user, array $items, string $idempotencyKey): CardOrder
    {
        $externalReference = $this->buildExternalReference($user->id, $idempotencyKey);

        return DB::transaction(function () use ($user, $items, $externalReference): CardOrder {
            $existingOrder = CardOrder::query()
                ->where('external_reference', $externalReference)
                ->first();

            if ($existingOrder) {
                return $existingOrder->load(['items', 'items.card']);
            }

            $wallet = $user->wallet()->lockForUpdate()->first();
            if (! $wallet || $wallet->status !== 'active') {
                throw new RuntimeException('Wallet is unavailable.');
            }

            $preparedItems = [];
            $grandTotal = 0.0;

            foreach ($items as $item) {
                $package = Package::query()
                    ->whereKey($item['package_id'])
                    ->where('status', 'active')
                    ->first();

                if (! $package) {
                    throw new RuntimeException("Package {$item['package_id']} is not available.");
                }

                $quantity = max(1, (int) $item['quantity']);
                $lineTotal = (float) $package->price * $quantity;
                $grandTotal += $lineTotal;

                $preparedItems[] = [
                    'package' => $package,
                    'quantity' => $quantity,
                    'line_total' => $lineTotal,
                ];
            }

            if ((float) $wallet->balance < $grandTotal) {
                throw new RuntimeException('Insufficient wallet balance.');
            }

            try {
                $order = CardOrder::query()->create([
                    'user_id' => $user->id,
                    'total_amount' => $grandTotal,
                    'payment_channel' => 'platform_wallet',
                    'status' => 'paid',
                    'external_reference' => $externalReference,
                    'paid_at' => now(),
                ]);
            } catch (QueryException $exception) {
                if ($this->isDuplicateKeyException($exception)) {
                    $existingOrder = CardOrder::query()
                        ->where('external_reference', $externalReference)
                        ->first();

                    if ($existingOrder) {
                        return $existingOrder->load(['items', 'items.card']);
                    }
                }

                throw $exception;
            }

            foreach ($preparedItems as $preparedItem) {
                /** @var Package $package */
                $package = $preparedItem['package'];

                for ($i = 0; $i < $preparedItem['quantity']; $i++) {
                    $card = $this->cardInventoryService->reserveNextActiveCard($package->id, $user);
                    $card = $this->cardInventoryService->confirmReservedCard($card, $user);

                    $order->items()->create([
                        'package_id' => $package->id,
                        'card_id' => $card->id,
                        'package_name' => $package->name,
                        'card_code' => $card->code,
                        'unit_price' => $package->price,
                        'quantity' => 1,
                        'line_total' => $package->price,
                    ]);
                }
            }

            $walletTransaction = $this->walletService->debit(
                wallet: $wallet,
                amount: $grandTotal,
                description: "Card checkout order #{$order->id}",
                referenceType: CardOrder::class,
                referenceId: $order->id
            );

            $order->update(['wallet_transaction_id' => $walletTransaction->id]);

            return $order->load(['items', 'items.card']);
        });
    }

    private function buildExternalReference(int $userId, string $idempotencyKey): string
    {
        return sprintf('wallet_checkout:%d:%s', $userId, trim($idempotencyKey));
    }

    private function isDuplicateKeyException(QueryException $exception): bool
    {
        return (string) $exception->getCode() === '23000';
    }
}
