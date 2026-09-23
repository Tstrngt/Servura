<?php

namespace App\Models;

use App\Observers\CustomerServiceObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(CustomerServiceObserver::class)]
class CustomerService extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'service_price_id',
        'domain',
        'external_username',
        'external_password',
        'provisioning_status',
        'provisioning_error',
        'provisioned_at',
        'external_suspended_at',
        'status',
        'suspension_reason',
        'suspended_at',
        'price',
        'price_type',
        'billing_cycle',
        'start_date',
        'current_period_start',
        'current_period_end',
        'next_invoice_date',
        'auto_renew',
        'payment_method',
        'cancel_at_period_end',
        'cancelled_at',
        'end_date',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'current_period_start' => 'date',
        'current_period_end' => 'date',
        'next_invoice_date' => 'date',
        'cancelled_at' => 'datetime',
        'suspended_at' => 'datetime',
        'provisioned_at' => 'datetime',
        'external_suspended_at' => 'datetime',
        'external_password' => 'encrypted',
        'end_date' => 'date',
        'price' => 'decimal:2',
        'auto_renew' => 'boolean',
        'cancel_at_period_end' => 'boolean',
    ];

    // Relationships
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

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function cancellationRequests()
    {
        return $this->hasMany(ServiceCancellationRequest::class);
    }

    // Status checking methods
    public function isActive(): bool
    {
        return $this->status === 'active' &&
               (! $this->end_date || $this->end_date >= now());
    }

    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired' ||
               ($this->end_date && $this->end_date < now());
    }

    // Get formatted price
    public function getFormattedPriceAttribute(): string
    {
        if ($this->price_type === 'op-aanvraag') {
            return 'Op aanvraag';
        }

        $price = number_format($this->price, 2, ',', '.');

        switch ($this->price_type) {
            case 'maandelijks':
                return "€ {$price}/maand";
            case 'jaarlijks':
                return "€ {$price}/jaar";
            default:
                return "€ {$price}";
        }
    }

    // Get status label with color
    public function getStatusLabelAttribute(): array
    {
        return match ($this->status) {
            'active' => [
                'text' => 'Actief',
                'color' => 'green',
            ],
            'inactive' => [
                'text' => 'Inactief',
                'color' => 'gray',
            ],
            'suspended' => [
                'text' => match ($this->suspension_reason) {
                    'non_payment' => 'Geschorst wegens wanbetaling',
                    'provisioning_failed' => 'Provisioning mislukt',
                    'quote_request' => 'Offerte in behandeling',
                    default => 'In afwachting',
                },
                'color' => in_array($this->suspension_reason, ['non_payment', 'provisioning_failed'], true) ? 'red' : 'yellow',
            ],
            'cancelled' => [
                'text' => 'Geannuleerd',
                'color' => 'red',
            ],
            'expired' => [
                'text' => 'Verlopen',
                'color' => 'orange',
            ],
            default => [
                'text' => $this->status,
                'color' => 'gray',
            ]
        };
    }

    // Scope for active services
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
    }

    // Scope by status
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope for expiring soon (within 30 days)
    public function scopeExpiringSoon($query)
    {
        return $query->where('status', 'active')
            ->whereNotNull('end_date')
            ->where('end_date', '<=', now()->addDays(30))
            ->where('end_date', '>', now());
    }

    // Scope for expired
    public function scopeExpired($query)
    {
        return $query->where(function ($query) {
            $query->where('status', 'expired')
                ->orWhere(function ($query) {
                    $query->whereNotNull('end_date')
                        ->where('end_date', '<', now());
                });
        });
    }
}
