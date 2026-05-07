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
        Schema::table('cards', function (Blueprint $table): void {
            $table->foreignId('reserved_by_user_id')->nullable()->after('sold_to_user_id')->constrained('users')->nullOnDelete();
            $table->timestamp('reserved_at')->nullable()->after('sold_at');
            $table->timestamp('reservation_expires_at')->nullable()->after('reserved_at');

            $table->index(['reserved_by_user_id', 'reserved_at']);
            $table->index(['reservation_expires_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table): void {
            $table->dropIndex(['reserved_by_user_id', 'reserved_at']);
            $table->dropIndex(['reservation_expires_at', 'status']);
            $table->dropConstrainedForeignId('reserved_by_user_id');
            $table->dropColumn(['reserved_at', 'reservation_expires_at']);
        });
    }
};
