<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'company',
        'phone',
        'subject',
        'message',
        'ip_address',
        'user_agent',
        'is_spam',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_spam' => 'boolean',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function scopeNotSpam($query)
    {
        return $query->where('is_spam', false);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
