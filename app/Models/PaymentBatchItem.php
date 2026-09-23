<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentBatchItem extends Model
{
    protected $fillable = [
        'payment_batch_id',
        'invoice_id',
        'amount',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function batch()
    {
        return $this->belongsTo(PaymentBatch::class, 'payment_batch_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
