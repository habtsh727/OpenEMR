<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CuppingTherapy extends Model
{
    protected $table = 'cupping_therapies';
    
    protected $fillable = [
        'encounter_id',
        'doctor_id',
        'notes',
        'total_amount',
        'discount',
        'final_amount',
        'total_sessions',
        'status'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'total_sessions' => 'integer'
    ];

    // Status Constants
    const STATUS_PENDING = 'pending';
    const STATUS_ORDERED = 'ordered';
    const STATUS_PARTIAL_PAID = 'partial_paid';
    const STATUS_FULLY_PAID = 'fully_paid';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Relationships
    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(CuppingSession::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(CuppingPayment::class);
    }

    // Accessors
    public function getPaidAmountAttribute(): float
    {
        return $this->sessions->sum('paid_amount');
    }

    public function getRemainingAmountAttribute(): float
    {
        return $this->final_amount - $this->paid_amount;
    }

    public function getNextUnpaidSessionAttribute()
    {
        return $this->sessions()
            ->where('payment_status', '!=', 'paid')
            ->orderBy('session_number')
            ->first();
    }

    // Methods
    public function updateStatus(): void
    {
        $totalPaid = $this->paid_amount;
        $totalSessions = $this->sessions->count();
        $completedSessions = $this->sessions->where('treatment_status', 'completed')->count();
        $inProgressSessions = $this->sessions->where('treatment_status', 'in_progress')->count();

        if ($this->status === self::STATUS_CANCELLED) {
            return;
        }

        if ($completedSessions === $totalSessions) {
            $this->status = self::STATUS_COMPLETED;
        } elseif ($inProgressSessions > 0 || $completedSessions > 0) {
            $this->status = self::STATUS_IN_PROGRESS;
        } elseif ($totalPaid >= $this->final_amount) {
            $this->status = self::STATUS_FULLY_PAID;
        } elseif ($totalPaid > 0) {
            $this->status = self::STATUS_PARTIAL_PAID;
        } else {
            $this->status = self::STATUS_ORDERED;
        }

        $this->saveQuietly();
    }
}