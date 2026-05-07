<?php

namespace App\Services\Cards;

use App\Models\Card;
use App\Models\User;
use RuntimeException;

class CardInventoryService
{
    public function lockNextActiveCard(int $packageId): Card
    {
        $card = Card::query()
            ->where('package_id', $packageId)
            ->where('status', 'active')
            ->lockForUpdate()
            ->first();

        if (! $card) {
            throw new RuntimeException("No active cards available for package {$packageId}.");
        }

        return $card;
    }

    public function reserveNextActiveCard(int $packageId, User $user): Card
    {
        $card = $this->lockNextActiveCard($packageId);

        $card->update([
            'status' => 'reserved',
            'reserved_by_user_id' => $user->id,
            'reserved_at' => now(),
            'reservation_expires_at' => null,
        ]);

        return $card->refresh();
    }

    public function confirmReservedCard(Card $card, User $user): Card
    {
        if ($card->status !== 'reserved') {
            throw new RuntimeException('Card is not reserved.');
        }

        $card->update([
            'sold_to_user_id' => $user->id,
            'status' => 'sold',
            'sold_at' => now(),
        ]);

        return $card->refresh();
    }

    public function releaseReservedCard(Card $card): Card
    {
        if ($card->status !== 'reserved') {
            return $card;
        }

        $card->update([
            'reserved_by_user_id' => null,
            'reserved_at' => null,
            'reservation_expires_at' => null,
            'status' => 'active',
        ]);

        return $card->refresh();
    }
}
