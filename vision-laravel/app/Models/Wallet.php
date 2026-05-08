<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    use HasFactory;

    /**
     * Balance fields are not mass-assignable — mutate only via WalletService inside transactions.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'status',
        'last_activity_at',
    ];

    protected function casts(): array
    {
        return [
            'balance' => 'decimal:2',
            'points_balance' => 'integer',
            'last_activity_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }
}
