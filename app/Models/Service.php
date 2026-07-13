<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'image_url',
        'price',
        'price_type',
        'features',
        'is_popular',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'decimal:2',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    public function getFormattedPriceAttribute(): string
    {
        if ($this->price_type === 'op-aanvraag') {
            return 'Op aanvraag';
        }

        $price = number_format($this->price, 2, ',', '.');
        
        switch ($this->price_type) {
            case 'maandelijks':
                return "€ {$price}/maand";
            case 'jaarlijks':
                return "€ {$price}/jaar";
            default:
                return "€ {$price}";
        }
    }
}
