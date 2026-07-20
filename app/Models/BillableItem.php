<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillableItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_service_id',
        'description',
        'quantity',
        'unit_price',
        'total',
        'status',
        'invoice_id',
        'period_start',
        'period_end',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total' => 'decimal:2',
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customerService()
    {
        return $this->belongsTo(CustomerService::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeInvoiced($query)
    {
        return $query->where('status', 'gefactureerd');
    }
}
