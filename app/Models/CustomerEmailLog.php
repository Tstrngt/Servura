<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerEmailLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sent_by',
        'template',
        'recipient_email',
        'recipient_name',
        'subject',
        'body_html',
        'status',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }
}
