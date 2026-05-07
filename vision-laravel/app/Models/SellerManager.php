<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerManager extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'user_id',
        'username',
        'status',
        'last_login_at',
    ];

    protected function casts(): array
    {
        return ['last_login_at' => 'datetime'];
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
