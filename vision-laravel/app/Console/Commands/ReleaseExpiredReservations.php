<?php

namespace App\Console\Commands;

use App\Services\Cards\CardInventoryService;
use Illuminate\Console\Command;

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

        try {
            $releasedCount = $this->inventoryService->releaseExpiredReservations();
            $this->info("Process completed: {$releasedCount} reservations released.");
        } catch (\Throwable $e) {
            $this->error("Failed to release reservations: {$e->getMessage()}");

            \Log::error('Failed to release expired reservations', [
                'error' => $e->getMessage(),
            ]);

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
