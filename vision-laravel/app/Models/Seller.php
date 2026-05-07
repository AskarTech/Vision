<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seller extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'phone',
        'commission_rate',
        'status',
        'settings',
    ];

    protected function casts(): array
    {
        return ['commission_rate' => 'decimal:2', 'settings' => 'array'];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function networks(): HasMany
    {
        return $this->hasMany(Network::class);
    }

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class);
    }

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    public function banks(): HasMany
    {
        return $this->hasMany(Bank::class);
    }

    public function managers(): HasMany
    {
        return $this->hasMany(SellerManager::class);
    }
}
