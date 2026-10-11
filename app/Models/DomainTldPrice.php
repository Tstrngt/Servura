<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainTldPrice extends Model
{
    use HasFactory;

    public const OPERATIONS = ['create', 'renew', 'transfer'];

    protected $fillable = [
        'domain_tld_id', 'environment', 'operation', 'period', 'currency',
        'regular_cost', 'effective_cost', 'calculated_sale_price', 'override_sale_price',
        'markup_percentage', 'is_premium', 'is_promotion', 'promotion_starts_at',
        'promotion_ends_at', 'promotion_data', 'synced_at', 'sync_error',
    ];

    protected $casts = [
        'regular_cost' => 'decimal:4',
        'effective_cost' => 'decimal:4',
        'calculated_sale_price' => 'decimal:2',
        'override_sale_price' => 'decimal:2',
        'markup_percentage' => 'decimal:2',
        'is_premium' => 'boolean',
        'is_promotion' => 'boolean',
        'promotion_starts_at' => 'datetime',
        'promotion_ends_at' => 'datetime',
        'promotion_data' => 'array',
        'synced_at' => 'datetime',
    ];

    public function tld()
    {
        return $this->belongsTo(DomainTld::class, 'domain_tld_id');
    }

    public function getSalePriceAttribute(): ?float
    {
        return $this->override_sale_price !== null
            ? (float) $this->override_sale_price
            : ($this->calculated_sale_price !== null ? (float) $this->calculated_sale_price : null);
    }
}
