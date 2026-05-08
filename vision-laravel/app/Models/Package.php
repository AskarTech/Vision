<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'network_id',
        'name',
        'price',
        'amount',
        'unit',
        'period_type',
        'category',
        'gradient',
        'status',
        'meta',
    ];

    protected function casts(): array
    {
        return ['price' => 'decimal:2', 'meta' => 'array'];
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function network(): BelongsTo
    {
        return $this->belongsTo(Network::class);
    }

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(CardOrderItem::class);
    }
}
