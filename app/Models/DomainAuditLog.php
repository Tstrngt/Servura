<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomainAuditLog extends Model
{
    protected $fillable = [
        'domain_registration_id',
        'user_id',
        'action',
        'before',
        'after',
        'note',
        'ip_address',
        'status',
    ];

    protected $casts = [
        'before' => 'array',
        'after' => 'array',
    ];

    public function domainRegistration()
    {
        return $this->belongsTo(DomainRegistration::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
