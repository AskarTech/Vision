<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CardOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wallet_transaction_id',
        'total_amount',
        'payment_channel',
        'status',
        'reserved_at',
        'external_reference',
        'meta',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'meta' => 'array',
            'paid_at' => 'datetime',
            'reserved_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function walletTransaction(): BelongsTo
    {
        return $this->belongsTo(WalletTransaction::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CardOrderItem::class);
    }
}
