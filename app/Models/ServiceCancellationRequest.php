<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCancellationRequest extends Model
{
    protected $fillable = [
        'customer_service_id',
        'user_id',
        'ticket_id',
        'status',
        'policy_type',
        'requested_at',
        'effective_at',
        'original_end_date',
        'estimated_usage_cost',
        'reason',
        'admin_notes',
    ];

    protected $casts = [
        'requested_at' => 'date',
        'effective_at' => 'date',
        'original_end_date' => 'date',
        'estimated_usage_cost' => 'decimal:2',
    ];

    public function customerService()
    {
        return $this->belongsTo(CustomerService::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
