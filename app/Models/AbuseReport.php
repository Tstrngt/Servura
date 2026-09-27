<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbuseReport extends Model
{
    protected $fillable = [
        'name',
        'email',
        'domain',
        'url',
        'category',
        'description',
        'reason',
        'attachment_path',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public const CATEGORIES = [
        'phishing' => 'Phishing',
        'spam' => 'Spam',
        'malware' => 'Malware',
        'fraud' => 'Fraude',
        'illegal' => 'Illegale content',
        'copyright' => 'Auteursrecht',
        'privacy' => 'Privacy',
        'other' => 'Overig',
    ];

    public const STATUSES = [
        'new' => 'Nieuw',
        'reviewing' => 'In behandeling',
        'action_taken' => 'Actie ondernomen',
        'rejected' => 'Afgewezen',
        'closed' => 'Gesloten',
    ];

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
