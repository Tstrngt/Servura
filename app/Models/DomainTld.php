<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainTld extends Model
{
    use HasFactory;

    protected $fillable = [
        'extension',
        'is_active',
        'registration_price',
        'renewal_price',
        'transfer_price',
        'cost_price',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'registration_price' => 'decimal:2',
        'renewal_price' => 'decimal:2',
        'transfer_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('extension');
    }

    public function servicePrice()
    {
        return $this->hasOne(ServicePrice::class, 'tld', 'extension')
            ->whereHas('service', fn ($q) => $q->where('fulfillment_type', 'domain'));
    }
}
