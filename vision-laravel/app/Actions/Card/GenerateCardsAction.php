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
                'package_id' => $package->id,
                'code' => $code,
                'pin' => $options['pin'] ?? $this->generatePin(),
                'price' => $options['price'] ?? $package->price,
                'status' => 'available',
                'expires_at' => $options['expires_at'] ?? null,
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

    private function generatePin(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}
