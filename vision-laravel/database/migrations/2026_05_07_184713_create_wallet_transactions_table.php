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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['credit', 'debit', 'hold', 'release', 'refund', 'adjustment'])->index();
            $table->enum('channel', ['platform_wallet', 'bank_transfer', 'floosak', 'jawali', 'manual_admin', 'reward_points'])->index();
            $table->enum('status', ['pending', 'approved', 'rejected', 'failed', 'reversed'])->default('approved')->index();
            $table->decimal('amount', 14, 2);
            $table->unsignedBigInteger('points_amount')->default(0);
            $table->decimal('balance_before', 14, 2)->default(0);
            $table->decimal('balance_after', 14, 2)->default(0);
            $table->string('reference_type', 100)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('external_reference', 120)->nullable()->unique();
            $table->string('description')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['wallet_id', 'type', 'status', 'id']);
            $table->index(['user_id', 'created_at']);
            $table->index(['channel', 'status', 'created_at']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
