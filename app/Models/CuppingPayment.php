<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CuppingPayment extends Model
{
    protected $fillable = [
        'cupping_therapy_id',
        'amount',
        'payment_method',
        'received_by',
        'status',
        'paid_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';

    public function cuppingTherapy(): BelongsTo
    {
        return $this->belongsTo(CuppingTherapy::class);
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}