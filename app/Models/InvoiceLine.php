<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'description',
        'quantity',
        'unit_price',
        'total',
        'vat_percentage',
        'discount_amount',
        'period_start',
        'period_end',
        'service_reference',
        'customer_service_id',
        'sort_order',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total' => 'decimal:2',
        'vat_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function customerService()
    {
        return $this->belongsTo(CustomerService::class);
    }
}
