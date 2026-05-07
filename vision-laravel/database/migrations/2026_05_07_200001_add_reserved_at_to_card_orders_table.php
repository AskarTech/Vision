<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('card_orders', function (Blueprint $table) {
            // Add reserved_at column for tracking when order was initially reserved
            $table->timestamp('reserved_at')->nullable()->after('status');
            
            // Index for finding expired reservations efficiently
            $table->index(['status', 'reserved_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('card_orders', function (Blueprint $table) {
            $table->dropIndex(['status', 'reserved_at']);
            $table->dropColumn('reserved_at');
        });
    }
};
