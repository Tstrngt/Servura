<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomainRedirect extends Model
{
    protected $fillable = [
        'domain_registration_id',
        'created_by_user_id',
        'source_path',
        'target_url',
        'type',
        'is_active',
        'activated_at',
        'deactivated_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'activated_at' => 'datetime',
        'deactivated_at' => 'datetime',
    ];

    public function domainRegistration()
    {
        return $this->belongsTo(DomainRegistration::class);
    }
}
