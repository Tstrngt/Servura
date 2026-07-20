<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_number',
        'user_id',
        'quote_date',
        'valid_until',
        'subtotal',
        'vat_amount',
        'total',
        'vat_percentage',
        'status',
        'notes',
        'sent_at',
        'accepted_at',
        'converted_invoice_id',
    ];

    protected $casts = [
        'quote_date' => 'date',
        'valid_until' => 'date',
        'subtotal' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'vat_percentage' => 'decimal:2',
        'sent_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    public const STATUSES = [
        'concept' => 'Concept',
        'verzonden' => 'Verzonden',
        'geaccepteerd' => 'Geaccepteerd',
        'afgewezen' => 'Afgewezen',
        'verlopen' => 'Verlopen',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lines()
    {
        return $this->hasMany(QuoteLine::class)->orderBy('sort_order');
    }

    public function convertedInvoice()
    {
        return $this->belongsTo(Invoice::class, 'converted_invoice_id');
    }

    public function getStatusLabelAttribute(): array
    {
        $colors = [
            'concept' => 'gray',
            'verzonden' => 'blue',
            'geaccepteerd' => 'green',
            'afgewezen' => 'red',
            'verlopen' => 'yellow',
        ];

        return [
            'text' => self::STATUSES[$this->status] ?? $this->status,
            'color' => $colors[$this->status] ?? 'gray',
        ];
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
        $last = static::where('quote_number', 'like', "OFF-{$year}-%")
            ->orderByDesc('quote_number')
            ->first();

        if ($last) {
            $num = (int) substr($last->quote_number, -4) + 1;
        } else {
            $num = 1;
        }

        return sprintf("OFF-%s-%04d", $year, $num);
    }
}
