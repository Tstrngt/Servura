<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'content',
        'status',
        'version',
        'effective_date',
        'published_at',
        'published_by',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'published_at' => 'datetime',
    ];

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';

    public function versions()
    {
        return $this->hasMany(LegalDocumentVersion::class)->orderByDesc('created_at');
    }

    public function publishedBy()
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeSlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }
}
