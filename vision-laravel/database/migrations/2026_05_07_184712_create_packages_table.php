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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained()->cascadeOnDelete();
            $table->foreignId('network_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->decimal('price', 12, 2);
            $table->unsignedInteger('amount')->nullable();
            $table->string('unit', 20)->nullable();
            $table->enum('period_type', ['daily', 'weekly', 'monthly'])->default('daily')->index();
            $table->string('category', 40)->default('best_selling')->index();
            $table->string('gradient', 80)->nullable();
            $table->enum('status', ['active', 'disabled'])->default('active')->index();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['seller_id', 'status', 'id']);
            $table->index(['network_id', 'status', 'id']);
            $table->index(['category', 'status', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
