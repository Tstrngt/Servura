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
        'role',
        'is_active',
        'last_login_at',
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

    // Check if user can access admin area
    public function canAccessAdmin(): bool
    {
        return $this->isAdmin() || $this->isEmployee();
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
}
