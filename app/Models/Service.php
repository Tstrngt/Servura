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
        'service_type',
        'short_description',
        'description',
        'image_url',
        'price',
        'price_type',
        'features',
        'is_popular',
        'sort_order',
        'is_active',
        'show_on_homepage',
        'show_on_services_page',
    ];

    public const SERVICE_TYPES = [
        'website_pakket' => 'Website Pakket',
        'hosting' => 'Hosting',
        'custom' => 'Custom Pakket',
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'decimal:2',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
        'show_on_homepage' => 'boolean',
        'show_on_services_page' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('service_type', $type);
    }

    public function getServiceTypeLabelAttribute(): string
    {
        return self::SERVICE_TYPES[$this->service_type] ?? $this->service_type;
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    public function scopeHomepage($query)
    {
        return $query->where('show_on_homepage', true);
    }

    public function scopeServicesPage($query)
    {
        return $query->where('show_on_services_page', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    public function customerServices()
    {
        return $this->hasMany(CustomerService::class);
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
