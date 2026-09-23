<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentBatch extends Model
{
    protected $fillable = [
        'user_id',
        'batch_number',
        'amount',
        'status',
        'mollie_payment_id',
        'checkout_url',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(PaymentBatchItem::class);
    }

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class, 'payment_batch_items')
            ->withPivot(['amount', 'status', 'paid_at'])
            ->withTimestamps();
    }

    public static function generateNumber(): string
    {
        return 'PAY-'.now()->format('Ymd-His').'-'.strtoupper(bin2hex(random_bytes(2)));
    }
}
