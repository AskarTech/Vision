<?php

namespace App\Actions\Card;

use App\Models\Card;
use App\Models\Package;
use Illuminate\Support\Str;

class GenerateCardsAction
{
    public function execute(
        Package $package,
        int $quantity,
        array $options = []
    ): array {
        $cards = [];
        $generatedCodes = [];

        for ($i = 0; $i < $quantity; $i++) {
            $code = $this->generateUniqueCode($generatedCodes);
            $generatedCodes[] = $code;

            $card = Card::create([
                'seller_id' => $package->seller_id,
                'network_id' => $package->network_id,
                'package_id' => $package->id,
                'code' => $code,
                'price' => $options['price'] ?? $package->price,
                'status' => 'active',
                'meta' => [
                    'expires_at' => $options['expires_at'] ?? null,
                ],
            ]);

            $cards[] = $card;
        }

        return $cards;
    }

    private function generateUniqueCode(array &$existing): string
    {
        do {
            $code = strtoupper(Str::random(12));
        } while (in_array($code, $existing) || Card::where('code', $code)->exists());

        return $code;
    }

}
