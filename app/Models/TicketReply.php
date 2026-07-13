<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'is_internal',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
    ];

    // Relationships
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Check if reply is from customer
    public function isFromCustomer(): bool
    {
        return $this->user->isCustomer();
    }

    // Check if reply is from staff
    public function isFromStaff(): bool
    {
        return $this->user->canAccessAdmin();
    }

    // Get formatted message (with line breaks)
    public function getFormattedMessageAttribute(): string
    {
        return nl2br(e($this->message));
    }

    // Scopes
    public function scopePublic($query)
    {
        return $query->where('is_internal', false);
    }

    public function scopeInternal($query)
    {
        return $query->where('is_internal', true);
    }

    public function scopeFromCustomer($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->role('customer');
        });
    }

    public function scopeFromStaff($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->whereIn('role', ['admin', 'employee']);
        });
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeOldest($query)
    {
        return $query->orderBy('created_at', 'asc');
    }
}
