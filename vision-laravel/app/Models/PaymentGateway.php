<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'provider_type',
        'is_active',
        'merchant_phone',
        'short_code',
        'auth_token',
        'source_wallet_id',
        'webhook_secret',
        'config',
        'last_synced_at',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'config' => 'array', 'last_synced_at' => 'datetime'];
    }

    public function topupRequests(): HasMany
    {
        return $this->hasMany(TopupRequest::class);
    }
}
