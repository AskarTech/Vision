<?php

namespace App\Support;

/**
 * Server-side points allocation for checkout (config-driven).
 */
final class MarketplacePoints
{
    /**
     * @return array{cash_portion: float, points_portion: int}
     */
    public static function allocateTowardsOrder(int $pointsOffered, float $orderTotal): array
    {
        $pointsOffered = max(0, $pointsOffered);
        $orderTotal = max(0.0, $orderTotal);

        if ($pointsOffered === 0 || $orderTotal <= 0.0) {
            return ['cash_portion' => round($orderTotal, 2), 'points_portion' => 0];
        }

        $per1000 = (float) config('marketplace.points.cash_equivalent_per_1000', 0);
        if ($per1000 <= 0) {
            return ['cash_portion' => round($orderTotal, 2), 'points_portion' => 0];
        }

        $cashPerPoint = $per1000 / 1000;
        $maxCashCover = $pointsOffered * $cashPerPoint;
        $cashCover = min($maxCashCover, $orderTotal);
        $pointsConsumed = (int) floor($cashCover / $cashPerPoint);
        $pointsConsumed = min($pointsConsumed, $pointsOffered);
        $cashCover = round($pointsConsumed * $cashPerPoint, 2);
        $cashPortion = round($orderTotal - $cashCover, 2);
        if ($cashPortion < 0) {
            $cashPortion = 0;
        }

        return ['cash_portion' => $cashPortion, 'points_portion' => $pointsConsumed];
    }
}
