<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'user_id',
        'customer_service_id',
        'assigned_to',
        'title',
        'description',
        'priority',
        'status',
        'category',
        'last_reply_at',
        'resolved_at',
        'closed_at',
        'resolution_notes',
        'request_type',
        'request_details',
        'page',
        'customer_notes',
    ];

    protected $casts = [
        'last_reply_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
        'request_details' => 'array',
    ];

    // Boot method to generate ticket number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (empty($ticket->ticket_number)) {
                $ticket->ticket_number = 'SR-'.date('Y').'-'.str_pad(static::max('id') + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function customerService()
    {
        return $this->belongsTo(CustomerService::class);
    }

    public function replies()
    {
        return $this->hasMany(TicketReply::class)->orderBy('created_at', 'asc');
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    // Public replies (not internal)
    public function publicReplies()
    {
        return $this->replies()->where('is_internal', false);
    }

    // Internal replies (staff only)
    public function internalReplies()
    {
        return $this->replies()->where('is_internal', true);
    }

    // Status checking methods
    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isWaitingForCustomer(): bool
    {
        return $this->status === 'waiting_for_customer';
    }

    public function isResolved(): bool
    {
        return $this->status === 'resolved';
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    public function canBeReplied(): bool
    {
        return in_array($this->status, ['open', 'in_progress', 'waiting_for_customer']);
    }

    public function canBeClosed(): bool
    {
        return ! $this->isClosed();
    }

    // Get status label with color
    public function getStatusLabelAttribute(): array
    {
        return match ($this->status) {
            'open' => [
                'text' => 'Open',
                'color' => 'blue',
            ],
            'in_progress' => [
                'text' => 'In Behandeling',
                'color' => 'yellow',
            ],
            'waiting_for_customer' => [
                'text' => 'Wacht op Klant',
                'color' => 'orange',
            ],
            'resolved' => [
                'text' => 'Opgelost',
                'color' => 'green',
            ],
            'closed' => [
                'text' => 'Gesloten',
                'color' => 'gray',
            ],
            default => [
                'text' => $this->status,
                'color' => 'gray',
            ]
        };
    }

    // Get priority label with color
    public function getPriorityLabelAttribute(): array
    {
        return match ($this->priority) {
            'low' => [
                'text' => 'Laag',
                'color' => 'gray',
            ],
            'medium' => [
                'text' => 'Medium',
                'color' => 'blue',
            ],
            'high' => [
                'text' => 'Hoog',
                'color' => 'orange',
            ],
            'urgent' => [
                'text' => 'Urgent',
                'color' => 'red',
            ],
            default => [
                'text' => $this->priority,
                'color' => 'gray',
            ]
        };
    }

    // Get request type label
    public function getRequestTypeLabelAttribute(): string
    {
        return match ($this->request_type) {
            'website_aanpassen' => 'Aanpassing',
            'iets_toevoegen' => 'Toevoeging',
            'website_uitbreiden' => 'Uitbreiding',
            'hulp_nodig' => 'Hulp',
            'algemeen' => 'Algemeen',
            'offerte' => 'Offerte-aanvraag',
            default => 'Support'
        };
    }

    // Get category label
    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'technical' => 'Technisch',
            'billing' => 'Facturatie',
            'general' => 'Algemeen',
            'feature_request' => 'Feature Verzoek',
            'bug_report' => 'Bug Report',
            'offerte' => 'Offerte-aanvraag',
            default => ucfirst($this->category)
        };
    }

    // Update last reply timestamp
    public function updateLastReply(): void
    {
        $this->update(['last_reply_at' => now()]);
    }

    public function transitionTo(string $status, ?string $resolutionNotes = null): void
    {
        if (! in_array($status, ['open', 'in_progress', 'waiting_for_customer', 'resolved', 'closed'], true)) {
            throw new \InvalidArgumentException('Ongeldige ticketstatus.');
        }

        $attributes = ['status' => $status];

        if ($status === 'resolved') {
            $attributes['resolved_at'] = $this->resolved_at ?? now();
            $attributes['closed_at'] = null;
            $attributes['resolution_notes'] = $resolutionNotes ?? $this->resolution_notes;
        } elseif ($status === 'closed') {
            $attributes['closed_at'] = now();
            $attributes['resolution_notes'] = $resolutionNotes ?? $this->resolution_notes;
        } else {
            $attributes['resolved_at'] = null;
            $attributes['closed_at'] = null;

            if ($status === 'open') {
                $attributes['resolution_notes'] = null;
            }
        }

        $this->update($attributes);
    }

    public function markAsResolved(?string $notes = null): void
    {
        $this->transitionTo('resolved', $notes);
    }

    public function markAsClosed(?string $notes = null): void
    {
        $this->transitionTo('closed', $notes);
    }

    public function reopen(): void
    {
        $this->transitionTo('open');
    }

    // Check if ticket is overdue (no reply for 48 hours)
    public function isOverdue(): bool
    {
        if ($this->isClosed() || $this->isResolved()) {
            return false;
        }

        $lastActivity = $this->last_reply_at ?? $this->created_at;

        return $lastActivity->lt(now()->subHours(48));
    }

    // Get unread replies count for customer
    public function getUnreadRepliesCount(User $user): int
    {
        if ($user->id !== $this->user_id) {
            return 0;
        }

        return $this->replies()
            ->where('user_id', '!=', $user->id)
            ->where('created_at', '>', $user->last_login_at ?? $this->created_at)
            ->count();
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeWaitingForCustomer($query)
    {
        return $query->where('status', 'waiting_for_customer');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['open', 'in_progress', 'waiting_for_customer']);
    }

    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('assigned_to');
    }

    public function scopeOverdue($query)
    {
        return $query->where(function ($q) {
            $q->where('last_reply_at', '<', now()->subHours(48))
                ->orWhere('created_at', '<', now()->subHours(48));
        })->whereNotIn('status', ['resolved', 'closed']);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('last_reply_at', 'desc');
    }
}
