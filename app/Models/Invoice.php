<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'invoice_date',
        'due_date',
        'subtotal',
        'vat_amount',
        'total',
        'vat_percentage',
        'status',
        'notes',
        'sent_at',
        'paid_at',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'vat_percentage' => 'decimal:2',
        'sent_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public const STATUSES = [
        'concept' => 'Concept',
        'verzonden' => 'Verzonden',
        'betaald' => 'Betaald',
        'vervallen' => 'Vervallen',
        'gecrediteerd' => 'Gecrediteerd',
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

    public function getStatusLabelAttribute(): array
    {
        $colors = [
            'concept' => 'gray',
            'verzonden' => 'blue',
            'betaald' => 'green',
            'vervallen' => 'red',
            'gecrediteerd' => 'purple',
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
        return $query->where('status', 'verzonden')
                     ->where('due_date', '<', now());
    }

    public function recalculate()
    {
        $subtotal = $this->lines()->sum('total');
        $vatAmount = round($subtotal * ($this->vat_percentage / 100), 2);
        $this->update([
            'subtotal' => $subtotal,
            'vat_amount' => $vatAmount,
            'total' => $subtotal + $vatAmount,
        ]);
    }

    public static function generateNumber(): string
    {
        $year = date('Y');
        $last = static::where('invoice_number', 'like', "FAC-{$year}-%")
            ->orderByDesc('invoice_number')
            ->first();

        if ($last) {
            $num = (int) substr($last->invoice_number, -4) + 1;
        } else {
            $num = 1;
        }

        return sprintf("FAC-%s-%04d", $year, $num);
    }
}
