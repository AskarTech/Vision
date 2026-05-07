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
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('code', 40)->unique();
            $table->string('name');
            $table->enum('provider_type', ['mobile_wallet', 'bank', 'card', 'internal'])->index();
            $table->boolean('is_active')->default(false)->index();
            $table->string('merchant_phone', 20)->nullable();
            $table->string('short_code', 40)->nullable();
            $table->text('auth_token')->nullable();
            $table->string('source_wallet_id', 80)->nullable();
            $table->string('webhook_secret', 120)->nullable();
            $table->json('config')->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
