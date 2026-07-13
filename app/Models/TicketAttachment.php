<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TicketAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'uploaded_by',
        'original_name',
        'file_path',
        'mime_type',
        'file_size',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    // Relationships
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Get formatted file size
    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    // Get file extension
    public function getFileExtensionAttribute(): string
    {
        return pathinfo($this->original_name, PATHINFO_EXTENSION);
    }

    // Check if file is an image
    public function isImage(): bool
    {
        return in_array(strtolower($this->file_extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    }

    // Check if file is a PDF
    public function isPdf(): bool
    {
        return strtolower($this->file_extension) === 'pdf';
    }

    // Get file icon based on type
    public function getFileIconAttribute(): string
    {
        if ($this->isImage()) {
            return 'image';
        }

        if ($this->isPdf()) {
            return 'pdf';
        }

        $extension = strtolower($this->file_extension);
        
        if (in_array($extension, ['doc', 'docx'])) {
            return 'word';
        }

        if (in_array($extension, ['xls', 'xlsx'])) {
            return 'excel';
        }

        if (in_array($extension, ['ppt', 'pptx'])) {
            return 'powerpoint';
        }

        if (in_array($extension, ['zip', 'rar', '7z'])) {
            return 'archive';
        }

        if (in_array($extension, ['txt', 'md'])) {
            return 'text';
        }

        return 'file';
    }

    // Get download URL
    public function getDownloadUrlAttribute(): string
    {
        return route('tickets.attachments.download', $this->id);
    }

    // Get preview URL (for images)
    public function getPreviewUrlAttribute(): ?string
    {
        if ($this->isImage()) {
            return route('tickets.attachments.preview', $this->id);
        }

        return null;
    }

    // Delete file from storage when model is deleted
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($attachment) {
            Storage::disk('public')->delete($attachment->file_path);
        });
    }

    // Scopes
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeImages($query)
    {
        return $query->whereIn('mime_type', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }

    public function scopeDocuments($query)
    {
        return $query->whereNotIn('mime_type', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }
}
