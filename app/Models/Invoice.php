<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'document_type',
        'user_id',
        'invoice_date',
        'due_date',
        'subtotal',
        'discount_amount',
        'vat_amount',
        'total',
        'vat_percentage',
        'status',
        'notes',
        'internal_notes',
        'sent_at',
        'paid_at',
        'payment_confirmation_sent_at',
        'mollie_payment_id',
        'payment_url',
        'quote_id',
        'renewal_key',
        'customer_service_id',
        'invoice_design_version_id',
        'credited_invoice_id',
        'issuer_snapshot',
        'customer_snapshot',
        'period_start',
        'period_end',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'issuer_snapshot' => 'array',
        'customer_snapshot' => 'array',
        'total' => 'decimal:2',
        'vat_percentage' => 'decimal:2',
        'sent_at' => 'datetime',
        'paid_at' => 'datetime',
        'payment_confirmation_sent_at' => 'datetime',
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    public const STATUSES = [
        'concept' => 'Concept',
        'verzonden' => 'Verzonden',
        'openstaand' => 'Openstaand',
        'vervallen' => 'Vervallen',
        'te_laat' => 'Te laat betaald',
        'betaald' => 'Betaald',
        'geannuleerd' => 'Geannuleerd',
        'gecrediteerd' => 'Gecrediteerd',
        'in_behandeling' => 'Betaling in behandeling',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lines()
    {
        return $this->hasMany(InvoiceLine::class)->orderBy('sort_order');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function billableItems()
    {
        return $this->hasMany(BillableItem::class);
    }

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function customerService()
    {
        return $this->belongsTo(CustomerService::class);
    }

    public function designVersion()
    {
        return $this->belongsTo(InvoiceDesignVersion::class, 'invoice_design_version_id');
    }

    public function creditedInvoice()
    {
        return $this->belongsTo(self::class, 'credited_invoice_id');
    }

    public function creditInvoices()
    {
        return $this->hasMany(self::class, 'credited_invoice_id');
    }

    public function getPaidAmountAttribute(): float
    {
        return max(0, (float) $this->transactions()->where('status', 'voltooid')->where('type', 'inkomst')->sum('amount')
            - (float) $this->transactions()->where('status', 'terugbetaald')->sum('amount'));
    }

    public function getOutstandingAmountAttribute(): float
    {
        return max(0, (float) $this->total - $this->paid_amount);
    }

    public function dunningEvents()
    {
        return $this->hasMany(InvoiceDunningEvent::class);
    }

    public function paymentBatchItems()
    {
        return $this->hasMany(PaymentBatchItem::class);
    }

    public function getStatusLabelAttribute(): array
    {
        $colors = [
            'concept' => 'gray',
            'verzonden' => 'blue',
            'openstaand' => 'blue',
            'vervallen' => 'red',
            'te_laat' => 'red',
            'betaald' => 'green',
            'geannuleerd' => 'gray',
            'gecrediteerd' => 'purple',
            'in_behandeling' => 'yellow',
        ];

        return [
            'text' => self::STATUSES[$this->status] ?? $this->status,
            'color' => $colors[$this->status] ?? 'gray',
        ];
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'openstaand')
            ->where('due_date', '<', now());
    }

    public function recalculate()
    {
        $lines = $this->lines()->get();
        $subtotal = $lines->sum('total');
        $discount = $lines->sum('discount_amount');
        $vatAmount = $lines->sum(fn ($line) => round(((float) $line->total - (float) $line->discount_amount) * ((float) ($line->vat_percentage ?? $this->vat_percentage) / 100), 2));
        $this->update([
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'vat_amount' => $vatAmount,
            'total' => $subtotal - $discount + $vatAmount,
        ]);
    }

    public static function generateCreditNumber(): string
    {
        $year = date('Y');
        $prefix = BillingSetting::valueFor('credit_number_prefix', 'CR');
        $last = static::where('invoice_number', 'like', "{$prefix}-{$year}-%")->orderByDesc('invoice_number')->first();
        $num = $last ? (int) substr($last->invoice_number, -4) + 1 : 1;

        return sprintf('%s-%s-%04d', $prefix, $year, $num);
    }

    public static function generateNumber(): string
    {
        $year = date('Y');
        $prefix = BillingSetting::valueFor('invoice_number_prefix', 'FAC');
        $last = static::where('invoice_number', 'like', "{$prefix}-{$year}-%")
            ->orderByDesc('invoice_number')
            ->first();

        if ($last) {
            $num = (int) substr($last->invoice_number, -4) + 1;
        } else {
            $num = 1;
        }

        return sprintf('%s-%s-%04d', $prefix, $year, $num);
    }
}
