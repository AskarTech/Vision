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
        Schema::create('card_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->foreignId('card_id')->nullable()->constrained()->nullOnDelete();
            $table->string('package_name');
            $table->string('card_code')->nullable();
            $table->decimal('unit_price', 14, 2);
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->decimal('line_total', 14, 2);
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['card_order_id', 'id']);
            $table->index(['package_id', 'id']);
            $table->index('card_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_order_items');
    }
};
