<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'user_id', 'service_id', 'service_price_id', 'customer_service_id',
        'invoice_id', 'billing_cycle', 'fulfillment_type', 'subtotal', 'vat_percentage',
        'billing_country', 'vat_amount', 'total',
        'status', 'paid_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'vat_percentage' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public static function generateNumber(): string
    {
        $prefix = 'ORD-' . now()->format('Ym') . '-';
        $last = static::where('order_number', 'like', $prefix . '%')->latest('id')->value('order_number');
        $sequence = $last ? ((int) substr($last, -5)) + 1 : 1;

        return $prefix . str_pad((string) $sequence, 5, '0', STR_PAD_LEFT);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function servicePrice()
    {
        return $this->belongsTo(ServicePrice::class);
    }

    public function customerService()
    {
        return $this->belongsTo(CustomerService::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
