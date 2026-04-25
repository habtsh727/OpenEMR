<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CuppingPayment extends Model
{
    protected $table = 'cupping_payments';

    protected $fillable = [
        'cupping_session_id',
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

    public function cuppingSession(): BelongsTo
    {
        return $this->belongsTo(CuppingSession::class);
    }
    public function therapyPackage()
    {
        return $this->belongsTo(CuppingTherapyPackage::class, 'cupping_therapy_package_id');
    }

    public function cuppingTherapy(): BelongsTo
    {
        return $this->belongsTo(CuppingTherapy::class);
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
