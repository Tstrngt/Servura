<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerConnection extends Model
{
    use HasFactory;

    public const PROVIDERS = ['directadmin' => 'DirectAdmin'];

    protected $fillable = [
        'name', 'provider', 'url', 'username', 'password', 'shared_ip', 'verify_ssl',
        'timeout', 'settings', 'is_active', 'last_tested_at', 'last_test_status', 'last_test_message',
    ];

    protected $casts = [
        'password' => 'encrypted',
        'settings' => 'encrypted:array',
        'verify_ssl' => 'boolean',
        'is_active' => 'boolean',
        'last_tested_at' => 'datetime',
    ];

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getProviderLabelAttribute(): string
    {
        return self::PROVIDERS[$this->provider] ?? ucfirst($this->provider);
    }
}
