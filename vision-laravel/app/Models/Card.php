<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'network_id',
        'package_id',
        'sold_to_user_id',
        'reserved_by_user_id',
        'code',
        'serial_number',
        'price',
        'status',
        'uploaded_at',
        'reserved_at',
        'reservation_expires_at',
        'sold_at',
        'reported_at',
        'report_reason',
        'dispute_resolution',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'uploaded_at' => 'datetime',
            'reserved_at' => 'datetime',
            'reservation_expires_at' => 'datetime',
            'sold_at' => 'datetime',
            'reported_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function network(): BelongsTo
    {
        return $this->belongsTo(Network::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function soldToUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sold_to_user_id');
    }

    public function reservedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reserved_by_user_id');
    }
}
