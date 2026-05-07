<?php

namespace App\Console\Commands;

use App\Models\CardOrder;
use App\Services\CardInventoryService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReleaseExpiredReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:release-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Release expired card reservations automatically';

    private CardInventoryService $inventoryService;

    /**
     * Execute the console command.
     */
    public function handle(CardInventoryService $inventoryService): int
    {
        $this->inventoryService = $inventoryService;

        $this->info('Starting expired reservation release process...');

        $expiredReservations = CardOrder::where('status', 'reserved')
            ->where('reserved_at', '<', now()->subMinutes(config('yemenwifi.reservation_timeout_minutes', 15)))
            ->lockForUpdate()
            ->get();

        if ($expiredReservations->isEmpty()) {
            $this->info('No expired reservations found.');
            return self::SUCCESS;
        }

        $this->info("Found {$expiredReservations->count()} expired reservations to release.");

        $releasedCount = 0;
        $failedCount = 0;

        foreach ($expiredReservations as $order) {
            try {
                DB::transaction(function () use ($order) {
                    $this->inventoryService->releaseReservation($order);
                });

                $releasedCount++;
                $this->line("✓ Released reservation #{$order->id} for card {$order->card_id}");
            } catch (\Exception $e) {
                $failedCount++;
                $this->error("✗ Failed to release reservation #{$order->id}: {$e->getMessage()}");
                
                // Log the error for monitoring
                \Log::error('Failed to release expired reservation', [
                    'order_id' => $order->id,
                    'card_id' => $order->card_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Process completed: {$releasedCount} released, {$failedCount} failed.");

        return self::SUCCESS;
    }
}
