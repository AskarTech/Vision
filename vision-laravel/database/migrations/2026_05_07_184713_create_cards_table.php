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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained()->cascadeOnDelete();
            $table->foreignId('network_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sold_to_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('code', 120)->unique();
            $table->string('serial_number', 120)->nullable();
            $table->decimal('price', 12, 2);
            $table->enum('status', ['active', 'reserved', 'sold', 'reported', 'refunded', 'disabled'])->default('active');
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamp('sold_at')->nullable();
            $table->timestamp('reported_at')->nullable();
            $table->text('report_reason')->nullable();
            $table->enum('dispute_resolution', ['refunded', 'rejected'])->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['package_id', 'status', 'id']);
            $table->index(['status', 'id']);
            $table->index(['seller_id', 'status', 'id']);
            $table->index(['network_id', 'status', 'id']);
            $table->index(['sold_to_user_id', 'sold_at']);
            $table->index('uploaded_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
