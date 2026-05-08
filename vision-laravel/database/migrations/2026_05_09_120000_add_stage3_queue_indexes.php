<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Composite indexes for admin queues and date-sorted listings (aligned with PROJECT_CONTEXT read paths).
     */
    public function up(): void
    {
        Schema::table('card_orders', function (Blueprint $table): void {
            $table->index(['status', 'created_at']);
        });

        Schema::table('topup_requests', function (Blueprint $table): void {
            $table->index(['status', 'created_at']);
        });

        Schema::table('withdrawals', function (Blueprint $table): void {
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('card_orders', function (Blueprint $table): void {
            $table->dropIndex(['status', 'created_at']);
        });

        Schema::table('topup_requests', function (Blueprint $table): void {
            $table->dropIndex(['status', 'created_at']);
        });

        Schema::table('withdrawals', function (Blueprint $table): void {
            $table->dropIndex(['status', 'created_at']);
        });
    }
};
