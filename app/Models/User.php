<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'company',
        'street',
        'house_number',
        'postal_code',
        'city',
        'country',
        'kvk_number',
        'vat_number',
        'profile_logo_path',
        'mollie_customer_id',
        'role',
        'is_active',
        'last_login_at',
        'email_verified_at',
        'email_verification_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    // Role checking methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner' || $this->role === 'admin';
    }

    // Check if user can access admin area
    public function canAccessAdmin(): bool
    {
        return $this->isAdmin() || $this->isEmployee() || $this->isOwner();
    }

    // Customer services relationship
    public function customerServices()
    {
        return $this->hasMany(CustomerService::class);
    }

    // Get active services for customer
    public function activeServices()
    {
        return $this->customerServices()
            ->with('service')
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
    }

    /**
     * Whether the customer has an active service with priority support
     * (e.g. a maintenance package). Used to badge tickets for staff.
     */
    public function hasPrioritySupport(): bool
    {
        return $this->customerServices()
            ->where('status', 'active')
            ->whereHas('service', fn ($query) => $query->where('priority_support', true))
            ->exists();
    }

    // Get all services including inactive
    public function allServices()
    {
        return $this->customerServices()
            ->with('service')
            ->orderBy('created_at', 'desc');
    }

    // Tickets relationship
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // Notifications relationship
    public function notifications()
    {
        return $this->hasMany(Notification::class)->latest();
    }

    // Get active tickets
    public function activeTickets()
    {
        return $this->tickets()->active();
    }

    // Get open tickets
    public function openTickets()
    {
        return $this->tickets()->open();
    }

    // Update last login
    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }

    // Scope for active users
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope by role
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    // Scope for customers
    public function scopeCustomers($query)
    {
        return $query->role('customer');
    }

    // Scope for staff (admin + employee)
    public function scopeStaff($query)
    {
        return $query->whereIn('role', ['admin', 'employee']);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function billableItems()
    {
        return $this->hasMany(BillableItem::class);
    }

    public function paymentBatches()
    {
        return $this->hasMany(PaymentBatch::class);
    }
}
