<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePrice extends Model
{
    use HasFactory;

    public const CYCLES = [
        'one_time' => 'Eenmalig',
        'monthly' => 'Maandelijks',
        'quarterly' => 'Per kwartaal',
        'semiannual' => 'Ieder halfjaar',
        'yearly' => 'Jaarlijks',
        'biennial' => 'Per 2 jaar',
        'triennial' => 'Per 3 jaar',
    ];

    protected $fillable = ['service_id', 'billing_cycle', 'price', 'is_enabled'];

    protected $casts = ['price' => 'decimal:2', 'is_enabled' => 'boolean'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getLabelAttribute(): string
    {
        return self::CYCLES[$this->billing_cycle] ?? $this->billing_cycle;
    }

    public function getPriceIncludingVatAttribute(): string
    {
        return number_format((float) $this->price * 1.21, 2, ',', '.');
    }
}
