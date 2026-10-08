<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDesignVersion extends Model
{
    use HasFactory;

    protected $fillable = ['version', 'status', 'settings', 'created_by', 'published_by', 'published_at'];

    protected $casts = ['settings' => 'array', 'published_at' => 'datetime'];

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public static function current(): ?self
    {
        return static::published()->latest('published_at')->first();
    }

    public static function draft(): self
    {
        if ($draft = static::where('status', 'draft')->first()) {
            return $draft;
        }

        return static::create([
            'version' => ((int) static::max('version')) + 1,
            'status' => 'draft',
            'settings' => static::current()?->settings ?? app(\App\Services\InvoiceDocumentService::class)->defaultSettings(),
            'created_by' => auth()->id(),
        ]);
    }
}
