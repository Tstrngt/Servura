<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalAcceptance extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'legal_document_id',
        'version',
        'accepted_at',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function document()
    {
        return $this->belongsTo(LegalDocument::class, 'legal_document_id');
    }
}
