<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalDocumentVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'legal_document_id',
        'version',
        'content',
        'effective_date',
        'published_by',
        'published_at',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'published_at' => 'datetime',
    ];

    public function document()
    {
        return $this->belongsTo(LegalDocument::class, 'legal_document_id');
    }

    public function publishedBy()
    {
        return $this->belongsTo(User::class, 'published_by');
    }
}
