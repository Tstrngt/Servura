<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_number',
        'user_id',
        'invoice_id',
        'amount',
        'type',
        'payment_method',
        'status',
        'description',
        'transaction_date',
        'reference',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    public const TYPES = [
        'inkomst' => 'Inkomst',
        'uitgave' => 'Uitgave',
        'creditering' => 'Creditering',
    ];

    public const PAYMENT_METHODS = [
        'bank' => 'Bankoverschrijving',
        'ideal' => 'iDEAL',
        'contant' => 'Contant',
        'overig' => 'Overig',
    ];

    public const STATUSES = [
        'in_afwachting' => 'In afwachting',
        'voltooid' => 'Voltooid',
        'mislukt' => 'Mislukt',
        'terugbetaald' => 'Terugbetaald',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function getStatusLabelAttribute(): array
    {
        $colors = [
            'in_afwachting' => 'yellow',
            'voltooid' => 'green',
            'mislukt' => 'red',
            'terugbetaald' => 'purple',
        ];

        return [
            'text' => self::STATUSES[$this->status] ?? $this->status,
            'color' => $colors[$this->status] ?? 'gray',
        ];
    }

    public static function generateNumber(): string
    {
        $year = date('Y');
        $last = static::where('transaction_number', 'like', "TRX-{$year}-%")
            ->orderByDesc('transaction_number')
            ->first();

        if ($last) {
            $num = (int) substr($last->transaction_number, -4) + 1;
        } else {
            $num = 1;
        }

        return sprintf("TRX-%s-%04d", $year, $num);
    }
}
