<?php
// app/Models/RehabPaymentInstallment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RehabPaymentInstallment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'rehab_order_id',
        'installment_number',
        'amount',
        'due_date',
        'paid_date',
        'paid_amount',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_date' => 'date',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2'
    ];

    public function rehabOrder(): BelongsTo
    {
        return $this->belongsTo(RehabOrder::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isOverdue(): bool
    {
        return $this->status === 'overdue' || 
               ($this->status !== 'paid' && $this->due_date->isPast());
    }

    public function getRemainingAmount(): float
    {
        return $this->amount - $this->paid_amount;
    }
}