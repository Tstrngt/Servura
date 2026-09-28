<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainRegistration extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_AWAITING_PAYMENT = 'awaiting_payment';
    public const STATUS_PAID = 'paid';
    public const STATUS_REGISTERING = 'registering';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_REGISTRATION_FAILED = 'registration_failed';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_TRANSFER_PENDING = 'transfer_pending';
    public const STATUS_TRANSFER_PROCESSING = 'transfer_processing';
    public const STATUS_TRANSFER_ACTIVE = 'active';
    public const STATUS_TRANSFER_FAILED = 'transfer_failed';

    public const TYPE_REGISTRATION = 'registration';
    public const TYPE_TRANSFER = 'transfer';

    public const STATUSES = [
        self::STATUS_PENDING => 'In afwachting',
        self::STATUS_AWAITING_PAYMENT => 'Wacht op betaling',
        self::STATUS_PAID => 'Betaald',
        self::STATUS_REGISTERING => 'Bezig met registreren',
        self::STATUS_ACTIVE => 'Actief',
        self::STATUS_REGISTRATION_FAILED => 'Registratie mislukt',
        self::STATUS_EXPIRED => 'Verlopen',
        self::STATUS_CANCELLED => 'Geannuleerd',
        self::STATUS_TRANSFER_PENDING => 'Verhuizing in afwachting',
        self::STATUS_TRANSFER_PROCESSING => 'Verhuizing bezig',
        self::STATUS_TRANSFER_ACTIVE => 'Actief',
        self::STATUS_TRANSFER_FAILED => 'Verhuizing mislukt',
    ];

    protected $fillable = [
        'user_id',
        'order_id',
        'customer_service_id',
        'type',
        'domain_name',
        'tld',
        'status',
        'provider',
        'registration_price',
        'renewal_price',
        'transfer_price',
        'registered_at',
        'expires_at',
        'auto_renew',
        'external_id',
        'error_message',
        'auth_code',
    ];

    protected $casts = [
        'registration_price' => 'decimal:2',
        'renewal_price' => 'decimal:2',
        'transfer_price' => 'decimal:2',
        'registered_at' => 'date',
        'expires_at' => 'date',
        'auto_renew' => 'boolean',
        'auth_code' => 'encrypted',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customerService()
    {
        return $this->belongsTo(CustomerService::class);
    }

    public function getStatusLabelAttribute(): array
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => ['text' => 'Actief', 'color' => 'green'],
            self::STATUS_PENDING => ['text' => 'In afwachting', 'color' => 'gray'],
            self::STATUS_AWAITING_PAYMENT => ['text' => 'Wacht op betaling', 'color' => 'yellow'],
            self::STATUS_PAID => ['text' => 'Betaald', 'color' => 'blue'],
            self::STATUS_REGISTERING => ['text' => 'Bezig met registreren', 'color' => 'blue'],
            self::STATUS_REGISTRATION_FAILED => ['text' => 'Registratie mislukt', 'color' => 'red'],
            self::STATUS_EXPIRED => ['text' => 'Verlopen', 'color' => 'orange'],
            self::STATUS_CANCELLED => ['text' => 'Geannuleerd', 'color' => 'red'],
            self::STATUS_TRANSFER_PENDING => ['text' => 'Verhuizing in afwachting', 'color' => 'yellow'],
            self::STATUS_TRANSFER_PROCESSING => ['text' => 'Verhuizing bezig', 'color' => 'blue'],
            self::STATUS_TRANSFER_ACTIVE => ['text' => 'Actief', 'color' => 'green'],
            self::STATUS_TRANSFER_FAILED => ['text' => 'Verhuizing mislukt', 'color' => 'red'],
            default => ['text' => $this->status, 'color' => 'gray'],
        };
    }
}
