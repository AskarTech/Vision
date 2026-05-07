<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_order_id',
        'package_id',
        'card_id',
        'package_name',
        'card_code',
        'unit_price',
        'quantity',
        'line_total',
        'meta',
    ];

    protected function casts(): array
    {
        return ['unit_price' => 'decimal:2', 'line_total' => 'decimal:2', 'meta' => 'array'];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(CardOrder::class, 'card_order_id');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
