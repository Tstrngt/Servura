<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'object_type',
        'object_id',
        'before',
        'after',
        'note',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'before' => 'array',
        'after' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function record(
        string $action,
        ?Model $object = null,
        ?array $before = null,
        ?array $after = null,
        ?string $note = null,
        ?User $user = null,
    ): self {
        $actor = $user ?? auth()->user();
        $request = request();

        return static::create([
            'user_id' => $actor?->id,
            'action' => $action,
            'object_type' => $object ? get_class($object) : null,
            'object_id' => $object?->id,
            'before' => $before,
            'after' => $after,
            'note' => $note,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }
}
