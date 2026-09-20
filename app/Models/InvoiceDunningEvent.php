<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDunningEvent extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_id', 'event_type', 'processed_at', 'metadata'];

    protected $casts = ['processed_at' => 'datetime', 'metadata' => 'array'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
