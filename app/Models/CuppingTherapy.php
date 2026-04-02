<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CuppingTherapy extends Model
{
    protected $fillable = [
        'encounter_id',
        'doctor_id',
        'treatment_date',
        'notes',
        'total_amount',
        'discount',
        'final_amount',
        'status'
    ];

    protected $casts = [
        'treatment_date' => 'date',
        'total_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'final_amount' => 'decimal:2',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_ORDERED = 'ordered';
    const STATUS_PAYMENT_PARTIAL = 'payment_partial';
    const STATUS_PAYMENT_COMPLETED = 'payment_completed';
    const STATUS_SENT_TO_CUPPING = 'sent_to_cupping';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_ORDERED,
        self::STATUS_PAYMENT_PARTIAL,
        self::STATUS_PAYMENT_COMPLETED,
        self::STATUS_SENT_TO_CUPPING,
        self::STATUS_COMPLETED,
        self::STATUS_CANCELLED,
    ];

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(CuppingTherapyItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(CuppingPayment::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(CuppingReport::class);
    }

    public function updateStatusBasedOnPayments(): void
    {
        $totalPaid = $this->payments()->where('status', 'paid')->sum('amount');
        
        if ($totalPaid >= $this->final_amount) {
            if ($this->status === self::STATUS_PAYMENT_PARTIAL || $this->status === self::STATUS_ORDERED) {
                $this->update(['status' => self::STATUS_PAYMENT_COMPLETED]);
            }
        } elseif ($totalPaid > 0 && $totalPaid < $this->final_amount) {
            if ($this->status === self::STATUS_ORDERED) {
                $this->update(['status' => self::STATUS_PAYMENT_PARTIAL]);
            }
        }
    }

    public function getTotalPaidAttribute(): float
    {
        return $this->payments()->where('status', 'paid')->sum('amount');
    }

    public function getRemainingAmountAttribute(): float
    {
        return $this->final_amount - $this->total_paid;
    }
}